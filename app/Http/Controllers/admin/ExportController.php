<?php

namespace App\Http\Controllers\admin;

use App\Exports\SiswaExport;
use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;

class ExportController extends Controller
{
    /**
     * GET /admin/export/pdf
     * Query: kelas, q (cari nama/token), urut (kelas|nama_asc|nama_desc), layout (daftar|kartu)
     * Satu PDF; jika semua kelas dipilih, tiap kelas dimulai di halaman baru.
     */
    public function pdf(Request $request): Response
    {
        $siswa = $this->ambilSiswa($request);
        if ($siswa->isEmpty()) {
            return $this->kosong();
        }

        $layout   = $this->layout($request);
        $kelompok = $this->kelompokKelas($siswa);

        $nama = $request->filled('kelas')
            ? 'token-' . $layout . '-' . (Str::slug($request->query('kelas')) ?: 'kelas') . '.pdf'
            : 'token-' . $layout . '-semua-kelas.pdf';

        return $this->unduh($this->renderPdf($layout, $kelompok), $nama, 'application/pdf');
    }

    /**
     * GET /admin/export/pdf-per-kelas
     * ZIP berisi satu PDF untuk setiap kelas (untuk dibagikan ke masing-masing wali kelas).
     */
    public function pdfPerKelas(Request $request): Response
    {
        $siswa = $this->ambilSiswa($request, false);
        if ($siswa->isEmpty()) {
            return $this->kosong();
        }

        $layout   = $this->layout($request);
        $zipPath  = tempnam(sys_get_temp_dir(), 'tokenzip');
        $zip      = new \ZipArchive();

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return response()->json(['message' => 'Gagal membuat file ZIP.'], 500);
        }

        $dipakai = [];
        foreach ($this->kelompokKelas($siswa) as $kelas => $rows) {
            $dasar = Str::slug((string) $kelas) ?: 'kelas';
            $nama  = $dasar;
            for ($i = 2; isset($dipakai[$nama]); $i++) {
                $nama = $dasar . '-' . $i;
            }
            $dipakai[$nama] = true;

            $zip->addFromString(
                'token-' . $layout . '-' . $nama . '.pdf',
                $this->renderPdf($layout, collect([$kelas => $rows]))
            );
        }
        $zip->close();

        return response()
            ->download($zipPath, 'token-' . $layout . '-per-kelas.zip', ['Content-Type' => 'application/zip'])
            ->deleteFileAfterSend(true);
    }

    /**
     * GET /admin/export/excel
     * Query: kelas, q, urut, per_kelas (1 = satu sheet per kelas)
     */
    public function excel(Request $request): Response
    {
        $siswa = $this->ambilSiswa($request);
        if ($siswa->isEmpty()) {
            return $this->kosong();
        }

        $perKelas = $request->boolean('per_kelas') && !$request->filled('kelas');
        $nama     = $request->filled('kelas')
            ? 'data-siswa-' . (Str::slug($request->query('kelas')) ?: 'kelas') . '.xlsx'
            : 'data-siswa-semua-kelas.xlsx';

        return Excel::download(new \App\Exports\SiswaExport($siswa, $perKelas), $nama);
    }

    /* ------------------------------------------------------------------ */

    /** Ambil siswa sesuai filter (kelas, q) dan urutan. */
    private function ambilSiswa(Request $request, bool $pakaiFilter = true): Collection
    {
        $query = Siswa::query();

        if ($pakaiFilter) {
            $kelas = trim((string) $request->query('kelas', ''));
            if ($kelas !== '') {
                $query->where('kelas', $kelas);
            }

            $q = trim((string) $request->query('q', ''));
            if ($q !== '') {
                $like = '%' . addcslashes($q, '%_\\') . '%';
                $query->where(function ($w) use ($like) {
                    $w->where('nama', 'like', $like)->orWhere('token', 'like', $like);
                });
            }
        }

        $urut = $request->query('urut', 'kelas');

        return $query->get()->sort(function ($a, $b) use ($urut) {
            if ($urut === 'nama_asc') {
                return strnatcasecmp($a->nama, $b->nama);
            }
            if ($urut === 'nama_desc') {
                return strnatcasecmp($b->nama, $a->nama);
            }
            return strnatcasecmp($a->kelas, $b->kelas) ?: strnatcasecmp($a->nama, $b->nama);
        })->values();
    }

    /** Kelompokkan per kelas (urut alami: 10, 11, 12), isi tiap kelas mengikuti urutan terpilih. */
    private function kelompokKelas(Collection $siswa): Collection
    {
        return $siswa->groupBy('kelas')
            ->map(fn ($rows) => $rows->values())
            ->sortKeysUsing('strnatcasecmp');
    }

    private function layout(Request $request): string
    {
        return $request->query('layout') === 'kartu' ? 'kartu' : 'daftar';
    }

    private function pengaturan(): array
    {
        $p = null;
        try {
            $p = DB::table('tb_settings')->first();
        } catch (\Throwable $e) {
            report($e);
        }

        return [
            'sekolah' => data_get($p, 'nama_sekolah') ?: 'SMK Informatika Sumedang',
            'judul'   => data_get($p, 'judul_pemilihan') ?: 'E-Voting OSIS',
            'tahun'   => data_get($p, 'tahun_ajaran') ?: '',
        ];
    }

    private function renderPdf(string $layout, Collection $kelompok): string
    {
        $html = view('pdf.token', [
            'layout'      => $layout,
            'kelompok'    => $kelompok,
            'pengaturan'  => $this->pengaturan(),
            'urlVoting'   => url('/'),
            'dicetak'     => now()->translatedFormat('d F Y, H:i'),
        ])->render();

        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');   // mendukung karakter UTF-8
        $options->set('isRemoteEnabled', false);
        $options->set('isPhpEnabled', false);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }

    private function unduh(string $isi, string $nama, string $mime): Response
    {
        return response($isi, 200, [
            'Content-Type'        => $mime,
            'Content-Disposition' => 'attachment; filename="' . $nama . '"',
            'Cache-Control'       => 'no-store', // berisi token siswa
        ]);
    }

    private function kosong(): Response
    {
        return response()->json(['message' => 'Tidak ada data siswa untuk diexport.'], 422);
    }
}
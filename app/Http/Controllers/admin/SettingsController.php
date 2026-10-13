<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SettingsController extends Controller
{
    /** GET /api/admin/settings : data pengaturan + status jadwal + jam server */
    public function index(): JsonResponse
    {
        return response()->json($this->payload(Settings::ambil()));
    }

    /** POST /admin/settings : simpan identitas, jadwal, view_voted, dan logo (multipart). */
    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nama_sekolah'    => ['required', 'string', 'max:100'],
            'judul_pemilihan' => ['required', 'string', 'max:100'],
            'tahun_ajaran'    => ['required', 'regex:/^\d{4}-\d{4}$/', function ($atribut, $nilai, $gagal) {
                [$awal, $akhir] = array_map('intval', explode('-', $nilai));
                if ($akhir !== $awal + 1) {
                    $gagal('Tahun ajaran harus berurutan, contoh 2026-2027.');
                }
            }],
            'waktu_mulai'     => ['required', 'date'],
            'waktu_selesai'   => ['required', 'date', 'after:waktu_mulai'],
            'view_voted'      => ['required', 'boolean'],
            'logo'            => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'nama_sekolah.required'    => 'Nama sekolah wajib diisi.',
            'judul_pemilihan.required' => 'Judul pemilihan wajib diisi.',
            'tahun_ajaran.required'    => 'Tahun ajaran wajib diisi.',
            'tahun_ajaran.regex'       => 'Format tahun ajaran harus 2026-2027.',
            'waktu_mulai.required'     => 'Waktu mulai wajib diisi.',
            'waktu_mulai.date'         => 'Waktu mulai tidak valid.',
            'waktu_selesai.required'   => 'Waktu selesai wajib diisi.',
            'waktu_selesai.date'       => 'Waktu selesai tidak valid.',
            'waktu_selesai.after'      => 'Waktu selesai harus setelah waktu mulai.',
            'logo.image'               => 'Logo harus berupa gambar.',
            'logo.mimes'               => 'Logo harus berformat JPG, PNG, atau WEBP.',
            'logo.max'                 => 'Ukuran logo maksimal 2 MB.',
        ]);

        $isi = [
            'nama_sekolah'    => trim($data['nama_sekolah']),
            'judul_pemilihan' => trim($data['judul_pemilihan']),
            'tahun_ajaran'    => $data['tahun_ajaran'],
            'view_voted'      => $request->boolean('view_voted') ? 1 : 0,
            'waktu_mulai'     => Carbon::parse($data['waktu_mulai'])->format('Y-m-d H:i:s'),
            'waktu_selesai'   => Carbon::parse($data['waktu_selesai'])->format('Y-m-d H:i:s'),
        ];

        $lama = Settings::query()->first();   // langsung dari database, bukan cache

        $logoLama = null;
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $nama = Str::lower(Str::random(10)) . '_login.' . strtolower($file->extension());
            $file->storeAs('logo', $nama, 'public');

            $isi['logo_sekolah'] = $nama;
            $logoLama = $lama?->logo_sekolah;
        }

        if ($lama) {
            // tabel hanya berisi satu baris, jadi update tanpa where
            Settings::query()->update($isi);
        } else {
            Settings::create($isi + [
                'status_voting' => 0,
                'logo_sekolah'  => $isi['logo_sekolah'] ?? 'users.png',
            ]);
        }

        // hapus logo lama hanya di folder storage/logo (logo bawaan di folder lain tidak disentuh)
        if ($logoLama && $logoLama !== ($isi['logo_sekolah'] ?? null)) {
            Storage::disk('public')->delete('logo/' . basename($logoLama));
        }

        Settings::hapusCache();

        return response()->json([
            'message' => 'Pengaturan berhasil disimpan.',
            'data'    => $this->payload(Settings::ambil()),
        ]);
    }

    /**
     * POST /admin/settings/voting : saklar nyala/mati.
     * Menyalakan saat jadwal sudah berakhir ditolak (422 + peringatan = true); mematikan selalu boleh.
     */
    public function toggleVoting(Request $request): JsonResponse
    {
        $data = $request->validate(['aktif' => ['required', 'boolean']]);
        $aktif = $request->boolean('aktif');

        $setting = Settings::query()->first();
        if (!$setting) {
            return response()->json(['message' => 'Pengaturan belum dibuat. Simpan pengaturan terlebih dahulu.'], 422);
        }

        if ($aktif && $setting->jadwalBerakhir()) {
            return response()->json([
                'peringatan' => true,
                'message'    => 'Jadwal voting sudah berakhir, saklar tidak bisa dinyalakan. Perpanjang waktu selesai terlebih dahulu.',
            ], 422);
        }

        Settings::query()->update(['status_voting' => $aktif ? 1 : 0]);
        Settings::hapusCache();

        return response()->json([
            'message' => $aktif ? 'Saklar voting dinyalakan.' : 'Saklar voting dimatikan.',
            'data'    => $this->payload(Settings::ambil()),
        ]);
    }

    private function payload(Settings $s): array
    {
        return [
            'nama_sekolah'    => $s->nama_sekolah,
            'judul_pemilihan' => $s->judul_pemilihan,
            'tahun_ajaran'    => $s->tahun_ajaran,
            'status_voting'   => (bool) $s->status_voting,
            'view_voted'      => (bool) $s->view_voted,
            'waktu_mulai'     => $s->waktu_mulai?->format('Y-m-d\TH:i'),
            'waktu_selesai'   => $s->waktu_selesai?->format('Y-m-d\TH:i'),
            'logo_url'        => $s->logoUrl(),
            'status'          => $s->statusJadwal(),
            'sekarang'        => now()->format('Y-m-d\TH:i:s'),
        ];
    }
}
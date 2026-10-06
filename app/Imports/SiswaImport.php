<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;

/**
 * Import siswa dari Excel/CSV (aman untuk file besar, mis. 1000+ baris).
 *
 * - Hanya kolom nama dan kelas yang dibaca; token dibuat otomatis, status/voted = 0.
 * - Token terpakai dan data lama dimuat SEKALI di awal (bukan query per baris).
 * - Baris disimpan per batch (bulk insert), bukan satu per satu.
 * - Baris bermasalah dicatat dan dilaporkan, tidak menggagalkan seluruh file.
 */
class SiswaImport implements OnEachRow, WithHeadingRow
{
    private const UKURAN_BATCH = 200;

    /** judul kolom yang diterima (sudah dalam bentuk slug: huruf kecil, spasi jadi "_") */
    private const ALIAS_NAMA  = ['nama', 'nama_siswa', 'nama_lengkap', 'name'];
    private const ALIAS_KELAS = ['kelas', 'class', 'rombel'];

    public int $berhasil = 0;
    public bool $kolomHilang = false;

    /** @var array<int, array{baris:int,nama:string,kelas:string}> */
    public array $duplikat = [];

    /** @var array<int, array{baris:int,nama:string,kelas:string,alasan:string}> */
    public array $gagal = [];

    private array $sudahAda = [];       // "nama|kelas" yang sudah ada / sudah masuk antrean
    private array $tokenTerpakai = [];  // token (huruf besar) yang sudah dipakai
    private array $buffer = [];         // antrean insert
    private ?array $kolom = null;       // ['nama' => ..., 'kelas' => ...] hasil deteksi judul kolom

    public function __construct()
    {
        foreach (Siswa::query()->toBase()->get(['token', 'nama', 'kelas']) as $s) {
            $this->sudahAda[$this->kunci($s->nama, $s->kelas)] = true;
            $this->tokenTerpakai[strtoupper((string) $s->token)] = true;
        }
    }

    public function onRow(Row $row): void
    {
        if ($this->kolomHilang) {
            return;
        }

        $baris = $row->getIndex();
        $data  = $row->toArray();

        // deteksi judul kolom pada baris data pertama
        if ($this->kolom === null) {
            $this->kolom = $this->cariKolom($data);
            if ($this->kolom === null) {
                $this->kolomHilang = true;
                return;
            }
        }

        $nama  = $this->bersihkan($data[$this->kolom['nama']] ?? null);
        $kelas = $this->bersihkan($data[$this->kolom['kelas']] ?? null);

        // baris kosong: abaikan tanpa dilaporkan
        if ($nama === '' && $kelas === '') {
            return;
        }

        if ($nama === '' || $kelas === '') {
            $this->catatGagal($baris, $nama, $kelas, $nama === '' ? 'Nama kosong' : 'Kelas kosong');
            return;
        }
        if (mb_strlen($nama) > 100) {
            $this->catatGagal($baris, $nama, $kelas, 'Nama terlalu panjang (maks 100 karakter)');
            return;
        }
        if (mb_strlen($kelas) > 50) {
            $this->catatGagal($baris, $nama, $kelas, 'Kelas terlalu panjang (maks 50 karakter)');
            return;
        }

        $kunci = $this->kunci($nama, $kelas);
        if (isset($this->sudahAda[$kunci])) {
            $this->duplikat[] = ['baris' => $baris, 'nama' => $nama, 'kelas' => $kelas];
            return;
        }
        $this->sudahAda[$kunci] = true;

        $this->buffer[] = [
            'baris' => $baris,
            'data'  => [
                'token'  => $this->buatToken(),
                'nama'   => $nama,
                'kelas'  => $kelas,
                'status' => 0,
                'voted'  => 0,
            ],
        ];

        if (count($this->buffer) >= self::UKURAN_BATCH) {
            $this->simpan();
        }
    }

    /** Simpan antrean ke database. Dipanggil otomatis tiap batch, dan sekali lagi oleh controller di akhir. */
    public function simpan(): void
    {
        if (!$this->buffer) {
            return;
        }

        try {
            Siswa::insert(array_column($this->buffer, 'data'));
            $this->berhasil += count($this->buffer);
        } catch (\Throwable $e) {
            report($e);

            // satu batch gagal: coba satu per satu supaya baris bermasalahnya ketahuan
            foreach ($this->buffer as $item) {
                try {
                    Siswa::insert($item['data']);
                    $this->berhasil++;
                } catch (\Throwable $e2) {
                    report($e2);
                    $this->catatGagal($item['baris'], $item['data']['nama'], $item['data']['kelas'], 'Gagal menyimpan ke database');
                }
            }
        }

        $this->buffer = [];
    }

    private function cariKolom(array $data): ?array
    {
        $nama  = null;
        $kelas = null;

        foreach (self::ALIAS_NAMA as $alias) {
            if (array_key_exists($alias, $data)) { $nama = $alias; break; }
        }
        foreach (self::ALIAS_KELAS as $alias) {
            if (array_key_exists($alias, $data)) { $kelas = $alias; break; }
        }

        return ($nama !== null && $kelas !== null) ? ['nama' => $nama, 'kelas' => $kelas] : null;
    }

    private function catatGagal(int $baris, string $nama, string $kelas, string $alasan): void
    {
        $this->gagal[] = ['baris' => $baris, 'nama' => $nama, 'kelas' => $kelas, 'alasan' => $alasan];
    }

    private function bersihkan($nilai): string
    {
        return trim((string) preg_replace('/\s+/u', ' ', (string) $nilai));
    }

    private function kunci(string $nama, string $kelas): string
    {
        return mb_strtolower(trim($nama)) . '|' . mb_strtolower(trim($kelas));
    }

    /** Token 4 karakter (tanpa O/0/I/1), dicek ke daftar di memori, tanpa query per baris. */
    private function buatToken(): string
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $max   = strlen($chars) - 1;

        do {
            $token = '';
            for ($i = 0; $i < 4; $i++) {
                $token .= $chars[random_int(0, $max)];
            }
        } while (isset($this->tokenTerpakai[$token]));

        $this->tokenTerpakai[$token] = true;
        return $token;
    }
}
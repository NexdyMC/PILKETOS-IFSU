<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;

/**
 * Import siswa dari Excel/CSV. Hanya kolom "nama" dan "kelas" yang dibaca;
 * token dibuat otomatis, status dan voted diset 0.
 * Baris bermasalah dicatat (bukan menggagalkan seluruh file).
 */
class SiswaImport implements OnEachRow, WithHeadingRow
{
    public int $berhasil = 0;
    public bool $kolomHilang = false;

    /** @var array<int, array{baris:int,nama:string,kelas:string}> */
    public array $duplikat = [];

    /** @var array<int, array{baris:int,nama:string,kelas:string,alasan:string}> */
    public array $gagal = [];

    /** nama|kelas yang sudah ada di database / sudah diimpor pada file ini */
    private array $sudahAda = [];

    public function __construct()
    {
        foreach (Siswa::query()->get(['nama', 'kelas']) as $siswa) {
            $this->sudahAda[$this->kunci($siswa->nama, $siswa->kelas)] = true;
        }
    }

    public function onRow(Row $row): void
    {
        if ($this->kolomHilang) {
            return;
        }

        $baris = $row->getIndex();
        $data  = $row->toArray();

        // judul kolom di baris pertama harus ada
        if (!array_key_exists('nama', $data) || !array_key_exists('kelas', $data)) {
            $this->kolomHilang = true;
            return;
        }

        $nama  = $this->bersihkan($data['nama']);
        $kelas = $this->bersihkan($data['kelas']);

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

        try {
            Siswa::create([
                'token'  => $this->buatToken(),
                'nama'   => $nama,
                'kelas'  => $kelas,
                'status' => 0,
                'voted'  => 0,
            ]);
            $this->sudahAda[$kunci] = true;
            $this->berhasil++;
        } catch (\Throwable $e) {
            report($e);
            $this->catatGagal($baris, $nama, $kelas, 'Gagal menyimpan ke database');
        }
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

    /** Token 4 karakter (tanpa O/0/I/1), dijamin belum dipakai. */
    private function buatToken(): string
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $max   = strlen($chars) - 1;

        do {
            $token = '';
            for ($i = 0; $i < 4; $i++) {
                $token .= $chars[random_int(0, $max)];
            }
        } while (Siswa::where('token', $token)->exists());

        return $token;
    }
}
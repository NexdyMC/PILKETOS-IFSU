<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SiswaExport implements WithMultipleSheets
{
    public function __construct(private Collection $siswa, private bool $sheetPerKelas = false)
    {
    }

    public function sheets(): array
    {
        if (!$this->sheetPerKelas) {
            $kelas = $this->siswa->pluck('kelas')->unique();
            $judul = $kelas->count() === 1 ? (string) $kelas->first() : 'Siswa';

            return [new SiswaSheet($judul, $this->siswa)];
        }

        // "Semua Kelas" + satu sheet untuk tiap kelas
        $sheets = [new SiswaSheet('Semua Kelas', $this->siswa)];

        foreach ($this->siswa->groupBy('kelas')->sortKeysUsing('strnatcasecmp') as $kelas => $rows) {
            $sheets[] = new SiswaSheet((string) $kelas, $rows->values());
        }

        return $sheets;
    }
}
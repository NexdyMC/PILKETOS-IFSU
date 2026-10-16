<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SiswaTemplateExport implements FromArray, WithHeadings, ShouldAutoSize
{
    protected string $kategori;

    public function __construct(string $kategori = 'siswa')
    {
        $this->kategori = strtolower(trim($kategori)) === 'guru' ? 'guru' : 'siswa';
    }

    public function headings(): array
    {
        return $this->kategori === 'guru' ? ['nama'] : ['nama', 'kelas'];
    }

    public function array(): array
    {
        return [];
    }
}

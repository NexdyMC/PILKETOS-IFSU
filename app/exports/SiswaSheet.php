<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/** Satu sheet: No | Token | Nama | Kelas */
class SiswaSheet extends DefaultValueBinder implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithTitle,
    ShouldAutoSize,
    WithStyles,
    WithCustomValueBinder
{
    private int $no = 0;

    public function __construct(private string $judul, private Collection $siswa)
    {
    }

    public function collection(): Collection
    {
        return $this->siswa;
    }

    public function headings(): array
    {
        return ['No', 'Token', 'Nama', 'Kelas'];
    }

    public function map($siswa): array
    {
        return [++$this->no, $siswa->token, $siswa->nama, $siswa->kelas];
    }

    public function title(): string
    {
        // aturan Excel: maks 31 karakter dan tanpa  \ / ? * [ ] :
        $judul = trim(str_replace(['\\', '/', '?', '*', '[', ']', ':'], '-', $this->judul));
        return mb_substr($judul !== '' ? $judul : 'Siswa', 0, 31);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);
        $sheet->getStyle('A1:D1')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE2E8F0');
        $sheet->freezePane('A2');

        return [];
    }

    /** Kolom Token (B) selalu teks, supaya token seperti "0206" tidak berubah jadi angka 206. */
    public function bindValue(Cell $cell, $value): bool
    {
        if ($cell->getColumn() === 'B' && $cell->getRow() > 1) {
            $cell->setValueExplicit((string) $value, DataType::TYPE_STRING);
            return true;
        }

        return parent::bindValue($cell, $value);
    }
}
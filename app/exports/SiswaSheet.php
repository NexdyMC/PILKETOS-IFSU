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

/**
 * Satu sheet export pemilih:
 * - Siswa : No | Nama | Kelas | Token
 * - Guru  : No | Nama Guru | Token
 */
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
    private bool $isGuru = false;

    public function __construct(private string $judul, private Collection $siswa)
    {
        // Deteksi apakah data sheet ini khusus Guru (berdasarkan judul atau isi kelas)
        $this->isGuru = strcasecmp(trim($this->judul), 'guru') === 0 
            || ($this->siswa->isNotEmpty() && $this->siswa->every(fn($s) => strcasecmp(trim((string)($s->kelas ?? '')), 'guru') === 0));
    }

    public function collection(): Collection
    {
        return $this->siswa;
    }

    public function headings(): array
    {
        if ($this->isGuru) {
            return ['No', 'Nama Guru', 'Token'];
        }

        return ['No', 'Nama', 'Kelas', 'Token'];
    }

    public function map(mixed $siswa): array
    {
        if ($this->isGuru) {
            return [++$this->no, $siswa->nama, $siswa->token];
        }

        return [++$this->no, $siswa->nama, $siswa->kelas, $siswa->token];
    }

    public function title(): string
    {
        // aturan Excel: maks 31 karakter dan tanpa \ / ? * [ ] :
        $judul = trim(str_replace(['\\', '/', '?', '*', '[', ']', ':'], '-', $this->judul));
        return mb_substr($judul !== '' ? $judul : ($this->isGuru ? 'Guru' : 'Siswa'), 0, 31);
    }

    public function styles(Worksheet $sheet): ?array
    {
        $lastCol = $this->isGuru ? 'C' : 'D';
        $sheet->getStyle("A1:{$lastCol}1")->getFont()->setBold(true);
        $sheet->getStyle("A1:{$lastCol}1")->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE2E8F0');
        $sheet->freezePane('A2');

        return [];
    }

    /** Kolom Token selalu diformat teks, supaya token angka awalan nol tidak terpotong */
    public function bindValue(Cell $cell, $value): bool
    {
        $kolomToken = $this->isGuru ? 'C' : 'D';
        if ($cell->getColumn() === $kolomToken && $cell->getRow() > 1) {
            $cell->setValueExplicit((string) $value, DataType::TYPE_STRING);
            return true;
        }

        return parent::bindValue($cell, $value);
    }
}
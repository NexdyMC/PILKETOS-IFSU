<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\admin\SiswaTemplateExport;
use App\Http\Controllers\Controller;
use App\Imports\SiswaImport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImportController extends Controller
{
    private const BATAS_DAFTAR = 50; // jumlah baris bermasalah yang dikirim ke popup

    /** POST /admin/siswa/import */
    public function import(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'extensions:xlsx,xls,csv', 'max:2048'],
        ], [
            'file.required'   => 'Pilih file Excel terlebih dahulu.',
            'file.extensions' => 'Format file harus .xlsx, .xls, atau .csv.',
            'file.max'        => 'Ukuran file maksimal 2MB.',
        ]);

        $import = new SiswaImport();

        try {
            Excel::import($import, $request->file('file'));
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'message' => 'File tidak bisa dibaca. Pastikan file .xlsx, .xls, atau .csv yang valid.',
            ], 422);
        }

        if ($import->kolomHilang) {
            return response()->json([
                'message' => 'Kolom "nama" dan "kelas" tidak ditemukan. Pastikan baris pertama berisi judul kolom tersebut (unduh template jika perlu).',
            ], 422);
        }

        return response()->json([
            'berhasil'       => $import->berhasil,
            'duplikat_total' => count($import->duplikat),
            'gagal_total'    => count($import->gagal),
            'duplikat'       => array_slice($import->duplikat, 0, self::BATAS_DAFTAR),
            'gagal'          => array_slice($import->gagal, 0, self::BATAS_DAFTAR),
        ]);
    }

    /** GET /admin/siswa/template */
    public function template(): BinaryFileResponse
    {
        return Excel::download(new SiswaTemplateExport(), 'template-siswa.xlsx');
    }
}
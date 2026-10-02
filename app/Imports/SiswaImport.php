<?php

namespace App\Imports;

use App\Models\Siswa;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SiswaImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row): Model|array|null
    {
        $token = trim((string) ($row['token'] ?? ''));

        // Kalau token kosong/null, buat token acak yang belum dipakai
        if ($token === '') {
            do {
                $token = random_token(4);
            } while (Siswa::where('token', $token)->exists());
        }

        return new Siswa([
            'nama'    => $row['nama'],
            'kelas'   => $row['kelas'],
            'token'   => $token,
        ]);
    }

    public function rules(): array
    {
        return [
            'nama'    => 'required|string',
            'kelas'   => 'required',
            'token'   => 'nullable|unique:siswa,token',
        ];
    }

}
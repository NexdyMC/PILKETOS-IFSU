<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /** POST /admin/siswa : tambah satu siswa. Token boleh diisi sendiri; kosong = dibuat otomatis. */
    public function store(Request $request): JsonResponse
    {
        // token disimpan huruf besar (token lama juga huruf besar; login siswa case-sensitive)
        if ($request->filled('token')) {
            $request->merge(['token' => strtoupper(trim($request->input('token')))]);
        }

        $data = $request->validate([
            'nama'  => ['required', 'string', 'max:100'],
            'kelas' => ['required', 'string', 'max:50'],
            'token' => ['nullable', 'regex:/^[A-Z0-9]{4,8}$/', 'unique:tb_siswa,token'],
        ], [
            'nama.required'  => 'Nama siswa wajib diisi.',
            'kelas.required' => 'Kelas wajib diisi.',
            'token.regex'    => 'Token harus 4-8 karakter, hanya huruf dan angka.',
            'token.unique'   => 'Token sudah dipakai siswa lain.',
        ]);

        $token = $data['token'] ?? $this->buatToken();

        Siswa::create([
            'token'  => $token,
            'nama'   => trim($data['nama']),
            'kelas'  => trim($data['kelas']),
            'status' => 0,
            'voted'  => 0,
        ]);

        return response()->json([
            'message' => 'Siswa berhasil ditambahkan.',
            'token'   => $token,
        ], 201);
    }

    /** POST /admin/siswa/reset : status -> 0 dan voted -> 0 untuk semua siswa */
    public function reset(): JsonResponse
    {
        $jumlah = Siswa::where('status', 1)
            ->orWhere('voted', '<>', 0)
            ->update(['status' => 0, 'voted' => 0]);

        return response()->json([
            'message' => "Voting berhasil direset ($jumlah siswa dikembalikan ke status Belum).",
            'jumlah'  => $jumlah,
        ]);
    }

    /** Token otomatis 4 karakter (tanpa O/0/I/1), dijamin unik. */
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
<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Kandidat;
use App\Models\Siswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    /**
     * POST /admin/siswa/ubah : edit banyak siswa sekaligus.
     * Body JSON: { siswa: [ { token, nama, kelas, voted } ] }
     * voted kosong/null = belum memilih (status 0, voted 0); isi id kandidat = sudah memilih (status 1).
     */
    public function ubah(Request $request): JsonResponse
    {
        $data = $request->validate([
            'siswa'         => ['required', 'array', 'min:1', 'max:500'],
            'siswa.*.token' => ['required', 'string', 'max:20'],
            'siswa.*.nama'  => ['required', 'string', 'max:100'],
            'siswa.*.kelas' => ['required', 'string', 'max:50'],
            'siswa.*.voted' => ['nullable', 'integer', 'min:0'],
        ], [
            'siswa.required'         => 'Tidak ada data yang dikirim.',
            'siswa.max'              => 'Maksimal 500 siswa sekali edit.',
            'siswa.*.nama.required'  => 'Nama tidak boleh kosong.',
            'siswa.*.nama.max'       => 'Nama maksimal 100 karakter.',
            'siswa.*.kelas.required' => 'Kelas tidak boleh kosong.',
            'siswa.*.kelas.max'      => 'Kelas maksimal 50 karakter.',
            'siswa.*.voted.integer'  => 'Pilihan kandidat tidak valid.',
        ]);

        // kandidat yang dipilih harus ada
        $idKandidat = collect($data['siswa'])
            ->pluck('voted')
            ->filter(fn ($v) => (int) $v > 0)
            ->map(fn ($v) => (int) $v)
            ->unique()
            ->values();

        if ($idKandidat->isNotEmpty()) {
            $ada = Kandidat::whereIn('id', $idKandidat)->pluck('id')->map(fn ($v) => (int) $v);
            if ($ada->count() !== $idKandidat->count()) {
                return response()->json(['message' => 'Kandidat pilihan tidak ditemukan.'], 422);
            }
        }

        DB::transaction(function () use ($data) {
            foreach ($data['siswa'] as $row) {
                $voted = (int) ($row['voted'] ?? 0);

                Siswa::where('token', $row['token'])->update([
                    'nama'   => trim($row['nama']),
                    'kelas'  => trim($row['kelas']),
                    'status' => $voted > 0 ? 1 : 0,   // status selalu konsisten dengan pilihan
                    'voted'  => $voted,
                ]);
            }
        });

        $jumlah = count($data['siswa']);

        return response()->json([
            'message' => "$jumlah siswa berhasil diperbarui.",
            'jumlah'  => $jumlah,
        ]);
    }

    /**
     * POST /admin/siswa/hapus : hapus banyak siswa sekaligus.
     * Body JSON: { tokens: [ ... ] }
     */
    public function hapus(Request $request): JsonResponse
    {
        $data = $request->validate([
            'tokens'   => ['required', 'array', 'min:1', 'max:2000'],
            'tokens.*' => ['required', 'string', 'max:20'],
        ], [
            'tokens.required' => 'Pilih siswa yang akan dihapus.',
            'tokens.max'      => 'Maksimal 2000 siswa sekali hapus.',
        ]);

        $jumlah = Siswa::whereIn('token', $data['tokens'])->delete();

        return response()->json([
            'message' => "$jumlah siswa berhasil dihapus.",
            'jumlah'  => $jumlah,
        ]);
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
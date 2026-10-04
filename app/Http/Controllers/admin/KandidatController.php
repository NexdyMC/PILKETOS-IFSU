<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Kandidat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class KandidatController extends Controller
{
    /** GET /admin/kandidat/data */
    public function list(): JsonResponse
    {
        return response()->json([
            'data' => Kandidat::orderBy('id')->get(),
        ]);
    }

    /** POST /admin/kandidat */
    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);

        // form lama tidak punya "kelas"; isi kosong agar aman kalau kolomnya NOT NULL
        $data['kelas'] = $data['kelas'] ?? '';
        $data['image'] = $request->hasFile('foto') ? $this->simpanFoto($request->file('foto')) : null;

        $kandidat = Kandidat::create($data);

        return response()->json([
            'message' => 'Data kandidat berhasil disimpan.',
            'data'    => $kandidat,
        ], 201);
    }

    /** PUT /admin/kandidat/{kandidat} */
    public function update(Request $request, Kandidat $kandidat): JsonResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('foto')) {
            $this->hapusFoto($kandidat->image);
            $data['image'] = $this->simpanFoto($request->file('foto'));
        }

        $kandidat->update($data);

        return response()->json([
            'message' => 'Data kandidat berhasil diperbarui.',
            'data'    => $kandidat->fresh(),
        ]);
    }

    /** DELETE /admin/kandidat/{kandidat} */
    public function destroy(Kandidat $kandidat): JsonResponse
    {
        $image = $kandidat->image;

        DB::transaction(function () use ($kandidat) {
            // setara delete_kandidat() lama: siswa yang memilih kandidat ini kembali ke belum memilih
            DB::table('tb_siswa')
                ->where('voted', $kandidat->id)
                ->update(['voted' => 0, 'status' => 0]);

            $kandidat->delete();
        });

        $this->hapusFoto($image);

        return response()->json(['message' => 'Kandidat berhasil dihapus.']);
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'nama'  => ['required', 'string', 'max:255'],
            'kelas' => ['nullable', 'string', 'max:100'],
            'visi'  => ['required', 'string'],
            'misi'  => ['required', 'string'],
            'foto'  => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
        ], [
            'foto.image' => 'File harus berupa gambar (PNG/JPG/WEBP).',
            'foto.mimes' => 'Format foto harus PNG, JPG, atau WEBP.',
            'foto.max'   => 'Ukuran foto maksimal 2MB.',
        ]);

        unset($validated['foto']);
        return $validated;
    }

    private function simpanFoto(UploadedFile $file): string
    {
        $dir = public_path('upload/photo');
        File::ensureDirectoryExists($dir);

        $name = Str::random(10) . '.' . strtolower($file->extension());
        $file->move($dir, $name);

        return $name;
    }

    private function hapusFoto(?string $name): void
    {
        if ($name) {
            File::delete(public_path('upload/photo/' . basename($name)));
        }
    }
}
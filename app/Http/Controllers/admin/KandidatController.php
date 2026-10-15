<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Kandidat;
use Illuminate\Support\Facades\Storage;
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

        // kolom image NOT NULL; kalau tidak ada foto, isi string kosong (accessor image_url memakai placeholder)
        $data['image'] = $request->hasFile('foto') ? $this->simpanFoto($request->file('foto')) : '';

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
            'kelas' => ['required', 'string', 'max:100'],
            'visi'  => ['required', 'string'],
            'misi'  => ['required', 'string'],
            'foto'  => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
        ], [
            'nama.required'  => 'Nama kandidat wajib diisi.',
            'kelas.required' => 'Kelas kandidat wajib diisi.',
            'kelas.max'      => 'Kelas maksimal 100 karakter.',
            'foto.image'     => 'File harus berupa gambar (PNG/JPG/WEBP).',
            'foto.mimes'     => 'Format foto harus PNG, JPG, atau WEBP.',
            'foto.max'       => 'Ukuran foto maksimal 2MB.',
        ]);

        // rapikan spasi berlebih di kelas, mis. "  11  RPL 1 " -> "11 RPL 1"
        $validated['kelas'] = trim(preg_replace('/\s+/', ' ', $validated['kelas']));

        unset($validated['foto']);
        return $validated;
    }

    private function simpanFoto(UploadedFile $file): string
    {
        $disk = Storage::disk('public');

        do {
            $name = Str::lower(Str::random(10)) . '_kandidat.' . strtolower($file->extension());
        } while ($disk->exists('kandidat/' . $name));

        $file->storeAs('kandidat', $name, 'public');

        return $name;
    }

    public function hapusFoto(?string $name): void
    {
        if (!$name) {
            return;
        }

        $nama = basename($name);

        // foto baru: disimpan di storage/app/public/kandidat
        Storage::disk('public')->delete('kandidat/' . $nama);

        // foto lama (sebelum dipindah ke storage)
        File::delete(public_path('upload/photo/' . $nama));
    }
}
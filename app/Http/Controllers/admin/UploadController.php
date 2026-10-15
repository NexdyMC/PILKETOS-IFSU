<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Kandidat;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function create()
    {
        return view('admin.upload');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string|max:255',
            'kelas' => 'required|string',
            'visi'  => 'required|string',
            'misi'  => 'required|string',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $file = $request->file('image');
        $nama = Str::lower(Str::random(10)) . '_kandidat.' . strtolower($file->extension());
        $file->storeAs('kandidat', $nama, 'public');

        Kandidat::create([
            'nama'  => $request->nama,
            'kelas' => $request->kelas,
            'visi'  => $request->visi,
            'misi'  => $request->misi,
            'image' => $nama,
        ]);

        return redirect()->route('admin.upload')->with('success', 'Kandidat berhasil ditambahkan');
    }
}
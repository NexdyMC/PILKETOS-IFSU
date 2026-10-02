<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Kandidat;
use Illuminate\Http\Request;

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

        $path = $request->file('image')->store('kandidat', 'public');

        Kandidat::create([
            'nama'  => $request->nama,
            'kelas' => $request->kelas,
            'visi'  => $request->visi,
            'misi'  => $request->misi,
            'image' => $path,
        ]);

        return redirect()->route('admin.upload')->with('success', 'Kandidat berhasil ditambahkan');
    }
}
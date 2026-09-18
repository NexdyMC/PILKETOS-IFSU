<?php

namespace App\Http\Controllers\siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kandidat;
use App\Models\Siswa;

class VotingController extends Controller
{
    public function index() 
    {
        $kandidat = Kandidat::all();
        // $path = $request->file('foto')->store('kandidat', 'public');

        return view('siswa.voting.index', compact('kandidat'));
    }
    
    public function vote(Request $request)
    {
        $request->validate([
            'id_kandidat' => 'required|integer',
        ]);

        // ambil siswa berdasarkan session yang di-set waktu login pakai token
        $siswa = Siswa::where('token', session('token'))->first();

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi tidak valid, silakan login ulang'
            ], 401);
        }

        // cegah vote dobel kalau status sudah 1
        if ($siswa->status == 1) {
            return response()->json([
                'success' => false,
                'message' => 'Kamu sudah melakukan voting'
            ], 403);
        }

        // pastikan kandidat yang dipilih memang ada
        $kandidat = Kandidat::find($request->id_kandidat);
        if (!$kandidat) {
            return response()->json([
                'success' => false,
                'message' => 'Kandidat tidak ditemukan'
            ], 404);
        }

        $siswa->voted = $kandidat->id;
        $siswa->status = 1;
        $siswa->save();

        return response()->json([
            'success' => true,
            'message' => 'Vote berhasil disimpan'
        ]);
    }

}

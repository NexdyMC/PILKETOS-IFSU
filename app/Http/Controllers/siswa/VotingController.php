<?php

namespace App\Http\Controllers\siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kandidat;
use App\Models\Settings;
use App\Models\Siswa;

class VotingController extends Controller
{
    public function index()
    {
        // voting tertutup / belum dimulai / sudah berakhir: tampilkan halaman info (bukan surat suara)
        // $setting = Settings::ambil();
        // if (!$setting->votingBuka()) {
        //     return response()->view('siswa.voting.tutup', [
        //         'setting' => $setting,
        //         'status'  => $setting->statusJadwal(),
        //     ]);
        // }

        $kandidat = Kandidat::all();

        return view('siswa.voting.index', compact('kandidat'));
    }

    public function vote(Request $request)
    {
        // aturan jadwal dicek di server, jadi tidak bisa ditembus lewat URL langsung
        $setting = Settings::ambil();
        if (!$setting->votingBuka()) {
            return response()->json([
                'success' => false,
                'message' => $setting->pesanTutup()
            ], 403);
        }

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

        // update atomik: hanya berhasil kalau status masih 0
        $tersimpan = Siswa::where('token', $siswa->token)
            ->where('status', 0)
            ->update(['voted' => $kandidat->id, 'status' => 1]);

        if (!$tersimpan) {
            return response()->json([
                'success' => false,
                'message' => 'Kamu sudah melakukan voting'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Vote berhasil disimpan'
        ]);
    }

}
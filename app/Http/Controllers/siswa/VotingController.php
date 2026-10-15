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
        $pengaturan = Settings::ambil();
        $votingBuka = $pengaturan->votingBuka();
        $kandidat   = Kandidat::all();

        $siswa = [
            'nama'  => session('nama') ?? 'Siswa',
            'kelas' => session('kelas') ?? '-',
        ];

        $jam    = now('Asia/Jakarta')->hour;
        $sapaan = match (true) {
            $jam >= 4 && $jam < 11  => 'Selamat pagi',
            $jam >= 11 && $jam < 15 => 'Selamat siang',
            $jam >= 15 && $jam < 18 => 'Selamat sore',
            default                 => 'Selamat malam',
        };

        // dummy: nanti bisa dipindah ke tabel/config
        $langkahVoting = [
            ['ikon' => 'fa-user-check',      'judul' => 'Masuk dengan Token',   'deskripsi' => 'Login memakai token unik dari panitia. Jangan berikan token Anda kepada siapa pun.'],
            ['ikon' => 'fa-book-open',       'judul' => 'Kenali Kandidat',      'deskripsi' => 'Baca visi dan misi setiap kandidat. Klik kartu kandidat untuk melihat selengkapnya.'],
            ['ikon' => 'fa-check-to-slot',   'judul' => 'Pilih Satu Kandidat',  'deskripsi' => 'Tekan "Pilih Kandidat Ini" lalu konfirmasi. Suara hanya bisa diberikan satu kali.'],
            ['ikon' => 'fa-right-from-bracket', 'judul' => 'Selesai',           'deskripsi' => 'Setelah suara tersimpan, Anda otomatis keluar dari sistem.'],
        ];

        return view('siswa.voting.index', compact(
            'kandidat', 'pengaturan', 'votingBuka', 'siswa', 'sapaan', 'langkahVoting'
        ));
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
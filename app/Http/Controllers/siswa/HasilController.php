<?php

namespace App\Http\Controllers\siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kandidat;

class HasilController extends Controller
{
    public function index() 
    {
        $siswa = Siswa::where('token', session('token'))->first();
        $isGuru = $siswa && strcasecmp(trim((string) $siswa->kelas), 'guru') === 0;

        // Jika bukan guru dan belum voting, tolak dan alihkan ke bilik voting
        if (!$isGuru && (!$siswa || $siswa->status != 1)) {
            return redirect()->route('siswa.voting')->with('error', 'Silakan lakukan voting terlebih dahulu sebelum melihat hasil pemilihan.');
        }

        return view('siswa.hasil.index');
    }

    // mysql grafik : kandidat 
    public function statistik()
    {
        return response()->json(Siswa::static_hasil());
    }
    
    public function suaraKandidat()
    {
        $kandidat = Kandidat::orderBy('id')->get(); // ganti dari nomor_urut ke id

        $totalSuara = 0;
        $data = [];

        foreach ($kandidat as $k) {
            $suara = Siswa::where('voted', $k->id)->count();
            $totalSuara += $suara;
            $data[] = [
                'nomor_urut' => $k->id, // pakai id sebagai pengganti nomor urut
                'nama' => $k->nama,
                'suara' => $suara,
            ];
        }

        foreach ($data as &$d) {
            $d['persen'] = $totalSuara > 0 ? round(($d['suara'] / $totalSuara) * 100, 1) : 0;
        }

        return response()->json([
            'labels' => collect($data)->pluck('nama'),
            'suara' => collect($data)->pluck('suara'),
            'kandidat' => $data,
        ]);
    }

}
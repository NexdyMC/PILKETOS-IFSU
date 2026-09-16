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
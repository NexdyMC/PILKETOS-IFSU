<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Kandidat;
use App\Models\Settings;
use App\Models\Siswa;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    /**
     * API JSON: Data semua siswa untuk statistik / table dashboard admin.
     * Kolom 'voted' disembunyikan jika saklar view_voted bernilai false.
     */
    public function apiSiswa(): JsonResponse
    {
        $data = Siswa::all();

        if (!Settings::ambil()->view_voted) {
            $data = collect($data)->map(fn ($s) => collect($s)->except('voted')->all())->values();
        }

        return response()->json($data);
    }

    /**
     * API JSON: Data perolehan suara kandidat dan komposisi partisipasi untuk grafik realtime admin.
     */
    public function apiChartStats(): JsonResponse
    {
        $kandidats = Kandidat::orderBy('id')->get();
        $totalDpt = Siswa::count();
        $suaraMasuk = Siswa::where('status', 1)->count();
        $belumVoting = Siswa::where('status', 0)->count();

        $labels = [];
        $suara = [];
        $kandidatList = [];

        foreach ($kandidats as $k) {
            $count = Siswa::where('voted', $k->id)->count();
            $labels[] = $k->nama;
            $suara[] = $count;
            $kandidatList[] = [
                'id' => $k->id,
                'nama' => $k->nama,
                'kelas' => $k->kelas,
                'suara' => $count,
                'persen' => $suaraMasuk > 0 ? round(($count / $suaraMasuk) * 100, 1) : 0,
            ];
        }

        return response()->json([
            'labels' => $labels,
            'suara' => $suara,
            'totalDpt' => $totalDpt,
            'suaraMasuk' => $suaraMasuk,
            'belumVoting' => $belumVoting,
            'kandidat' => $kandidatList,
        ]);
    }
}
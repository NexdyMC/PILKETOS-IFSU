<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
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
}
<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Models\Kandidat;
use App\Models\Siswa;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'home');

        $data = $this->getTabData($tab);

        // kalau request AJAX (dari JS di atas), return partial saja
        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('admin.dashboard.' . $tab, $data);
        }
        
        return view('admin.dashboard.home', array_merge($data, ['tab' => $tab]));
    }

    private function getTabData($tab)
    {
        switch ($tab) {
            case 'home':
                return ['totalKandidat' => Kandidat::count()];
            case 'kandidat':
                return ['kandidat' => Kandidat::all()];
            default:
                return [];
        }
    }

    public function apiSiswa() 
    {
        $data = Siswa::all();

        if (!Settings::ambil()->view_voted) {
            $data = collect($data)->map(fn ($s) => collect($s)->except('voted')->all())->values();
        }

        return response()->json($data);
    }
}
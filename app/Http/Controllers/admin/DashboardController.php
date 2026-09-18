<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Kandidat;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'home');

        $data = $this->getTabData($tab);

        // kalau request AJAX (dari JS di atas), return partial saja
        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('admin.partials.' . $tab, $data);
        }

        // kalau akses langsung/refresh browser, return halaman penuh
        return view('admin.index', array_merge($data, ['tab' => $tab]));
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
}
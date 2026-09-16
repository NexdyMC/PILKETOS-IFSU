<?php

namespace App\Http\Controllers;

use App\Http\Controllers;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected array $allowedTabs = ['home', 'projects', 'settings'];

    public function index(Request $request)
    {
        $tab = $request->query('tab', 'home');

        if (!in_array($tab, $this->allowedTabs)) {
            $tab = 'home';
        }

        if ($request->ajax()) {
            return view("dashboard.partials.$tab");
        }

        return view('dashboard.index', compact('tab'));
    }
}
<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index() 
    {
        if (session('id_admin')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $admin = Admin::where('username', $request->username)->first();

        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Username tidak ditemukan'
            ], 404);
        }

        if ($request->password !== $admin->password) {
            return response()->json([
                'success' => false,
                'message' => 'Password salah'
            ], 401);
        }
        
        $request->session()->regenerate();
        
        session([
            'id_admin' => $admin->id_admin,
            'username' => $admin->username,
            'kelas'    => $admin->kelas,
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'Login berhasil',
            'redirect' => route('admin.dashboard')
        ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin')->with('success', 'Anda telah berhasil keluar.');
    }
}

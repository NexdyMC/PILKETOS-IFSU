<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    public function index() 
    {
        if (session('admin_id')) {
            return view('admin.dashboard');
        } else {
            return view('admin.login');
        }
    }


    public function login(Request $request)
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

        if (!($request->password == $admin->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password salah'
            ], 401);
        }

        session([
            'id_admin' => $admin->id_admin,
            'username' => $admin->username,
            'kelas' => $admin->kelas,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'redirect' => route('admin.dashboard') // sesuaikan nama route dashboard admin kamu
        ]);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:admins,username',
            'password' => 'required|min:6',
        ]);

        Admin::create([
            'username' => $request->username,
            'password' => Hash::make($request->password), // wajib di-hash
        ]);

        return response()->json(['success' => true, 'message' => 'Admin berhasil ditambahkan']);
    }

    public function logout(Request $request): RedirectResponse
    {
        // 1. Logout guard autentikasi
        Auth::logout();

        // 2. Hapus data session pengguna saat ini
        $request->session()->invalidate();

        // 3. Buat token CSRF baru untuk keamanan
        $request->session()->regenerateToken();

        // 4. Redirect ke halaman login atau beranda
        return redirect('/admin/login')->with('success', 'Anda telah berhasil keluar.');
    }
}

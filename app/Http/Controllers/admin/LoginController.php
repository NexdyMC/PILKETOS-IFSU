<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index() 
    {
        return view('admin.login');
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

        if (!Hash::check($request->password, $admin->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password salah'
            ], 401);
        }

        session([
            'admin_id' => $admin->id,
            'username' => $admin->username,
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
}

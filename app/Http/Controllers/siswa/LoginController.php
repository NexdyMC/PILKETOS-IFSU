<?php

namespace App\Http\Controllers\siswa;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class loginController extends Controller
{

    public function index() 
    {
        if (session()->has('token') && session()->has('nama')) {
            return redirect()->route('siswa.voting');
        }

        return view('siswa.login');
    }
    public function login(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'token' => ['required', 'string']
        ]);

        // 2. Cari data siswa berdasarkan token
        $siswa = Siswa::where('token', $request->token)->first();

        // 3. Cek apakah token ada
        if (!$siswa) {
            return response()->json([
                'success' => false, 
                'message' => 'Token tidak valid'
            ], 401); // Gunakan 401 Unauthorized
        }

        // 4. Cek apakah siswa sudah voting
        if ($siswa->status == 1) {
            return response()->json([
                'success' => false, 
                'message' => 'Kamu sudah melakukan voting'
            ], 403); // 403 Forbidden
        }

        // 5. Regenerasi session ID untuk keamanan
        $request->session()->regenerate();

        // 6. Simpan data siswa ke session
        session([
            'token' => $siswa->token,
            'nama'  => $siswa->nama,
            'kelas' => $siswa->kelas,
            'is_logged_in' => true // Opsional: flag login
        ]);

        // 7. Berikan response JSON
        return response()->json([
            'success'  => true,
            'message'  => 'Login berhasil',
            'redirect' => route('siswa.voting')
        ]);
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
        return redirect('/siswa/login')->with('success', 'Anda telah berhasil keluar.');
    }
}

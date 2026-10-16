<?php

namespace App\Http\Controllers\siswa;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index() 
    {
        if (session()->has('token') && session()->has('nama')) {
            $siswa = Siswa::where('token', session('token'))->first();
            if ($siswa && $siswa->status == 1) {
                return redirect()->route('siswa.hasil');
            }
            return redirect()->route('siswa.voting');
        }

        return view('siswa.login');
    }

    public function login(Request $request): JsonResponse
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
            ], 401);
        }

        $isGuru = strcasecmp(trim((string) $siswa->kelas), 'guru') === 0;

        // 4. Cek apakah pemilih sudah voting
        // Siswa yang sudah voting ditolak; Guru yang sudah voting tetap diizinkan login
        if ($siswa->status == 1 && !$isGuru) {
            return response()->json([
                'success' => false, 
                'message' => 'Kamu sudah melakukan voting'
            ], 403);
        }

        // 5. Regenerasi session ID untuk keamanan
        $request->session()->regenerate();

        // 6. Simpan data siswa ke session
        session([
            'token'        => $siswa->token,
            'nama'         => $siswa->nama,
            'kelas'        => $siswa->kelas,
            'status'       => (int) $siswa->status,
            'is_logged_in' => true,
        ]);

        // 7. Berikan response JSON
        // Guru yang sudah voting langsung diarahkan ke halaman hasil
        $targetRoute = ($isGuru && $siswa->status == 1)
            ? route('siswa.hasil')
            : route('siswa.voting');

        return response()->json([
            'success'  => true,
            'message'  => 'Login berhasil',
            'redirect' => $targetRoute
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
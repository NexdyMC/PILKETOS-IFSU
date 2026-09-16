<?php

namespace App\Http\Controllers\siswa;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Siswa;

class loginController extends Controller
{

    public function showLogin() 
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate(['token' => 'required']);

        // testing tanpa database
        // $siswa = Siswa::findByTokenDummy($request->token);
        $siswa = Siswa::where('token', $request->token)->first();


        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'Token tidak valid'], 404);
        }

        if ($siswa->status == 1) {
            return response()->json(['success' => false, 'message' => 'Kamu sudah melakukan voting'], 403);
        }

        session([
            'token' => $siswa->token,
            'nama' => $siswa->nama,
            'kelas' => $siswa->kelas
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil (mode testing)',
            'redirect' => route('siswa.voting')
        ]);
    }
}

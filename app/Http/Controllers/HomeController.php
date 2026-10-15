<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kandidat;
use App\Models\Settings;
use App\Models\Siswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

class HomeController extends Controller
{
    private const SESSION_KEYS = ['user', 'siswa', 'token'];

    private const BERAKHIR_DEFAULT = '2026-09-26 08:00:00';

    public function index()
    {
        $siswa  = $this->siswaDariSesi();
        $voting = $this->statusVoting();

        $sudahMemilih = $siswa && (int) $siswa->status === 1;

        return response()
            ->view('index', [
                'kandidat'     => Kandidat::orderBy('id')->get(),
                'siswa'        => $siswa,
                'sudahMemilih' => $sudahMemilih,
                'pilihanId'    => $sudahMemilih ? $siswa->voted : null,
                'voting'       => $voting,
                'berakhir'     => $voting['berakhir'],
                'loginUrl'     => Route::has('siswa.login') ? route('siswa.login') : url('/siswa'),
                'logoutUrl'    => Route::has('siswa.logout') ? route('siswa.logout') : url('/siswa/logout'),
            ])
            // halaman memuat token CSRF & status login: jangan sampai di-cache (penting di HP / tombol back)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    public function vote(Kandidat $kandidat): JsonResponse
    {
        $siswa = $this->siswaDariSesi();
        if (!$siswa) {
            return response()->json(['message' => 'Sesi login tidak ditemukan. Silakan login ulang.'], 401);
        }
 
        $setting = Settings::ambil();
        if (!$setting->votingBuka()) {
            return response()->json(['message' => $setting->pesanTutup()], 403);
        }
 
        // satu query atomik: hanya berhasil jika siswa ini masih belum memilih
        $berhasil = Siswa::where('token', $siswa->token)
            ->where('status', 0)
            ->update(['status' => 1, 'voted' => $kandidat->id]);
 
        if (!$berhasil) {
            return response()->json(['message' => 'Kamu sudah menggunakan hak pilihmu. Suara tidak bisa diubah.'], 409);
        }
 
        return response()->json([
            'message'  => 'Vote berhasil disimpan.',
            'kandidat' => $kandidat->nama,
        ]);
    }
 
    /** Ambil siswa yang sedang login dari session (selalu dibaca ulang dari database). */
    private function siswaDariSesi(): ?Siswa
    {
        // Cek langsung session token (yang diset oleh Siswa\LoginController)
        if (session()->has('token')) {
            $token = session('token');
            $siswa = Siswa::find($token);
            if ($siswa) {
                return $siswa;
            }
        }

        foreach (self::SESSION_KEYS as $key) {
            $data  = session($key);
            $token = is_array($data) ? ($data['token'] ?? null) : (is_object($data) ? ($data->token ?? null) : null);
 
            if ($token) {
                $siswa = Siswa::find($token);
                if ($siswa) {
                    return $siswa;
                }
            }
        }
        return null;
    }
 
    /** Status voting memanfaatkan model Settings yang sudah di-cache */
    private function statusVoting(): array
    {
        $setting = Settings::ambil();
        $buka = $setting->votingBuka();

        return [
            'buka'     => $buka,
            'pesan'    => $buka ? '' : $setting->pesanTutup(),
            'berakhir' => $setting->waktu_selesai ? $setting->waktu_selesai->timestamp : Carbon::parse(self::BERAKHIR_DEFAULT)->timestamp,
        ];
    }
}

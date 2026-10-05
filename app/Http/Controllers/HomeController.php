<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Kandidat;
use App\Models\Siswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class HomeController extends Controller
{
    private const SESSION_KEYS = ['user', 'siswa'];

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
 
        $voting = $this->statusVoting();
        if (!$voting['buka']) {
            return response()->json(['message' => $voting['pesan']], 403);
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
 
    /** Status voting dari tb_settings (status_voting, waktu_mulai, waktu_selesai). */
    private function statusVoting(): array
    {
        $now = Carbon::now();
        $pengaturan = null;
 
        try {
            $pengaturan = DB::table('tb_settings')->first();
        } catch (\Throwable $e) {
            report($e);
        }
 
        $mulai   = $pengaturan && data_get($pengaturan, 'waktu_mulai')   ? Carbon::parse($pengaturan->waktu_mulai)   : null;
        $selesai = $pengaturan && data_get($pengaturan, 'waktu_selesai') ? Carbon::parse($pengaturan->waktu_selesai) : null;
 
        $buka  = true;
        $pesan = '';
 
        if ($pengaturan && (int) data_get($pengaturan, 'status_voting', 1) !== 1) {
            $buka  = false;
            $pesan = 'Voting sedang ditutup oleh panitia.';
        } elseif ($mulai && $now->lt($mulai)) {
            $buka  = false;
            $pesan = 'Voting belum dimulai.';
        } elseif ($selesai && $now->gt($selesai)) {
            $buka  = false;
            $pesan = 'Waktu voting telah berakhir.';
        }
 
        return [
            'buka'     => $buka,
            'pesan'    => $pesan,
            'berakhir' => ($selesai ?: Carbon::parse(self::BERAKHIR_DEFAULT))->timestamp,
        ];
    }
}

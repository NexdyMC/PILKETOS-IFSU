<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'tb_siswa';
    protected $primaryKey = 'token';
    public $incrementing = false;
    // protected $keyType = 'string';
    // protected $fillable = ['id', 'token', 'nama', 'kelas', 'status', 'voted', 'last_active_at', 'is_logged_in'];


    public static function static_hasil()
    {
        $totalSiswa = self::count();
        $sudahVoting = self::where('status', 1)->count();
        $belumVoting = $totalSiswa - $sudahVoting;
        $totalKandidat = Kandidat::count();

        $partisipasi = $totalSiswa > 0 ? round(($sudahVoting / $totalSiswa) * 100) : 0;
        $belumPersen = $totalSiswa > 0 ? round(($belumVoting / $totalSiswa) * 100) : 0;

        return [
            'total_suara' => $sudahVoting,
            'partisipasi' => $partisipasi,
            'belum_voting' => $belumPersen,
            'kandidat' => $totalKandidat,
        ];
    }

    // // ⚠️ dummy data untuk testing, TIDAK connect ke database
    // public static function dummyData()
    // {
    //     return [
    //         'id' => 'S001',
    //         'token' => 'NXYZ',
    //         'nama' => 'Siswa Testing',
    //         'kelas' => 'XII RPL 1',
    //         'status' => 1,
    //         'voted' => null,
    //     ];
    // }

    // // override method cari token, pakai dummy bukan query DB
    // public static function findByTokenDummy($token)
    // {
    //     $dummy = self::dummyData();

    //     if ($token === $dummy['token']) {
    //         $siswa = new self();
    //         $siswa->id = $dummy['id'];
    //         $siswa->token = $dummy['token'];
    //         $siswa->nama = $dummy['nama'];
    //         $siswa->kelas = $dummy['kelas'];
    //         $siswa->status = $dummy['status'];
    //         $siswa->voted = $dummy['voted'];
    //         return $siswa;
    //     }

    //     return null;
    // }
}
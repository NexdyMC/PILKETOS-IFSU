<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'tb_siswa';
    protected $primaryKey = 'token';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false; // tambahkan ini — tabel tidak punya created_at/updated_at
    protected $fillable = ['token', 'token', 'nama', 'kelas', 'status', 'voted'];

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
}
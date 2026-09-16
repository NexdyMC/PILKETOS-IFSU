<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'tb_admin';
    protected $primaryKey = 'id_admin';
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
}
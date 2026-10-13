<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('tb_settings')->exists()) {
            return; // tabel hanya berisi satu baris
        }

        DB::table('tb_settings')->insert([
            'nama_sekolah'    => 'SMK INFORMATIKA SUMEDANG',
            'judul_pemilihan' => 'E-VOTE OSIS',
            'tahun_ajaran'    => '2026-2027',
            'status_voting'   => 0,
            'view_voted'      => 0,
            'waktu_mulai'     => null,
            'waktu_selesai'   => null,
            'logo_sekolah'    => 'users.png',
        ]);

        Settings::hapusCache();
    }
}
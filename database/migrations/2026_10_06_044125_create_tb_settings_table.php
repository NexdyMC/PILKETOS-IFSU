<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_settings', function (Blueprint $table) {
            $table->id('id_settings');
            $table->string('nama_sekolah', 100);
            $table->string('judul_pemilihan', 100);
            $table->string('tahun_ajaran', 20);
            $table->tinyInteger('status_voting')->default(0);
            $table->tinyInteger('view_voted')->default(0);
            $table->dateTime('waktu_mulai')->nullable();
            $table->dateTime('waktu_selesai')->nullable();
            $table->string('logo_sekolah', 100)->nullable();
            $table->timestamps();
        });
        DB::table('tb_settings')->insert([
            'nama_sekolah'      => 'SMK INFORMATIKA SUMEDANG',
            'judul_pemilihan' => 'E-VOTE OSIS',
            'tahun_ajaran'    => '2026-2027',
            'status_voting'   => 0,
            'view_voted'      => 0,
            'waktu_mulai'     => now(),
            'waktu_selesai'   => now(),
            'logo_sekolah'    => null,
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_settings');
    }
};
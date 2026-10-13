<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_settings');
    }
};
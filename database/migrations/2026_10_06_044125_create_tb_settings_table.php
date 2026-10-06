<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_settings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sekolah', 40);
            $table->string('judul_pemilihan', 30);
            $table->string('tahun_ajaran', 20);
            $table->enum('status_voting', ['0', '1'])->default('0');
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_selesai');
            $table->string('logo_sekolah', 40)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_settings');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tb_siswa', function (Blueprint $table) {
            $table->string('nama', 100);
            $table->string('kelas', 50);
            $table->string('token', 10)->primary();
            $table->integer('status')->default(0);
            $table->integer('voted')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_siswa');
    }
};

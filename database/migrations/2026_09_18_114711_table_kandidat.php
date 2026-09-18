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
        Schema::create('tb_kandidat', function (Blueprint $table) {
            $table->integer('id_kandidat')->primary()->autoIncrement();
            $table->string('nama', 100);
            $table->string('kelas', 20);
            $table->text('visi');
            $table->text('misi');
            $table->string('image', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_kandidats');
    }
};

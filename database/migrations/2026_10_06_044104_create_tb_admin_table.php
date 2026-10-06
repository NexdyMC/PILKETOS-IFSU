<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::create('tb_admin', function (Blueprint $table) {
        $table->id('id_admin');
        $table->string('admin', 50);
        $table->string('kelas', 20);
        $table->string('username', 50);
        $table->string('password', 255);
        $table->timestamps();
      });

      DB::table('tb_admin')->insert([
        'admin'      => 'Febri Pratama',
        'kelas'      => '12 RPL 1',
        'username'   => 'admin',
        'password'   => 'piketos2026',
        'created_at' => now(),
        'updated_at' => now(),
      ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_admin');
    }
};

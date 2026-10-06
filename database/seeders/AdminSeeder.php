<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tb_admin')->insert([
            'admin'      => 'Febri Pratama',
            'kelas'      => '12 RPL 1',
            'username'   => 'admin',
            'password'   => 'piketos2026',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
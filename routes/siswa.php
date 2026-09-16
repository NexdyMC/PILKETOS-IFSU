<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\siswa\loginController;
use App\Http\Controllers\siswa\VotingController;
use App\Http\Controllers\siswa\HasilController;

Route::get('/siswa', function () {
  return view('siswa.login');
});

Route::post('/siswa/check', [loginController::class, 'login'])->name('siswa.check');

Route::get('/siswa/voting', [VotingController::class, 'index'])->name('siswa.voting');

Route::get('/siswa/hasil', [HasilController::class, 'index'])->name('siswa.hasil');

Route::get('/siswa/hasil/statistik', [HasilController::class, 'statistik']);

Route::get('/siswa/hasil/suara-kandidat', [HasilController::class, 'suaraKandidat']);
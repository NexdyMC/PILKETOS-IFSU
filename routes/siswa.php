<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\siswa\LoginController;
use App\Http\Controllers\siswa\VotingController;
use App\Http\Controllers\siswa\HasilController;
use GuzzleHttp\Middleware;

Route::middleware(['siswa.guest'])->group(function () {
  Route::get('/siswa', [LoginController::class, 'index'])->name('siswa.index');

  Route::get('/siswa/login', [LoginController::class, 'index'])->name('siswa.login');
});

Route::post('/siswa/check', [LoginController::class, 'login'])->name('siswa.check');

Route::get('/siswa/logout', [LoginController::class, 'logout'])->name('siswa.logout');

Route::middleware(['siswa.token'])->group(function () {

  Route::get('/siswa/voting', [VotingController::class, 'index'])->name('siswa.voting');
  
  Route::post('/siswa/vote', [VotingController::class, 'vote'])->name('siswa.vote');
  
  Route::get('/siswa/hasil', [HasilController::class, 'index'])->name('siswa.hasil');
  
  Route::get('/siswa/hasil/statistik', [HasilController::class, 'statistik'])->name('siswa.hasil.statis');
  
  Route::get('/siswa/hasil/suara-kandidat', [HasilController::class, 'suaraKandidat'])->name('siswa.hasil.kandidat');
});

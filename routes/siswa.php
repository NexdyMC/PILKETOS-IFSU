<?php

use App\Http\Controllers\admin\LoginController as AdminLoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\siswa\loginController;
use App\Http\Controllers\siswa\VotingController;
use App\Http\Controllers\siswa\HasilController;
use GuzzleHttp\Middleware;

Route::middleware(['siswa.guest'])->group(function () {
  Route::get('/siswa', [loginController::class, 'index'])->name('siswa.index');

  Route::get('/siswa/login', [loginController::class, 'index'])->name('siswa.login');
});

Route::post('/siswa/check', [loginController::class, 'login'])->name('siswa.check');

Route::get('/siswa/logout', [loginController::class, 'logout'])->name('siswa.logout');

Route::middleware(['siswa.token'])->group(function () {

  Route::get('/siswa/voting', [VotingController::class, 'index'])->name('siswa.voting');
  
  Route::post('/siswa/vote', [VotingController::class, 'vote'])->name('siswa.vote');
  
  Route::get('/siswa/hasil', [HasilController::class, 'index'])->name('siswa.hasil');
  
  Route::get('/siswa/hasil/statistik', [HasilController::class, 'statistik'])->name('siswa.hasil.statis');
  
  Route::get('/siswa/hasil/suara-kandidat', [HasilController::class, 'suaraKandidat'])->name('siswa.hasil.kandidat');
});

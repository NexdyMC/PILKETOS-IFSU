<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/vote/{kandidat}', [HomeController::class, 'vote'])->name('home.vote');

Route::redirect('/dashboard', '/admin/dashboard')->name('dashboard');

require __DIR__ . '/admin.php';
require __DIR__ . '/siswa.php';
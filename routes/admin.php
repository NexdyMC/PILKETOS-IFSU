<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\LoginController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\UploadController;
use App\Livewire\Dashboard\Home;
use App\Livewire\Dashboard\Siswa;

Route::get('/admin', function () {
    return view('admin.login');
});

Route::post('/admin/login', [LoginController::class, 'login'])->name('admin.check');

Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

Route::get('/admin/content/{tab}', [DashboardController::class, 'loadContent'])->name('admin.content');

Route::get('/admin/upload', [UploadController::class, 'create'])->name('admin.upload');

Route::post('/admin/upload', [UploadController::class, 'store'])->name('kandidat.store');


Route::get('/dashboard', Home::class)->name('dashboard.home');

Route::get('/dashboard/siswa', Siswa::class)->name('dashboard.siswa');

// Route::get('/dashboard', Home::class)->name('admin.dashboard.home');

// Route::get('/dashboard/kandidat', Kandidat::class)->name('admin.dashboard.kandidat');

// Route::get('/dashboard/siswa', Siswa::class)->name('admin.dashboard.siswa');

// Route::get('/dashboard/settings', Settings::class)->name('admin.dashboard.settings');

// Route::get('/dashboard/export', Export::class)->name('admin.dashboard.export');
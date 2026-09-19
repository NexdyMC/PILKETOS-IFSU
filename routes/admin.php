<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\LoginController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\UploadController;
use App\Livewire\Dashboard\Home;
use App\Livewire\Dashboard\Siswa;
use App\Livewire\Dashboard\Kandidat;
use App\Livewire\Dashboard\Export;
use App\Livewire\Dashboard\Settings;

Route::get('/admin', function () {
    return view('admin.login');
});

Route::post('/admin/login', [LoginController::class, 'login'])->name('admin.check');

Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

Route::get('/admin/content/{tab}', [DashboardController::class, 'loadContent'])->name('admin.content');

Route::get('/admin/upload', [UploadController::class, 'create'])->name('admin.upload');

Route::post('/admin/upload', [UploadController::class, 'store'])->name('kandidat.store');

Route::get('/admin/dashboard', Home::class)->name('admin.dashboard.home');

Route::get('/admin/dashboard/kandidat', Kandidat::class)->name('admin.dashboard.kandidat');

Route::get('/admin/dashboard/siswa', Siswa::class)->name('admin.dashboard.siswa');

Route::get('/admin/dashboard/settings', Settings::class)->name('admin.dashboard.settings');

Route::get('/admin/dashboard/export', Export::class)->name('admin.dashboard.export');
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\LoginController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\UploadController;
use App\Http\Controllers\admin\KandidatController;
use App\Http\Controllers\admin\SiswaController;
use App\Http\Controllers\admin\ExportExcelController;
use App\Livewire\Dashboard\Home;
use App\Livewire\Dashboard\Siswa;
use App\Livewire\Dashboard\Kandidat;
use App\Livewire\Dashboard\Export;
use App\Livewire\Dashboard\Settings;
use App\Imports\SiswaImport;

Route::get('/admin', function () {
    return view('admin.login');
});

Route::post('/admin/login', [LoginController::class, 'login'])->name('admin.check');

Route::get('/admin/logout', [LoginController::class, 'logout'])->name('admin.logout');

Route::get('/admin/upload', [UploadController::class, 'create'])->name('admin.upload');

Route::post('/admin/upload', [UploadController::class, 'store'])->name('kandidat.store');

Route::get('/admin/dashboard', Home::class)->name('admin.dashboard');

Route::get('/admin/dashboard/kandidat', Kandidat::class)->name('admin.dashboard.kandidat');

Route::get('/admin/dashboard/siswa', Siswa::class)->name('admin.dashboard.siswa');

Route::get('/admin/dashboard/settings', Settings::class)->name('admin.dashboard.settings');

Route::get('/admin/dashboard/export', Export::class)->name('admin.dashboard.export');

Route::post('/siswa/import', [ExportExcelController::class, 'import'])->name('siswa.import');

Route::post('/admin/siswa', [SiswaController::class, 'store'])->name('admin.siswa.store');

Route::post('/admin/siswa/reset', [SiswaController::class, 'reset'])->name('admin.siswa.reset');

Route::prefix('admin/kandidat')->group(function () {
    Route::get('data', [KandidatController::class, 'list'])->name('kandidat.api.list');
    Route::post('/', [KandidatController::class, 'store'])->name('kandidat.api.store');
    Route::put('{kandidat}', [KandidatController::class, 'update'])->name('kandidat.api.update');
    Route::delete('{kandidat}', [KandidatController::class, 'destroy'])->name('kandidat.api.destroy');
});

Route::get('/api/admin/siswa', [DashboardController::class, 'apiSiswa']);
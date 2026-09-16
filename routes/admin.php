<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\LoginController;
use App\Http\Controllers\admin\DashboardController;

Route::get('/admin', function () {
    return view('admin.login');
});

Route::post('/admin/login', [LoginController::class, 'login'])->name('admin.check');

Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

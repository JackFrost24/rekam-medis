<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;


Route::get('/', function () {
    return redirect('/login');
});


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Admin
Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

// Dokter
Route::get('/pasien', [DokterController::class, 'index'])->name('dokter.pasien');
Route::get('/jadwal', [DokterController::class, 'schedule'])->name('dokter.jadwal');

//Login
Route::get('/login', function () {
    return view('auth.login', [
        'title' => 'Login'
    ]);
})->name('login');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// <- tambahkan ini di paling bawah file web.php
require __DIR__.'/auth.php';

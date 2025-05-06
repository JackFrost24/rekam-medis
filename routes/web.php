<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OdontogramController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ScheduleController;

Route::get('/', function () {
    return redirect('/login');
});

// Dokter
Route::get('/dokter/dashboard', [DokterController::class, 'dashboard']);

// Admin
Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);


// Dokter
Route::get('/pasien', [DokterController::class, 'index'])->name('dokter.pasien');
Route::get('/jadwal', [DokterController::class, 'schedule'])->name('dokter.jadwal');
Route::get('/odontogram/{id}/view-model', [OdontogramController::class, 'viewModel'])->name('odontogram.viewModel');
Route::get('/dokter/pasien/input', function () {
    return view('dokter.input-pasien');
});

Route::resource('patients', PatientController::class);

//Login
Route::get('/login', function () {
    return view('auth.login', [
        'title' => 'Login'
    ]);
})->name('login');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


//sementara
Route::post('/api/patients', [PatientController::class, 'store']);


Route::middleware(['auth'])->group(function () {
    Route::get('/jadwal', [ScheduleController::class, 'index'])->name('jadwal.index');
    Route::post('/jadwal', [ScheduleController::class, 'store'])->name('jadwal.store');
    Route::get('/jadwal/{id}/edit', [ScheduleController::class, 'edit'])->name('jadwal.edit');
    Route::put('/jadwal/{id}', [ScheduleController::class, 'update'])->name('jadwal.update');
    Route::get('/jadwal/create', [ScheduleController::class, 'create'])->name('jadwal.create');
    Route::delete('/jadwal/{id}', [ScheduleController::class, 'destroy'])->name('jadwal.destroy');
});

Route::resource('patients', PatientController::class)->only([
    'create', 'store', 'show'
]);
Route::get('/dokter/pasien/input', [DokterController::class, 'inputPasien'])->name('dokter.input-pasien');



// <- tambahkan ini di paling bawah file web.php
require __DIR__.'/auth.php';

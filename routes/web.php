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
use App\Http\Controllers\AppointmentController;

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


Route::middleware(['auth', 'role:dokter'])->group(function() {
    // Gunakan form request untuk validasi
    Route::post('/appointments', [AppointmentController::class, 'store'])
         ->name('appointments.store');
});
     

Route::resource('patients', PatientController::class)->only([
    'create', 'store', 'show'
]);
Route::get('/dokter/pasien/input', [DokterController::class, 'inputPasien'])->name('dokter.input-pasien');

// Route untuk form input
Route::get('/patients/create', [PatientController::class, 'create'])
     ->name('patients.create');

// Route untuk menyimpan data
Route::post('/patients', [PatientController::class, 'store'])
     ->name('patients.store');

// Route untuk viewer 3D
Route::get('/patients/{patient}/odontogram', [PatientController::class, 'showOdontogram'])->name('patients.odontogram');


// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
     ->name('dashboard')
     ->middleware(['auth']);

// Patients
Route::resource('patients', PatientController::class)
     ->middleware(['auth']);

// Appointments
Route::resource('appointments', AppointmentController::class)
     ->middleware(['auth']);

     
// <- tambahkan ini di paling bawah file web.php
require __DIR__.'/auth.php';

<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PatientController; // Tambahkan ini
use App\Http\Controllers\DokterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OdontogramController;
use App\Http\Controllers\ScheduleController;

// ========================
// PUBLIC ROUTES
// ========================
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes (Login/Register)
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
});

// ========================
// AUTHENTICATED ROUTES
// ========================
Route::middleware(['auth'])->group(function () {
    // Logout
    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/login');
    })->name('logout');

    // Dashboard Redirect
    Route::get('/dashboard', function () {
        return Auth::user()->role === 'admin' 
            ? redirect()->route('admin.dashboard')
            : redirect()->route('dokter.dashboard');
    })->name('dashboard');
});

// ========================
// ADMIN ROUTES
// ========================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/reset-password', [AdminController::class, 'editPassword'])->name('admin.reset_password');
    Route::post('/reset-password', [AdminController::class, 'updatePassword'])->name('admin.update_password');
});

// ========================
// DOKTER ROUTES
// ========================
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':dokter'])
    ->prefix('dokter')
    ->name('dokter.')
    ->group(function () {
        
    // Dashboard
    Route::get('/dashboard', [DokterController::class, 'dashboard'])->name('dashboard');
    
    // Manajemen Pasien (Ganti ke PatientController)
    Route::prefix('pasien')->name('pasien.')->group(function () {
        Route::get('/', [PatientController::class, 'index'])->name('index');
        Route::get('/create', [PatientController::class, 'create'])->name('create');
        Route::post('/', [PatientController::class, 'store'])->name('store');
        Route::get('/{patient}', [PatientController::class, 'show'])->name('show');
        Route::get('/{patient}/edit', [PatientController::class, 'edit'])->name('edit');
        Route::put('/{patient}', [PatientController::class, 'update'])->name('update');
        Route::delete('/{patient}', [PatientController::class, 'destroy'])->name('destroy');
        
        // Pencarian pasien
        Route::get('/search', [PatientController::class, 'search'])->name('search');
    });
    
    // Odontogram
    Route::prefix('odontogram')->name('odontogram.')->group(function () {
        Route::get('/{id}/view-model', [OdontogramController::class, 'viewModel'])
            ->name('view-model');
        Route::get('/3d-viewer', [OdontogramController::class, 'show3dModel'])
            ->name('3d-viewer');
        Route::post('/{id}/save', [OdontogramController::class, 'store'])
            ->name('store');
            
        // Tambahan route untuk odontogram pasien
        Route::post('/patient/{patient}/save', [OdontogramController::class, 'savePatientOdontogram'])
            ->name('patient.save');
    });
    
    // Jadwal
    Route::resource('jadwal', ScheduleController::class)->except(['show']);
});

// ========================
// API ROUTES
// ========================
Route::prefix('api')->middleware('auth')->group(function () {
    Route::get('/patients/search', [PatientController::class, 'search'])->name('api.patients.search');
});

// ========================
// AUTH ROUTES (Default Laravel)
// ========================
require __DIR__.'/auth.php';
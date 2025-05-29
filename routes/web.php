<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':dokter'])->prefix('dokter')->name('dokter.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DokterController::class, 'dashboard'])->name('dashboard');
    
    // Manajemen Pasien
    Route::prefix('pasien')->name('pasien.')->group(function () {
        Route::get('/', [DokterController::class, 'index'])->name('index');
        Route::get('/create', [DokterController::class, 'create'])->name('create');
        Route::post('/', [DokterController::class, 'store'])->name('store');
        Route::get('/{id}', [DokterController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [DokterController::class, 'edit'])->name('edit');
        Route::put('/{id}', [DokterController::class, 'update'])->name('update');
        Route::delete('/{id}', [DokterController::class, 'destroy'])->name('destroy');
    });
    
    // Odontogram
Route::prefix('odontogram')->name('odontogram.')->group(function () {
    Route::get('/{id}/view-model', [OdontogramController::class, 'viewModel'])
        ->name('view-model');
    Route::get('/3d-viewer', [OdontogramController::class, 'show3dModel'])
        ->name('3d-viewer');
    Route::post('/{id}/save', [OdontogramController::class, 'store'])
        ->name('store');
});
    
    // Jadwal
    Route::resource('jadwal', ScheduleController::class)->except(['show']);
});

// ========================
// API ROUTES (jika diperlukan)
// ========================
Route::prefix('api')->middleware('auth')->group(function () {
    Route::get('/patients/search', [DokterController::class, 'search'])->name('api.patients.search');
});

// ========================
// AUTH ROUTES (Default Laravel)
// ========================
require __DIR__.'/auth.php';
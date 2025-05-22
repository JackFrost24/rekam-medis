<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AdminController,
    DokterController,
    PatientController,
    AppointmentController,
    OdontogramController,
    ProfileController,
    DashboardController
};

use App\Http\Controllers\Auth\{
    AuthenticatedSessionController,
    RegisteredUserController,
    PasswordResetLinkController,
    NewPasswordController,
    EmailVerificationNotificationController,
    EmailVerificationPromptController,
    VerifyEmailController,
    ConfirmablePasswordController,
    PasswordController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Auth Routes (Tanpa Middleware Auth)
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');
    
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

// Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard Umum
    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');
    
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    
    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
    
    // Email Verification
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
    
    // Password Confirmation
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
    
    // Password Update
    Route::put('password', [PasswordController::class, 'update'])
        ->name('password.update');
});

// Admin Routes
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');
    
    // Tambahkan route admin khusus di sini
});

// Dokter Routes
Route::middleware(['auth', 'verified', 'role:dokter'])->prefix('dokter')->group(function () {
    Route::get('dashboard', [DokterController::class, 'dashboard'])
        ->name('dokter.dashboard');
    
    // Patients Management
    Route::get('patients', [DokterController::class, 'index'])
        ->name('dokter.patients.index');
    Route::get('patients/create', [DokterController::class, 'createPatient'])
        ->name('dokter.patients.create');
    Route::post('patients', [DokterController::class, 'storePatient'])
        ->name('dokter.patients.store');
    
    // Appointments
    Route::get('appointments', [DokterController::class, 'appointments'])
        ->name('dokter.appointments.index');
});

// Shared Resource Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Patients Resource (untuk semua role yang berwenang)
    Route::resource('patients', PatientController::class)->except(['create', 'store']);
    
    // Appointments Resource
    Route::resource('appointments', AppointmentController::class);
    
    // Odontogram
    Route::get('patients/{patient}/odontogram', [PatientController::class, 'showOdontogram'])
        ->name('patients.odontogram');
    Route::get('odontogram/{id}/view-model', [OdontogramController::class, 'viewModel'])
        ->name('odontogram.viewModel');
});
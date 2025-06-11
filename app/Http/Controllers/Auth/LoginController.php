<?php

// app/Http/Controllers/Auth/LoginController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email dokter wajib diisi',
            'password.required' => 'Password wajib diisi'
        ]);
    }

    protected function authenticated(Request $request, $user)
    {
        // Tambahkan logika khusus setelah login sukses
        return redirect()->intended($this->redirectPath());
    }

    // Tambahkan di LoginController
    protected function credentials(Request $request)
    {
        return [
            'email' => $request->email,
            'password' => $request->password,
            'email' => env('DOCTOR_EMAIL') // Sesuaikan dengan .env
        ];
    }
}
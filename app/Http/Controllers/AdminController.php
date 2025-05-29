<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
{
    return view('admin.AdminDashboard');
}

public function editPassword()
{
    $dokter = \App\Models\User::where('role', 'dokter')->firstOrFail();
    return view('admin.reset_password', compact('dokter'));
}

public function updatePassword(Request $request)
{
    $request->validate([
        'password' => 'required|string|min:8|confirmed',
    ]);

    $dokter = \App\Models\User::where('role', 'dokter')->firstOrFail();
    $dokter->password = bcrypt($request->password);
    $dokter->save();

    return redirect()->route('admin.dashboard')->with('success', 'Password dokter berhasil direset.');
}


}
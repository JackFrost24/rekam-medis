<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DokterController extends Controller
{
    public function dashboard()
{
    return view('dokter.DokterDashboard');
}

public function inputPasien()
{
    $bloodTypes = [
        'A' => 'A',
        'B' => 'B',
        'AB' => 'AB',
        'O' => 'O'
    ];

    return view('dokter.input-pasien', compact('bloodTypes'));
}
}
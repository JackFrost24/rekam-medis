<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\Patient; // Menggunakan model Patient bukan Pasien

class DashboardController extends Controller
{
    public function index()
    {
        $doctorId = Auth::id();
        
        // Hitung total pasien yang pernah berobat ke dokter ini
        $totalPatients = Patient::whereHas('appointments', function($query) use ($doctorId) {
            $query->where('user_id', $doctorId);
        })->count();

        // Ambil 5 appointment mendatang
        $upcomingAppointments = Appointment::with('patient')
            ->where('user_id', $doctorId)
            ->upcoming() // Menggunakan scope yang sudah dibuat
            ->take(5)
            ->get();

        return view('dokter.dashboard', [
            'totalPatients' => $totalPatients,
            'upcomingAppointments' => $upcomingAppointments
        ]);
    }
}
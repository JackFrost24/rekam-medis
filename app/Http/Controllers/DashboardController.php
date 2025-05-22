<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_patients' => Patient::count(),
            'today_appointments' => Appointment::whereDate('appointment_date', today())->count(),
            'upcoming_appointments' => Appointment::whereDate('appointment_date', '>=', today())
                ->orderBy('appointment_date')
                ->limit(5)
                ->get()
        ];

        return view('dashboard', compact('stats'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAppointmentRequest;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['patient', 'doctor'])
                        ->where('appointment_date', '>=', now())
                        ->orderBy('appointment_date')
                        ->paginate(10);
                        
        return view('appointments.index', compact('appointments'));
    }

    public function create()
    {
        $patients = Patient::all();
        $doctors = User::where('role', 'dokter')->get();
        
        return view('appointments.create', compact('patients', 'doctors'));
    }

    public function store(StoreAppointmentRequest $request)
{
    // Data sudah divalidasi
    $validated = $request->validated();
    
    Appointment::create($validated);

    return redirect()->route('appointments.index')
                     ->with('success', 'Appointment created successfully.');
}

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        
        return redirect()->route('appointments.index')
                         ->with('success', 'Appointment deleted successfully.');
    }
}
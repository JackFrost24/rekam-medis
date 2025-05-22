<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User; // Pastikan model User di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DokterController extends Controller
{
    public function dashboard()
    {
        /** @var User $user */
        $user = Auth::user();
        
        $appointments = Appointment::where('user_id', $user->id) // Menggunakan $user->id
            ->whereDate('appointment_date', '>=', now())
            ->orderBy('appointment_date')
            ->limit(5)
            ->get();

        return view('dokter.dashboard', compact('appointments'));
    }

    public function index()
    {
        $patients = Patient::latest()->paginate(10);
        return view('dokter.patients.index', compact('patients'));
    }

    public function createPatient()
    {
        return view('dokter.patients.create');
    }

    public function storePatient(Request $request)
    {
        // Validasi dan simpan data pasien
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:patients,email',
            // tambahkan validasi lainnya
        ]);

        Patient::create($validated);

        return redirect()->route('dokter.patients.index')
            ->with('success', 'Pasien berhasil ditambahkan');
    }

    public function appointments()
    {
        /** @var User $user */
        $user = Auth::user();
        
        $appointments = Appointment::with('patient')
            ->where('user_id', $user->id) // Menggunakan $user->id
            ->latest()
            ->paginate(10);

        return view('dokter.appointments.index', compact('appointments'));
    }
}
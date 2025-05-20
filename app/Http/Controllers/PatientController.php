<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Menampilkan form input pasien
     */
    public function create()
{
    $bloodTypes = [
        'A' => 'A',
        'B' => 'B',
        'AB' => 'AB',
        'O' => 'O'
    ];

    return view('patients.input-pasien', compact('bloodTypes'));
}

    /**
     * Menyimpan data pasien baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // General Information
            'name' => 'required|string|max:255',
            'age' => 'nullable|integer|min:0|max:120',
            'gender' => 'nullable|in:male,female',
            'contact' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            
            // Medical Information
            'blood_type' => 'nullable|in:A,B,AB,O',
            'blood_pressure' => 'nullable|string|max:20',
            'heart_disease' => 'nullable|boolean',
            'diabetes' => 'nullable|boolean',
            'hepatitis' => 'nullable|boolean',
            'allergies' => 'nullable|string',
            'blood_disorders' => 'nullable|string',
            'other_diseases' => 'nullable|string',
            'current_medication' => 'nullable|string',
            
            // Dental Information
            'occlusion' => 'nullable|string',
            'torus_palatinus' => 'nullable|string',
            'torus_mandibularis' => 'nullable|string',
            'supernumerary' => 'nullable|string',
            'diastema' => 'nullable|string',
            'other_anomalies' => 'nullable|string',
            'doctor_notes' => 'nullable|string',
            'odontogram_data' => 'nullable|json'
        ]);

        $patient = Patient::create($validated);

        return response()->json([
            'success' => true,
            'redirect' => route('patients.show', $patient->id)
        ]);
    }

    /**
     * Menampilkan viewer odontogram 3D
     */
    public function showOdontogram($id)
{
    $patient = Patient::findOrFail($id);
    $odontogramData = json_decode($patient->odontogram_data, true) ?? [];

    return view('patients.odontogram-viewer', [
        'patient' => $patient,
        'odontogramData' => $odontogramData
    ]);
}

    
}
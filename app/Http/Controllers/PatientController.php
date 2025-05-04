<?php

// app/Http/Controllers/PatientController.php
namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Odontogram;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function store(Request $request)
    {
        // Validasi data utama pasien
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:1',
            'gender' => 'required|in:male,female',
            'contact_number' => 'required|string|max:20',
            'doctor_notes' => 'nullable|string',
            'occlusion' => 'nullable|string',
            'torus_palatinus' => 'nullable|string',
            'torus_mandibularis' => 'nullable|string',
            'supernumerary_teeth' => 'nullable|string',
            'diastema' => 'nullable|string',
            'other_anomalies' => 'nullable|string',
            'odontogram_data' => 'required|json' // Data odontogram dalam format JSON
        ]);

        // Simpan data pasien
        $patient = Patient::create($validated);

        // Proses data odontogram
        $odontogramData = json_decode($request->odontogram_data, true);
        
        foreach ($odontogramData as $tooth) {
            Odontogram::create([
                'patient_id' => $patient->id,
                'tooth_number' => $tooth['number'],
                'condition' => $tooth['condition'],
                'surface' => $tooth['surface'],
                'notes' => $tooth['notes'] ?? null
            ]);
        }

        return response()->json(['success' => true]);
    }
}
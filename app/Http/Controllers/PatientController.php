<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PatientController extends Controller
{
    public function create()
{
    return view('patients.create', [
        'bloodTypes' => [
            'A' => 'A',
            'B' => 'B',
            'AB' => 'AB',
            'O' => 'O'
        ]
    ]);
}

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

        try {
            $patient = Patient::create($validated);
            
            Log::info('Patient created successfully', [
                'patient_id' => $patient->id,
                'name' => $patient->name
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Patient data saved successfully',
                'redirect' => route('patients.show', $patient->id)
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving patient data', [
                'error' => $e->getMessage(),
                'data' => $request->except('odontogram_data')
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to save patient data. Please try again.'
            ], 500);
        }
    }
}
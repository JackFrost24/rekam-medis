<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    public function create()
    {
        return view('patients.create', [
            'bloodTypes' => Patient::bloodTypes()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'nullable|integer|min:0',
            'gender' => 'nullable|in:male,female',
            'contact' => 'required|string|max:20',
            'address' => 'nullable|string',
            'blood_type' => 'nullable|in:A,B,AB,O,unknown',
            'blood_pressure' => 'nullable|string|max:20',
            'heart_disease' => 'boolean',
            'diabetes' => 'boolean',
            'hepatitis' => 'boolean',
            'allergies' => 'nullable|string',
            'blood_disorders' => 'nullable|string',
            'other_diseases' => 'nullable|string',
            'current_medication' => 'nullable|string',
            'occlusion' => 'nullable|string',
            'torus_palatinus' => 'nullable|string',
            'torus_mandibularis' => 'nullable|string',
            'supernumerary' => 'nullable|string',
            'diastema' => 'nullable|string',
            'other_anomalies' => 'nullable|string',
            'doctor_notes' => 'nullable|string',
            'odontogram_data' => 'nullable|json'
        ]);

        $validated['heart_disease'] = $request->has('heart_disease');
        $validated['diabetes'] = $request->has('diabetes');
        $validated['hepatitis'] = $request->has('hepatitis');

        Patient::create($validated);

        return redirect()->route('patients.index')
            ->with('success', 'Patient data saved successfully');
    }
}
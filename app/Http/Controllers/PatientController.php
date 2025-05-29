<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    public function show($id)
    {
        $patient = Patient::with('odontogram')->findOrFail($id);
        
        return view('patients.show', [
            'patient' => $patient,
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
        DB::beginTransaction();
        
        $patient = Patient::create($validated);
        
        if ($request->odontogram_data) {
            $odontogramData = json_decode($request->odontogram_data, true);
            
            foreach ($odontogramData as $toothNumber => $data) {
                // Hanya simpan jika bukan kondisi default
                if ($data['condition'] !== 'healthy' || $data['notes'] || $data['gv_black_class']) {
                    $patient->odontogram()->updateOrCreate(
                        ['tooth_number' => $toothNumber],
                        [
                            'condition' => $data['condition'],
                            'surface' => $data['surface'],
                            'gv_black_class' => $data['gv_black_class'] ?? null,
                            'notes' => $data['notes'] ?? ''
                        ]
                    );
                }
            }
        }
        
        DB::commit();
        
        return response()->json([
            'success' => true,
            'message' => 'Data pasien berhasil disimpan',
            'redirect' => route('dokter.pasien.show', $patient->id)
        ]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Gagal menyimpan data: ' . $e->getMessage()
        ], 500);
    }
    }
}
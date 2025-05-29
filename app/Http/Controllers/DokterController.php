<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient; // Tambahkan ini
use Illuminate\Support\Facades\Log; // Tambahkan ini

class DokterController extends Controller
{
    public function dashboard()
    {
        return view('dokter.DokterDashboard');
    }

    public function store(Request $request)
    {
        $validated = $this->validatePatientData($request);
        
        try {
            $patient = Patient::create($validated);
            
            Log::info('Patient created', [
                'id' => $patient->id,
                'name' => $patient->name
            ]);

            // Untuk request AJAX
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'redirect' => route('dokter.pasien.show', $patient->id)
                ]);
            }

            return redirect()->route('dokter.pasien.show', $patient->id)
                           ->with('success', 'Data pasien berhasil disimpan');

        } catch (\Exception $e) {
            Log::error('Error saving patient', ['error' => $e->getMessage()]);
            
            return $request->wantsJson()
                ? response()->json(['error' => 'Gagal menyimpan data'], 500)
                : back()->with('error', 'Gagal menyimpan data');
        }
    }


    public function show($id)
    {
        $patient = Patient::findOrFail($id);
        return view('dokter.pasien.show', compact('patient'));
    }

    public function create()
    {
    return view('dokter.pasien.create', [
        'bloodTypes' => ['A' => 'A', 'B' => 'B', 'AB' => 'AB', 'O' => 'O']
    ]);
    }

    public function index(Request $request)
{
    $search = $request->query('search');

    $patients = Patient::when($search, function($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                         ->orWhere('contact', 'like', "%{$search}%")
                         ->orWhere('address', 'like', "%{$search}%");
        })
        ->orderBy('name')
        ->paginate(10)
        ->withQueryString();

    return view('dokter.pasien.index', compact('patients', 'search'));
}

public function edit($id)
{
    $patient = Patient::findOrFail($id);

    return view('dokter.pasien.edit', [
        'patient' => $patient,
        'bloodTypes' => ['A' => 'A', 'B' => 'B', 'AB' => 'AB', 'O' => 'O']
    ]);
}

public function update(Request $request, $id)
{
    $patient = Patient::findOrFail($id);

    $validated = $this->validatePatientData($request);

    try {
        $patient->update($validated);

        // Jika ingin response JSON untuk AJAX
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'redirect' => route('dokter.pasien.show', $patient->id)
            ]);
        }

        return redirect()->route('dokter.pasien.show', $patient->id)
                         ->with('success', 'Data pasien berhasil diperbarui');

    } catch (\Exception $e) {
        Log::error('Error updating patient', ['error' => $e->getMessage()]);

        return $request->wantsJson()
            ? response()->json(['error' => 'Gagal memperbarui data'], 500)
            : back()->with('error', 'Gagal memperbarui data');
    }
}



    private function validatePatientData(Request $request)
    {
        return $request->validate([
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
    }
}
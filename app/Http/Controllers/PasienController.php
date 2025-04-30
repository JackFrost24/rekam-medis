<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Odontogram;

class PasienController extends Controller
{
    public function store(Request $request)
    {
        // Validasi dasar
        $request->validate([
            'name' => 'required|string',
            'age' => 'required|integer',
            'gender' => 'required|string',
            'contact' => 'nullable|string',
            'odontogram_data' => 'nullable|json',
        ]);

        // Simpan data pasien
        $pasien = Patient::create([
            'name' => $request->name,
            'age' => $request->age,
            'gender' => $request->gender,
            'contact' => $request->contact,
        ]);

        // Simpan data odontogram
        if ($request->has('odontogram_data')) {
            $data = json_decode($request->odontogram_data, true);

            foreach ($data as $toothNumber => $info) {
                Odontogram::create([
                    'patients_id' => $pasien->id,
                    'tooth_number' => $toothNumber,
                    'condition' => $info['condition'],
                    'surface' => $info['surface'],
                    'notes' => $info['notes'] ?? null,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Data pasien berhasil disimpan.');
    }
}

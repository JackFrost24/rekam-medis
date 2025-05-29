<?php

namespace App\Http\Controllers;

use App\Models\Odontogram;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OdontogramController extends Controller
{
    public function viewModel($id)
    {
        $odontogram = Odontogram::with('entries')->findOrFail($id);
        return view('odontogram.view-model', compact('odontogram'));
    }

    public function show3dModel(Request $request)
    {
        $data = json_decode($request->query('data'), true);
        $type = $request->query('type', 'adult');
        
        return view('odontogram.3d-viewer', [
            'odontogramData' => $data,
            'type' => $type
        ]);
    }

    public function store(Request $request, $id)
    {
        $validated = $request->validate([
            'tooth_number' => 'required|string',
            'condition' => 'required|string',
            'surface' => 'required|string',
            'gv_black_class' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $patient = Patient::findOrFail($id);
            
            $odontogram = $patient->odontogram()->updateOrCreate(
                ['tooth_number' => $validated['tooth_number']],
                [
                    'condition' => $validated['condition'],
                    'surface' => $validated['surface'],
                    'gv_black_class' => $validated['gv_black_class'] ?? null,
                    'notes' => $validated['notes']
                ]
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $odontogram,
                'message' => 'Odontogram data saved successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to save odontogram data: ' . $e->getMessage()
            ], 500);
        }
    }
}
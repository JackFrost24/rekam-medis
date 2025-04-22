<?php

namespace App\Http\Controllers;

use App\Models\Odontogram;
use Illuminate\Http\Request;

class OdontogramController extends Controller
{
    public function viewModel($id)
    {
        $odontogram = Odontogram::with('entries')->findOrFail($id);
        return view('odontogram.view-model', compact('odontogram'));
    }
}

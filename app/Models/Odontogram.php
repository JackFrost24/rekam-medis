<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Odontogram extends Model
{
    protected $fillable = [
        'patient_id', 'tooth_number', 
        'condition', 'surface', 'notes', 'gv_black_class'
    ];

    public static function formatForDisplay($odontograms)
{
    $result = [];
    foreach ($odontograms as $odontogram) {
        $result[$odontogram->tooth_number] = [
            'condition' => $odontogram->condition,
            'surface' => $odontogram->surface,
            'gv_black_class' => $odontogram->gv_black_class,
            'notes' => $odontogram->notes
        ];
    }
    return $result;
}
}
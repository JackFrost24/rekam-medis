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

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function getConditionColorAttribute()
    {
        return match($this->condition) {
            'healthy' => '#d1fae5',
            'caries' => '#fecaca',
            'filling' => '#bfdbfe',
            'extracted' => '#e5e7eb',
            'root_canal' => '#ddd6fe',
            'crown' => '#fef08a',
            default => '#ffffff'
        };
    }
}
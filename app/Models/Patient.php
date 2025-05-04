<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    protected $fillable = [
        'name', 'age', 'gender', 'contact_number', 
        'doctor_notes', 'occlusion', 'torus_palatinus',
        'torus_mandibularis', 'supernumerary_teeth',
        'diastema', 'other_anomalies'
    ];

    public function odontograms(): HasMany
    {
        return $this->hasMany(Odontogram::class);
    }
}

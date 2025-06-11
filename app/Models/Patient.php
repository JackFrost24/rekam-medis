<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'age',
        'gender',
        'contact',
        'address',
        'blood_type',
        'blood_pressure',
        'heart_disease',
        'diabetes',
        'hepatitis',
        'allergies',
        'blood_disorders',
        'other_diseases',
        'current_medication',
        'occlusion',
        'torus_palatinus',
        'torus_mandibularis',
        'supernumerary',
        'diastema',
        'other_anomalies',
        'doctor_notes',
        'odontogram_data'
    ];

    protected $casts = [
        'heart_disease' => 'boolean',
        'diabetes' => 'boolean',
        'hepatitis' => 'boolean',
        'odontogram_data' => 'json'
    ];

    public static function bloodTypes()
    {
        return [
            'A' => 'A',
            'B' => 'B',
            'AB' => 'AB',
            'O' => 'O',
            'unknown' => 'Unknown'
        ];
    }
}
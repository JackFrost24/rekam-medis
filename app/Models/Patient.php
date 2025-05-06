<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        // General Information
        'name', 'age', 'gender', 'contact', 'address',
        
        // Medical Information
        'blood_type', 'blood_pressure', 'heart_disease', 'diabetes', 
        'hepatitis', 'allergies', 'blood_disorders', 'other_diseases',
        'current_medication',
        
        // Dental Information
        'occlusion', 'torus_palatinus', 'torus_mandibularis', 
        'supernumerary', 'diastema', 'other_anomalies', 'doctor_notes',
        'odontogram_data'
    ];

    protected $casts = [
        'heart_disease' => 'boolean',
        'diabetes' => 'boolean',
        'hepatitis' => 'boolean',
        'odontogram_data' => 'array'
    ];

    public static function bloodTypes()
    {
        return [
            'A' => 'A',
            'B' => 'B',
            'AB' => 'AB',
            'O' => 'O'
        ];
    }
}
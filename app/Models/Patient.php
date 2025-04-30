<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'gender',
        'medical_record_number',
        'birthdate',
        'address',
        'phone_number',
    ];

    public function odontograms()
    {
        return $this->hasMany(Odontogram::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }


    
}

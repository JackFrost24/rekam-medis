<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        // kolom-kolom medis umum nanti kita tambahkan di migration
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}

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

public function dokter()
{
    return $this->belongsTo(User::class, 'dokter_id');
}

public function odontogram()
{
    return $this->hasOne(Odontogram::class);
}

}

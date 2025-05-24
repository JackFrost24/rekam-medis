<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'patient_id', 'date', 'time'];

    public function doctor()
{
    return $this->belongsTo(User::class, 'doctor_id');
}


    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Pastikan ini diimport

class Appointment extends Model
{
    protected $fillable = [
        'patient_id',
        'user_id',
        'appointment_date',
        'reason',
        'status'
    ];

    protected $casts = [
        'appointment_date' => 'datetime'
    ];

    /**
     * Get the patient that owns the appointment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the doctor that owns the appointment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    // Scope untuk appointment yang akan datang
    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>=', now())
                    ->where('status', 'scheduled')
                    ->orderBy('appointment_date');
    }
}
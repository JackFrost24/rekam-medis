<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\OdontogramEntry;


class Odontogram extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        // tambahkan kolom lain nanti pas kita buat migration-nya
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function entries()
    {
    return $this->hasMany(OdontogramEntry::class);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OdontogramEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'odontogram_id',
        'tooth_number',
        'condition',
        'positions', // ini bisa digunakan untuk menyimpan posisi caries seperti M, O, D, V, L
    ];

    protected $casts = [
        'positions' => 'array', // agar bisa menyimpan array JSON misalnya ['M', 'O']
    ];

    public function odontogram()
    {
        return $this->belongsTo(Odontogram::class);
    }
}

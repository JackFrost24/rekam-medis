<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\DoctorAvailableRule; // Tambahkan ini

class StoreAppointmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'user_id' => 'required|exists:users,id',
            'appointment_date' => [
                'required',
                'date',
                'after:now',
                new DoctorAvailableRule($this->input('user_id')) // Gunakan rule
            ],
            'reason' => 'required|string|max:500'
        ];
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\Odontogram;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $patients = [
            [
                'name' => 'Budi Santoso',
                'gender' => 'L',
                'birthdate' => '1985-04-10',
                'medical_record_number' => 'RM001',
                'address' => 'Jl. Merdeka No. 123',
                'phone_number' => '081234567890',
            ],
            [
                'name' => 'Siti Aminah',
                'gender' => 'P',
                'birthdate' => '1990-07-21',
                'medical_record_number' => 'RM002',
                'address' => 'Jl. Sudirman No. 45',
                'phone_number' => '082345678901',
            ],
        ];

        foreach ($patients as $data) {
            $patient = Patient::create($data);

            // Buatkan 1 odontogram kosong untuk setiap pasien
            Odontogram::create([
                'patient_id' => $patient->id,
                'date' => now(),
                'notes' => 'Odontogram awal.',
            ]);
        }
    }
}

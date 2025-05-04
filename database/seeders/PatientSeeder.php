<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    public function run()
    {
        Patient::query()->delete();

        $patients = [
            [
                'name' => 'Budi Santoso',
                'age' => 35,
                'gender' => 'male',
                'contact_number' => '081234567890',
                'doctor_notes' => 'Kontrol rutin setiap 6 bulan',
                'occlusion' => 'Class I',
                'torus_palatinus' => 'Tidak ada',
                'torus_mandibularis' => 'Ada di bilateral',
                'supernumerary_teeth' => 'Tidak ada',
                'diastema' => 'Diastema 2mm anterior',
                'other_anomalies' => 'Gigi 13 impaksi'
            ],
            [
                'name' => 'Ani Wijaya',
                'age' => 28,
                'gender' => 'female',
                'contact_number' => '082345678901',
                'doctor_notes' => 'Perlu perawatan ortodonti',
                'occlusion' => 'Class II div 1',
                'torus_palatinus' => 'Ada kecil',
                'torus_mandibularis' => 'Tidak ada',
                'supernumerary_teeth' => 'Gigi 48 supernumerary',
                'diastema' => 'Tidak ada',
                'other_anomalies' => 'Microdontia gigi 12'
            ],
            [
                'name' => 'Citra Dewi',
                'age' => 42,
                'gender' => 'female',
                'contact_number' => '083456789012',
                'doctor_notes' => 'Riwayat periodontitis',
                'occlusion' => 'Class III',
                'torus_palatinus' => 'Tidak ada',
                'torus_mandibularis' => 'Tidak ada',
                'supernumerary_teeth' => 'Tidak ada',
                'diastema' => 'Tidak ada',
                'other_anomalies' => 'Gigi 36-46 hilang'
            ]
        ];

        foreach ($patients as $patient) {
            Patient::create($patient);
        }

        $this->command->info('Patient table seeded with complete data!');
    }
}
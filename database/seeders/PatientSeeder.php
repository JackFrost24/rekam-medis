<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    public function run()
    {
        Patient::factory()->count(20)->create([
            'blood_type' => function() {
                return array_rand(['A' => 'A', 'B' => 'B', 'AB' => 'AB', 'O' => 'O']);
            },
            'blood_pressure' => function() {
                return rand(90, 140) . '/' . rand(60, 90);
            },
            'heart_disease' => rand(0, 1),
            'diabetes' => rand(0, 1),
            'hepatitis' => rand(0, 1),
            'allergies' => function() {
                $allergies = ['Penicillin', 'Aspirin', 'Latex', 'Pollen', 'Dust'];
                return rand(0, 1) ? $allergies[array_rand($allergies)] : null;
            }
        ]);
    }
}
<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\Odontogram;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    public function run()
    {
        // Create 10 adult patients with permanent teeth
        Patient::factory()
            ->count(10)
            ->has(Odontogram::factory()->count(28), 'odontogram') // Fixed this line
            ->create();

        // Create 5 child patients with primary teeth
        Patient::factory()
            ->count(5)
            ->has(Odontogram::factory()->count(20)->state(function () {
                return [
                    'tooth_number' => $this->getPrimaryTeethNumber()
                ];
            }), 'odontogram') // Fixed this line
            ->create(['age' => rand(5, 12)]);
    }

    private function getPrimaryTeethNumber()
    {
        $primaryTeeth = [
            // Upper primary teeth
            51, 52, 53, 54, 55,
            61, 62, 63, 64, 65,
            // Lower primary teeth
            71, 72, 73, 74, 75,
            81, 82, 83, 84, 85
        ];
        
        return $primaryTeeth[array_rand($primaryTeeth)];
    }
}
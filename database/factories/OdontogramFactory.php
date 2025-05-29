<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class OdontogramFactory extends Factory
{
    public function definition()
    {
        return [
            'patient_id' => Patient::factory(),
            'tooth_number' => $this->faker->randomElement([
                // Permanent teeth
                11,12,13,14,15,16,17,18,
                21,22,23,24,25,26,27,28,
                31,32,33,34,35,36,37,38,
                41,42,43,44,45,46,47,48,
                // Primary teeth
                51,52,53,54,55,
                61,62,63,64,65,
                71,72,73,74,75,
                81,82,83,84,85
            ]),
            'condition' => $this->faker->randomElement(['healthy', 'caries', 'filling', 'extracted', 'root_canal', 'crown']),
            'gv_black_class' => function (array $attributes) {
                return $attributes['condition'] === 'caries' 
                    ? $this->faker->randomElement(['1', '2', '3', '4', '5', '6'])
                    : null;
            },
            'surface' => $this->faker->randomElement(['whole', 'buccal', 'lingual', 'occlusal', 'mesial', 'distal']),
            'notes' => $this->faker->optional()->sentence
        ];
    }
}
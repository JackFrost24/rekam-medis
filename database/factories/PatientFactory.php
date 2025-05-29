<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    public function definition()
    {
        return [
            // General Information
            'name' => $this->faker->name,
            'age' => $this->faker->numberBetween(5, 90),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'contact' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            
            // Medical Information
            'blood_type' => $this->faker->randomElement(['A', 'B', 'AB', 'O', null]),
            'blood_pressure' => $this->faker->optional()->randomElement(['120/80', '130/85', '110/70', '140/90']),
            'heart_disease' => $this->faker->boolean(20),
            'diabetes' => $this->faker->boolean(15),
            'hepatitis' => $this->faker->boolean(10),
            'allergies' => $this->faker->optional(0.3)->randomElement(['Penicillin', 'Aspirin', 'Latex', 'Pollen']),
            'blood_disorders' => $this->faker->optional(0.1)->randomElement(['Hemophilia', 'Thalassemia', 'Anemia']),
            'other_diseases' => $this->faker->optional(0.2)->randomElement(['Asthma', 'Hypertension', 'Arthritis']),
            'current_medication' => $this->faker->optional(0.4)->randomElement(['Aspirin', 'Insulin', 'Antihistamine']),
            
            // Dental Information
            'occlusion' => $this->faker->optional()->randomElement(['Class I', 'Class II', 'Class III']),
            'torus_palatinus' => $this->faker->optional()->word,
            'torus_mandibularis' => $this->faker->optional()->word,
            'supernumerary' => $this->faker->optional()->word,
            'diastema' => $this->faker->optional()->word,
            'other_anomalies' => $this->faker->optional()->sentence,
            'doctor_notes' => $this->faker->optional()->paragraph
        ];
    }
}
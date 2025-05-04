<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\Odontogram;
use Illuminate\Database\Seeder;

class OdontogramSeeder extends Seeder
{
    public function run()
    {
        Odontogram::query()->delete();

        $patients = Patient::all();

        $odontogramTemplates = [
            // Gigi anterior atas
            [
                'tooth_number' => 11,
                'condition' => 'healthy',
                'surface' => 'whole',
                'notes' => ''
            ],
            [
                'tooth_number' => 12, 
                'condition' => 'caries',
                'surface' => 'mesial',
                'notes' => 'Karies kelas II'
            ],
            // Gigi molar bawah
            [
                'tooth_number' => 36,
                'condition' => 'extracted',
                'surface' => 'whole',
                'notes' => 'Dicabut 2020 karena karies profunda'
            ],
            [
                'tooth_number' => 37,
                'condition' => 'filling',
                'surface' => 'occlusal',
                'notes' => 'Tambalan komposit'
            ],
            // Gigi lainnya
            [
                'tooth_number' => 16,
                'condition' => 'crown',
                'surface' => 'whole',
                'notes' => 'Mahkota logam-keramik'
            ],
            [
                'tooth_number' => 47,
                'condition' => 'root_canal',
                'surface' => 'whole',
                'notes' => 'PSA selesai 2022'
            ]
        ];

        foreach ($patients as $patient) {
            foreach ($odontogramTemplates as $template) {
                // Tambahkan variasi kondisi untuk pasien berbeda
                $variation = [
                    'patient_id' => $patient->id,
                    'tooth_number' => $template['tooth_number'],
                    'condition' => ($patient->id % 2 == 0) 
                        ? $template['condition'] 
                        : 'healthy', // Alternatif kondisi
                    'surface' => $template['surface'],
                    'notes' => ($patient->id == 1) 
                        ? $template['notes'] 
                        : 'Catatan untuk pasien ' . $patient->name
                ];

                Odontogram::create($variation);
            }

            // Tambahkan 2 gigi yang dicabut khusus
            Odontogram::create([
                'patient_id' => $patient->id,
                'tooth_number' => ($patient->id + 30), // Contoh: 31, 32, dst
                'condition' => 'extracted',
                'surface' => 'whole',
                'notes' => 'Pencabutan tahun ' . (2020 + $patient->id)
            ]);

            Odontogram::create([
                'patient_id' => $patient->id,
                'tooth_number' => ($patient->id + 40), // Contoh: 41, 42, dst
                'condition' => 'extracted',
                'surface' => 'whole',
                'notes' => 'Pencabutan karena trauma'
            ]);
        }

        $this->command->info('Odontogram table seeded with complete dental records!');
    }
}
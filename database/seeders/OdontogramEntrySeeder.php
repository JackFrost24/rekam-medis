<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Odontogram;
use App\Models\OdontogramEntry;

class OdontogramEntrySeeder extends Seeder
{
    public function run()
    {
        $odontogram = Odontogram::first(); // Asumsikan satu data dulu

        if ($odontogram) {
            $entries = [
                ['tooth_number' => 11, 'condition' => 'healthy', 'positions' => null],
                ['tooth_number' => 16, 'condition' => 'extracted', 'positions' => null],
                ['tooth_number' => 26, 'condition' => 'caries', 'positions' => json_encode(['mesial', 'occlusal'])],
                ['tooth_number' => 36, 'condition' => 'caries', 'positions' => json_encode(['distal'])],
            ];

            foreach ($entries as $entry) {
                OdontogramEntry::create([
                    'odontogram_id' => $odontogram->id,
                    'tooth_number' => $entry['tooth_number'],
                    'condition' => $entry['condition'],
                    'positions' => $entry['positions'],
                ]);
            }
        }
    }
}

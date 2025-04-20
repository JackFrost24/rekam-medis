<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Odontogram;
use App\Models\Patient;
use Carbon\Carbon;

class OdontogramSeeder extends Seeder
{
    public function run()
    {
        // Ambil salah satu pasien (pastikan sudah ada di tabel patients)
        $patient = Patient::first();

        if ($patient) {
            // Buat data odontogram baru
            Odontogram::create([
                'patient_id' => $patient->id,
                'date' => Carbon::now()->toDateString(),
                'notes' => 'Pemeriksaan awal, beberapa gigi berlubang.',
            ]);
        }
    }
}

<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Appointment;
use App\Models\DoctorSchedule;
use Carbon\Carbon;
use Closure;

class DoctorAvailableRule implements ValidationRule
{
    protected int $doctorId;
    protected int $appointmentDuration = 30; // Durasi appointment dalam menit

    public function __construct(int $doctorId)
    {
        $this->doctorId = $doctorId;
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $appointmentTime = Carbon::parse($value);
        $endTime = $appointmentTime->copy()->addMinutes($this->appointmentDuration);

        // 1. Cek apakah waktu appointment di masa lalu
        if ($appointmentTime->isPast()) {
            $fail('Waktu appointment tidak boleh di masa lalu');
            return;
        }

        // 2. Cek hari libur dokter
        $isDayOff = DoctorSchedule::where('user_id', $this->doctorId)
            ->where('date', $appointmentTime->toDateString())
            ->where('is_available', false)
            ->exists();

        if ($isDayOff) {
            $fail('Dokter tidak bekerja di tanggal tersebut');
            return;
        }

        // 3. Cek jam kerja (09:00 - 17:00)
        $startHour = 9;
        $endHour = 17;
        
        if ($appointmentTime->hour < $startHour || 
            $appointmentTime->hour >= $endHour || 
            ($appointmentTime->hour == $endHour - 1 && $appointmentTime->minute > 0)) {
            $fail("Dokter hanya tersedia antara jam {$startHour}:00 - {$endHour}:00");
            return;
        }

        // 4. Cek apakah dokter sudah ada appointment di waktu tersebut
        $conflictingAppointment = Appointment::where('user_id', $this->doctorId)
            ->where('status', '!=', 'cancelled')
            ->where(function($query) use ($appointmentTime, $endTime) {
                $query->whereBetween('appointment_date', [
                    $appointmentTime->format('Y-m-d H:i:s'),
                    $endTime->subSecond()->format('Y-m-d H:i:s')
                ])
                ->orWhere(function($q) use ($appointmentTime) {
                    $q->where('appointment_date', '<', $appointmentTime)
                      ->whereRaw("DATE_ADD(appointment_date, INTERVAL 30 MINUTE) > ?", [$appointmentTime]);
                });
            })
            ->exists();

        if ($conflictingAppointment) {
            $fail('Dokter sudah memiliki appointment di waktu tersebut');
        }
    }
}
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use Carbon\Carbon;

class CleanupExpiredAppointments extends Command
{
    protected $signature = 'appointments:cleanup';
    protected $description = 'Remove expired appointments';

    public function handle()
    {
        $expired = Appointment::where('appointment_date', '<', Carbon::now())
                     ->where('status', 'scheduled')
                     ->delete();
                     
        $this->info("Deleted {$expired} expired appointments.");
        return 0;
    }
}
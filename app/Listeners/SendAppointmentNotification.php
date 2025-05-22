<?php

namespace App\Listeners;

use App\Events\AppointmentCreated;
use App\Notifications\AppointmentScheduled;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAppointmentNotification implements ShouldQueue
{
    public function handle(AppointmentCreated $event)
    {
        $event->appointment->patient->notify(
            new AppointmentScheduled($event->appointment)
        );
    }
}
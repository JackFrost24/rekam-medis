<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentScheduled extends Notification implements ShouldQueue
{
    use Queueable;

    public $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // Bisa dikirim via email dan disimpan di database
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Appointment Confirmation')
            ->line('Your appointment has been scheduled.')
            ->line('Appointment Date: ' . $this->appointment->appointment_date->format('l, F j, Y \a\t g:i A'))
            ->line('Doctor: Dr. ' . $this->appointment->doctor->name)
            ->action('View Appointment', url('/appointments/' . $this->appointment->id))
            ->line('Thank you for using our service!');
    }

    public function toArray($notifiable)
    {
        return [
            'appointment_id' => $this->appointment->id,
            'date' => $this->appointment->appointment_date->format('Y-m-d H:i'),
            'message' => 'Your appointment has been scheduled with Dr. ' . $this->appointment->doctor->name
        ];
    }
}
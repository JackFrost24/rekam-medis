<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User; // Tambahkan import untuk model User
use Illuminate\Http\Request; // Import Request dari namespace yang benar
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        
        $appointments = Appointment::with(['patient', 'doctor'])
            ->where('appointment_date', '>=', now()->subDay())
            ->orderBy('appointment_date')
            ->filter(request(['search', 'status']))
            ->paginate(10);

        return view('appointments.index', compact('appointments'));
    }

    public function calendar()
    {
        $events = Appointment::where('appointment_date', '>=', now())
            ->get()
            ->map(function ($item) {
                return [
                    'title' => $item->patient->name . ' - ' . $item->reason,
                    'start' => $item->appointment_date,
                    'url'   => route('appointments.show', $item->id),
                    'color' => $this->getStatusColor($item->status)
                ];
            });

        return view('appointments.calendar', compact('events'));
    }

    public function create()
    {
        /** @var User $user */
        $user = Auth::user();
        
        $patients = Patient::all();
        $doctors = User::where('role', 'dokter')->get();
        
        return view('appointments.create', [
            'patients' => $patients,
            'doctors' => $doctors,
            'timeSlots' => $this->generateTimeSlots()
        ]);
    }

    public function store(Request $request) // Request diambil dari namespace yang benar
    {
        /** @var User $user */
        $user = Auth::user();
        
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'user_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after:now',
            'reason' => 'required|string|max:500',
            'type' => 'required|in:checkup,treatment,consultation'
        ]);
        
        $validated['created_by'] = $user->id; // Menggunakan $user->id
        
        Appointment::create($validated);

        return redirect()->route('appointments.index')
             ->with('success', 'Appointment created!');
    }

    public function show(Appointment $appointment)
    {
        return view('appointments.show', compact('appointment'));
    }

    public function updateStatus(Appointment $appointment, Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        
        $request->validate(['status' => 'required|in:confirmed,cancelled,completed']);
        
        $appointment->update([
            'status' => $request->status,
            'updated_by' => $user->id // Menggunakan $user->id
        ]);

        return back()->with('success', 'Status updated!');
    }

    private function generateTimeSlots()
    {
        $slots = [];
        $start = Carbon::createFromTime(9, 0, 0); // Jam 09:00
        $end = Carbon::createFromTime(17, 0, 0); // Jam 17:00

        while ($start <= $end) {
            $slots[] = $start->format('H:i');
            $start->addMinutes(30);
        }

        return $slots;
    }
    
    private function getStatusColor($status)
    {
        $colors = [
            'scheduled' => '#3b82f6',
            'confirmed' => '#10b981', 
            'cancelled' => '#ef4444',
            'completed' => '#64748b'
        ];
        
        return $colors[$status] ?? '#94a3b8';
    }

    public function exportToCalendar(Appointment $appointment)
{
    $ical = "BEGIN:VCALENDAR\n";
    $ical .= "VERSION:2.0\n";
    $ical .= "PRODID:-//DentalClinic//Appointments//EN\n";
    $ical .= "BEGIN:VEVENT\n";
    $ical .= "UID:" . md5($appointment->id) . "@dentalclinic\n";
    $ical .= "DTSTAMP:" . gmdate('Ymd').'T'. gmdate('His') . "Z\n";
    $ical .= "DTSTART:" . gmdate('Ymd\THis', strtotime($appointment->appointment_date)) . "Z\n";
    $ical .= "DTEND:" . gmdate('Ymd\THis', strtotime($appointment->appointment_date . ' +30 minutes')) . "Z\n";
    $ical .= "SUMMARY:Appointment with Dr. " . $appointment->doctor->name . "\n";
    $ical .= "DESCRIPTION:" . $appointment->reason . "\n";
    $ical .= "END:VEVENT\n";
    $ical .= "END:VCALENDAR\n";

    return response($ical)
        ->header('Content-Type', 'text/calendar; charset=utf-8')
        ->header('Content-Disposition', 'attachment; filename="appointment.ics"');
}
}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Appointment Management</h2>
    
    <div class="card mb-4">
        <div class="card-header">
            <h5>Create New Appointment</h5>
        </div>
        <div class="card-body">
            @include('appointments._form')
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Upcoming Appointments</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Reason</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appointment)
                            <tr>
                                <td>{{ $appointment->appointment_date->format('d M Y H:i') }}</td>
                                <td>{{ $appointment->patient->name }}</td>
                                <td>Dr. {{ $appointment->doctor->name }}</td>
                                <td>{{ Str::limit($appointment->reason, 50) }}</td>
                                <td>
                                    <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('Are you sure?')">
                                            Cancel
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No upcoming appointments</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $appointments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
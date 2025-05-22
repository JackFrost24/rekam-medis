@extends('layouts.app')

@section('title', 'Appointments')

@section('content')
<div class="bg-white shadow rounded-lg p-4">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Appointment Management</h2>
        <div class="flex space-x-2">
            <a href="{{ route('appointments.calendar') }}" 
               class="px-4 py-2 bg-blue-100 text-blue-800 rounded-lg">
               Calendar View
            </a>
            <a href="{{ route('appointments.create') }}" 
               class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
               + New Appointment
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="mb-4 flex space-x-4">
        <div class="w-1/3">
            <input type="text" placeholder="Search..." class="w-full rounded-lg border-gray-300">
        </div>
        <select class="rounded-lg border-gray-300">
            <option value="">All Status</option>
            <option value="scheduled">Scheduled</option>
            <option value="confirmed">Confirmed</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>

    <!-- Appointment Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left">Date & Time</th>
                    <th class="px-6 py-3 text-left">Patient</th>
                    <th class="px-6 py-3 text-left">Doctor</th>
                    <th class="px-6 py-3 text-left">Type</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($appointments as $appointment)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $appointment->appointment_date->format('d M Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $appointment->patient->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        Dr. {{ $appointment->doctor->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ ucfirst($appointment->type) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($appointment->status == 'confirmed') bg-green-100 text-green-800
                            @elseif($appointment->status == 'cancelled') bg-red-100 text-red-800
                            @else bg-blue-100 text-blue-800 @endif">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('appointments.show', $appointment->id) }}" 
                           class="text-blue-600 hover:text-blue-900">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $appointments->links() }}
    </div>
</div>
@endsection
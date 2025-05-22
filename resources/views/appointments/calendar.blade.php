@extends('layouts.app')

@section('title', 'Appointment Calendar')

@section('content')
<div class="bg-white shadow rounded-lg p-4">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Appointment Calendar</h2>
        <a href="{{ route('appointments.index') }}" 
           class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
           List View
        </a>
    </div>

    <div id="calendar" class="mt-4"></div>
</div>

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: @json($events),
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            eventClick: function(info) {
                window.location.href = info.event.url;
            }
        });
        calendar.render();
    });
</script>
@endpush
@endsection
@extends('layouts.app')

@section('title', 'Patient Details')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Patient Details</h1>
            <div class="flex space-x-3">
                <a href="{{ route('patients.edit', $patient) }}" 
                   class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                    Edit
                </a>
                <form action="{{ route('patients.destroy', $patient) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                            onclick="return confirm('Are you sure you want to delete this patient?')">
                        Delete
                    </button>
                </form>
                <a href="{{ route('patients.index') }}" 
                   class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                    Back to List
                </a>
            </div>
        </div>

        <!-- General Information -->
        <section class="mb-8">
            <h2 class="text-xl font-semibold mb-4 border-b pb-2">General Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm font-medium text-gray-500">Patient Name</p>
                    <p class="mt-1">{{ $patient->name }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Age</p>
                    <p class="mt-1">{{ $patient->age ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Gender</p>
                    <p class="mt-1">
                        @if($patient->gender === 'male') Male @elseif($patient->gender === 'female') Female @else - @endif
                    </p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Contact Number</p>
                    <p class="mt-1">{{ $patient->contact }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm font-medium text-gray-500">Address</p>
                    <p class="mt-1">{{ $patient->address ?? '-' }}</p>
                </div>
            </div>
        </section>

        <!-- Medical Information -->
        <section class="mb-8">
            <h2 class="text-xl font-semibold mb-4 border-b pb-2">Medical Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm font-medium text-gray-500">Blood Type</p>
                    <p class="mt-1">{{ $patient->blood_type ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Blood Pressure</p>
                    <p class="mt-1">{{ $patient->blood_pressure ?? '-' }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm font-medium text-gray-500">Medical History</p>
                    <div class="mt-1 flex flex-wrap gap-3">
                        @if($patient->heart_disease)
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Heart Disease</span>
                        @endif
                        @if($patient->diabetes)
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Diabetes</span>
                        @endif
                        @if($patient->hepatitis)
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Hepatitis</span>
                        @endif
                        @if(!$patient->heart_disease && !$patient->diabetes && !$patient->hepatitis)
                            <span class="text-gray-500">None</span>
                        @endif
                    </div>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Allergies</p>
                    <p class="mt-1">{{ $patient->allergies ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Blood Disorders</p>
                    <p class="mt-1">{{ $patient->blood_disorders ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Other Diseases</p>
                    <p class="mt-1">{{ $patient->other_diseases ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Current Medication</p>
                    <p class="mt-1">{{ $patient->current_medication ?? '-' }}</p>
                </div>
            </div>
        </section>

        <!-- Odontogram Section -->
        <section class="mb-8">
            <h2 class="text-xl font-semibold mb-4 border-b pb-2">Odontogram</h2>
            <div class="bg-gray-50 p-4 rounded-lg">
                @include('partials.odontogram-display', ['odontogramData' => $patient->odontogram_data])
                @if($patient->odontogram_data)
                    <div class="mt-4 flex space-x-3">
                        <button type="button" 
                                onclick="show3dView('adult')"
                                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            View 3D Adult
                        </button>
                        <button type="button" 
                                onclick="show3dView('child')"
                                class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                            View 3D Child
                        </button>
                    </div>
                @endif
            </div>
        </section>

        <!-- Dental Conditions -->
        <section class="mb-8">
            <h2 class="text-xl font-semibold mb-4 border-b pb-2">Dental Conditions</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm font-medium text-gray-500">Occlusion</p>
                    <p class="mt-1">{{ $patient->occlusion ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Torus Palatinus</p>
                    <p class="mt-1">{{ $patient->torus_palatinus ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Torus Mandibularis</p>
                    <p class="mt-1">{{ $patient->torus_mandibularis ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Supernumerary Teeth</p>
                    <p class="mt-1">{{ $patient->supernumerary ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Diastema</p>
                    <p class="mt-1">{{ $patient->diastema ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Other Anomalies</p>
                    <p class="mt-1">{{ $patient->other_anomalies ?? '-' }}</p>
                </div>
            </div>
        </section>

        <!-- Doctor's Notes -->
        <section>
            <h2 class="text-xl font-semibold mb-4 border-b pb-2">Doctor's Notes</h2>
            <div class="bg-gray-50 p-4 rounded">
                <p>{{ $patient->doctor_notes ?? 'No notes available' }}</p>
            </div>
        </section>
    </div>
</div>

<script>
    function show3dView(type) {
        // Implement 3D viewer based on type (adult/child)
        alert(`${type} 3D viewer will be implemented here`);
    }
</script>
@endsection
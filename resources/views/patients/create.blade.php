@extends('layouts.app')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold mb-6 text-center">Patient Input Form</h1>

        <form id="patientForm" method="POST" action="{{ route('patients.store') }}">
            @csrf
            <input type="hidden" name="odontogram_data" id="odontogram_data">

            <!-- General Information -->
            <section class="mb-8">
                <h2 class="text-xl font-semibold mb-4 border-b pb-2">General Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium">Patient Name *</label>
                        <input type="text" id="name" name="name" required 
                               class="mt-1 block w-full p-2 border rounded" 
                               value="{{ old('name') }}" />
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="age" class="block text-sm font-medium">Age</label>
                        <input type="number" id="age" name="age" 
                               class="mt-1 block w-full p-2 border rounded" 
                               value="{{ old('age') }}" />
                    </div>
                    <div>
                        <label for="gender" class="block text-sm font-medium">Gender</label>
                        <select id="gender" name="gender" class="mt-1 block w-full p-2 border rounded">
                            <option value="">Select gender</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                    <div>
                        <label for="contact" class="block text-sm font-medium">Contact Number *</label>
                        <input type="text" id="contact" name="contact" required 
                               class="mt-1 block w-full p-2 border rounded" 
                               value="{{ old('contact') }}" />
                        @error('contact')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium">Address</label>
                        <textarea id="address" name="address" rows="2" 
                                  class="mt-1 block w-full p-2 border rounded">{{ old('address') }}</textarea>
                    </div>
                </div>
            </section>

            <!-- Medical Information -->
            <section class="mb-8">
                <h2 class="text-xl font-semibold mb-4 border-b pb-2">Medical Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="blood_type" class="block text-sm font-medium">Blood Type</label>
                        <select id="blood_type" name="blood_type" class="mt-1 block w-full p-2 border rounded">
                            <option value="">Select blood type</option>
                            @foreach($bloodTypes as $key => $value)
                                <option value="{{ $key }}" {{ old('blood_type') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="blood_pressure" class="block text-sm font-medium">Blood Pressure</label>
                        <input type="text" id="blood_pressure" name="blood_pressure" 
                               class="mt-1 block w-full p-2 border rounded" 
                               value="{{ old('blood_pressure') }}" />
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium">Medical History</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mt-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" id="heart_disease" name="heart_disease" value="1" 
                                       {{ old('heart_disease') ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600">
                                <span class="ml-2">Heart Disease</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" id="diabetes" name="diabetes" value="1" 
                                       {{ old('diabetes') ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600">
                                <span class="ml-2">Diabetes</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" id="hepatitis" name="hepatitis" value="1" 
                                       {{ old('hepatitis') ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600">
                                <span class="ml-2">Hepatitis</span>
                            </label>
                        </div>
                    </div>
                    <div>
                        <label for="allergies" class="block text-sm font-medium">Allergies</label>
                        <textarea id="allergies" name="allergies" rows="2" 
                                  class="mt-1 block w-full p-2 border rounded">{{ old('allergies') }}</textarea>
                    </div>
                    <div>
                        <label for="blood_disorders" class="block text-sm font-medium">Blood Disorders</label>
                        <textarea id="blood_disorders" name="blood_disorders" rows="2" 
                                  class="mt-1 block w-full p-2 border rounded">{{ old('blood_disorders') }}</textarea>
                    </div>
                    <div>
                        <label for="other_diseases" class="block text-sm font-medium">Other Diseases</label>
                        <textarea id="other_diseases" name="other_diseases" rows="2" 
                                  class="mt-1 block w-full p-2 border rounded">{{ old('other_diseases') }}</textarea>
                    </div>
                    <div>
                        <label for="current_medication" class="block text-sm font-medium">Current Medication</label>
                        <textarea id="current_medication" name="current_medication" rows="2" 
                                  class="mt-1 block w-full p-2 border rounded">{{ old('current_medication') }}</textarea>
                    </div>
                </div>
            </section>

            <!-- Odontogram Section -->
            <section class="mb-8">
                <h2 class="text-xl font-semibold mb-4 border-b pb-2">Odontogram</h2>
                <div class="bg-gray-50 p-4 rounded-lg">
                    @include('partials.odontogram')
                    <div class="mt-4 flex space-x-3 hidden" id="show3dButtons">
                        <button id="show3dAdult" type="button" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Tampilkan 3D Dewasa</button>
                        <button id="show3dChild" type="button" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">Tampilkan 3D Anak</button>
                    </div>
                </div>
            </section>

            <!-- Dental Conditions Section -->
            <section class="mb-8">
                <h2 class="text-xl font-semibold mb-4 border-b pb-2">Dental Conditions</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium">Occlusion</label>
                            <input type="text" name="occlusion" 
                                   class="w-full px-3 py-2 border rounded-md" 
                                   value="{{ old('occlusion') }}" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Torus Palatinus</label>
                            <input type="text" name="torus_palatinus" 
                                   class="w-full px-3 py-2 border rounded-md" 
                                   value="{{ old('torus_palatinus') }}" />
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium">Torus Mandibularis</label>
                            <input type="text" name="torus_mandibularis" 
                                   class="w-full px-3 py-2 border rounded-md" 
                                   value="{{ old('torus_mandibularis') }}" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Supernumerary Teeth</label>
                            <input type="text" name="supernumerary" 
                                   class="w-full px-3 py-2 border rounded-md" 
                                   value="{{ old('supernumerary') }}" />
                        </div>
                    </div>
                    <div class="md:col-span-2 space-y-4">
                        <div>
                            <label class="block text-sm font-medium">Diastema</label>
                            <input type="text" name="diastema" 
                                   class="w-full px-3 py-2 border rounded-md" 
                                   value="{{ old('diastema') }}" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Other Anomalies</label>
                            <input type="text" name="other_anomalies" 
                                   class="w-full px-3 py-2 border rounded-md" 
                                   value="{{ old('other_anomalies') }}" />
                        </div>
                    </div>
                </div>
            </section>

            <!-- Doctor's Notes -->
            <section class="mb-8">
                <h2 class="text-xl font-semibold mb-4 border-b pb-2">Doctor's Notes</h2>
                <textarea name="doctor_notes" rows="4" 
                          class="w-full px-3 py-2 border rounded-md">{{ old('doctor_notes') }}</textarea>
            </section>

            <!-- Submit -->
            <div class="text-right">
                <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Submit Patient Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Tooth Styles */
    .tooth {
        width: 40px;
        height: 40px;
        margin: 2px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #ccc;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.8rem;
    }
    
    .tooth:hover {
        transform: scale(1.05);
        box-shadow: 0 0 5px rgba(0,0,0,0.2);
    }
    
    /* Jaw Styles */
    .jaw {
        display: flex;
        justify-content: center;
        margin-bottom: 10px;
    }
    
    /* Condition Colors */
    .healthy { background-color: #d1fae5; border-color: #a7f3d0; }
    .caries { background-color: #fecaca; border-color: #fca5a5; }
    .filling { background-color: #bfdbfe; border-color: #93c5fd; }
    .extracted { 
        background-color: #e5e7eb; 
        border-color: #d1d5db;
        position: relative;
    }
    .extracted::after {
        content: "";
        position: absolute;
        width: 80%;
        height: 2px;
        background: #ef4444;
        transform: rotate(-45deg);
    }
    .root_canal { background-color: #ddd6fe; border-color: #c4b5fd; }
    .crown { background-color: #fef08a; border-color: #fde047; }
    
    /* Tooth Details Form */
    .tooth-details {
        background: #f8fafc;
        border-radius: 8px;
        padding: 1.5rem;
        margin-top: 1.5rem;
    }
    .tooth-details h3 {
        margin-bottom: 1rem;
        font-weight: 600;
    }
    .form-group {
        margin-bottom: 1rem;
    }
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }
    .form-group select, 
    .form-group textarea {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
    }
    .form-group textarea {
        min-height: 80px;
    }
</style>
@endpush

@vite(['resources/css/app.css', 'resources/js/odontogram.js'])
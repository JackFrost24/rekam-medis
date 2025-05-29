@extends('layouts.app')

@section('title', 'Edit Data Pasien')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Edit Data Pasien</h2>

    @if(session('error'))
        <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('pasien.update', $patient->id) }}">
        @csrf
        @method('PUT')

        {{-- Nama --}}
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Nama</label>
            <input type="text" name="name" class="w-full border rounded p-2" value="{{ old('name', $patient->name) }}" required>
        </div>

        {{-- Umur --}}
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Umur</label>
            <input type="number" name="age" class="w-full border rounded p-2" value="{{ old('age', $patient->age) }}">
        </div>

        {{-- Jenis Kelamin --}}
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Jenis Kelamin</label>
            <select name="gender" class="w-full border rounded p-2">
                <option value="">- Pilih -</option>
                <option value="male" {{ old('gender', $patient->gender) == 'male' ? 'selected' : '' }}>Laki-laki</option>
                <option value="female" {{ old('gender', $patient->gender) == 'female' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>

        {{-- Kontak --}}
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Kontak</label>
            <input type="text" name="contact" class="w-full border rounded p-2" value="{{ old('contact', $patient->contact) }}" required>
        </div>

        {{-- Alamat --}}
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Alamat</label>
            <textarea name="address" class="w-full border rounded p-2">{{ old('address', $patient->address) }}</textarea>
        </div>

        {{-- Golongan Darah --}}
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Golongan Darah</label>
            <select name="blood_type" class="w-full border rounded p-2">
                <option value="">- Pilih -</option>
                @foreach ($bloodTypes as $type => $label)
                    <option value="{{ $type }}" {{ old('blood_type', $patient->blood_type) == $type ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        {{-- Tekanan Darah --}}
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Tekanan Darah</label>
            <input type="text" name="blood_pressure" class="w-full border rounded p-2" value="{{ old('blood_pressure', $patient->blood_pressure) }}">
        </div>

        {{-- Penyakit dan kondisi lainnya --}}
        @php
            $checkboxes = [
                'heart_disease' => 'Penyakit Jantung',
                'diabetes' => 'Diabetes',
                'hepatitis' => 'Hepatitis'
            ];
        @endphp

        @foreach ($checkboxes as $field => $label)
        <div class="mb-4">
            <label>
                <input type="checkbox" name="{{ $field }}" value="1" {{ old($field, $patient->$field) ? 'checked' : '' }}>
                {{ $label }}
            </label>
        </div>
        @endforeach

        {{-- Lainnya --}}
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Alergi</label>
            <input type="text" name="allergies" class="w-full border rounded p-2" value="{{ old('allergies', $patient->allergies) }}">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Kelainan Darah</label>
            <input type="text" name="blood_disorders" class="w-full border rounded p-2" value="{{ old('blood_disorders', $patient->blood_disorders) }}">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Penyakit Lainnya</label>
            <input type="text" name="other_diseases" class="w-full border rounded p-2" value="{{ old('other_diseases', $patient->other_diseases) }}">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Obat yang Sedang Dikonsumsi</label>
            <input type="text" name="current_medication" class="w-full border rounded p-2" value="{{ old('current_medication', $patient->current_medication) }}">
        </div>

        {{-- Anomali Gigi --}}
        @foreach (['occlusion', 'torus_palatinus', 'torus_mandibularis', 'supernumerary', 'diastema', 'other_anomalies'] as $field)
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">{{ ucfirst(str_replace('_', ' ', $field)) }}</label>
            <input type="text" name="{{ $field }}" class="w-full border rounded p-2" value="{{ old($field, $patient->$field) }}">
        </div>
        @endforeach

        {{-- Catatan Dokter --}}
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Catatan Dokter</label>
            <textarea name="doctor_notes" class="w-full border rounded p-2">{{ old('doctor_notes', $patient->doctor_notes) }}</textarea>
        </div>

        {{-- Odontogram (hidden JSON) --}}
        <input type="hidden" name="odontogram_data" id="odontogram_data" value="{{ old('odontogram_data', $patient->odontogram_data) }}">

        <div class="text-right">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection

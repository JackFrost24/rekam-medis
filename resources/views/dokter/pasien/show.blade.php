<!-- resources/views/patients/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Detail Pasien</h1>
        <a href="{{ route('patients.edit', $patient->id) }}" 
           class="bg-yellow-500 text-white px-4 py-2 rounded">
            Edit Data
        </a>
    </div>

    <!-- Informasi Pasien -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4 border-b pb-2">Data Umum</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p><span class="font-medium">Nama:</span> {{ $patient->name }}</p>
                <p><span class="font-medium">Usia:</span> {{ $patient->age }} tahun</p>
            </div>
            <div>
                <p><span class="font-medium">No. Telp:</span> {{ $patient->contact }}</p>
                <p><span class="font-medium">Alamat:</span> {{ $patient->address }}</p>
            </div>
            <div>
                <p><span class="font-medium">Gol. Darah:</span> {{ $patient->blood_type }}</p>
                <p><span class="font-medium">Alergi:</span> {{ $patient->allergies ?? '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Odontogram -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4 border-b pb-2">Odontogram</h2>
        @if($patient->odontogram)
            <div class="odontogram-view">
                <!-- Tampilkan odontogram -->
                @include('partials.odontogram-view', ['data' => json_decode($patient->odontogram->data)])
                
                <!-- Tombol 3D Model -->
                <div class="mt-4">
                    <a href="{{ route('odontogram.viewModel', $patient->id) }}" 
                       class="bg-blue-500 text-white px-4 py-2 rounded inline-flex items-center">
                        <i class="fas fa-cube mr-2"></i> Lihat 3D Model
                    </a>
                </div>
            </div>
        @else
            <p class="text-gray-500">Belum ada data odontogram</p>
        @endif
    </div>

    <!-- Riwayat Medis -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4 border-b pb-2">Riwayat Kunjungan</h2>
        @if($patient->appointments->count() > 0)
            <table class="min-w-full">
                <!-- Tabel riwayat -->
            </table>
        @else
            <p class="text-gray-500">Belum ada riwayat kunjungan</p>
        @endif
    </div>

    <!-- Tombol Kembali -->
    <div class="mt-6">
        <a href="{{ route('dokter.pasien') }}" 
           class="bg-gray-500 text-white px-4 py-2 rounded">
            Kembali ke Daftar Pasien
        </a>
    </div>
</div>
@endsection
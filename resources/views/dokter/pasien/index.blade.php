@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Daftar Pasien</h1>
        <a href="{{ route('dokter.pasien.create') }}" 
       class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
       <i class="fas fa-plus mr-1"></i> Tambah Pasien
    </a>
    </div>

    <!-- Search Bar -->
    <div class="mb-4">
        <input type="text" placeholder="Cari nama pasien..." class="input-search">
    </div>

    <!-- Patients Table -->
    <table class="table-auto w-full">
        <thead>
            <tr>
                <th class="px-4 py-2">No</th>
                <th class="px-4 py-2">Nama</th>
                <th class="px-4 py-2">Usia</th>
                <th class="px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($patients as $patient)
            <tr>
                <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                <td class="border px-4 py-2">
                    <a href="{{ route('dokter.pasien.show', $patient->id) }}" class="text-blue-600">
                        {{ $patient->name }}
                    </a>
                </td>
                <td class="border px-4 py-2">{{ $patient->age }}</td>
                <td class="border px-4 py-2">
                    @include('dokter.pasien._actions', ['patient' => $patient])
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
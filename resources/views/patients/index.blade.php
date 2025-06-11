@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold">Daftar Pasien</h1>
        <a href="{{ route('dokter.pasien.create') }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Tambah Pasien</a>
    </div>

    <form method="GET" action="{{ route('dokter.pasien.index') }}" class="mb-4">
        <input 
            type="text" 
            name="search" 
            placeholder="Cari pasien..." 
            value="{{ old('search', $search) }}" 
            class="border rounded px-3 py-2 w-full md:w-1/3"
        />
    </form>

    @if($patients->count())
        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Nama</th>
                    <th class="py-2 px-4 border-b">Kontak</th>
                    <th class="py-2 px-4 border-b">Alamat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($patients as $patient)
                <tr class="hover:bg-gray-100 cursor-pointer" 
                    onclick="window.location='{{ route('dokter.pasien.show', $patient->id) }}'">
                    <td class="py-2 px-4 border-b">{{ $patient->name }}</td>
                    <td class="py-2 px-4 border-b">{{ $patient->contact }}</td>
                    <td class="py-2 px-4 border-b">{{ Str::limit($patient->address, 40) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $patients->links() }}
        </div>
    @else
        <p class="text-center text-gray-500">Data pasien tidak ditemukan.</p>
    @endif
</div>
@endsection

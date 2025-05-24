@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Jadwal Dokter</h1>

    {{-- Alert success --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Form Tambah Jadwal --}}
    <div class="card mb-4">
        <div class="card-header">Tambah Jadwal Baru</div>
        <div class="card-body">
            <form action="{{ route('jadwal.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="doctor_id" class="form-label">Dokter</label>
                    <select name="doctor_id" id="doctor_id" class="form-control" required>
                        <option value="">-- Pilih Dokter --</option>
                        @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="schedule_date" class="form-label">Tanggal</label>
                    <input type="date" name="schedule_date" id="schedule_date" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="schedule_time" class="form-label">Waktu</label>
                    <input type="time" name="schedule_time" id="schedule_time" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Tambah Jadwal</button>
            </form>
        </div>
    </div>

    {{-- Tabel Jadwal --}}
    <div class="card">
        <div class="card-header">Daftar Jadwal</div>
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Dokter</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($schedules as $schedule)
                        <tr>
                            <td>{{ $schedule->doctor->name ?? 'N/A' }}</td>
                            <td>{{ $schedule->schedule_date }}</td>
                            <td>{{ $schedule->schedule_time }}</td>
                            <td>
                                <a href="{{ route('jadwal.edit', $schedule->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('jadwal.destroy', $schedule->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus jadwal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada jadwal</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/jadwal.css') }}">
@endsection
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard Dokter</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <!-- Total Pasien Card -->
                        <div class="col-md-4">
                            <div class="card text-white bg-primary">
                                <div class="card-body">
                                    <h5 class="card-title">Total Pasien</h5>
                                    <p class="card-text display-4">{{ $totalPasien }}</p>
                                    <a href="{{ route('pasien.index') }}" class="text-white">Lihat daftar pasien</a>
                                </div>
                            </div>
                        </div>

                        <!-- Upcoming Appointments Card -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    Janji Temu Mendatang
                                </div>
                                <div class="card-body">
                                    @if($upcomingAppointments->isEmpty())
                                        <p>Tidak ada janji temu mendatang</p>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <th>Nama Pasien</th>
                                                        <th>Keluhan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                   @foreach($upcomingAppointments as $appointment)
                                                    <tr>
                                                        <td>{{ $appointment->appointment_date->format('d/m/Y H:i') }}</td>
                                                        <td>{{ $appointment->patient->name }}</td>
                                                        <td>{{ $appointment->reason }}</td>
                                                        <td>{{ ucfirst($appointment->status) }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <a href="{{ route('appointment.index') }}" class="btn btn-primary mt-2">Lihat Semua Janji Temu</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shortcut Buttons -->
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('pasien.index') }}" class="btn btn-success btn-block py-3">
                                <i class="fas fa-users mr-2"></i> Daftar Pasien
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('appointment.index') }}" class="btn btn-warning btn-block py-3">
                                <i class="fas fa-calendar-alt mr-2"></i> Janji Temu
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('rekam-medis.index') }}" class="btn btn-info btn-block py-3">
                                <i class="fas fa-file-medical mr-2"></i> Rekam Medis
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('profile.edit') }}" class="btn btn-secondary btn-block py-3">
                                <i class="fas fa-user-cog mr-2"></i> Profil Saya
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
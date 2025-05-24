<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    // Menampilkan daftar jadwal
    public function index()
    {
        // Ambil semua jadwal yang ada di database
        $schedules = Schedule::all();

        // Tampilkan view dengan data jadwal
        return view('jadwal.index', compact('schedules'));
    }

    // Menampilkan form untuk membuat jadwal baru
    public function create()
    {
        // Ambil semua user yang berperan sebagai dokter
        $doctors = User::where('role', 'dokter')->get();

        // Tampilkan form create dengan data dokter
        return view('jadwal.create', compact('doctors'));
    }

    // Menyimpan jadwal baru
    public function store(Request $request)
    {
        // Validasi data yang dikirimkan
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'schedule_date' => 'required|date',
            'schedule_time' => 'required',
        ]);

        // Simpan jadwal baru ke dalam database
        Schedule::create([
            'doctor_id' => $request->doctor_id,
            'schedule_date' => $request->schedule_date,
            'schedule_time' => $request->schedule_time,
        ]);

        // Redirect ke halaman jadwal dengan pesan sukses
        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dibuat!');
    }

    // Menampilkan form untuk mengedit jadwal
    public function edit($id)
    {
        // Ambil jadwal berdasarkan ID
        $schedule = Schedule::findOrFail($id);

        // Ambil data dokter
        $doctors = User::where('role', 'dokter')->get();

        // Tampilkan form edit dengan data jadwal dan dokter
        return view('jadwal.edit', compact('schedule', 'doctors'));
    }

    // Mengupdate jadwal yang ada
    public function update(Request $request, $id)
    {
        // Validasi data yang dikirimkan
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'schedule_date' => 'required|date',
            'schedule_time' => 'required',
        ]);

        // Ambil jadwal berdasarkan ID dan update data
        $schedule = Schedule::findOrFail($id);
        $schedule->update([
            'doctor_id' => $request->doctor_id,
            'schedule_date' => $request->schedule_date,
            'schedule_time' => $request->schedule_time,
        ]);

        // Redirect ke halaman jadwal dengan pesan sukses
        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diupdate!');
    }

    // Menghapus jadwal berdasarkan ID
    public function destroy($id)
    {
        // Ambil jadwal berdasarkan ID dan hapus
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        // Redirect ke halaman jadwal dengan pesan sukses
        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus!');
    }
}
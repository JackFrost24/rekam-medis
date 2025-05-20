<!-- resources/views/appointments/_form.blade.php -->
<form action="{{ route('appointments.store') }}" method="POST">
    @csrf
    
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="patient_id" class="form-label">Patient</label>
                <select name="patient_id" id="patient_id" class="form-select" required>
                    <option value="">Select Patient</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                            {{ $patient->name }} ({{ $patient->medical_record_number ?? 'N/A' }})
                        </option>
                    @endforeach
                </select>
                @error('patient_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="user_id" class="form-label">Doctor</label>
                <select name="user_id" id="user_id" class="form-select" required>
                    <option value="">Select Doctor</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}" {{ old('user_id') == $doctor->id ? 'selected' : '' }}>
                            Dr. {{ $doctor->name }}
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    
    <div class="row mt-3">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="appointment_date" class="form-label">Date & Time</label>
                <input type="datetime-local" name="appointment_date" id="appointment_date" 
                       class="form-control" required 
                       min="{{ now()->format('Y-m-d\TH:i') }}"
                       value="{{ old('appointment_date') }}">
                @error('appointment_date')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    
    <div class="mb-3">
        <label for="reason" class="form-label">Reason</label>
        <textarea name="reason" id="reason" rows="3" class="form-control" required>{{ old('reason') }}</textarea>
        @error('reason')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-calendar-plus"></i> Create Appointment
    </button>
</form>
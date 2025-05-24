<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');

            // Data medis
            $table->string('blood_type')->nullable();
            $table->string('blood_pressure')->nullable();
            $table->boolean('heart_disease')->default(false);
            $table->boolean('diabetes')->default(false);
            $table->boolean('hepatitis')->default(false);
            $table->boolean('allergy')->default(false);
            $table->boolean('blood_disorder')->default(false);
            $table->text('others')->nullable();

            // Kondisi khusus rongga mulut
            $table->string('occlusion')->nullable();
            $table->boolean('torus_palatinus')->default(false);
            $table->boolean('torus_mandibularis')->default(false);
            $table->boolean('supernumerary')->default(false);
            $table->boolean('diastema')->default(false);
            $table->text('other_anomalies')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
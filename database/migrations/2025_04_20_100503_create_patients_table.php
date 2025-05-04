<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('age');
            $table->enum('gender', ['male', 'female']);
            $table->string('contact_number');
            $table->text('doctor_notes')->nullable();
            $table->string('occlusion')->nullable();
            $table->string('torus_palatinus')->nullable();
            $table->string('torus_mandibularis')->nullable();
            $table->string('supernumerary_teeth')->nullable();
            $table->string('diastema')->nullable();
            $table->string('other_anomalies')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->integer('age')->nullable();
                $table->enum('gender', ['male', 'female'])->nullable();
                $table->string('contact')->nullable();
                $table->text('address')->nullable();
                $table->enum('blood_type', ['A', 'B', 'AB', 'O', 'unknown'])->nullable();
                $table->string('blood_pressure')->nullable();
                $table->boolean('heart_disease')->default(false);
                $table->boolean('diabetes')->default(false);
                $table->boolean('hepatitis')->default(false);
                $table->text('allergies')->nullable();
                $table->text('blood_disorders')->nullable();
                $table->text('other_diseases')->nullable();
                $table->text('current_medication')->nullable();
                $table->text('occlusion')->nullable();
                $table->text('torus_palatinus')->nullable();
                $table->text('torus_mandibularis')->nullable();
                $table->text('supernumerary')->nullable();
                $table->text('diastema')->nullable();
                $table->text('other_anomalies')->nullable();
                $table->text('doctor_notes')->nullable();
                $table->json('odontogram_data')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
    }

    public function down()
    {
        Schema::dropIfExists('patients');
    }
};
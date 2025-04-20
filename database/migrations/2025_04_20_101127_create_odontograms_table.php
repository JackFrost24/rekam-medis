<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOdontogramsTable extends Migration
{
    public function up()
    {
        Schema::create('odontograms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade'); // Relasi ke tabel pasien
            $table->date('date')->nullable();
            $table->text('notes')->nullable(); // Catatan tambahan dari dokter
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('odontograms');
    }
}

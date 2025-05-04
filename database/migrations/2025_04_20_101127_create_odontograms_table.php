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
            $table->foreignId('patient_id')->constrained();
            $table->integer('tooth_number'); // Nomor gigi (11, 12, ..., 28, etc)
            $table->enum('condition', ['healthy', 'caries', 'filling', 'extracted', 'root_canal', 'crown']);
            $table->enum('surface', ['whole', 'buccal', 'lingual', 'occlusal', 'mesial', 'distal']);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('odontograms');
    }
}

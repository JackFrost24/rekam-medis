<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('odontogram_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('odontogram_id')->constrained()->onDelete('cascade');
            $table->string('tooth_number'); // contoh: 11, 12, ..., 85
            $table->enum('condition', ['healthy', 'caries', 'extracted'])->default('healthy');
            $table->json('positions')->nullable(); // hanya diisi kalau condition = caries
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('odontogram_entries');
    }
};

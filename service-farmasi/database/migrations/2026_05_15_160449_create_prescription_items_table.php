<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_resep')
                  ->constrained('prescriptions')
                  ->cascadeOnDelete();

            $table->foreignId('id_obat')
                  ->constrained('medicines')
                  ->cascadeOnDelete();

            $table->integer('jumlah');
            $table->string('dosis');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescription_items');
    }
};
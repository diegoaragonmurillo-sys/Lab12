<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recordatorios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nota_id')->constrained()->onDelete('cascade');
            $table->dateTime('fecha_vencimiento'); // Cambiado 'due_date' a 'fecha_vencimiento'
            $table->boolean('completado')->default(false); // Cambiado 'is_completed' a 'completado'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recordatorios');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('actividades', function (Blueprint $table) {
            $table->id();

            // FK a notas
            $table->foreignId('nota_id')
                  ->constrained('notas')   // nombre de la tabla notas
                  ->onDelete('cascade');   // si se borra la nota, se borran sus actividades

            $table->text('descripcion');
            $table->string('estado')->default('pendiente'); // o el valor que quieras por defecto

            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();

            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividades');
    }
};

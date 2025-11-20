<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('actividades', function (Blueprint $table) {
            // 1. Quitar la FK anterior
            $table->dropForeign(['nota_id']);

            // 2. Crear la FK con ON DELETE CASCADE
            $table->foreign('nota_id')
                  ->references('id')->on('notas')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('actividades', function (Blueprint $table) {
            $table->dropForeign(['nota_id']);

            // Opcional: volver a crear sin cascade
            $table->foreign('nota_id')
                  ->references('id')->on('notas');
        });
    }
};


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('actividades', function (Blueprint $table) {
            
            $table->dropForeign(['nota_id']);

            
            $table->foreign('nota_id')
                  ->references('id')->on('notas')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('actividades', function (Blueprint $table) {
            $table->dropForeign(['nota_id']);

            
            $table->foreign('nota_id')
                  ->references('id')->on('notas');
        });
    }
};


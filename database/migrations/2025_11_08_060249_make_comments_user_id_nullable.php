<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeCommentsUserIdNullable extends Migration
{
 
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
         
            $table->dropForeign(['user_id']);

            $table->foreignId('user_id')
                  ->nullable() 
                  ->change()
                  ->constrained()
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
        
            $table->dropForeign(['user_id']);
            
  
            $table->foreignId('user_id')
                  ->change() 
                  ->constrained()
                  ->onDelete('cascade');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->foreignId('startup_id')->nullable()->constrained('startups')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropForeign(['startup_id']);
            $table->dropColumn('startup_id');
        });
    }    
};

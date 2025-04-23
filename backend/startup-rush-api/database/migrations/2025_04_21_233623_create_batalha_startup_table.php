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
        Schema::create('batalha_startup', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batalha_id');
            $table->unsignedBigInteger('startup_id');
            
            $table->foreign('batalha_id')->references('id')->on('batalhas');
            $table->foreign('startup_id')->references('id')->on('startups');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batalha_startup');
    }
};

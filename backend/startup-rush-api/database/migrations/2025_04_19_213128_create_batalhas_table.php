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
        Schema::create('batalhas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('startup_1_id')->constrained('startups')->onDelete('cascade');
            $table->foreignId('startup_2_id')->constrained('startups')->onDelete('cascade');
            $table->integer('pontos_startup_1')->default(70);
            $table->integer('pontos_startup_2')->default(70);
            $table->integer('vencedor_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batalhas');
    }
};

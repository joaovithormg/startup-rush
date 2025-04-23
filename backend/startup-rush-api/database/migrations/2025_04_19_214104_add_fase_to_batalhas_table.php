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
    Schema::table('batalhas', function (Blueprint $table) {
        $table->integer('fase')->default(1);
    });
}

public function down()
{
    Schema::table('batalhas', function (Blueprint $table) {
        $table->dropColumn('fase');
    });
}

};

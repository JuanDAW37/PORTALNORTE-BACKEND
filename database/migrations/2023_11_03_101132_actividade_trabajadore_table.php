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
        Schema::create('actividade_trabajadore', function (Blueprint $table) {
            $table->id();
            $table->unsigBigInteger('actividade_id');
            $table->foreign('actividade_id')->references('id')->on('actividades');
            $table->unsignedBigInteger('trabajadore_id');
            $table->foreign('trabajadore_id')->references('id')->on('trabajadores');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

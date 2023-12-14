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
        Schema::create('actividade_materiale', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('actividade_id');
            $table->foreign('actividade_id')->references('id')->on('actividades');
            $table->unsignedBigInteger('materiale_id');
            $table->foreign('materiale_id')->references('id')->on('materiales');
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

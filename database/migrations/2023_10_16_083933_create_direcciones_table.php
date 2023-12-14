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
        Schema::create('direcciones', function (Blueprint $table) {
            $table->id();
            $table->string('calle',75);
            $table->string('numero',25)->nullable();
            $table->string('km',25)->nullable();
            $table->string('bloque',25)->nullable();
            $table->string('piso',25)->nullable();
            $table->string('letra',25)->nullable();
            $table->unsignedBigInteger('cp_id');//foreign key
            $table->foreign('cp_id')->references('id')->on('cps');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('direcciones');
    }
};

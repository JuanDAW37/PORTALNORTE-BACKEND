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
        Schema::create('actvidade_ublicacione', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('actividade_id');
            $table->foreign('actividade_id')->references('id')->on('actividades');
            $table->unsignedBigInteger('ubicacione_id');
            $table->foreign('ubicacione_id')->references('id')->on('ubicaciones');
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

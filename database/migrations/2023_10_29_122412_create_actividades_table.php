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
        Schema::create('actividades', function (Blueprint $table) {
            $table->id();
            $table->integer('duracion');
            $table->integer('personas');
            $table->string('actividad', 150);
            $table->longText('descripcion')->nullable();
            $table->double('tarifa', 6, 2);
            $table->time('hora_inicio')->nullable();
            $table->time('hora_fin')->nullable();
            $table->unsignedBigInteger('gestor_id');//foreign key
            $table->string('foto', 255)->nullable();
            $table->unsignedBigInteger('iva_id');//foreign key
            $table->unsignedBigInteger('tipoactividad_id');//foreign key
            $table->foreign('gestor_id')->references('id')->on('gestors');
            $table->foreign('tipoactividad_id')->references('id')->on('tiposactividades');
            $table->foreign('iva_id')->references('id')->on('ivas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividades');
    }
};

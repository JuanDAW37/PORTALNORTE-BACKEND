<?php

use Brick\Math\BigInteger;
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
        Schema::create('trabajadores', function (Blueprint $table) {
            $table->id();
            $table->string('nif',20);
            $table->string('nombre', 150);
            $table->string('apellido1', 50);
            $table->string('apellido2', 50)->nullable();
            $table->unsignedBigInteger('direccione_id');
            $table->binary('foto')->nullable();
            $table->string('user', 50);
            $table->string('password', 255);
            $table->string('contrato', 100);
            $table->double('sueldo', 6,2);
            $table->double('incentivo',6,2)->nullable();
            $table->rememberToken();
            $table->unsignedBigInteger('empresa_id');
            $table->rememberToken();
            $table->tinyInteger('rol')->default(2);//Rol 2
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('direccion_id')->references('id')->on('direcciones');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trabajadores');
    }
};

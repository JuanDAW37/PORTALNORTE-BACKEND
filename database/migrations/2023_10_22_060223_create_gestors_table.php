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
        Schema::create('gestors', function (Blueprint $table) {
            $table->id();
            $table->string('nif',20);
            $table->string('nombre', 50);
            $table->string('apellido1', 50);
            $table->string('apellido2', 50)->nullable();
            $table->unsignedBigInteger('direccione_id')->nullable();
            $table->binary('foto')->nullable();
            $table->string('user', 25);
            $table->string('password', 255);
            $table->string('contrato', 100);
            $table->double('sueldo', 6,2);
            $table->rememberToken();
            $table->tinyInteger('rol')->default(1);//Rol 1
            $table->foreign('direccione_id')->references('id')->on('direcciones')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gestors');
    }
};

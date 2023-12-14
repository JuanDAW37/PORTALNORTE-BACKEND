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
        Schema::create('publicidades', function (Blueprint $table) {
            $table->id();
            $table->String('imagen')->nullable();
            $table->String('titulo', 50)->nullable();
            $table->double('importe', 4, 2)->nullable();
            $table->unsignedBigInteger('empresa_id');//foreign key
            $table->unsignedBigInteger('gestor_id');//foreign key
            $table->foreign('gestor_id')->references('id')->on('gestors')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publicidades');
    }
};

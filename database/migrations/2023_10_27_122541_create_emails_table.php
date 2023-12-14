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
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->string('email', 255)->unique();
            $table->unsignedBigInteger('cliente_id')->nullable();//foreign key;
            $table->unsignedBigInteger('gestor_id')->nullable();//foreign key;
            $table->unsignedBigInteger('trabajadore_id')->nullable();//foreign key;
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('gestor_id')->references('id')->on('gestors')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('trabajadore_id')->references('id')->on('trabajadores')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emails');
    }
};

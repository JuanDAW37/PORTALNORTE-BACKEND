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
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->integer('numero')->unique();
            $table->date('fecha');
            $table->unsignedBigInteger('cliente_id')->nullable();//foreign key
            $table->unsignedBigInteger('reserva_id')->nullable();//foreign key
            $table->longText('concepto');
            $table->decimal('base', 6,2);
            $table->decimal('iva', 4,2);
            $table->decimal('cuota', 6,2);
            $table->decimal('total', 6,2);
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('set null');
            $table->foreign('reserva_id')->references('id')->on('reservas')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};

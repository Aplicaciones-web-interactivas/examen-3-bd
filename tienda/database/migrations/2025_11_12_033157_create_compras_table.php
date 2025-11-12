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
        Schema::create('compras', function (Blueprint $table) {
            $table->id('id_compra');
            $table->unsignedBigInteger('id_carrito_origen');
            $table->unsignedBigInteger('id_usuario');
            $table->double('total');
            $table->dateTime('fecha_compra');
            $table->foreign('id_carrito_origen')->references('id_carrito')->on('carrito');
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios');
            $table->SoftDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};

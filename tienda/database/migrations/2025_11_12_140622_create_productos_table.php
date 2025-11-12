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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->double('precio');
            $table->unsignedBigInteger('id_imagen');
            $table->unsignedBigInteger('id_descuento');
            $table->integer('stock');

            $table->foreign('id_imagen')->references('id')->on('imagenes');
            //$table->foreign('id_descuento')->references('id')->on('descuentos');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};

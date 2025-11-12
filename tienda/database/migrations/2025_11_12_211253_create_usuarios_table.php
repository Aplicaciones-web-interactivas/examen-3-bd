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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id_usuario');
             $table->string('nombre');
            $table->string('apellido');
            $table->string('correo')->unique();
            $table->timestamp('correo_verified_at')->nullable();
            $table->string('contraseña');
            $table->unsignedBigInteger('id_descuento')->nullable();
            $table->foreign('id_descuento')->references('id')->on('descuentos');
            $table->enum('rol', ['admin', 'cliente'])->default('cliente');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};

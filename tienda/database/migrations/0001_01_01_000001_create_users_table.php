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
            $table->foreign('id_descuento')->references('id_descuento')->on('descuentos')->onDelete('set null');
            $table->enum('rol', ['admin', 'cliente'])->default('cliente');
            $table->softDeletes();
            $table->timestamps();
            $table->rememberToken();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('correo')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};

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
        Schema::table('users', function (Blueprint $table) {
            $table->string('apellido')->nullable()->after('name');
            $table->unsignedBigInteger('id_descuento')->nullable()->after('password');
            $table->enum('rol', ['admin', 'cliente'])->default('cliente')->after('id_descuento');
            $table->softDeletes()->after('remember_token');
            $table->foreign('id_descuento')->references('id')->on('descuentos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['id_descuento']);
            $table->dropColumn(['apellido', 'id_descuento', 'rol', 'deleted_at']);
        });
    }
};

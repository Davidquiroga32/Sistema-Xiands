<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('nombre_completo', 150);
            $table->string('cedula', 20)->unique();
            $table->string('correo_electronico', 150)->nullable();
            $table->string('numero_telefono', 20)->nullable();
            $table->text('direccion')->nullable();
            $table->string('nombre_codeudor', 150)->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};

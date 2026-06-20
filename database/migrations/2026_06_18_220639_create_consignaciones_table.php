<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consignaciones', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('persona_id')->constrained('personas')->cascadeOnDelete();
            $table->decimal('valor_consignado', 15, 2);
            $table->date('fecha_consignacion');
            $table->text('observacion')->nullable();
            $table->string('comprobante_path')->nullable();
            $table->enum('comprobante_tipo', ['imagen', 'pdf'])->nullable();
            $table->decimal('interes_aplicado', 10, 2)->default(0);
            $table->decimal('total_con_interes', 15, 2)->default(0);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('interes_aplicado_by')->nullable()->constrained('users');
            $table->timestamp('interes_aplicado_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consignaciones');
    }
};

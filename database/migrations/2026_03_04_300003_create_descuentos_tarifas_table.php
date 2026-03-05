<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('descuentos_tarifas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tarifa_principal_id')->constrained('tarifas_principales')->cascadeOnDelete();
            $table->string('descripcion');
            $table->decimal('porcentaje_descuento', 5, 2)->nullable();
            $table->decimal('monto_descuento', 10, 2)->nullable();
            $table->date('fecha_habilitacion');
            $table->date('fecha_termino')->nullable();
            $table->enum('estado', ['VIGENTE', 'VENCIDA', 'CANCELADA'])->default('VIGENTE');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('descuentos_tarifas');
    }
};

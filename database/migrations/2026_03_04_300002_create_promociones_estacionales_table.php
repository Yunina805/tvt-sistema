<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promociones_estacionales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tarifa_principal_id')->constrained('tarifas_principales')->cascadeOnDelete();
            $table->string('nombre_comercial');
            $table->text('descripcion')->nullable();
            $table->decimal('porcentaje_descuento', 5, 2)->nullable();
            $table->decimal('monto_descuento', 10, 2)->nullable();
            $table->decimal('precio_mensualidad', 10, 2);   // precio final de la promo
            $table->date('fecha_habilitacion');
            $table->date('fecha_termino')->nullable();
            $table->date('fecha_registro_crt')->nullable();
            $table->string('folio_crt')->nullable();
            $table->enum('estado', ['VIGENTE', 'VENCIDA', 'CANCELADA'])->default('VIGENTE');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promociones_estacionales');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('rfc_cuenta_motivo')->nullable();    // RFC / cuenta de depósito / motivo
            $table->string('correo')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('tipo_servicio');                    // Mecánico/Señales/CFE/Gasolina/etc
            $table->enum('tipo_pago', ['CONTADO', 'CREDITO'])->default('CONTADO');
            $table->text('especificacion')->nullable();
            $table->boolean('activo')->default(true);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};

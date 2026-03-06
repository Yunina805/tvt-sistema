<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['TARIFA_PRINCIPAL', 'TARIFA_ADICIONAL', 'FALLA_SERVICIO', 'PERSONAL']);
            $table->string('nombre', 150);
            $table->foreignId('tarifa_principal_id')
                ->nullable()->constrained('tarifas_principales')->nullOnDelete();
            $table->foreignId('tarifa_adicional_id')
                ->nullable()->constrained('tarifas_adicionales')->nullOnDelete();
            $table->enum('quien_reporta', ['CLIENTE', 'TU_VISION'])->nullable();
            $table->string('puesto_asignado', 100)->nullable();
            $table->foreignId('user_id')
                ->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};

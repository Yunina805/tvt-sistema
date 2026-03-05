<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos_ingreso_egreso', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['INGRESO', 'EGRESO']);
            $table->string('nombre');
            $table->date('fecha_habilitacion');
            $table->date('fecha_termino')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('perfil_autorizado')->nullable(); // rol autorizado para usar este tipo
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipos_ingreso_egreso');
    }
};

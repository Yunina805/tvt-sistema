<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accesos_sistema', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->unique()->constrained('empleados')->cascadeOnDelete();
            $table->foreignId('user_id')->unique()->nullable()->constrained('users')->nullOnDelete();
            $table->enum('rol', ['ADMINISTRADOR', 'GERENTE', 'SUPERVISOR', 'OPERADOR', 'CAJA', 'LECTURA']);
            $table->json('modulos')->nullable();
            $table->boolean('activo')->default(true);
            $table->datetime('ultimo_acceso')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accesos_sistema');
    }
};

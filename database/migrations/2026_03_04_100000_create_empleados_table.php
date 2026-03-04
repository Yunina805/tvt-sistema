<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('clave_empleado', 10)->unique();
            $table->string('nombre', 80);
            $table->string('apellido_paterno', 80);
            $table->string('apellido_materno', 80)->nullable();
            $table->string('curp', 18)->nullable()->unique();
            $table->string('rfc', 13)->nullable();
            $table->string('nss', 11)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->enum('sexo', ['M', 'F'])->nullable();
            $table->string('telefono', 15)->nullable();
            $table->string('email', 120)->nullable();
            $table->foreignId('sucursal_id')->nullable()->constrained('sucursales')->nullOnDelete();
            $table->enum('area', [
                'DIRECCION', 'ADMINISTRACION', 'TECNICO_CAMPO', 'TECNICO_INSTALACIONES',
                'ATENCION_CLIENTE', 'CAJA_COBRANZA', 'RRHH', 'SISTEMAS',
            ]);
            $table->string('puesto', 100);
            $table->enum('tipo_contrato', ['PLANTA', 'CONFIANZA', 'TEMPORAL', 'HONORARIOS']);
            $table->date('fecha_ingreso');
            $table->decimal('salario_base', 10, 2);
            $table->boolean('activo')->default(true);
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sucursales', function (Blueprint $table) {
            $table->id();
            $table->string('clave')->unique();
            $table->enum('tipo_red', ['COBRE', 'HIBRIDA', 'FIBRA']);
            $table->string('nombre');
            $table->foreignId('localidad_id')->constrained('inegi_localidades');
            $table->foreignId('municipio_id')->constrained('inegi_municipios');
            $table->foreignId('estado_id')->constrained('inegi_estados');
            $table->string('codigo_postal', 5)->nullable();
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sucursales');
    }
};

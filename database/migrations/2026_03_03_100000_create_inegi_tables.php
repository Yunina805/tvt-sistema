<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inegi_estados', function (Blueprint $table) {
            $table->id();
            $table->char('clave_estado', 2)->unique();
            $table->string('nombre_estado');
            $table->timestamps();
        });

        Schema::create('inegi_municipios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estado_id')->constrained('inegi_estados')->cascadeOnDelete();
            $table->char('clave_municipio', 3);
            $table->string('nombre_municipio');
            $table->timestamps();

            $table->unique(['estado_id', 'clave_municipio']);
        });

        Schema::create('inegi_localidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('municipio_id')->constrained('inegi_municipios')->cascadeOnDelete();
            $table->foreignId('estado_id')->constrained('inegi_estados');
            $table->char('clave_localidad', 4);
            $table->string('nombre_localidad');
            $table->string('codigo_postal', 5)->nullable();
            $table->timestamps();

            $table->unique(['municipio_id', 'clave_localidad']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inegi_localidades');
        Schema::dropIfExists('inegi_municipios');
        Schema::dropIfExists('inegi_estados');
    }
};

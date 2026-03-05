<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarifas_principales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_comercial');
            $table->text('descripcion')->nullable();
            $table->decimal('precio_instalacion', 10, 2)->default(0);
            $table->decimal('precio_mensualidad', 10, 2);
            $table->date('fecha_habilitacion');
            $table->date('fecha_termino')->nullable();       // null = indefinida
            $table->date('fecha_registro_crt')->nullable();
            $table->string('folio_crt')->nullable();
            $table->enum('estado', [
                'VIGENTE_CONTRATAR',
                'VIGENTE_MENSUALIDAD',
                'VENCIDA',
                'CANCELADA',
            ])->default('VIGENTE_CONTRATAR');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarifas_principales');
    }
};

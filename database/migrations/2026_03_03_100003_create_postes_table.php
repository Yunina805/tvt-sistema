<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('postes', function (Blueprint $table) {
            $table->id();
            $table->string('id_poste')->unique();
            $table->foreignId('sucursal_id')->constrained('sucursales')->cascadeOnDelete();
            $table->string('numero_poste');
            $table->enum('tipo_poste', ['CFE', 'TELMEX', 'PROPIO_TVT']);
            $table->foreignId('calle_id')->nullable()->constrained('calles')->nullOnDelete();
            $table->foreignId('entre_calle_1_id')->nullable()->constrained('calles')->nullOnDelete();
            $table->foreignId('entre_calle_2_id')->nullable()->constrained('calles')->nullOnDelete();
            $table->decimal('latitud_utm', 10, 8)->nullable();
            $table->decimal('longitud_utm', 10, 8)->nullable();
            $table->decimal('latitud_grados', 10, 8)->nullable();
            $table->decimal('longitud_grados', 10, 8)->nullable();
            $table->string('zona')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('postes');
    }
};

<?php

namespace App\Models\Clientes;

use App\Models\Infraestructura\Calle;
use App\Models\Infraestructura\Sucursal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'telefono',
        'correo',
        'curp',
        'sucursal_id',
        'calle_id',
        'numero_exterior',
        'numero_interior',
        'referencias',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }

    public function calle(): BelongsTo
    {
        return $this->belongsTo(Calle::class, 'calle_id');
    }

    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->apellido_paterno} {$this->apellido_materno}");
    }
}

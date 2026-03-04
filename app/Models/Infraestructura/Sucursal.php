<?php

namespace App\Models\Infraestructura;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sucursal extends Model
{
    protected $table = 'sucursales';

    protected $fillable = [
        'clave',
        'tipo_red',
        'nombre',
        'localidad_id',
        'municipio_id',
        'estado_id',
        'codigo_postal',
        'activa',
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    public function localidad(): BelongsTo
    {
        return $this->belongsTo(InegLocalidad::class, 'localidad_id');
    }

    public function municipio(): BelongsTo
    {
        return $this->belongsTo(InegMunicipio::class, 'municipio_id');
    }

    public function estado(): BelongsTo
    {
        return $this->belongsTo(InegEstado::class, 'estado_id');
    }

    public function calles(): HasMany
    {
        return $this->hasMany(Calle::class, 'sucursal_id');
    }

    public function postes(): HasMany
    {
        return $this->hasMany(Poste::class, 'sucursal_id');
    }
}

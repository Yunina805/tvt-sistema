<?php

namespace App\Models\Infraestructura;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InegMunicipio extends Model
{
    protected $table = 'inegi_municipios';

    protected $fillable = [
        'estado_id',
        'clave_municipio',
        'nombre_municipio',
    ];

    public function estado(): BelongsTo
    {
        return $this->belongsTo(InegEstado::class, 'estado_id');
    }

    public function localidades(): HasMany
    {
        return $this->hasMany(InegLocalidad::class, 'municipio_id');
    }
}

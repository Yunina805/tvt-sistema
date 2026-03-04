<?php

namespace App\Models\Infraestructura;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InegLocalidad extends Model
{
    protected $table = 'inegi_localidades';

    protected $fillable = [
        'municipio_id',
        'estado_id',
        'clave_localidad',
        'nombre_localidad',
        'codigo_postal',
    ];

    public function municipio(): BelongsTo
    {
        return $this->belongsTo(InegMunicipio::class, 'municipio_id');
    }

    public function estado(): BelongsTo
    {
        return $this->belongsTo(InegEstado::class, 'estado_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InegEstado extends Model
{
    protected $table = 'inegi_estados';

    protected $fillable = [
        'clave_estado',
        'nombre_estado',
    ];

    public function municipios(): HasMany
    {
        return $this->hasMany(InegMunicipio::class, 'estado_id');
    }

    public function localidades(): HasMany
    {
        return $this->hasMany(InegLocalidad::class, 'estado_id');
    }
}

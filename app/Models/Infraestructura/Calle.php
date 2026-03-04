<?php

namespace App\Models\Infraestructura;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Calle extends Model
{
    protected $table = 'calles';

    protected $fillable = [
        'nombre_calle',
        'sucursal_id',
        'activa',
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }
}

<?php

namespace App\Models\Infraestructura;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Poste extends Model
{
    protected $table = 'postes';

    protected $fillable = [
        'id_poste',
        'sucursal_id',
        'numero_poste',
        'tipo_poste',
        'calle_id',
        'entre_calle_1_id',
        'entre_calle_2_id',
        'latitud_utm',
        'longitud_utm',
        'latitud_grados',
        'longitud_grados',
        'zona',
        'activo',
    ];

    protected $casts = [
        'activo'          => 'boolean',
        'latitud_utm'     => 'decimal:8',
        'longitud_utm'    => 'decimal:8',
        'latitud_grados'  => 'decimal:8',
        'longitud_grados' => 'decimal:8',
    ];

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }

    public function calle(): BelongsTo
    {
        return $this->belongsTo(Calle::class, 'calle_id');
    }

    public function entreCalle1(): BelongsTo
    {
        return $this->belongsTo(Calle::class, 'entre_calle_1_id');
    }

    public function entreCalle2(): BelongsTo
    {
        return $this->belongsTo(Calle::class, 'entre_calle_2_id');
    }
}

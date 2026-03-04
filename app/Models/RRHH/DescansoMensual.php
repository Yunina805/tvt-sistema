<?php

namespace App\Models\RRHH;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DescansoMensual extends Model
{
    protected $table = 'descansos_mensuales';

    protected $fillable = [
        'empleado_id',
        'anio',
        'mes',
        'dias_asignados',
        'dias_tomados',
        'estado',
        'observaciones',
    ];

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }
}

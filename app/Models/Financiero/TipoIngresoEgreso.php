<?php

namespace App\Models\Financiero;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TipoIngresoEgreso extends Model
{
    protected $table = 'tipos_ingreso_egreso';

    protected $fillable = [
        'tipo', 'nombre',
        'fecha_habilitacion', 'fecha_termino',
        'user_id', 'perfil_autorizado', 'activo',
    ];

    protected $casts = [
        'fecha_habilitacion' => 'date',
        'fecha_termino'      => 'date',
        'activo'             => 'boolean',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

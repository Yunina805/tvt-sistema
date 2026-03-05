<?php

namespace App\Models\Financiero;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DescuentoTarifa extends Model
{
    protected $table = 'descuentos_tarifas';

    protected $fillable = [
        'tarifa_principal_id', 'descripcion',
        'porcentaje_descuento', 'monto_descuento',
        'fecha_habilitacion', 'fecha_termino',
        'estado', 'user_id',
    ];

    protected $casts = [
        'fecha_habilitacion'   => 'date',
        'fecha_termino'        => 'date',
        'porcentaje_descuento' => 'decimal:2',
        'monto_descuento'      => 'decimal:2',
    ];

    public function tarifaPrincipal(): BelongsTo
    {
        return $this->belongsTo(TarifaPrincipal::class, 'tarifa_principal_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

<?php

namespace App\Models\Financiero;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromocionEstacional extends Model
{
    protected $table = 'promociones_estacionales';

    protected $fillable = [
        'tarifa_principal_id', 'nombre_comercial', 'descripcion',
        'porcentaje_descuento', 'monto_descuento', 'precio_mensualidad',
        'fecha_habilitacion', 'fecha_termino',
        'fecha_registro_crt', 'folio_crt',
        'estado', 'user_id',
    ];

    protected $casts = [
        'fecha_habilitacion'   => 'date',
        'fecha_termino'        => 'date',
        'fecha_registro_crt'   => 'date',
        'porcentaje_descuento' => 'decimal:2',
        'monto_descuento'      => 'decimal:2',
        'precio_mensualidad'   => 'decimal:2',
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

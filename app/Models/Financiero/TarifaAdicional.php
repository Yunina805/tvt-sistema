<?php

namespace App\Models\Financiero;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TarifaAdicional extends Model
{
    protected $table = 'tarifas_adicionales';

    protected $fillable = [
        'tarifa_principal_id', 'nombre_comercial', 'descripcion',
        'precio_instalacion', 'precio_mensualidad',
        'fecha_habilitacion', 'fecha_termino',
        'fecha_registro_crt', 'folio_crt',
        'estado', 'user_id',
    ];

    protected $casts = [
        'fecha_habilitacion' => 'date',
        'fecha_termino'      => 'date',
        'fecha_registro_crt' => 'date',
        'precio_instalacion' => 'decimal:2',
        'precio_mensualidad' => 'decimal:2',
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

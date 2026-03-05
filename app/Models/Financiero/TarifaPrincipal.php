<?php

namespace App\Models\Financiero;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TarifaPrincipal extends Model
{
    protected $table = 'tarifas_principales';

    protected $fillable = [
        'nombre_comercial', 'descripcion',
        'precio_instalacion', 'precio_mensualidad',
        'fecha_habilitacion', 'fecha_termino',
        'fecha_registro_crt', 'folio_crt',
        'estado', 'user_id',
    ];

    protected $casts = [
        'fecha_habilitacion'  => 'date',
        'fecha_termino'       => 'date',
        'fecha_registro_crt'  => 'date',
        'precio_instalacion'  => 'decimal:2',
        'precio_mensualidad'  => 'decimal:2',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function adicionales(): HasMany
    {
        return $this->hasMany(TarifaAdicional::class, 'tarifa_principal_id');
    }

    public function promociones(): HasMany
    {
        return $this->hasMany(PromocionEstacional::class, 'tarifa_principal_id');
    }

    public function descuentos(): HasMany
    {
        return $this->hasMany(DescuentoTarifa::class, 'tarifa_principal_id');
    }
}

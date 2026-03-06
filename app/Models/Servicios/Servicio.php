<?php

namespace App\Models\Servicios;

use App\Models\Financiero\TarifaAdicional;
use App\Models\Financiero\TarifaPrincipal;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Servicio extends Model
{
    protected $table = 'servicios';

    protected $fillable = [
        'tipo', 'nombre',
        'tarifa_principal_id', 'tarifa_adicional_id',
        'quien_reporta', 'puesto_asignado',
        'user_id', 'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function tarifaPrincipal(): BelongsTo
    {
        return $this->belongsTo(TarifaPrincipal::class, 'tarifa_principal_id');
    }

    public function tarifaAdicional(): BelongsTo
    {
        return $this->belongsTo(TarifaAdicional::class, 'tarifa_adicional_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

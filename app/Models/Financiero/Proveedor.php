<?php

namespace App\Models\Financiero;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Proveedor extends Model
{
    protected $table = 'proveedores';

    protected $fillable = [
        'nombre', 'rfc_cuenta_motivo', 'correo', 'telefono',
        'tipo_servicio', 'tipo_pago', 'especificacion',
        'activo', 'user_id',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

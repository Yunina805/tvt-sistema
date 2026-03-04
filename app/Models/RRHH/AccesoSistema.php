<?php

namespace App\Models\RRHH;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccesoSistema extends Model
{
    protected $table = 'accesos_sistema';

    protected $fillable = [
        'empleado_id',
        'user_id',
        'rol',
        'modulos',
        'activo',
        'ultimo_acceso',
    ];

    protected $casts = [
        'modulos'       => 'array',
        'activo'        => 'boolean',
        'ultimo_acceso' => 'datetime',
    ];

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

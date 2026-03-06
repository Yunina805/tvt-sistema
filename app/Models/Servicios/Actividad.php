<?php

namespace App\Models\Servicios;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Actividad extends Model
{
    protected $table = 'actividades';

    protected $fillable = [
        'tipo',
        'nombre',
        'servicio_id',
        'puesto_responsable',
        'user_id',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

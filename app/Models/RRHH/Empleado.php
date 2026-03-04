<?php

namespace App\Models\RRHH;

use App\Models\Infraestructura\Sucursal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Empleado extends Model
{
    protected $table = 'empleados';

    protected $fillable = [
        'clave_empleado',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'curp',
        'rfc',
        'nss',
        'fecha_nacimiento',
        'sexo',
        'telefono',
        'email',
        'sucursal_id',
        'area',
        'puesto',
        'tipo_contrato',
        'fecha_ingreso',
        'salario_base',
        'activo',
        'observaciones',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_ingreso'    => 'date',
        'salario_base'     => 'decimal:2',
        'activo'           => 'boolean',
    ];

    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->apellido_paterno} {$this->apellido_materno}");
    }

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }

    public function vacaciones(): HasMany
    {
        return $this->hasMany(Vacacion::class, 'empleado_id');
    }

    public function descansosMensuales(): HasMany
    {
        return $this->hasMany(DescansoMensual::class, 'empleado_id');
    }

    public function permisos(): HasMany
    {
        return $this->hasMany(Permiso::class, 'empleado_id');
    }

    public function acceso(): HasOne
    {
        return $this->hasOne(AccesoSistema::class, 'empleado_id');
    }
}

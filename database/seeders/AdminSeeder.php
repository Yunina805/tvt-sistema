<?php

namespace Database\Seeders;

use App\Models\RRHH\AccesoSistema;
use App\Models\RRHH\Empleado;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuario de autenticación
        $user = User::firstOrCreate(
            ['email' => 'admin@tvt.com'],
            [
                'name'              => 'Administrador TVT',
                'email_verified_at' => now(),
                'password'          => Hash::make('admin1234'),
            ]
        );

        // Crear empleado base para el admin
        $empleado = Empleado::firstOrCreate(
            ['clave_empleado' => 'EMP-001'],
            [
                'nombre'           => 'Administrador',
                'apellido_paterno' => 'TVT',
                'apellido_materno' => null,
                'area'             => 'SISTEMAS',
                'puesto'           => 'Administrador del Sistema',
                'tipo_contrato'    => 'PLANTA',
                'fecha_ingreso'    => now()->toDateString(),
                'salario_base'     => 0,
                'activo'           => true,
            ]
        );

        // Crear acceso al sistema
        AccesoSistema::firstOrCreate(
            ['empleado_id' => $empleado->id],
            [
                'user_id' => $user->id,
                'rol'     => 'ADMINISTRADOR',
                'modulos' => null,
                'activo'  => true,
            ]
        );
    }
}

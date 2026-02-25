<?php

namespace App\Livewire\GestionClientes;

use Livewire\Component;
use Livewire\WithPagination;

class SuspensionClientes extends Component
{
    use WithPagination;

    public $busqueda = '';

    public function generarReporteSuspension($clienteId)
    {
        // 1. Lógica para crear el reporte en la BD con tipo "SUSPENSION FALTA DE PAGO" 
        // 2. Conectar API SMS para notificar al cliente [cite: 1244]
        // 3. Notificar al técnico por SMS si requiere desconexión física [cite: 1243]
        
        session()->flash('mensaje', 'Reporte de suspensión generado y notificaciones enviadas.');
    }

    public function render()
    {
        // Simulación de la vista de deudores > 31 días [cite: 1239, 1240]
        $clientesDeudores = collect([
            [
                'id' => '01-0004567',
                'nombre' => 'PEDRO ARMENDARIZ',
                'sucursal' => 'Oaxaca Centro',
                'saldo' => 960.00,
                'servicio' => 'Retro TV + Internet',
                'estatus' => 'Activo',
                'ultimo_pago' => '2026-01-15',
                'soporta_remoto' => true // Define si se puede suspender por OLT/Winbox
            ],
            [
                'id' => '01-0008910',
                'nombre' => 'LUCÍA MÉNDEZ',
                'sucursal' => 'San Pedro Amuzgos',
                'saldo' => 480.00,
                'servicio' => 'Retro TV',
                'estatus' => 'Activo',
                'ultimo_pago' => '2026-01-10',
                'soporta_remoto' => false // Requiere técnico para desconexión física
            ]
        ]);

        return view('livewire.gestion-clientes.suspension-clientes', [
            'clientes' => $clientesDeudores
        ])->layout('layouts.app');
    }
}
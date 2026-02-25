<?php

namespace App\Livewire\GestionClientes;

use Livewire\Component;
use Livewire\WithPagination;

class RecuperacionEquipos extends Component
{
    use WithPagination;

    public $paso = 1; 
    public $clienteSeleccionado = null;
    public $tecnicoAsignado = '';
    public $folioReporte;
    public $notificarSms = true;

    public function seleccionarCliente($cliente)
    {
        $this->clienteSeleccionado = $cliente;
        $this->folioReporte = 'REC-' . date('Ymd') . '-' . rand(100, 999);
        $this->paso = 2;
    }

    public function generarReporte()
    {
        // Lógica del manual: crear reporte y disparar notificaciones
        session()->flash('mensaje', 'Reporte ' . $this->folioReporte . ' generado exitosamente.');
        return redirect()->route('reportes.servicio');
    }

    public function render()
    {
        // Datos simulados con la clave 'domicilio' añadida para evitar el error
        $clientesDeudores = collect([
            [
                'id' => '01-0002233',
                'nombre' => 'MARCO ANTONIO SOLIS',
                'sucursal' => 'OAXACA CENTRO',
                'saldo' => 1440.00,
                'servicio' => 'RETRO TV + INTERNET',
                'estatus' => 'SUSPENDIDO',
                'ultimo_pago' => '2025-11-20',
                'nap' => 'NAP-08',
                'direccion_nap' => 'CALLE INDEPENDENCIA ESQ. PINOS',
                'domicilio' => 'AV. JUAREZ 500, COL. CENTRO', // Esta es la clave que faltaba
                'equipo' => 'ONU HUAWEI SN-990011'
            ]
        ]);

        return view('livewire.gestion-clientes.recuperacion-equipos', [
            'clientes' => $clientesDeudores
        ])->layout('layouts.app');
    }
}
<?php

namespace App\Livewire\GestionClientes;

use Livewire\Component;

class ServiciosAdicionales extends Component
{
    public $paso = 1;

    // --- PASO 1: Servicios Disponibles ---
    public $serviciosAdicionales = [
        'tv_adicional' => [
            'nombre' => 'Televisión Adicional',
            'instalacion' => 150.00,
            'mensualidad' => 60.00,
            'tipo' => 'TV'
        ],
        'aumento_velocidad' => [
            'nombre' => 'Aumento de Velocidad Internet',
            'instalacion' => 0.00,
            'mensualidad' => 100.00,
            'tipo' => 'INTERNET'
        ],
    ];
    public $servicioSeleccionado = null;

    // --- PASO 2: Cliente ---
    public $busquedaCliente = '';
    public $clienteEncontrado = null;

    // --- PASO 3: Cobro ---
    public $metodo_pago = 'efectivo';
    public $confirmacionCaja = false;

    // --- PASO 4: Técnico ---
    public $tecnicoAsignado = '';
    public $notificarSms = true;

    // ESTA ES LA FUNCIÓN QUE FALTABA
    public function irAPaso($paso)
    {
        $this->paso = $paso;
    }

    public function seleccionarServicio($key)
    {
        $this->servicioSeleccionado = $this->serviciosAdicionales[$key];
        $this->paso = 2;
    }

    public function buscarCliente()
    {
        // Simulación de búsqueda
        $this->clienteEncontrado = [
            'id' => '01-0005432-RETRO-ACTIVO',
            'nombre' => 'JUAN PÉREZ GARCÍA',
            'sucursal' => 'Oaxaca Centro',
            'domicilio' => 'Av. Independencia 102',
            'servicio_actual' => 'Retro TV + Internet'
        ];
    }

    public function confirmarCobro()
    {
        $this->paso = 4;
    }

    public function generarReporte()
    {
        $this->paso = 5;
    }

    public function finalizar()
    {
        return redirect()->route('reportes.servicio');
    }

    public function render()
    {
        return view('livewire.gestion-clientes.servicios-adicionales')
            ->layout('layouts.app');
    }
}
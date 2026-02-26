<?php

namespace App\Livewire\GestionClientes;

use Livewire\Component;

class EstadoCuenta extends Component
{
    public $search = '';
    public $periodo;
    public $cliente = null;
    public $tabActual = 'mensualidades'; // mensualidades | otros

    public function mount()
    {
        $this->periodo = date('Y-m');
    }

    public function buscarCliente()
    {
        // Simulación de datos según especificación: NOMBRE/DIRECCION/SERVICIO ACTIVO
        $this->cliente = [
            'nombre' => 'JUAN PÉREZ GARCÍA',
            'direccion' => 'AV. INDEPENDENCIA 102, COL. CENTRO',
            'servicio_activo' => 'RETRO TV + INTERNET',
            
            // Datos Pestaña 1: CORTE DE MENSUALIDADES
            'mensualidades' => [
                [
                    'movimiento' => '1005',
                    'concepto' => 'CORTE FEBRERO 2026',
                    'servicio' => 'TV + INTERNET',
                    'fecha' => '2026-02-01',
                    'importe_cobrar' => 480.00,
                    'saldo_anterior' => 0.00,
                    'saldo_pagar_corte' => 480.00,
                    'pago_mensualidad' => 480.00,
                    'saldo_periodo' => 0.00
                ]
            ],

            // Datos Pestaña 2: 4 CONCEPTOS EN UNA VISTA
            'dias_uso' => [['mov' => '99', 'concepto' => 'PROPORCIONAL ENE', 'servicio' => 'TV', 'fecha' => '2026-01-15', 'importe' => 120.00]],
            'contratacion_nueva' => [['mov' => '102', 'concepto' => 'ALTA EQUIPO', 'servicio' => 'INTERNET', 'fecha' => '2026-01-10', 'importe' => 350.00]],
            'servicios_adicionales' => [['mov' => '150', 'concepto' => 'BOCA ADICIONAL', 'servicio' => 'TV', 'fecha' => '2026-02-12', 'importe' => 150.00]],
            'reconexiones' => [['mov' => '201', 'concepto' => 'RECONEXIÓN SISTEMA', 'servicio' => 'TV + INT', 'fecha' => '2026-02-05', 'importe' => 0.00]]
        ];
    }

    public function render()
    {
        return view('livewire.gestion-clientes.estado-cuenta')->layout('layouts.app');
    }
}
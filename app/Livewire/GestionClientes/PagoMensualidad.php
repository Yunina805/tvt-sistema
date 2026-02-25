<?php

namespace App\Livewire\GestionClientes;

use Livewire\Component;

class PagoMensualidad extends Component
{
    public $paso = 1;
    public $pagoId; // Id Pago 
    
    // Búsqueda y Selección
    public $busqueda = '';
    public $resultados = [];
    public $clienteSeleccionado = null;

    // Detalle del Cobro
    public $concepto = '';
    public $tarifaMonto = 0; // Muestra Tarifa 
    public $montoCobro = 0; // Campo monto automático 
    // Modificación Manual
    public $modificarMonto = false;
    public $montoManual = 0;
    public $passwordAutorizacion = '';

    // Facturación (Campos Facturama) 
    public $requiereFactura = false;
    public $datosFactura = [
        'nombre' => '',
        'rfc' => '',
        'cp' => '',
        'uso_cfdi' => 'G03',
        'correo' => ''
    ];

    public $enviarWhatsapp = true;

    public function mount()
    {
        $this->pagoId = 'PAG-' . strtoupper(bin2hex(random_bytes(3))); // Simulación Id Pago
    }

    public function buscarCliente()
    {
        if (strlen($this->busqueda) > 2) {
            // Simulación de búsqueda por Nombre, Teléfono, ID o Dirección 
            $this->resultados = [
                [
                    'id' => '01-0001234', 
                    'nombre' => 'JUAN PÉREZ GARCÍA', 
                    'telefono' => '9511234567', 
                    'direccion' => 'AV. INDEPENDENCIA 102, COL. CENTRO', 
                    'servicio' => 'RETRO TV + INTERNET', 
                    'tarifa' => 480.00,
                    'saldo' => 480.00
                ]
            ];
        }
    }

    public function seleccionarCliente($cliente)
    {
        $this->clienteSeleccionado = $cliente;
        $this->tarifaMonto = $cliente['tarifa'];
        $this->montoCobro = $cliente['saldo'];
        $this->montoManual = $this->montoCobro;
        $this->datosFactura['nombre'] = $cliente['nombre'];
        $this->busqueda = '';
        $this->resultados = [];
    }

    public function procesarPago()
    {
        // 1. Validar contraseña si el monto es manual 
        if ($this->modificarMonto && $this->passwordAutorizacion !== 'admin123') {
            $this->addError('password', 'Contraseña de autorización incorrecta.');
            return;
        }

        // Lógica de negocio del Modelo de Operación:
        // - Se guarda ingreso 
        // - Actualiza saldo
        // - Afecta caja y registra en ingresos
        // - Afecta estado de cuenta del cliente
        
        session()->flash('mensaje', 'Pago registrado correctamente. Recibo generado e ingreso enviado a caja.');
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.gestion-clientes.pago-mensualidad')->layout('layouts.app');
    }
}
<?php

namespace App\Livewire\GestionClientes;

use Livewire\Component;

class ReconexionCliente extends Component
{
    public $paso = 1; // 1: Tipo, 2: Búsqueda, 3: Datos/Adeudo, 4: Cobro/Nuevo Servicio, 5: Firma/Técnico
    public $tipoReconexion = ''; // 'mismo' o 'otro'
    
    // Búsqueda
    public $busqueda = '';
    public $cliente = null;

    // Cobro Adeudo
    public $aplicarDescuento = false;
    public $montoDescuento = 0;
    public $passwordAuth = '';
    public $adeudoPagado = false;

    // Días de Uso (Prorrateo)
    public $diasUso = 0;
    public $costoProrrateo = 0;

    // Nuevo Servicio (Si aplica)
    public $servicioSeleccionado = null;
    public $paquetes = [
        'tv_basico' => ['nombre' => 'Retro TV', 'mensualidad' => 240, 'instalacion' => 350],
        'combo_total' => ['nombre' => 'Retro TV + Internet', 'mensualidad' => 480, 'instalacion' => 450],
    ];

    public $tecnicoAsignado = '';
    public $aceptaTerminos = false;

    public function irAPaso($paso) { $this->paso = $paso; }

    public function seleccionarTipo($tipo)
    {
        $this->tipoReconexion = $tipo;
        $this->paso = 2;
    }

    public function buscarCliente()
    {
        // Simulación de datos según manual
        $this->cliente = [
            'nombre' => 'JUAN PÉREZ GARCÍA',
            'sucursal' => 'Oaxaca Centro',
            'ultimo_servicio' => 'Retro TV',
            'tarifa_anterior' => 240.00,
            'nap' => 'NAP-05',
            'dir_nap' => 'Poste 12, Reforma',
            'equipo' => 'Mininodo Arris (Serie: 12345)',
            'pagos' => [
                ['fecha' => '2025-12-15', 'monto' => 240, 'concepto' => 'Mensualidad'],
                ['fecha' => '2025-11-14', 'monto' => 240, 'concepto' => 'Mensualidad'],
            ],
            'fecha_suspension' => '2026-01-20',
            'saldo_pendiente' => 480.00
        ];
        
        $this->calcularProrrateo();
        $this->paso = 3;
    }

    public function calcularProrrateo()
    {
        $diaActual = (int)date('d');
        $this->diasUso = max(0, 30 - $diaActual);
        $tarifa = ($this->tipoReconexion == 'mismo') ? $this->cliente['tarifa_anterior'] : 0;
        $this->costoProrrateo = $this->diasUso * ($tarifa / 30);
    }

    public function procesarPagoAdeudo()
    {
        if ($this->aplicarDescuento && $this->passwordAuth !== 'admin123') {
            $this->addError('passwordAuth', 'Contraseña incorrecta.');
            return;
        }
        $this->adeudoPagado = true;
    }

    public function finalizar()
    {
        session()->flash('mensaje', 'Reconexión procesada. Reporte enviado al técnico.');
        return redirect()->route('reportes.servicio');
    }

    public function render()
    {
        return view('livewire.gestion-clientes.reconexion-cliente')->layout('layouts.app');
    }
}
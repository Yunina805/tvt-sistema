<?php

namespace App\Livewire\GestionClientes;

use Livewire\Component;
use Livewire\WithFileUploads;

class ContratacionNueva extends Component
{
    use WithFileUploads;

    public $paso = 1;

    // --- PASO 1: Servicio ---
    public $paquetes = [
        'tv_basico' => ['nombre' => 'TV Básica Digital', 'mensualidad' => 250, 'instalacion' => 200, 'canales' => 85, 'internet' => false],
        'combo_total' => ['nombre' => 'Combo Total (TV + Internet)', 'mensualidad' => 450, 'instalacion' => 500, 'canales' => 85, 'internet' => true],
        'internet_solo' => ['nombre' => 'Internet Ultra', 'mensualidad' => 350, 'instalacion' => 400, 'canales' => 0, 'internet' => true],
    ];
    public $servicioSeleccionado = null;
    public $identificacion;
    
    // Prorrateo
    public $diasUso = 0;
    public $costoProrrateo = 0;
    public $subtotal = 0;
    public $iva = 0;
    public $totalPagar = 0;

    // --- PASO 2: Datos ---
    public $nombre = '';
    public $apellidos = '';
    public $telefono = '';
    public $curp = '';
    public $sucursal = 'Oaxaca Centro';
    public $calle = '';
    public $num_ext = '';
    public $num_int = '';
    public $referencias = '';
    
    public $requiereFactura = false;
    public $rfc = '';
    public $cp_fiscal = '';
    public $correo = '';
    public $uso_cfdi = 'G03';

    // --- PASO 3: Caja ---
    public $metodo_pago = 'efectivo';
    public $confirmacionCaja = false;
    public $folioPago = '';

    // --- PASO 4: Contrato ---
    public $aceptaTerminos = false;
    public $clienteGeneradoId = '';

    // --- PASO 5: Técnico ---
    public $tecnicoAsignado = '';
    public $notificarSms = true;
    public $reporteGeneradoId = '';

    public function seleccionarServicio($key)
    {
        $this->servicioSeleccionado = $key;
        $paquete = $this->paquetes[$key];
        
        $diaActual = (int) date('d');
        $this->diasUso = max(0, 30 - $diaActual);
        $tarifaDiaria = $paquete['mensualidad'] / 30;
        $this->costoProrrateo = $this->diasUso * $tarifaDiaria;
        
        $this->totalPagar = $paquete['instalacion'] + $this->costoProrrateo;
        $this->subtotal = $this->totalPagar / 1.16;
        $this->iva = $this->totalPagar - $this->subtotal;
    }

    public function irAPaso($nuevoPaso)
    {
        if ($nuevoPaso == 3 && empty($this->folioPago)) {
            $this->folioPago = 'PRE-' . rand(10000, 99999);
        }
        $this->paso = $nuevoPaso;
    }

    public function confirmarCobro()
    {
        $numeroCliente = str_pad(rand(1, 9999), 7, '0', STR_PAD_LEFT);
        $tarifa = strtoupper(str_replace(' ', '', $this->paquetes[$this->servicioSeleccionado]['nombre']));
        $this->clienteGeneradoId = "01-{$numeroCliente}-{$tarifa}-PENDIENTE";
        
        $this->paso = 4;
    }

    public function firmarContrato()
    {
        $this->reporteGeneradoId = 'REQ-' . date('Y') . '-' . str_pad(rand(1, 999), 4, '0', STR_PAD_LEFT);
        $this->paso = 5;
    }

    public function finalizarProceso()
    {
        session()->flash('mensaje', '¡Contratación Exitosa! El reporte de servicio ha sido generado.');
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.gestion-clientes.contratacion-nueva')->layout('layouts.app');
    }
}
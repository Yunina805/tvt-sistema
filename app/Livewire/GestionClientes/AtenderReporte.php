<?php

namespace App\Livewire\GestionClientes;

use Livewire\Component;

class AtenderReporte extends Component
{
    // Datos del reporte (Simulados de la Base de Datos)
    public $reporte = [];

    // Variables generales de Cierre
    public $cambioEquipo = false;
    public $equipoNuevo = '';
    public $potenciaNap = '';
    public $potenciaEquipo = '';
    public $solucionOpcion = '';
    public $descripcionSolucion = '';
    public $calificacion = 'Excelente';
    
    // Variables específicas (TV)
    public $pruebaCanales = false;
    public $cantidadCanales = '';

    // Variables específicas (Internet / TV+Internet)
    public $ledsVerdes = false;
    public $detectoWifiOriginal = false;
    public $configuroWifiDefault = false;
    public $asignoNuevaPass = '';
    public $accesoInternet = false;
    public $velocidadRegistrada = '';
    public $confirmoOlt = false;
    public $confirmoPon = false;

    // Variables específicas (Cambio Domicilio)
    public $metrosAcometida = '';

    public function mount($folio = 'REQ-2026-0001')
    {
        // SIMULACIÓN: Aquí harías un Reporte::where('folio', $folio)->first();
        // Cambia el 'tipo_servicio' a 'TV', 'INTERNET', 'TV+INTERNET' o 'CAMBIO_DOMICILIO' para ver cómo cambia la vista.
        $this->reporte = [
            'folio' => $folio,
            'fecha' => '2026-02-25 09:00',
            'sucursal' => 'Oaxaca Centro',
            'cliente' => 'JUAN PÉREZ GARCÍA',
            'falla_reportada' => 'FALLA EN SERVICIO DE TELEVISIÓN + INTERNET',
            'quien_reporto' => 'Cliente',
            'domicilio' => 'Av. Independencia 102. Ref: Casa Azul',
            'servicio' => 'Combo Total (TV + Internet)',
            'tipo_servicio' => 'TV+INTERNET', // Clave para la lógica (TV, INTERNET, TV+INTERNET, CAMBIO_DOMICILIO)
            'estado_cliente' => 'Activo',
            'tecnico' => 'Cuadrilla 1',
            'nap' => 'NAP-OAX-05',
            'dir_nap' => 'Poste 12, Esquina Reforma',
            'info_equipo' => 'ONU Huawei HG8010 (Serie: 45678)',
            'ip' => '192.168.1.100',
            'wifi' => 'TuVision_102',
            'olt' => 'OLT-01',
            'pon' => 'PON-03',
            'ultima_potencia_nap' => '-18 dBm',
            'ultima_potencia_equipo' => '-22 dBm',
        ];
    }

    public function guardarPrecierre()
    {
        // Lógica para guardar avance pero mantener en "En Proceso"
        session()->flash('mensaje', 'Avance guardado. El reporte sigue en proceso (Precierre).');
        return redirect()->route('reportes.servicio');
    }

    public function cerrarReporte()
    {
        // Validaciones básicas de ejemplo
        $this->validate([
            'potenciaNap' => 'required',
            'potenciaEquipo' => 'required',
            'solucionOpcion' => 'required',
        ]);

        // Aquí iría la lógica de actualización en BD y envío de SMS
        session()->flash('mensaje', 'Reporte cerrado exitosamente. SMS enviado al cliente.');
        return redirect()->route('reportes.servicio');
    }

    public function render()
    {
        return view('livewire.gestion-clientes.atender-reporte')->layout('layouts.app');
    }

    public $confirmacionDesconexionFisica = false;
    public $confirmacionWinbox = false;
    public $confirmacionOLT = false;
    public $salidaNapLibre = '';

    public function cerrarSuspension()
    {
        // Lógica del manual:
        // 1. Afectar estado del cliente a SUSPENDIDO 
        // 2. Liberar puerto en la NAP si fue físico
        // 3. Registrar horas transcurridas 
        
        session()->flash('mensaje', 'Suspensión procesada correctamente.');
        return redirect()->route('reportes.servicio');
    }
}
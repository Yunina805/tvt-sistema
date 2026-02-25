<?php

namespace App\Livewire\GestionClientes;

use Livewire\Component;

class CancelacionServicio extends Component
{
    public $paso = 1; 
    public $busqueda = '';
    public $cliente = null;
    
    // Datos Reporte
    public $numeroReporte;
    public $fechaReporte;
    
    // Lógica Equipos
    public $recuperaEquipo = 'si'; 
    public $serieConfirmada = '';
    public $pagoPerdida = false;
    public $tecnicoAsignado = '';
    public $desconexionFisica = false;
    
    // Tareas Sucursal
    public $bajaWinboxNombre = false;
    public $bajaWinboxPlan = false;
    public $bajaOLT = false;
    
    public $calificacion = 'Excelente';

    public function mount() {
        $this->numeroReporte = 'CAN-' . date('Y') . '-' . rand(1000, 9999);
        $this->fechaReporte = date('d/m/Y H:i');
    }

    public function buscarCliente() {
        // Datos simulados según especificación
        $this->cliente = [
            'id' => '01-0004455',
            'nombre' => 'JUAN PÉREZ GARCÍA',
            'sucursal' => 'Oaxaca Centro',
            'servicio_actual' => 'Retro TV + Internet',
            'domicilio' => 'Av. Independencia 102',
            'referencias' => 'Frente a la escuela primaria',
            'estado_actual' => 'Activo',
            'saldo' => 0.00, // Prueba con 500.00 para ver el bloqueo de deuda
            'equipo_asignado' => 'ONU Huawei HG8010',
            'serie_registrada' => 'SN-HUA-998877',
            'nap' => 'NAP-05',
            'direccion_nap' => 'Poste 14, Esquina Reforma',
            'ip_asignada' => '10.20.30.150'
        ];
        $this->serieConfirmada = $this->cliente['serie_registrada'];
        $this->paso = 2;
    }

    public function generarReporteBaja() {
        if ($this->cliente['saldo'] > 0) {
            session()->flash('error', 'Debe liquidar el adeudo antes de cancelar.');
            return;
        }
        $this->paso = 3;
    }

    public function finalizarCancelacion() {
        session()->flash('mensaje', 'Cancelación completada y puerto NAP liberado.');
        return redirect()->route('reportes.servicio');
    }

    public function render() {
        return view('livewire.gestion-clientes.cancelacion-servicio')->layout('layouts.app');
    }
}
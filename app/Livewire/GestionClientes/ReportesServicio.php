<?php

namespace App\Livewire\GestionClientes;

use Livewire\Component;
use Livewire\WithPagination;

class ReportesServicio extends Component
{
    use WithPagination;

    public $busqueda = '';
    public $filtroEstado = 'Todos';

    // Función para exportar todos los reportes de la vista actual
    public function exportarTotal()
    {
        // Aquí iría la lógica con Excel o PDF
        session()->flash('mensaje', 'Generando exportación total de reportes...');
    }

    // Función para exportar un reporte individual
    public function exportarIndividual($folio)
    {
        session()->flash('mensaje', "Generando PDF del reporte {$folio}...");
    }

    public function render()
    {
        // Simulación de base de datos cumpliendo con los datos esenciales
        $reportesQuery = collect([
            [
                'folio' => 'REQ-2026-0001',
                'fecha_apertura' => '2026-02-20 08:30', // El más viejo para que aparezca arriba
                'tipo_reporte' => 'Instalación Nueva',
                'cliente' => 'JUAN PÉREZ GARCÍA',
                'servicio_actual' => 'Retro TV + Internet',
                'sucursal' => 'Oaxaca Centro',
                'tecnico' => 'Téc. Roberto Gómez',
                'estado' => 'Pendiente'
            ],
            [
                'folio' => 'REP-2026-0015',
                'fecha_apertura' => '2026-02-24 10:00',
                'tipo_reporte' => 'Falla de Servicio de TV',
                'cliente' => 'MARÍA LÓPEZ CRUZ',
                'servicio_actual' => 'Retro TV',
                'sucursal' => 'San Pedro Amuzgos',
                'tecnico' => 'Cuadrilla 2',
                'estado' => 'En Proceso'
            ],
            [
                'folio' => 'REP-2026-0022',
                'fecha_apertura' => '2026-02-25 11:15',
                'tipo_reporte' => 'Cambio de Domicilio',
                'cliente' => 'CARLOS RUIZ',
                'servicio_actual' => 'Solo Internet',
                'sucursal' => 'Santa María Zacatepec',
                'tecnico' => 'Téc. Ana Martínez',
                'estado' => 'Pendiente'
            ],
        ]);

        // Ordenar: "Colocar el más viejo arriba"
        $reportesMostrados = $reportesQuery->sortBy('fecha_apertura')
            ->when($this->filtroEstado !== 'Todos', function($collection) {
                return $collection->where('estado', $this->filtroEstado);
            })
            ->when($this->busqueda, function($collection) {
                return $collection->filter(function($item) {
                    return stripos($item['cliente'], $this->busqueda) !== false || 
                           stripos($item['folio'], $this->busqueda) !== false;
                });
            });

        return view('livewire.gestion-clientes.reportes-servicio', [
            'reportes' => $reportesMostrados
        ])->layout('layouts.app');
    }
}
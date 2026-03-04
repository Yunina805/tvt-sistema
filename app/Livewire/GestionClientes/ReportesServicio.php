<?php

namespace App\Livewire\GestionClientes;

use App\Traits\WithToasts;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('layouts.app')]
class ReportesServicio extends Component
{
    use WithPagination, WithToasts;

    public string $busqueda       = '';
    public string $filtroEstado   = 'Todos';
    public string $filtroTipo     = 'Todos';
    public string $filtroSucursal = '';
    public string $filtroPeriodo  = '';

    public function updatingBusqueda(): void { $this->resetPage(); }
    public function updatingFiltroEstado(): void { $this->resetPage(); }
    public function updatingFiltroTipo(): void { $this->resetPage(); }

    public function limpiarFiltros(): void
    {
        $this->busqueda       = '';
        $this->filtroEstado   = 'Todos';
        $this->filtroTipo     = 'Todos';
        $this->filtroSucursal = '';
        $this->filtroPeriodo  = '';
        $this->resetPage();
    }

    public function exportarTotal(): void
    {
        // TODO: generar Excel/PDF con los reportes filtrados actuales
        // \Maatwebsite\Excel\Facades\Excel::download(new ReportesExport(...), 'reportes.xlsx');
        $this->toastExito('Generando exportación total de reportes...');
    }

    public function exportarIndividual(string $folio): void
    {
        // TODO: generar PDF del reporte individual
        // return \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.reporte', compact('folio'))->download("reporte-{$folio}.pdf");
        $this->toastExito("Generando PDF del reporte {$folio}...");
    }

    public function render()
    {
        // ───────────────────────────────────────────────────────────────
        // TODO: Reemplazar con consulta Eloquent real:
        //
        // $query = ReporteServicio::with(['cliente','tecnico','sucursal'])
        //     ->orderBy('fecha_apertura','asc')   // más viejo arriba
        //     ->when($this->busqueda, fn($q) =>
        //         $q->where(fn($q) =>
        //             $q->whereHas('cliente', fn($q) => $q->where('nombre','LIKE',"%{$this->busqueda}%"))
        //               ->orWhere('folio','LIKE',"%{$this->busqueda}%")
        //         ))
        //     ->when($this->filtroEstado !== 'Todos', fn($q) => $q->where('estado', $this->filtroEstado))
        //     ->when($this->filtroTipo !== 'Todos',   fn($q) => $q->where('tipo_reporte', $this->filtroTipo))
        //     ->when($this->filtroSucursal,           fn($q) => $q->where('sucursal_id', $this->filtroSucursal))
        //     ->when($this->filtroPeriodo,            fn($q) => $q->whereYear('fecha_apertura', substr($this->filtroPeriodo,0,4))
        //                                                         ->whereMonth('fecha_apertura', substr($this->filtroPeriodo,5,2)))
        //     ->paginate(20);
        // ───────────────────────────────────────────────────────────────

        // DATOS DE EJEMPLO — todos los tipos de reporte
        $todos = collect([
            [
                'folio'          => 'REP-2026-0001',
                'fecha_apertura' => '2026-02-18 08:00',
                'tipo_reporte'   => 'INSTALACION',
                'tipo_servicio'  => 'TV+INTERNET',
                'cliente'        => 'JUAN PÉREZ GARCÍA',
                'servicio_actual'=> 'TV + Internet 30 Mbps',
                'sucursal'       => 'Oaxaca Centro',
                'tecnico'        => 'Ing. Roberto Gómez',
                'estado'         => 'Pendiente',
            ],
            [
                'folio'          => 'REP-2026-0002',
                'fecha_apertura' => '2026-02-20 09:30',
                'tipo_reporte'   => 'FALLA_TV',
                'tipo_servicio'  => 'TV',
                'cliente'        => 'MARÍA LÓPEZ CRUZ',
                'servicio_actual'=> 'Retro TV',
                'sucursal'       => 'San Pedro Amuzgos',
                'tecnico'        => 'Cuadrilla 2',
                'estado'         => 'En Proceso',
                'quien_reporto'  => 'Cliente',
            ],
            [
                'folio'          => 'REP-2026-0003',
                'fecha_apertura' => '2026-02-21 10:00',
                'tipo_reporte'   => 'FALLA_INTERNET',
                'tipo_servicio'  => 'INTERNET',
                'cliente'        => 'CARLOS RUIZ MENDOZA',
                'servicio_actual'=> 'Internet 50 Mbps',
                'sucursal'       => 'Santa María Zacatepec',
                'tecnico'        => 'Ing. Ana Martínez',
                'estado'         => 'Pendiente',
                'quien_reporto'  => 'Personal TVT',
            ],
            [
                'folio'          => 'REP-2026-0004',
                'fecha_apertura' => '2026-02-22 11:15',
                'tipo_reporte'   => 'FALLA_TV_INTERNET',
                'tipo_servicio'  => 'TV+INTERNET',
                'cliente'        => 'ROSA MARTÍNEZ DÍAZ',
                'servicio_actual'=> 'Combo TV + Internet',
                'sucursal'       => 'Oaxaca Norte',
                'tecnico'        => 'Cuadrilla 1',
                'estado'         => 'En Proceso',
                'quien_reporto'  => 'Cliente',
            ],
            [
                'folio'          => 'REP-2026-0005',
                'fecha_apertura' => '2026-02-23 07:30',
                'tipo_reporte'   => 'CAMBIO_DOMICILIO',
                'tipo_servicio'  => 'TV+INTERNET',
                'cliente'        => 'ERNESTO SÁNCHEZ LUNA',
                'servicio_actual'=> 'Combo Total',
                'sucursal'       => 'Oaxaca Centro',
                'tecnico'        => 'Ing. Roberto Gómez',
                'estado'         => 'Pendiente',
            ],
            [
                'folio'          => 'REP-2026-0006',
                'fecha_apertura' => '2026-02-24 08:45',
                'tipo_reporte'   => 'SUSPENSION',
                'tipo_servicio'  => 'TV',
                'cliente'        => 'LUIS HERNÁNDEZ VEGA',
                'servicio_actual'=> 'Retro TV',
                'sucursal'       => 'San Pedro Amuzgos',
                'tecnico'        => 'Cuadrilla 3',
                'estado'         => 'Pendiente',
            ],
            [
                'folio'          => 'REP-2026-0007',
                'fecha_apertura' => '2026-02-24 09:00',
                'tipo_reporte'   => 'RECUPERACION',
                'tipo_servicio'  => 'INTERNET',
                'cliente'        => 'PATRICIA GÓMEZ RIVAS',
                'servicio_actual'=> 'Internet 30 Mbps',
                'sucursal'       => 'Oaxaca Centro',
                'tecnico'        => 'Cuadrilla 1',
                'estado'         => 'En Proceso',
            ],
            [
                'folio'          => 'REP-2026-0008',
                'fecha_apertura' => '2026-02-25 10:00',
                'tipo_reporte'   => 'CANCELACION',
                'tipo_servicio'  => 'TV+INTERNET',
                'cliente'        => 'MANUEL ORTIZ CAMPOS',
                'servicio_actual'=> 'Combo TV + Internet',
                'sucursal'       => 'Oaxaca Centro',
                'tecnico'        => 'Ing. Ana Martínez',
                'estado'         => 'Pendiente',
            ],
            [
                'folio'          => 'REP-2026-0009',
                'fecha_apertura' => '2026-02-25 11:00',
                'tipo_reporte'   => 'INSTALACION',
                'tipo_servicio'  => 'INTERNET',
                'cliente'        => 'GABRIELA RÍOS SANTOS',
                'servicio_actual'=> 'Internet 100 Mbps',
                'sucursal'       => 'Oaxaca Norte',
                'tecnico'        => 'Cuadrilla 2',
                'estado'         => 'Cerrado',
            ],
            [
                'folio'          => 'REP-2026-0010',
                'fecha_apertura' => '2026-02-26 10:00',
                'tipo_reporte'   => 'SERVICIO_ADICIONAL_TV',
                'tipo_servicio'  => 'TV',
                'cliente'        => 'SOFÍA RAMÍREZ LUNA',
                'servicio_actual'=> 'Retro TV (Adicional)',
                'sucursal'       => 'Oaxaca Centro',
                'tecnico'        => 'Cuadrilla 2',
                'estado'         => 'Pendiente',
            ],
            [
                'folio'          => 'REP-2026-0011',
                'fecha_apertura' => '2026-02-27 09:00',
                'tipo_reporte'   => 'AUMENTO_VELOCIDAD',
                'tipo_servicio'  => 'INTERNET',
                'cliente'        => 'ANDRÉS VILLA RUIZ',
                'servicio_actual'=> 'Internet 30 Mbps → 100 Mbps',
                'sucursal'       => 'Oaxaca Norte',
                'tecnico'        => 'Ing. Ana Martínez',
                'estado'         => 'Pendiente',
            ],
        ]);

        $filtrados = $todos
            ->sortBy('fecha_apertura')
            ->when($this->busqueda, fn($c) => $c->filter(fn($r) =>
                stripos($r['cliente'], $this->busqueda) !== false ||
                stripos($r['folio'],   $this->busqueda) !== false
            ))
            ->when($this->filtroEstado !== 'Todos', fn($c) => $c->where('estado', $this->filtroEstado))
            ->when($this->filtroTipo   !== 'Todos', fn($c) => $c->where('tipo_reporte', $this->filtroTipo))
            ->when($this->filtroSucursal, fn($c) => $c->where('sucursal', $this->filtroSucursal));

        // Contadores para los KPI cards
        $totalReportes    = $todos->count();
        $totalPendientes  = $todos->where('estado', 'Pendiente')->count();
        $totalEnProceso   = $todos->where('estado', 'En Proceso')->count();
        $totalCerradosHoy = $todos->where('estado', 'Cerrado')->count();

        return view('livewire.gestion-clientes.reportes-servicio', compact(
            'filtrados',
            'totalReportes',
            'totalPendientes',
            'totalEnProceso',
            'totalCerradosHoy',
        ));
    }
}
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

        // DATOS DE EJEMPLO — todos los tipos de reporte implementados
        $todos = collect([
            // ── INSTALACIONES ────────────────────────────────────────────
            [
                'folio'          => 'REP-2026-0003',
                'fecha_apertura' => '2026-03-11 09:00',
                'tipo_reporte'   => 'INSTALACION',
                'tipo_servicio'  => 'TV',
                'cliente'        => 'CARMEN FUENTES ALBA',
                'telefono'       => '9519876543',
                'servicio_actual'=> 'Retro TV',
                'sucursal'       => 'Oaxaca Centro',
                'tecnico'        => 'Cuadrilla 2',
                'estado'         => 'Pendiente',
                'quien_reporto'  => 'Sistema',
            ],
            [
                'folio'          => 'REP-2026-0005',
                'fecha_apertura' => '2026-03-11 11:00',
                'tipo_reporte'   => 'INSTALACION',
                'tipo_servicio'  => 'INTERNET',
                'cliente'        => 'GABRIELA RÍOS SANTOS',
                'telefono'       => '9513579246',
                'servicio_actual'=> 'Internet 100 Mbps',
                'sucursal'       => 'Oaxaca Norte',
                'tecnico'        => 'Cuadrilla 2',
                'estado'         => 'Pendiente',
                'quien_reporto'  => 'Sistema',
            ],
            [
                'folio'          => 'REP-2026-0001',
                'fecha_apertura' => '2026-03-11 10:00',
                'tipo_reporte'   => 'INSTALACION',
                'tipo_servicio'  => 'TV+INTERNET',
                'cliente'        => 'JUAN PÉREZ GARCÍA',
                'telefono'       => '9511234567',
                'servicio_actual'=> 'TV + Internet 30 Mbps',
                'sucursal'       => 'Oaxaca Centro',
                'tecnico'        => 'Ing. Roberto Gómez',
                'estado'         => 'Pendiente',
                'quien_reporto'  => 'Sistema',
            ],
            // ── FALLAS ───────────────────────────────────────────────────
            [
                'folio'          => 'REP-2026-0002',
                'fecha_apertura' => '2026-03-11 08:30',
                'tipo_reporte'   => 'FALLA_TV',
                'tipo_servicio'  => 'TV',
                'cliente'        => 'MARÍA LÓPEZ CRUZ',
                'telefono'       => '9524567890',
                'servicio_actual'=> 'Retro TV',
                'sucursal'       => 'San Pedro Amuzgos',
                'tecnico'        => 'Cuadrilla 2',
                'estado'         => 'Pendiente',
                'quien_reporto'  => 'Cliente',
            ],
            [
                'folio'          => 'REP-2026-0009',
                'fecha_apertura' => '2026-03-12 08:15',
                'tipo_reporte'   => 'FALLA_INTERNET',
                'tipo_servicio'  => 'INTERNET',
                'cliente'        => 'JORGE CASTILLO MORA',
                'telefono'       => '9511234000',
                'servicio_actual'=> 'Internet 50 Mbps',
                'sucursal'       => 'Oaxaca Norte',
                'tecnico'        => 'Cuadrilla 3',
                'estado'         => 'Pendiente',
                'quien_reporto'  => 'Cliente',
            ],
            [
                'folio'          => 'REP-2026-0004',
                'fecha_apertura' => '2026-03-11 07:45',
                'tipo_reporte'   => 'FALLA_TV_INTERNET',
                'tipo_servicio'  => 'TV+INTERNET',
                'cliente'        => 'ROSA MARTÍNEZ DÍAZ',
                'telefono'       => '9513456789',
                'servicio_actual'=> 'Combo TV + Internet',
                'sucursal'       => 'Oaxaca Norte',
                'tecnico'        => 'Cuadrilla 1',
                'estado'         => 'Pendiente',
                'quien_reporto'  => 'Cliente',
            ],
            // ── CAMBIO DE DOMICILIO ───────────────────────────────────────
            [
                'folio'          => 'REP-2026-0014',
                'fecha_apertura' => '2026-03-12 09:30',
                'tipo_reporte'   => 'CAMBIO_DOMICILIO',
                'tipo_servicio'  => 'TV+INTERNET',
                'cliente'        => 'FERNANDA LUNA ESPINOZA',
                'telefono'       => '9515678901',
                'servicio_actual'=> 'TV + Internet 30 Mbps',
                'sucursal'       => 'Oaxaca Centro',
                'tecnico'        => 'Cuadrilla 1',
                'estado'         => 'Pendiente',
                'quien_reporto'  => 'Administración',
            ],
            // ── SUSPENSIONES ─────────────────────────────────────────────
            [
                'folio'          => 'SUP-2026-0001',
                'fecha_apertura' => '2026-03-10 07:30',
                'tipo_reporte'   => 'SUSPENSION',
                'tipo_servicio'  => 'TV+INTERNET',
                'cliente'        => 'PEDRO ARMENDÁRIZ RUIZ',
                'telefono'       => '9518765432',
                'servicio_actual'=> 'TV + Internet 30 Mbps',
                'sucursal'       => 'Oaxaca Centro',
                'tecnico'        => 'Sucursal (remoto)',
                'estado'         => 'Pendiente',
                'quien_reporto'  => 'Sistema Automático',
            ],
            [
                'folio'          => 'SUP-2026-0002',
                'fecha_apertura' => '2026-02-24 08:45',
                'tipo_reporte'   => 'SUSPENSION',
                'tipo_servicio'  => 'TV',
                'cliente'        => 'LUIS HERNÁNDEZ VEGA',
                'telefono'       => '9527654321',
                'servicio_actual'=> 'Retro TV',
                'sucursal'       => 'San Pedro Amuzgos',
                'tecnico'        => 'Cuadrilla 3',
                'estado'         => 'Pendiente',
                'quien_reporto'  => 'Sistema',
            ],
            // ── RECUPERACIONES ───────────────────────────────────────────
            [
                'folio'          => 'REC-2026-0001',
                'fecha_apertura' => '2026-03-10 06:00',
                'tipo_reporte'   => 'RECUPERACION',
                'tipo_servicio'  => 'TV+INTERNET',
                'cliente'        => 'PEDRO ARMENDÁRIZ RUIZ',
                'telefono'       => '9518765432',
                'servicio_actual'=> 'TV + Internet 30 Mbps',
                'sucursal'       => 'Oaxaca Centro',
                'tecnico'        => 'Cuadrilla 1',
                'estado'         => 'Pendiente',
                'quien_reporto'  => 'Sistema Automático',
            ],
            [
                'folio'          => 'REP-2026-0007',
                'fecha_apertura' => '2026-02-24 09:00',
                'tipo_reporte'   => 'RECUPERACION',
                'tipo_servicio'  => 'INTERNET',
                'cliente'        => 'PATRICIA GÓMEZ RIVAS',
                'telefono'       => '9512345678',
                'servicio_actual'=> 'Internet 30 Mbps',
                'sucursal'       => 'Oaxaca Centro',
                'tecnico'        => 'Cuadrilla 1',
                'estado'         => 'Pendiente',
                'quien_reporto'  => 'Sistema',
            ],
            // ── CAMBIOS Y AMPLIACIONES ───────────────────────────────────
            [
                'folio'          => 'REP-2026-0006',
                'fecha_apertura' => '2026-03-11 13:00',
                'tipo_reporte'   => 'CAMBIO_SERVICIO',
                'tipo_servicio'  => 'TV+INTERNET',
                'cliente'        => 'ROBERTO DÍAZ LUNA',
                'telefono'       => '9514561230',
                'servicio_actual'=> 'TV + Internet 100 Mbps',
                'sucursal'       => 'Oaxaca Norte',
                'tecnico'        => 'Cuadrilla 3',
                'estado'         => 'Pendiente',
                'quien_reporto'  => 'Administración',
            ],
            [
                'folio'          => 'REP-2026-0010',
                'fecha_apertura' => '2026-03-11 14:00',
                'tipo_reporte'   => 'SERVICIO_ADICIONAL_TV',
                'tipo_servicio'  => 'TV',
                'cliente'        => 'SOFÍA RAMÍREZ LUNA',
                'telefono'       => '9519012345',
                'servicio_actual'=> 'Retro TV (adicional)',
                'sucursal'       => 'Oaxaca Centro',
                'tecnico'        => 'Cuadrilla 2',
                'estado'         => 'Pendiente',
                'quien_reporto'  => 'Sistema',
            ],
            [
                'folio'          => 'REP-2026-0011',
                'fecha_apertura' => '2026-03-11 15:00',
                'tipo_reporte'   => 'AUMENTO_VELOCIDAD',
                'tipo_servicio'  => 'INTERNET',
                'cliente'        => 'ANDRÉS VILLA RUIZ',
                'telefono'       => '9513579246',
                'servicio_actual'=> 'Internet 30 Mbps → 100 Mbps',
                'sucursal'       => 'Oaxaca Norte',
                'tecnico'        => 'Ing. Ana Martínez',
                'estado'         => 'Pendiente',
                'quien_reporto'  => 'Sistema',
            ],
            // ── RECONEXIONES ─────────────────────────────────────────────
            [
                'folio'          => 'REP-2026-0012',
                'fecha_apertura' => '2026-03-11 16:00',
                'tipo_reporte'   => 'RECONEXION',
                'tipo_servicio'  => 'TV+INTERNET',
                'cliente'        => 'ELENA VARGAS MORALES',
                'telefono'       => '9516789012',
                'servicio_actual'=> 'TV + Internet 30 Mbps',
                'sucursal'       => 'Oaxaca Centro',
                'tecnico'        => 'Cuadrilla 1',
                'estado'         => 'Pendiente',
                'quien_reporto'  => 'Administración',
            ],
            [
                'folio'          => 'REP-2026-0013',
                'fecha_apertura' => '2026-03-11 17:00',
                'tipo_reporte'   => 'RECONEXION_CAMBIO',
                'tipo_servicio'  => 'TV+INTERNET',
                'cliente'        => 'MARCOS SANTILLÁN RUIZ',
                'telefono'       => '9514321098',
                'servicio_actual'=> 'TV + Internet 50 Mbps',
                'sucursal'       => 'Oaxaca Norte',
                'tecnico'        => 'Cuadrilla 2',
                'estado'         => 'Pendiente',
                'quien_reporto'  => 'Administración',
            ],
            // ── CANCELACIÓN ──────────────────────────────────────────────
            [
                'folio'          => 'REP-2026-0008',
                'fecha_apertura' => '2026-03-11 18:00',
                'tipo_reporte'   => 'CANCELACION',
                'tipo_servicio'  => 'TV+INTERNET',
                'cliente'        => 'MANUEL ORTIZ CAMPOS',
                'telefono'       => '9516543210',
                'servicio_actual'=> 'Combo TV + Internet',
                'sucursal'       => 'Oaxaca Centro',
                'tecnico'        => 'Ing. Ana Martínez',
                'estado'         => 'Pendiente',
                'quien_reporto'  => 'Administración',
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
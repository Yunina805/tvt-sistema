<?php

namespace App\Livewire\GestionClientes;

use App\Traits\WithToasts;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class EstadoCuenta extends Component
{
    use WithToasts;

    // ── Búsqueda ──────────────────────────────────────────────────────────────
    public string $busqueda        = '';
    public array  $resultados      = [];
    public bool   $busquedaHecha   = false;
    public ?array $suscriptor      = null;

    // ── Filtro de período ─────────────────────────────────────────────────────
    public string $tipoPeriodo = 'mensual';    // mensual | personalizado
    public string $periodoMes  = '';
    public string $fechaDesde  = '';
    public string $fechaHasta  = '';

    // ── Tab activa ────────────────────────────────────────────────────────────
    public string $tabActual = 'mensualidades';

    // ── Movimientos cargados ──────────────────────────────────────────────────
    public array $movimientos = [
        'mensualidades'        => [],
        'dias_uso'             => [],
        'contratacion_nueva'   => [],
        'servicios_adicionales'=> [],
        'reconexiones'         => [],
    ];

    // ─────────────────────────────────────────────────────────────────────────

    public function mount(): void
    {
        $this->periodoMes = now()->format('Y-m');
        $this->fechaDesde = now()->startOfMonth()->format('Y-m-d');
        $this->fechaHasta = now()->endOfMonth()->format('Y-m-d');
    }

    // ── Búsqueda de suscriptores ──────────────────────────────────────────────

    public function buscarCliente(): void
    {
        $this->busquedaHecha = true;
        $this->resultados    = [];

        if (mb_strlen(trim($this->busqueda)) < 3) {
            $this->toastError('Ingresa al menos 3 caracteres para buscar.');
            return;
        }

        // TODO: Cliente::with(['suscripcionActiva.tarifa', 'sucursal'])
        //     ->where(fn($q) => $q
        //         ->where('nombre',      'LIKE', "%{$this->busqueda}%")
        //         ->orWhere('telefono',  'LIKE', "%{$this->busqueda}%")
        //         ->orWhere('id_cliente','LIKE', "%{$this->busqueda}%")
        //         ->orWhere('direccion', 'LIKE', "%{$this->busqueda}%")
        //     )->where('estado', '!=', 'CANCELADO')->limit(8)->get()->toArray();

        $mock = [
            [
                'id'          => '01-0001234',
                'nombre'      => 'JUAN PÉREZ GARCÍA',
                'telefono'    => '9511234567',
                'direccion'   => 'AV. INDEPENDENCIA 102, COL. CENTRO',
                'servicios'   => ['RETRO TV + INTERNET 30 MBPS'],
                'estado'      => 'ACTIVO',
                'sucursal'    => 'Oaxaca Centro',
                'saldo_actual'=> 960.00,
                'meses_adeudo'=> 2,
                'tarifa'      => 480.00,
            ],
            [
                'id'          => '02-0005678',
                'nombre'      => 'MARÍA LÓPEZ CRUZ',
                'telefono'    => '9519876543',
                'direccion'   => 'CALLE MORELOS 45, COL. REFORMA',
                'servicios'   => ['RETRO TV'],
                'estado'      => 'ACTIVO',
                'sucursal'    => 'San Pedro Amuzgos',
                'saldo_actual'=> 0.00,
                'meses_adeudo'=> 0,
                'tarifa'      => 250.00,
            ],
            [
                'id'          => '03-0009012',
                'nombre'      => 'CARLOS RUIZ MENDOZA',
                'telefono'    => '9514561234',
                'direccion'   => 'PERIFÉRICO NORTE 301, COL. VOLCANES',
                'servicios'   => ['INTERNET 50 MBPS'],
                'estado'      => 'SUSPENDIDO',
                'sucursal'    => 'Oaxaca Norte',
                'saldo_actual'=> 700.00,
                'meses_adeudo'=> 2,
                'tarifa'      => 350.00,
            ],
        ];

        $term = mb_strtolower(trim($this->busqueda));
        $this->resultados = array_values(array_filter($mock, fn($c) =>
            str_contains(mb_strtolower($c['nombre']),    $term) ||
            str_contains($c['telefono'],                 $term) ||
            str_contains(mb_strtolower($c['id']),        $term) ||
            str_contains(mb_strtolower($c['direccion']), $term)
        ));

        if (empty($this->resultados)) {
            $this->toastWarning('No se encontraron suscriptores con "' . trim($this->busqueda) . '".');
        }
    }

    public function seleccionarSuscriptor(array $s): void
    {
        $this->suscriptor    = $s;
        $this->resultados    = [];
        $this->busqueda      = '';
        $this->busquedaHecha = false;
        $this->tabActual     = 'mensualidades';
        $this->cargarMovimientos();
        $this->toastInfo('Estado de cuenta cargado para ' . $s['nombre'] . '.');
    }

    public function limpiarSuscriptor(): void
    {
        $this->suscriptor    = null;
        $this->busquedaHecha = false;
        $this->tabActual     = 'mensualidades';
        $this->movimientos   = [
            'mensualidades'         => [],
            'dias_uso'              => [],
            'contratacion_nueva'    => [],
            'servicios_adicionales' => [],
            'reconexiones'          => [],
        ];
    }

    // ── Watchers de período ───────────────────────────────────────────────────

    public function updatedPeriodoMes(): void
    {
        if ($this->suscriptor) $this->cargarMovimientos();
    }

    public function updatedFechaDesde(): void
    {
        if ($this->suscriptor && $this->fechaHasta) $this->cargarMovimientos();
    }

    public function updatedFechaHasta(): void
    {
        if ($this->suscriptor && $this->fechaDesde) $this->cargarMovimientos();
    }

    public function updatedTipoPeriodo(): void
    {
        if ($this->suscriptor) $this->cargarMovimientos();
    }

    // ── Carga de movimientos ──────────────────────────────────────────────────

    public function cargarMovimientos(): void
    {
        if (! $this->suscriptor) return;

        // TODO: Los queries reales filtrarán por cliente_id y rango de fechas:
        //
        // $desde = $this->tipoPeriodo === 'mensual'
        //     ? Carbon::createFromFormat('Y-m', $this->periodoMes)->startOfMonth()
        //     : Carbon::parse($this->fechaDesde)->startOfDay();
        // $hasta = $this->tipoPeriodo === 'mensual'
        //     ? Carbon::createFromFormat('Y-m', $this->periodoMes)->endOfMonth()
        //     : Carbon::parse($this->fechaHasta)->endOfDay();
        //
        // $this->movimientos['mensualidades'] = CorteMensualidad::where('cliente_id', $this->suscriptor['id'])
        //     ->whereBetween('fecha_corte', [$desde, $hasta])
        //     ->orderBy('fecha_corte')->get()->map(fn($r) => [
        //         'movimiento'         => $r->folio,
        //         'concepto'           => $r->concepto,
        //         'servicio'           => $r->servicio,
        //         'fecha'              => $r->fecha_corte->format('d/m/Y'),
        //         'importe_cobrar'     => $r->importe_cobrar,
        //         'saldo_anterior'     => $r->saldo_anterior,
        //         'saldo_pagar_corte'  => $r->saldo_pagar_corte,
        //         'pago_mensualidad'   => $r->pago_mensualidad,
        //         'saldo_periodo'      => $r->saldo_periodo,
        //     ])->toArray();
        //
        // $this->movimientos['dias_uso'] = Ingreso::where('cliente_id', ...)
        //     ->where('categoria', 'DIAS_USO')->whereBetween(...)
        //     ->get()->map(...)->toArray();
        // ... (mismo patrón para contratacion_nueva, servicios_adicionales, reconexiones)

        $this->movimientos = [
            'mensualidades' => [
                ['movimiento' => 'COR-2026-00031', 'concepto' => 'CORTE ENERO 2026',   'servicio' => 'TV + INTERNET', 'fecha' => '01/01/2026', 'importe_cobrar' => 480.00, 'saldo_anterior' => 480.00, 'saldo_pagar_corte' => 960.00, 'pago_mensualidad' =>    0.00, 'saldo_periodo' => 960.00],
                ['movimiento' => 'PAG-2026-A1C3F2', 'concepto' => 'PAGO ENERO 2026',   'servicio' => 'TV + INTERNET', 'fecha' => '15/01/2026', 'importe_cobrar' =>   0.00, 'saldo_anterior' => 960.00, 'saldo_pagar_corte' =>   0.00, 'pago_mensualidad' => 480.00, 'saldo_periodo' => 480.00],
                ['movimiento' => 'COR-2026-00058', 'concepto' => 'CORTE FEBRERO 2026', 'servicio' => 'TV + INTERNET', 'fecha' => '01/02/2026', 'importe_cobrar' => 480.00, 'saldo_anterior' => 480.00, 'saldo_pagar_corte' => 960.00, 'pago_mensualidad' =>    0.00, 'saldo_periodo' => 960.00],
                ['movimiento' => 'COR-2026-00091', 'concepto' => 'CORTE MARZO 2026',   'servicio' => 'TV + INTERNET', 'fecha' => '01/03/2026', 'importe_cobrar' => 480.00, 'saldo_anterior' => 960.00, 'saldo_pagar_corte' => 1440.00,'pago_mensualidad' =>    0.00, 'saldo_periodo' => 1440.00],
            ],
            'dias_uso' => [
                ['movimiento' => 'PAG-2025-D8E2A1', 'concepto' => 'PROPORCIONAL ALTA DIC-2025', 'servicio' => 'TV + INTERNET', 'fecha' => '10/12/2025', 'importe' => 176.00],
                ['movimiento' => 'PAG-2026-C3F1B2', 'concepto' => 'PROPORCIONAL RECONEXIÓN FEB-2026', 'servicio' => 'INTERNET',   'fecha' => '05/02/2026', 'importe' => 116.67],
            ],
            'contratacion_nueva' => [
                ['movimiento' => 'PAG-2025-A0B1C2', 'concepto' => 'CARGO DE INSTALACIÓN', 'servicio' => 'TV + INTERNET 30 MBPS', 'fecha' => '10/12/2025', 'importe' => 300.00],
                ['movimiento' => 'PAG-2025-D4E5F6', 'concepto' => 'PAGO INICIAL ALTA',    'servicio' => 'TV + INTERNET 30 MBPS', 'fecha' => '10/12/2025', 'importe' => 480.00],
            ],
            'servicios_adicionales' => [
                ['movimiento' => 'PAG-2026-F9A2B3', 'concepto' => 'INSTALACIÓN ADICIONAL TV', 'servicio' => 'TV ADICIONAL', 'fecha' => '18/01/2026', 'importe' => 200.00],
            ],
            'reconexiones' => [
                ['movimiento' => 'PAG-2026-G1H2I3', 'concepto' => 'LIQUIDACIÓN ADEUDO ENERO-FEB',  'servicio' => 'TV + INTERNET', 'fecha' => '05/02/2026', 'importe' => 480.00],
                ['movimiento' => 'PAG-2026-J4K5L6', 'concepto' => 'CARGO RECONEXIÓN',              'servicio' => 'TV + INTERNET', 'fecha' => '05/02/2026', 'importe' => 150.00],
                ['movimiento' => 'PAG-2026-M7N8O9', 'concepto' => 'PROPORCIONAL REACTIVACIÓN FEB', 'servicio' => 'INTERNET',      'fecha' => '05/02/2026', 'importe' => 116.67],
            ],
        ];
    }

    // ── Render ────────────────────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.gestion-clientes.estado-cuenta');
    }
}

<?php

namespace App\Livewire\GestionClientes;

use App\Traits\WithToasts;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('layouts.app')]
class RecuperacionEquipos extends Component
{
    use WithPagination, WithToasts;

    public string $search          = '';
    public string $filterSucursal  = '';
    public string $filterServicio  = '';

    public function updatingSearch(): void { $this->resetPage(); }

    public function resetFilters(): void
    {
        $this->search         = '';
        $this->filterSucursal = '';
        $this->filterServicio = '';
        $this->resetPage();
    }

    public function generarReporteRecuperacion(string $clienteId): void
    {
        // TODO: DB::transaction(function() use ($clienteId) {
        //
        //   1. Crear ReporteServicio:
        //      ReporteServicio::create([
        //          'folio'        => 'REC-' . now()->format('Y') . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT),
        //          'tipo_reporte' => 'RECUPERACION',
        //          'cliente_id'   => $clienteId,
        //          'sucursal_id'  => auth()->user()->sucursal_id,
        //          'tecnico_id'   => null,  // la sucursal asignará el técnico
        //          'estado'       => 'Pendiente',
        //          'fecha'        => now(),
        //      ]);
        //
        //   2. SMS al cliente (API):
        //      SmsService::enviar($cliente->telefono,
        //          "Tu Vision Telecable: Su equipo sera recuperado por falta de pago. " .
        //          "Comuniquese al {$sucursal->telefono} para regularizar su situacion.");
        //
        //   3. SMS al técnico (cuando la sucursal lo asigne desde el reporte):
        //      SmsService::enviar($tecnico->telefono,
        //          "Nuevo reporte de recuperacion asignado: {$folio}. Cliente: {$cliente->nombre}.");
        // });

        $this->toastExito("Reporte de recuperación generado para cliente {$clienteId}.");
    }

    public function render()
    {
        // ─────────────────────────────────────────────────────────────
        // TODO: Reemplazar con Eloquent real — tarea nocturna automática
        //
        // $clientes = Cliente::with(['servicio','equipo','nap','sucursal'])
        //     ->where('estado', 'Suspendido')
        //     ->whereHas('estadoCuenta', function($q) {
        //         $q->where('saldo_pendiente', '>', 0)
        //           ->where('fecha_ultimo_pago', '<=', now()->subDays(61));
        //     })
        //     ->when($this->search, fn($q) =>
        //         $q->where(fn($q) =>
        //             $q->where('nombre','LIKE',"%{$this->search}%")
        //               ->orWhere('id_cliente','LIKE',"%{$this->search}%")
        //               ->orWhere('telefono','LIKE',"%{$this->search}%")
        //         ))
        //     ->when($this->filterSucursal, fn($q) => $q->where('sucursal_id', $this->filterSucursal))
        //     ->when($this->filterServicio, fn($q) => $q->where('tipo_servicio', $this->filterServicio))
        //     ->orderBy('fecha_ultimo_pago', 'asc')   // más antiguo arriba
        //     ->paginate(20);
        // ─────────────────────────────────────────────────────────────

        $todos = collect([
            [
                'id'           => '01-0004567',
                'nombre'       => 'PEDRO ARMENDÁRIZ RUIZ',
                'sucursal'     => 'Oaxaca Centro',
                'saldo'        => 1920.00,
                'servicio'     => 'TV + Internet 30 Mbps',
                'tipo_servicio'=> 'TV+INTERNET',
                'estatus'      => 'Suspendido',
                'ultimo_pago'  => '2025-12-10',
                'equipo'       => 'ONU Huawei HG8010M',
                'serie_equipo' => 'HW2023-001',
                'nap'          => 'NAP-OAX-03',
                'tiene_internet'=> true,
            ],
            [
                'id'           => '01-0008910',
                'nombre'       => 'LUCÍA MÉNDEZ SANTOS',
                'sucursal'     => 'San Pedro Amuzgos',
                'saldo'        => 960.00,
                'servicio'     => 'Retro TV',
                'tipo_servicio'=> 'TV',
                'estatus'      => 'Suspendido',
                'ultimo_pago'  => '2025-12-01',
                'equipo'       => 'Mininodo ARRIS',
                'serie_equipo' => 'ARR2022-089',
                'nap'          => 'NAP-SPA-01',
                'tiene_internet'=> false,
            ],
            [
                'id'           => '02-0011234',
                'nombre'       => 'ROBERTO JIMÉNEZ LUNA',
                'sucursal'     => 'Oaxaca Norte',
                'saldo'        => 2400.00,
                'servicio'     => 'Internet 50 Mbps',
                'tipo_servicio'=> 'INTERNET',
                'estatus'      => 'Suspendido',
                'ultimo_pago'  => '2025-11-25',
                'equipo'       => 'ONU ZTE F660',
                'serie_equipo' => 'ZTE2023-045',
                'nap'          => 'NAP-OAN-07',
                'tiene_internet'=> true,
            ],
        ]);

        $filtrados = $todos
            ->when($this->search, fn($c) => $c->filter(fn($r) =>
                stripos($r['nombre'],  $this->search) !== false ||
                stripos($r['id'],      $this->search) !== false
            ))
            ->when($this->filterSucursal, fn($c) => $c->where('sucursal', $this->filterSucursal))
            ->when($this->filterServicio, fn($c) => $c->where('tipo_servicio', $this->filterServicio))
            ->sortBy('ultimo_pago');   // más antiguo arriba

        $totalDeudores   = $filtrados->count();
        $montoTotal      = $filtrados->sum('saldo');
        $conInternet     = $filtrados->where('tiene_internet', true)->count();
        $soloTV          = $filtrados->where('tiene_internet', false)->count();

        return view('livewire.gestion-clientes.recuperacion-equipos', compact(
            'filtrados',
            'totalDeudores',
            'montoTotal',
            'conInternet',
            'soloTV',
        ));
    }
}
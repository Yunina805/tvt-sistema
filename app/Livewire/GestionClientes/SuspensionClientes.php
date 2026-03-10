<?php

namespace App\Livewire\GestionClientes;

use App\Traits\WithToasts;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('layouts.app')]
class SuspensionClientes extends Component
{
    use WithPagination, WithToasts;

    public string $search          = '';
    public string $filterSucursal  = '';
    public string $filterStatus    = '';
    public string $filterServicio  = '';

    public function updatingSearch(): void { $this->resetPage(); }

    public function resetFilters(): void
    {
        $this->search         = '';
        $this->filterSucursal = '';
        $this->filterStatus   = '';
        $this->filterServicio = '';
        $this->resetPage();
    }

    public function generarReporteSuspension(string $clienteId): void
    {
        // ─────────────────────────────────────────────────────────────
        // TODO: DB::transaction(function() use ($clienteId) {
        //
        //   $cliente = Cliente::with(['servicio','equipo','nap'])->findOrFail($clienteId);
        //
        //   1. Crear ReporteServicio con tipo SUSPENSION:
        //      $seq    = ReporteServicio::whereYear('created_at', now()->year)->count() + 1;
        //      $folio  = 'SUP-' . now()->format('Y') . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);
        //      $reporte = ReporteServicio::create([
        //          'folio'          => $folio,
        //          'tipo_reporte'   => 'SUSPENSION',
        //          'tipo_servicio'  => $cliente->tipo_servicio,   // TV | INTERNET | TV+INTERNET
        //          'cliente_id'     => $clienteId,
        //          'sucursal_id'    => auth()->user()->sucursal_id,
        //          'estado'         => 'Pendiente',
        //          'fecha'          => now(),
        //          'soporta_remoto' => $cliente->equipo->soporta_suspension_remota ?? false,
        //      ]);
        //
        //   2. SMS al CLIENTE (automático al generar):
        //      SmsService::enviar($cliente->telefono,
        //          "Tu Vision Telecable: Su servicio ha sido SUSPENDIDO por falta de pago. " .
        //          "Saldo pendiente: $" . number_format($cliente->saldo, 2) . ". " .
        //          "Regularice su situacion llamando al {$sucursal->telefono}.");
        //
        //   3. SMS al TÉCNICO (solo si requiere desconexión física — TV o TV+Internet sin remoto):
        //      if (!$reporte->soporta_remoto || $cliente->tipo_servicio === 'TV') {
        //          SmsService::enviar($tecnico->telefono,
        //              "Nuevo reporte de suspension asignado: {$reporte->folio}. " .
        //              "Requiere desconexion FISICA en NAP. Cliente: {$cliente->nombre}.");
        //      }
        //
        // });
        //
        // $this->redirect(route('reportes.atender', ['folio' => $folio]));
        // ─────────────────────────────────────────────────────────────

        // Mock: en producción el folio se genera secuencialmente en BD
        $folio = 'SUP-2026-0001';

        $this->toastExito("Reporte {$folio} generado. SMS enviado al cliente.");
        $this->redirect(route('reportes.atender', ['folio' => $folio]));
    }

    public function render()
    {
        // ─────────────────────────────────────────────────────────────
        // TODO: Vista automática nocturna — tarea programada (scheduler)
        // php artisan schedule:run  ←  ejecuta cada noche
        //
        // $clientes = Cliente::with(['servicio','equipo','nap','sucursal'])
        //     ->where('estado', 'Activo')
        //     ->whereHas('estadoCuenta', function($q) {
        //         $q->where('saldo_pendiente', '>', 0)
        //           ->where('fecha_ultimo_pago', '<=', now()->subDays(31));
        //     })
        //     ->when($this->search, fn($q) =>
        //         $q->where(fn($q) =>
        //             $q->where('nombre','LIKE',"%{$this->search}%")
        //               ->orWhere('id_cliente','LIKE',"%{$this->search}%")
        //               ->orWhere('telefono','LIKE',"%{$this->search}%")
        //         ))
        //     ->when($this->filterSucursal, fn($q) => $q->where('sucursal_id', $this->filterSucursal))
        //     ->when($this->filterStatus,   fn($q) => $q->where('estado', $this->filterStatus))
        //     ->when($this->filterServicio, fn($q) => $q->where('tipo_servicio', $this->filterServicio))
        //     ->orderBy('fecha_ultimo_pago', 'asc')   // más antiguo (más crítico) arriba
        //     ->paginate(20);
        // ─────────────────────────────────────────────────────────────

        $todos = collect([
            [
                'id'              => '01-0004567',
                'nombre'          => 'PEDRO ARMENDÁRIZ RUIZ',
                'sucursal'        => 'Oaxaca Centro',
                'saldo'           => 960.00,
                'servicio'        => 'TV + Internet 30 Mbps',
                'tipo_servicio'   => 'TV+INTERNET',   // TV | INTERNET | TV+INTERNET
                'estatus'         => 'Activo',
                'ultimo_pago'     => '2026-01-15',
                // soporta_remoto: ONU puede cortar TV+Internet vía Winbox/OLT
                // false = requiere técnico en campo para desconexión física
                'soporta_remoto'  => true,
                'equipo'          => 'ONU ZTE F660',
                'serie_equipo'    => 'ZTE2023-045',
                'nap'             => 'NAP-OAX-03',
                'dir_nap'         => 'Poste 8, Calle Alcalá',
            ],
            [
                'id'              => '01-0008910',
                'nombre'          => 'LUCÍA MÉNDEZ SANTOS',
                'sucursal'        => 'San Pedro Amuzgos',
                'saldo'           => 480.00,
                'servicio'        => 'Retro TV',
                'tipo_servicio'   => 'TV',             // Solo TV = siempre físico
                'estatus'         => 'Activo',
                'ultimo_pago'     => '2026-01-10',
                'soporta_remoto'  => false,            // TV nunca puede ser remoto
                'equipo'          => 'Mininodo ARRIS',
                'serie_equipo'    => 'ARR2022-089',
                'nap'             => 'NAP-SPA-01',
                'dir_nap'         => 'Poste 3, Esquina Hidalgo',
            ],
            [
                'id'              => '02-0033211',
                'nombre'          => 'GABRIEL ORTEGA LUNA',
                'sucursal'        => 'Oaxaca Norte',
                'saldo'           => 1440.00,
                'servicio'        => 'Internet 50 Mbps',
                'tipo_servicio'   => 'INTERNET',       // Internet = siempre lógico (Winbox/OLT)
                'estatus'         => 'Activo',
                'ultimo_pago'     => '2026-01-05',
                'soporta_remoto'  => true,
                'equipo'          => 'ONU Huawei HG8310M',
                'serie_equipo'    => 'HW2022-210',
                'nap'             => 'NAP-OAN-05',
                'dir_nap'         => 'Poste 14, Av. Ferrocarril',
            ],
            [
                'id'              => '01-0019872',
                'nombre'          => 'CARMEN FUENTES DÍAZ',
                'sucursal'        => 'Oaxaca Centro',
                'saldo'           => 720.00,
                'servicio'        => 'TV + Internet 30 Mbps',
                'tipo_servicio'   => 'TV+INTERNET',
                'estatus'         => 'Activo',
                'ultimo_pago'     => '2026-01-08',
                'soporta_remoto'  => false,            // ONU antigua, no soporta corte remoto
                'equipo'          => 'ONU CALIX (sin función remota)',
                'serie_equipo'    => 'CAL2021-003',
                'nap'             => 'NAP-OAX-07',
                'dir_nap'         => 'Poste 15, Calle Alcalá',
            ],
        ]);

        $filtrados = $todos
            ->when($this->search, fn($c) => $c->filter(fn($r) =>
                stripos($r['nombre'],  $this->search) !== false ||
                stripos($r['id'],      $this->search) !== false
            ))
            ->when($this->filterSucursal, fn($c) => $c->where('sucursal', $this->filterSucursal))
            ->when($this->filterStatus,   fn($c) => $c->where('estatus', $this->filterStatus))
            ->when($this->filterServicio, fn($c) => $c->where('tipo_servicio', $this->filterServicio))
            ->sortBy('ultimo_pago');   // más antiguo arriba (más urgente)

        // KPIs
        $totalDeudores        = $filtrados->count();
        $montoTotal           = $filtrados->sum('saldo');
        $suspendidosHoy       = 0;  // TODO: contar reportes tipo SUSPENSION generados hoy
        $pendientesSuspension = $filtrados->count(); // aún sin reporte generado

        return view('livewire.gestion-clientes.suspension-clientes', compact(
            'filtrados',
            'totalDeudores',
            'montoTotal',
            'suspendidosHoy',
            'pendientesSuspension',
        ));
    }
}
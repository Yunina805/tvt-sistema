<?php

namespace App\Livewire\GestionClientes;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('layouts.app')]
class ContratacionPromocion extends Component
{
    // ── Flujo ─────────────────────────────────────────────────────────
    public int    $paso             = 1;
    public string $folioContratacion = '';

    // ── Búsqueda ──────────────────────────────────────────────────────
    public string $search      = '';
    public array  $resultados  = [];
    public ?array $cliente     = null;

    // ── Selección de promo ────────────────────────────────────────────
    public ?array $promoSeleccionada = null;

    // ── Forma de pago ─────────────────────────────────────────────────
    public string $formaPago = 'efectivo';

    // ── Cálculos derivados ────────────────────────────────────────────
    public array $calculos = [
        'dias_uso'      => 0,
        'importe_dias'  => 0.0,
        'importe_promo' => 0.0,
        'total'         => 0.0,
        'fecha_inicio'  => '',
        'fecha_termino' => '',
        'proximo_pago'  => '',
    ];

    // ── Resultado (paso 3) ────────────────────────────────────────────
    public array $resultado = [];

    // ─────────────────────────────────────────────────────────────────

    public function mount(): void
    {
        $this->folioContratacion = 'PROMO-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(3)));
    }

    // ── Búsqueda de clientes ──────────────────────────────────────────

    public function buscarCliente(): void
    {
        if (strlen(trim($this->search)) < 2) {
            $this->resultados = [];
            return;
        }

        // ─────────────────────────────────────────────────────────────
        // TODO: Reemplazar con Eloquent real:
        // $this->resultados = Cliente::with('servicio')
        //     ->where(fn($q) =>
        //         $q->where('nombre','LIKE',"%{$this->search}%")
        //           ->orWhere('id_cliente','LIKE',"%{$this->search}%")
        //           ->orWhere('telefono','LIKE',"%{$this->search}%")
        //     )
        //     ->whereIn('estado', ['Activo'])
        //     ->limit(6)
        //     ->get()
        //     ->toArray();
        // ─────────────────────────────────────────────────────────────

        $mock = [
            ['id' => '01-0012345', 'nombre' => 'JUAN PÉREZ GARCÍA',       'servicio' => 'TV + Internet 30 Mbps', 'tipo_servicio' => 'TV+INTERNET', 'tarifa' => 480.00,  'estado' => 'Activo', 'sucursal' => 'Oaxaca Centro'],
            ['id' => '01-0019872', 'nombre' => 'CARMEN FUENTES DÍAZ',     'servicio' => 'Retro TV',              'tipo_servicio' => 'TV',           'tarifa' => 280.00,  'estado' => 'Activo', 'sucursal' => 'Oaxaca Centro'],
            ['id' => '02-0033211', 'nombre' => 'GABRIEL ORTEGA LUNA',     'servicio' => 'Internet 50 Mbps',      'tipo_servicio' => 'INTERNET',     'tarifa' => 380.00,  'estado' => 'Activo', 'sucursal' => 'Oaxaca Norte'],
            ['id' => '01-0007891', 'nombre' => 'MARÍA LÓPEZ CRUZ',        'servicio' => 'Retro TV',              'tipo_servicio' => 'TV',           'tarifa' => 280.00,  'estado' => 'Activo', 'sucursal' => 'San Pedro Amuzgos'],
            ['id' => '02-0099012', 'nombre' => 'PATRICIA GÓMEZ RIVAS',    'servicio' => 'Internet 100 Mbps',     'tipo_servicio' => 'INTERNET',     'tarifa' => 550.00,  'estado' => 'Activo', 'sucursal' => 'Oaxaca Centro'],
            ['id' => '01-0055234', 'nombre' => 'ROSA MARTÍNEZ DÍAZ',      'servicio' => 'TV + Internet 50 Mbps', 'tipo_servicio' => 'TV+INTERNET', 'tarifa' => 620.00,  'estado' => 'Activo', 'sucursal' => 'Oaxaca Norte'],
        ];

        $busq = mb_strtolower(trim($this->search));
        $this->resultados = array_values(array_filter(
            $mock,
            fn($c) => str_contains(mb_strtolower($c['nombre']), $busq) ||
                      str_contains(mb_strtolower($c['id']), $busq)
        ));
    }

    public function seleccionarCliente(array $c): void
    {
        $this->cliente           = $c;
        $this->resultados        = [];
        $this->search            = '';
        $this->promoSeleccionada = null;
        $this->calculos          = [
            'dias_uso'      => 0,
            'importe_dias'  => 0.0,
            'importe_promo' => 0.0,
            'total'         => 0.0,
            'fecha_inicio'  => '',
            'fecha_termino' => '',
            'proximo_pago'  => '',
        ];
        $this->paso = 2;
    }

    // ── Selección de promoción + recálculo ────────────────────────────

    public function seleccionarPromo(int $id): void
    {
        $promo = collect($this->getPromociones())->firstWhere('id', $id);
        if (!$promo) return;

        $this->promoSeleccionada = $promo;
        $this->recalcular();
    }

    private function recalcular(): void
    {
        if (!$this->promoSeleccionada || !$this->cliente) return;

        $hoy           = Carbon::now();
        $finMes        = Carbon::now()->endOfMonth();
        $diasRestantes = (int) $hoy->diffInDays($finMes) + 1;

        $tarifa        = (float) $this->cliente['tarifa'];
        $costoDia      = $tarifa / 30;
        $importeDias   = round($costoDia * $diasRestantes, 2);
        $importePromo  = round($tarifa * $this->promoSeleccionada['meses_pago'], 2);

        // Promo inicia el día 1 del mes siguiente
        $inicio  = Carbon::now()->addMonthNoOverflow()->startOfMonth();
        // Término: inicio + (meses_beneficio × 30 días — bloques exactos)
        $termino = $inicio->copy()->addDays($this->promoSeleccionada['meses_beneficio'] * 30);
        // Próximo pago: día siguiente al término
        $proxPago = $termino->copy()->addDay();

        $this->calculos = [
            'dias_uso'      => $diasRestantes,
            'costo_dia'     => round($costoDia, 2),
            'importe_dias'  => $importeDias,
            'importe_promo' => $importePromo,
            'total'         => $importeDias + $importePromo,
            'fecha_inicio'  => $inicio->format('d/m/Y'),
            'fecha_termino' => $termino->format('d/m/Y'),
            'proximo_pago'  => $proxPago->format('d/m/Y'),
        ];
    }

    // ── Confirmar contratación → paso 3 ───────────────────────────────

    public function confirmarContratacion(): void
    {
        if (!$this->cliente || !$this->promoSeleccionada) return;

        // ─────────────────────────────────────────────────────────────
        // TODO: DB::transaction(function () {
        //
        //   $promo = PromocionContratada::create([
        //       'folio'            => $this->folioContratacion,
        //       'cliente_id'       => $this->cliente['id'],
        //       'promocion_id'     => $this->promoSeleccionada['id'],
        //       'fecha_aplicacion' => Carbon::createFromFormat('d/m/Y', $this->calculos['fecha_inicio']),
        //       'fecha_termino'    => Carbon::createFromFormat('d/m/Y', $this->calculos['fecha_termino']),
        //       'proximo_pago'     => Carbon::createFromFormat('d/m/Y', $this->calculos['proximo_pago']),
        //       'importe_dias'     => $this->calculos['importe_dias'],
        //       'importe_promo'    => $this->calculos['importe_promo'],
        //       'total'            => $this->calculos['total'],
        //       'forma_pago'       => $this->formaPago,
        //   ]);
        //
        //   Cliente::where('id', $this->cliente['id'])->update([
        //       'estado'                => 'PROMOCION_ACTIVA',
        //       'promo_id'              => $promo->id,
        //       'promo_fecha_inicio'    => $promo->fecha_aplicacion,
        //       'promo_fecha_termino'   => $promo->fecha_termino,
        //       'promo_proximo_pago'    => $promo->proximo_pago,
        //   ]);
        //
        //   Ingreso::create([
        //       'concepto'    => "PROMO {$this->promoSeleccionada['nombre']}",
        //       'importe'     => $this->calculos['total'],
        //       'forma_pago'  => $this->formaPago,
        //       'cliente_id'  => $this->cliente['id'],
        //   ]);
        //
        //   CorteCaja::registrar($this->calculos['total'], $this->formaPago);
        //
        //   SmsService::enviar($this->cliente['telefono'],
        //       "Tu Visión Telecable: Promo {$this->promoSeleccionada['nombre']} activada. " .
        //       "Vigencia: {$this->calculos['fecha_inicio']} al {$this->calculos['fecha_termino']}. " .
        //       "Próximo pago: {$this->calculos['proximo_pago']}.");
        //
        // });
        // ─────────────────────────────────────────────────────────────

        $this->resultado = [
            'folio'          => $this->folioContratacion,
            'cliente'        => $this->cliente,
            'promo'          => $this->promoSeleccionada,
            'calculos'       => $this->calculos,
            'forma_pago'     => $this->formaPago,
            'fecha_registro' => now()->format('d/m/Y H:i'),
        ];

        $this->paso = 3;
    }

    // ── Reset completo ────────────────────────────────────────────────

    public function nuevaContratacion(): void
    {
        $this->reset(['search', 'resultados', 'cliente', 'promoSeleccionada', 'calculos', 'resultado', 'formaPago']);
        $this->folioContratacion = 'PROMO-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(3)));
        $this->paso = 1;
    }

    // ── Catálogo de promociones (filtrado por tipo de servicio) ────────

    public function getPromociones(): array
    {
        // ─────────────────────────────────────────────────────────────
        // TODO: Cargar desde BD:
        // $tipo = $this->cliente['tipo_servicio'] ?? '';
        // return Promocion::where('activa', true)
        //     ->whereJsonContains('tipos_servicio', $tipo)
        //     ->orderBy('meses_pago', 'asc')
        //     ->get()->toArray();
        // ─────────────────────────────────────────────────────────────

        $todas = [
            ['id' => 1, 'nombre' => 'BIMESTRE 2×3',   'codigo' => '2x3',   'meses_pago' => 2,  'meses_beneficio' => 3,  'tipos' => ['TV', 'INTERNET', 'TV+INTERNET']],
            ['id' => 2, 'nombre' => 'TRIMESTRE 3×4',   'codigo' => '3x4',   'meses_pago' => 3,  'meses_beneficio' => 4,  'tipos' => ['TV', 'INTERNET', 'TV+INTERNET']],
            ['id' => 3, 'nombre' => 'SEMESTRAL 6×7',   'codigo' => '6x7',   'meses_pago' => 6,  'meses_beneficio' => 7,  'tipos' => ['TV', 'INTERNET', 'TV+INTERNET']],
            ['id' => 4, 'nombre' => 'ANUALIDAD 12×14', 'codigo' => '12x14', 'meses_pago' => 12, 'meses_beneficio' => 14, 'tipos' => ['TV', 'INTERNET', 'TV+INTERNET']],
        ];

        $tipo = $this->cliente['tipo_servicio'] ?? '';

        return array_values(array_filter(
            $todas,
            fn($p) => empty($tipo) || in_array($tipo, $p['tipos'])
        ));
    }

    // ── Render ────────────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.gestion-clientes.contratacion-promocion', [
            'promociones' => $this->getPromociones(),
        ]);
    }
}

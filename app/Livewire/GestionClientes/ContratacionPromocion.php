<?php

namespace App\Livewire\GestionClientes;

use App\Traits\WithToasts;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ContratacionPromocion extends Component
{
    use WithToasts;

    // ─── Flujo ────────────────────────────────────────────────────────────────
    public int    $paso             = 1;
    public string $folioContratacion = '';

    // ─── Búsqueda ─────────────────────────────────────────────────────────────
    public string $search          = '';
    public array  $resultados      = [];
    public bool   $busquedaHecha   = false;
    public ?array $cliente         = null;

    // ─── Selección de promoción ───────────────────────────────────────────────
    public ?array $promoSeleccionada = null;

    // ─── Forma de pago ────────────────────────────────────────────────────────
    public string $formaPago = 'efectivo';

    // ─── Descuento con contraseña de supervisor ───────────────────────────────
    public bool   $mostrarDescuento    = false;
    public float  $montoDescuentoInput = 0.0;
    public string $passwordDescuento   = '';
    public bool   $descuentoAplicado   = false;
    public float  $montoDescuento      = 0.0;
    public float  $totalConDescuento   = 0.0;

    // ─── Cálculos derivados ───────────────────────────────────────────────────
    public array $calculos = [
        'dias_uso'      => 0,
        'costo_dia'     => 0.0,
        'importe_dias'  => 0.0,
        'importe_promo' => 0.0,
        'total'         => 0.0,
        'fecha_inicio'  => '',
        'fecha_termino' => '',
        'proximo_pago'  => '',
    ];

    // ─── Resultado final (paso 4) ─────────────────────────────────────────────
    public array $resultado = [];

    // ─────────────────────────────────────────────────────────────────────────

    public function mount(): void
    {
        $this->folioContratacion = 'PROMO-' . now()->format('Y') . '-' . strtoupper(bin2hex(random_bytes(3)));
    }

    // ─── Búsqueda de clientes ─────────────────────────────────────────────────

    public function buscarCliente(): void
    {
        $this->busquedaHecha = true;
        $this->resultados    = [];

        if (mb_strlen(trim($this->search)) < 2) {
            $this->toastError('Ingresa al menos 2 caracteres para buscar.');
            return;
        }

        // TODO: Cliente::with('suscripcionActiva.tarifa')
        //     ->where(fn($q) => $q
        //         ->where('nombre', 'LIKE', "%{$this->search}%")
        //         ->orWhere('id_cliente', 'LIKE', "%{$this->search}%")
        //         ->orWhere('telefono', 'LIKE', "%{$this->search}%"))
        //     ->whereHas('suscripcionActiva', fn($q) => $q->where('estado', 'ACTIVO'))
        //     ->limit(6)->get()->toArray()

        $mock = [
            ['id' => '01-0012345', 'nombre' => 'JUAN PÉREZ GARCÍA',    'servicio' => 'TV + Internet 30 Mbps', 'tipo_servicio' => 'TV+INTERNET', 'tarifa' => 480.00, 'estado' => 'ACTIVO', 'sucursal' => 'Oaxaca Centro'],
            ['id' => '01-0019872', 'nombre' => 'CARMEN FUENTES DÍAZ',  'servicio' => 'Retro TV',              'tipo_servicio' => 'TV',           'tarifa' => 280.00, 'estado' => 'ACTIVO', 'sucursal' => 'Oaxaca Centro'],
            ['id' => '02-0033211', 'nombre' => 'GABRIEL ORTEGA LUNA',  'servicio' => 'Internet 50 Mbps',      'tipo_servicio' => 'INTERNET',     'tarifa' => 380.00, 'estado' => 'ACTIVO', 'sucursal' => 'Oaxaca Norte'],
            ['id' => '01-0007891', 'nombre' => 'MARÍA LÓPEZ CRUZ',     'servicio' => 'Retro TV',              'tipo_servicio' => 'TV',           'tarifa' => 280.00, 'estado' => 'ACTIVO', 'sucursal' => 'San Pedro Amuzgos'],
            ['id' => '02-0099012', 'nombre' => 'PATRICIA GÓMEZ RIVAS', 'servicio' => 'Internet 100 Mbps',     'tipo_servicio' => 'INTERNET',     'tarifa' => 550.00, 'estado' => 'ACTIVO', 'sucursal' => 'Oaxaca Centro'],
            ['id' => '01-0055234', 'nombre' => 'ROSA MARTÍNEZ DÍAZ',   'servicio' => 'TV + Internet 50 Mbps', 'tipo_servicio' => 'TV+INTERNET', 'tarifa' => 620.00, 'estado' => 'ACTIVO', 'sucursal' => 'Oaxaca Norte'],
        ];

        $busq = mb_strtolower(trim($this->search));

        $this->resultados = array_values(array_filter(
            $mock,
            fn ($c) => str_contains(mb_strtolower($c['nombre']), $busq) ||
                       str_contains(mb_strtolower($c['id']), $busq)
        ));
    }

    public function seleccionarCliente(array $c): void
    {
        $this->cliente           = $c;
        $this->resultados        = [];
        $this->search            = '';
        $this->busquedaHecha     = false;
        $this->promoSeleccionada = null;
        $this->quitarDescuento();
        $this->resetCalculos();
        $this->paso = 2;
    }

    // ─── Selección de promoción + recálculo ──────────────────────────────────

    public function seleccionarPromo(int $id): void
    {
        $promo = collect($this->getPromociones())->firstWhere('id', $id);
        if (! $promo) return;

        $this->promoSeleccionada = $promo;
        $this->quitarDescuento();
        $this->recalcular();
    }

    private function recalcular(): void
    {
        if (! $this->promoSeleccionada || ! $this->cliente) return;

        $hoy           = Carbon::now();
        $finMes        = Carbon::now()->endOfMonth();
        $diasRestantes = (int) $hoy->diffInDays($finMes) + 1;

        $tarifa       = (float) $this->cliente['tarifa'];
        $costoDia     = $tarifa / 30;
        $importeDias  = round($costoDia * $diasRestantes, 2);
        $importePromo = round($tarifa * $this->promoSeleccionada['meses_pago'], 2);

        // Promo inicia el día 1 del mes siguiente
        $inicio   = Carbon::now()->addMonthNoOverflow()->startOfMonth();
        // Término: inicio + (meses_beneficio × 30 días — bloques exactos)
        $termino  = $inicio->copy()->addDays($this->promoSeleccionada['meses_beneficio'] * 30);
        // Próximo pago: día siguiente al término
        $proxPago = $termino->copy()->addDay();

        $this->calculos = [
            'dias_uso'      => $diasRestantes,
            'costo_dia'     => round($costoDia, 2),
            'importe_dias'  => $importeDias,
            'importe_promo' => $importePromo,
            'total'         => round($importeDias + $importePromo, 2),
            'fecha_inicio'  => $inicio->format('d/m/Y'),
            'fecha_termino' => $termino->format('d/m/Y'),
            'proximo_pago'  => $proxPago->format('d/m/Y'),
        ];

        // Recalcular descuento si estaba aplicado
        if ($this->descuentoAplicado) {
            $this->calcularDescuento();
        }
    }

    // ─── Avanzar al paso de confirmación de pago ─────────────────────────────

    public function irPaso3(): void
    {
        if (! $this->promoSeleccionada) {
            $this->toastError('Selecciona una promoción para continuar.');
            return;
        }
        $this->paso = 3;
    }

    // ─── Descuento con contraseña de supervisor ───────────────────────────────

    public function aplicarDescuento(): void
    {
        if ($this->montoDescuentoInput <= 0) {
            $this->toastError('Ingresa un monto de descuento mayor a $0.');
            return;
        }

        if ($this->montoDescuentoInput >= $this->calculos['total']) {
            $this->toastError('El descuento no puede ser mayor o igual al total ($' . number_format($this->calculos['total'], 2) . ').');
            return;
        }

        // TODO: Validar contra contraseña real almacenada en config/DB para la sucursal
        $passwordValida = config('tvt.password_supervisor', 'supervisor123');

        if ($this->passwordDescuento !== $passwordValida) {
            $this->toastError('Contraseña de supervisor incorrecta.');
            $this->passwordDescuento = '';
            return;
        }

        $this->descuentoAplicado = true;
        $this->calcularDescuento();
        $this->passwordDescuento = '';
        $this->toastExito('Descuento de $' . number_format($this->montoDescuentoInput, 2) . ' aplicado.');
    }

    private function calcularDescuento(): void
    {
        $this->montoDescuento    = round($this->montoDescuentoInput, 2);
        $this->totalConDescuento = round($this->calculos['total'] - $this->montoDescuento, 2);
    }

    public function quitarDescuento(): void
    {
        $this->descuentoAplicado   = false;
        $this->montoDescuentoInput = 0.0;
        $this->montoDescuento      = 0.0;
        $this->totalConDescuento   = 0.0;
        $this->passwordDescuento   = '';
    }

    // Total efectivo a cobrar (con o sin descuento)
    public function getTotalEfectivoProperty(): float
    {
        return $this->descuentoAplicado ? $this->totalConDescuento : $this->calculos['total'];
    }

    // ─── Confirmar contratación → paso 4 ─────────────────────────────────────

    public function confirmarContratacion(): void
    {
        if (! $this->cliente || ! $this->promoSeleccionada) {
            $this->toastError('Selecciona un cliente y una promoción para continuar.');
            return;
        }

        // TODO: DB::transaction(function () {
        //   $totalFinal = $this->totalEfectivo;
        //   $promo = PromocionContratada::create([
        //     'folio'            => $this->folioContratacion,
        //     'cliente_id'       => $this->cliente['id'],
        //     'promocion_id'     => $this->promoSeleccionada['id'],
        //     'fecha_aplicacion' => Carbon::createFromFormat('d/m/Y', $this->calculos['fecha_inicio']),
        //     'fecha_termino'    => Carbon::createFromFormat('d/m/Y', $this->calculos['fecha_termino']),
        //     'proximo_pago'     => Carbon::createFromFormat('d/m/Y', $this->calculos['proximo_pago']),
        //     'importe_dias'     => $this->calculos['importe_dias'],
        //     'importe_promo'    => $this->calculos['importe_promo'],
        //     'total'            => $totalFinal,
        //     'descuento'        => $this->montoDescuento,
        //     'forma_pago'       => $this->formaPago,
        //   ]);
        //   Cliente::where('id', $this->cliente['id'])->update([
        //     'estado'              => 'PROMOCION_ACTIVA',
        //     'promo_id'            => $promo->id,
        //     'promo_fecha_inicio'  => $promo->fecha_aplicacion,
        //     'promo_fecha_termino' => $promo->fecha_termino,
        //     'promo_proximo_pago'  => $promo->proximo_pago,
        //   ]);
        //   IngresoEgreso::create([concepto => "PROMO {$this->promoSeleccionada['nombre']}", importe => $totalFinal, forma_pago => $this->formaPago, cliente_id => $this->cliente['id']]);
        //   CorteCaja::registrar($totalFinal, $this->formaPago);
        //   SmsService::enviar($this->cliente['telefono'],
        //     "Tu Visión Telecable: Promo {$this->promoSeleccionada['nombre']} activada. " .
        //     "Vigencia: {$this->calculos['fecha_inicio']} al {$this->calculos['fecha_termino']}. " .
        //     "Próximo pago: {$this->calculos['proximo_pago']}.");
        // });

        $this->resultado = [
            'folio'          => $this->folioContratacion,
            'cliente'        => $this->cliente,
            'promo'          => $this->promoSeleccionada,
            'calculos'       => $this->calculos,
            'total_cobrado'  => $this->totalEfectivo,
            'descuento'      => $this->montoDescuento,
            'forma_pago'     => $this->formaPago,
            'fecha_registro' => now()->format('d/m/Y H:i'),
        ];

        $this->toastExito("Promoción {$this->promoSeleccionada['nombre']} activada para {$this->cliente['nombre']}.");
        $this->paso = 4;
    }

    // ─── Reset completo ───────────────────────────────────────────────────────

    public function nuevaContratacion(): void
    {
        $this->reset(['search', 'resultados', 'busquedaHecha', 'cliente', 'promoSeleccionada', 'resultado', 'formaPago', 'mostrarDescuento']);
        $this->quitarDescuento();
        $this->resetCalculos();
        $this->folioContratacion = 'PROMO-' . now()->format('Y') . '-' . strtoupper(bin2hex(random_bytes(3)));
        $this->paso = 1;
    }

    private function resetCalculos(): void
    {
        $this->calculos = [
            'dias_uso'      => 0,
            'costo_dia'     => 0.0,
            'importe_dias'  => 0.0,
            'importe_promo' => 0.0,
            'total'         => 0.0,
            'fecha_inicio'  => '',
            'fecha_termino' => '',
            'proximo_pago'  => '',
        ];
    }

    // ─── Catálogo de promociones (filtrado por tipo de servicio) ─────────────

    public function getPromociones(): array
    {
        // TODO: Cargar desde BD:
        // return Promocion::where('activa', true)
        //     ->whereJsonContains('tipos_servicio', $this->cliente['tipo_servicio'] ?? '')
        //     ->orderBy('meses_pago', 'asc')->get()->toArray()

        $todas = [
            ['id' => 1, 'nombre' => 'BIMESTRE 2×3',   'codigo' => '2x3',   'meses_pago' => 2,  'meses_beneficio' => 3,  'tipos' => ['TV', 'INTERNET', 'TV+INTERNET']],
            ['id' => 2, 'nombre' => 'TRIMESTRE 3×4',   'codigo' => '3x4',   'meses_pago' => 3,  'meses_beneficio' => 4,  'tipos' => ['TV', 'INTERNET', 'TV+INTERNET']],
            ['id' => 3, 'nombre' => 'SEMESTRAL 6×7',   'codigo' => '6x7',   'meses_pago' => 6,  'meses_beneficio' => 7,  'tipos' => ['TV', 'INTERNET', 'TV+INTERNET']],
            ['id' => 4, 'nombre' => 'ANUALIDAD 12×14', 'codigo' => '12x14', 'meses_pago' => 12, 'meses_beneficio' => 14, 'tipos' => ['TV', 'INTERNET', 'TV+INTERNET']],
        ];

        $tipo = $this->cliente['tipo_servicio'] ?? '';

        return array_values(array_filter(
            $todas,
            fn ($p) => empty($tipo) || in_array($tipo, $p['tipos'])
        ));
    }

    // ─────────────────────────────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.gestion-clientes.contratacion-promocion', [
            'promociones' => $this->getPromociones(),
        ]);
    }
}

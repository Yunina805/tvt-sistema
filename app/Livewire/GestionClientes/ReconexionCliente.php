<?php

namespace App\Livewire\GestionClientes;

use App\Traits\WithToasts;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ReconexionCliente extends Component
{
    use WithToasts;

    // ── Tipo y flujo ──────────────────────────────────────────────────────────
    public string $tipoReconexion = '';  // 'mismo' | 'otro'
    public int    $paso           = 1;  // 1:Tipo 2:Buscar 3:Adeudo 4:DiasUso 5:Cierre 6:Recibo

    // ── Búsqueda ──────────────────────────────────────────────────────────────
    public string $busqueda      = '';
    public array  $resultados    = [];
    public bool   $busquedaHecha = false;
    public ?array $suscriptor    = null;

    // ── Cobro adeudo ──────────────────────────────────────────────────────────
    public string $formaPagoAdeudo     = '';
    public bool   $mostrarDescuento    = false;
    public float  $montoDescuentoInput = 0;
    public string $passwordDescuento   = '';
    public bool   $descuentoAplicado   = false;
    public float  $montoDescuento      = 0;
    public float  $totalAdeudo         = 0;

    // ── Cobro días de uso ─────────────────────────────────────────────────────
    public int   $diasUso        = 0;
    public float $costoDia       = 0;
    public float $costoProrrateo = 0;
    public string $formaPagoDias = '';

    // ── Reconexión con otro servicio ──────────────────────────────────────────
    public ?string $servicioSeleccionado = null;
    public array   $serviciosCatalogo    = [];
    public float   $cargoInstalacion     = 0;
    public float   $costoNuevaMensual    = 0;

    // ── Cierre ────────────────────────────────────────────────────────────────
    public string $tecnicoAsignado = '';
    public bool   $comodatoFirmado = false;
    public string $notasTecnico    = '';

    // ── Recibo ────────────────────────────────────────────────────────────────
    public array $resultado = [];

    // ─────────────────────────────────────────────────────────────────────────

    public function mount(): void
    {
        $this->serviciosCatalogo = $this->getCatalogo();
        $this->recalcularDias();
    }

    // ── Selección de tipo ─────────────────────────────────────────────────────

    public function seleccionarTipo(string $tipo): void
    {
        $this->tipoReconexion = $tipo;
        $this->paso = 2;
    }

    // ── Búsqueda ──────────────────────────────────────────────────────────────

    public function buscarCliente(): void
    {
        $this->busquedaHecha = true;
        $this->resultados    = [];

        if (mb_strlen(trim($this->busqueda)) < 3) {
            $this->toastError('Ingresa al menos 3 caracteres para buscar.');
            return;
        }

        // TODO: Cliente::with(['suscripcionActiva.tarifa', 'sucursal', 'nap', 'equipo'])
        //     ->whereIn('estado', ['SUSPENDIDO', 'CANCELADO'])
        //     ->where(fn($q) => $q
        //         ->where('nombre',       'LIKE', "%{$this->busqueda}%")
        //         ->orWhere('id_cliente', 'LIKE', "%{$this->busqueda}%")
        //         ->orWhere('telefono',   'LIKE', "%{$this->busqueda}%")
        //         ->orWhere('direccion',  'LIKE', "%{$this->busqueda}%")
        //     )->limit(6)->get()->toArray();

        $mock = [
            [
                'id'               => '01-0001234',
                'nombre'           => 'JUAN PÉREZ GARCÍA',
                'telefono'         => '9511234567',
                'direccion'        => 'AV. INDEPENDENCIA 102, COL. CENTRO',
                'estado'           => 'SUSPENDIDO',
                'sucursal'         => 'Oaxaca Centro',
                'ultimo_servicio'  => 'TV + INTERNET 30 MBPS',
                'tarifa'           => 480.00,
                'fecha_suspension' => '20/01/2026',
                'nap'              => 'NAP-05',
                'dir_nap'          => 'Poste 12, Calle Reforma',
                'equipo'           => ['tipo' => 'ONU', 'marca' => 'TP-Link', 'serie' => 'TPL-20241101-A'],
                'saldo_pendiente'  => 960.00,
                'meses_adeudo'     => 2,
                'pagos'            => [
                    ['folio' => 'PAG-2025-C3D4E5', 'fecha' => '15/12/2025', 'monto' => 480.00, 'concepto' => 'MENSUALIDAD DICIEMBRE 2025'],
                    ['folio' => 'PAG-2025-A1B2C3', 'fecha' => '10/11/2025', 'monto' => 480.00, 'concepto' => 'MENSUALIDAD NOVIEMBRE 2025'],
                    ['folio' => 'PAG-2025-F7G8H9', 'fecha' => '08/10/2025', 'monto' => 480.00, 'concepto' => 'MENSUALIDAD OCTUBRE 2025'],
                    ['folio' => 'PAG-2025-I4J5K6', 'fecha' => '12/09/2025', 'monto' => 480.00, 'concepto' => 'MENSUALIDAD SEPTIEMBRE 2025'],
                ],
            ],
            [
                'id'               => '02-0005678',
                'nombre'           => 'CARLOS RUIZ MENDOZA',
                'telefono'         => '9514561234',
                'direccion'        => 'PERIFÉRICO NORTE 301, COL. VOLCANES',
                'estado'           => 'SUSPENDIDO',
                'sucursal'         => 'Oaxaca Norte',
                'ultimo_servicio'  => 'RETRO TV',
                'tarifa'           => 250.00,
                'fecha_suspension' => '05/02/2026',
                'nap'              => 'NAP-12',
                'dir_nap'          => 'Poste 8, Calle Volcanes',
                'equipo'           => ['tipo' => 'Mininodo', 'marca' => 'Arris', 'serie' => 'ARR-20231205-B'],
                'saldo_pendiente'  => 250.00,
                'meses_adeudo'     => 1,
                'pagos'            => [
                    ['folio' => 'PAG-2026-L1M2N3', 'fecha' => '14/01/2026', 'monto' => 250.00, 'concepto' => 'MENSUALIDAD ENERO 2026'],
                    ['folio' => 'PAG-2025-O4P5Q6', 'fecha' => '10/12/2025', 'monto' => 250.00, 'concepto' => 'MENSUALIDAD DICIEMBRE 2025'],
                    ['folio' => 'PAG-2025-R7S8T9', 'fecha' => '09/11/2025', 'monto' => 250.00, 'concepto' => 'MENSUALIDAD NOVIEMBRE 2025'],
                    ['folio' => 'PAG-2025-U1V2W3', 'fecha' => '07/10/2025', 'monto' => 250.00, 'concepto' => 'MENSUALIDAD OCTUBRE 2025'],
                ],
            ],
            [
                'id'               => '03-0009012',
                'nombre'           => 'SOFÍA MARTÍNEZ VERA',
                'telefono'         => '9517891234',
                'direccion'        => 'CALLE TINOCO Y PALACIOS 77, COL. REFORMA',
                'estado'           => 'CANCELADO',
                'sucursal'         => 'Oaxaca Centro',
                'ultimo_servicio'  => 'INTERNET 50 MBPS',
                'tarifa'           => 350.00,
                'fecha_suspension' => '15/11/2025',
                'nap'              => 'NAP-03',
                'dir_nap'          => 'Poste 22, Col. Reforma',
                'equipo'           => ['tipo' => 'ONU', 'marca' => 'Huawei', 'serie' => 'HUW-20230815-C'],
                'saldo_pendiente'  => 700.00,
                'meses_adeudo'     => 2,
                'pagos'            => [
                    ['folio' => 'PAG-2025-X9Y8Z7', 'fecha' => '10/10/2025', 'monto' => 350.00, 'concepto' => 'MENSUALIDAD OCTUBRE 2025'],
                    ['folio' => 'PAG-2025-W6V5U4', 'fecha' => '09/09/2025', 'monto' => 350.00, 'concepto' => 'MENSUALIDAD SEPTIEMBRE 2025'],
                    ['folio' => 'PAG-2025-T3S2R1', 'fecha' => '08/08/2025', 'monto' => 350.00, 'concepto' => 'MENSUALIDAD AGOSTO 2025'],
                    ['folio' => 'PAG-2025-Q0P9O8', 'fecha' => '11/07/2025', 'monto' => 350.00, 'concepto' => 'MENSUALIDAD JULIO 2025'],
                ],
            ],
        ];

        $term = mb_strtolower(trim($this->busqueda));
        $this->resultados = array_values(array_filter($mock, fn($c) =>
            str_contains(mb_strtolower($c['nombre']),    $term) ||
            str_contains($c['telefono'],                  $term) ||
            str_contains(mb_strtolower($c['id']),         $term) ||
            str_contains(mb_strtolower($c['direccion']),  $term)
        ));

        if (empty($this->resultados)) {
            $this->toastWarning('No se encontraron cuentas suspendidas con "' . trim($this->busqueda) . '".');
        }
    }

    public function seleccionarSuscriptor(array $s): void
    {
        $this->suscriptor    = $s;
        $this->resultados    = [];
        $this->busqueda      = '';
        $this->busquedaHecha = false;
        $this->totalAdeudo   = $s['saldo_pendiente'];
        $this->recalcularDias();
        $this->paso = 3;
        $this->toastInfo('Expediente cargado para ' . $s['nombre'] . '.');
    }

    // ── Descuento en adeudo ───────────────────────────────────────────────────

    public function aplicarDescuento(): void
    {
        if ($this->passwordDescuento !== config('tvt.password_supervisor', 'supervisor123')) {
            $this->addError('passwordDescuento', 'Contraseña gerencial incorrecta.');
            return;
        }
        if ($this->montoDescuentoInput <= 0) {
            $this->addError('montoDescuentoInput', 'El monto debe ser mayor a $0.');
            return;
        }
        if ($this->montoDescuentoInput >= ($this->suscriptor['saldo_pendiente'] ?? 0)) {
            $this->addError('montoDescuentoInput', 'El descuento no puede ser igual o mayor al saldo total.');
            return;
        }

        $this->descuentoAplicado   = true;
        $this->montoDescuento      = (float) $this->montoDescuentoInput;
        $this->totalAdeudo         = round(($this->suscriptor['saldo_pendiente'] ?? 0) - $this->montoDescuento, 2);
        $this->mostrarDescuento    = false;
        $this->passwordDescuento   = '';
        $this->resetErrorBag();
        $this->toastExito('Descuento de $' . number_format($this->montoDescuento, 2) . ' aplicado.');
    }

    public function quitarDescuento(): void
    {
        $this->descuentoAplicado   = false;
        $this->montoDescuento      = 0;
        $this->montoDescuentoInput = 0;
        $this->passwordDescuento   = '';
        $this->totalAdeudo         = $this->suscriptor['saldo_pendiente'] ?? 0;
        $this->mostrarDescuento    = false;
    }

    // ── Procesar pago de adeudo ───────────────────────────────────────────────

    public function procesarPagoAdeudo(): void
    {
        if (empty($this->formaPagoAdeudo)) {
            $this->toastError('Selecciona una forma de pago para el adeudo.');
            return;
        }

        // TODO: DB::transaction(function() {
        //     Ingreso::create([
        //         'cliente_id'   => $this->suscriptor['id'],
        //         'concepto'     => 'LIQUIDACION_ADEUDO',
        //         'monto'        => $this->totalAdeudo,
        //         'descuento'    => $this->montoDescuento,
        //         'forma_pago'   => $this->formaPagoAdeudo,
        //         'fecha'        => now(),
        //         'registrado_por' => auth()->id(),
        //     ]);
        //     Cliente::find($this->suscriptor['id'])->update(['saldo' => 0]);
        // });

        $this->paso = 4;
        $this->toastInfo('Adeudo liquidado. Continúa con el cobro de días de uso.');
    }

    // ── Días de uso ───────────────────────────────────────────────────────────

    private function recalcularDias(): void
    {
        $diaHoy        = (int) now()->format('d');
        $diasEnMes     = (int) now()->format('t');
        $this->diasUso = $diasEnMes - $diaHoy + 1;  // días restantes incluyendo hoy

        $tarifa = 0;
        if ($this->tipoReconexion === 'mismo' && $this->suscriptor) {
            $tarifa = $this->suscriptor['tarifa'];
        } elseif ($this->tipoReconexion === 'otro' && $this->costoNuevaMensual > 0) {
            $tarifa = $this->costoNuevaMensual;
        }

        $this->costoDia      = $tarifa > 0 ? round($tarifa / 30, 4) : 0;
        $this->costoProrrateo = round($this->diasUso * $this->costoDia, 2);
    }

    public function seleccionarServicio(string $key): void
    {
        $this->servicioSeleccionado = $key;
        $servicio = collect($this->serviciosCatalogo)->firstWhere('key', $key);
        if ($servicio) {
            $this->costoNuevaMensual = $servicio['mensualidad'];
            $this->cargoInstalacion  = $servicio['instalacion'];
            $this->recalcularDias();
        }
    }

    public function procesarCobroProporcional(): void
    {
        if ($this->tipoReconexion === 'otro' && ! $this->servicioSeleccionado) {
            $this->toastError('Selecciona el nuevo servicio antes de continuar.');
            return;
        }
        if (empty($this->formaPagoDias)) {
            $this->toastError('Selecciona una forma de pago para el cargo proporcional.');
            return;
        }

        // TODO: DB::transaction(function() {
        //     Ingreso::create([
        //         'cliente_id'   => $this->suscriptor['id'],
        //         'concepto'     => $this->tipoReconexion === 'otro' ? 'INSTALACION_CAMBIO' : 'DIAS_USO_RECONEXION',
        //         'monto'        => $this->costoProrrateo + $this->cargoInstalacion,
        //         'forma_pago'   => $this->formaPagoDias,
        //         'fecha'        => now(),
        //         'registrado_por' => auth()->id(),
        //     ]);
        // });

        $this->paso = 5;
        $this->toastInfo('Cobro proporcional registrado. Completa el cierre.');
    }

    // ── Cierre ────────────────────────────────────────────────────────────────

    public function finalizarReconexion(): void
    {
        if (empty($this->tecnicoAsignado)) {
            $this->toastError('Debes asignar un técnico.');
            return;
        }
        if (! $this->comodatoFirmado) {
            $this->addError('comodatoFirmado', 'El comodato debe ser aceptado por el titular para continuar.');
            return;
        }

        $folio = 'REC-' . now()->format('Y') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));

        $servNombre = $this->tipoReconexion === 'mismo'
            ? ($this->suscriptor['ultimo_servicio'] ?? '')
            : (collect($this->serviciosCatalogo)->firstWhere('key', $this->servicioSeleccionado)['nombre'] ?? '');

        // TODO: DB::transaction(function() use ($folio, $servNombre) {
        //     $tipo = $this->tipoReconexion === 'mismo' ? 'RECONEXION' : 'CAMBIO_SERVICIO';
        //     ReporteServicio::create([
        //         'folio'          => $folio,
        //         'tipo_reporte'   => $tipo,
        //         'cliente_id'     => $this->suscriptor['id'],
        //         'tecnico_id'     => $this->tecnicoAsignado,
        //         'estado'         => 'PENDIENTE',
        //         'notas'          => $this->notasTecnico,
        //         'fecha_creacion' => now(),
        //     ]);
        //     Cliente::find($this->suscriptor['id'])->update([
        //         'estado'             => 'ACTIVO',
        //         'tarifa_id'          => $this->tipoReconexion === 'otro' ? $tarifaId : null,
        //         'fecha_reconexion'   => now(),
        //     ]);
        // });

        $this->resultado = [
            'folio'              => $folio,
            'fecha'              => now()->format('d/m/Y H:i'),
            'nombre'             => $this->suscriptor['nombre'],
            'id_suscriptor'      => $this->suscriptor['id'],
            'sucursal'           => $this->suscriptor['sucursal'],
            'servicio'           => $servNombre,
            'tipo'               => $this->tipoReconexion,
            'tecnico'            => $this->tecnicoAsignado,
            'saldo_liquidado'    => $this->totalAdeudo,
            'descuento'          => $this->montoDescuento,
            'dias_uso'           => $this->diasUso,
            'costo_prorrateo'    => $this->costoProrrateo,
            'cargo_instalacion'  => $this->cargoInstalacion,
            'forma_pago_adeudo'  => $this->formaPagoAdeudo,
            'forma_pago_dias'    => $this->formaPagoDias,
            'notas'              => $this->notasTecnico,
        ];

        $this->paso = 6;
        $this->toastExito('Reconexión generada con folio ' . $folio . '. Reporte enviado al técnico.');
    }

    // ── Catálogo de servicios ─────────────────────────────────────────────────

    private function getCatalogo(): array
    {
        // TODO: Tarifa::where('estado', 'VIGENTE_CONTRATAR')->orderBy('mensualidad')->get()
        //     ->map(fn($t) => ['key' => $t->id, 'nombre' => $t->nombre, ...])->toArray();
        return [
            ['key' => 'retro_tv',         'nombre' => 'RETRO TV',              'mensualidad' => 250.00, 'instalacion' => 350.00, 'tipo' => 'TV'],
            ['key' => 'retro_tv_premium',  'nombre' => 'RETRO TV PREMIUM',      'mensualidad' => 350.00, 'instalacion' => 350.00, 'tipo' => 'TV'],
            ['key' => 'internet_10',       'nombre' => 'INTERNET 10 MBPS',      'mensualidad' => 250.00, 'instalacion' => 400.00, 'tipo' => 'INTERNET'],
            ['key' => 'internet_30',       'nombre' => 'INTERNET 30 MBPS',      'mensualidad' => 350.00, 'instalacion' => 400.00, 'tipo' => 'INTERNET'],
            ['key' => 'combo_basico',      'nombre' => 'TV + INTERNET 10 MBPS', 'mensualidad' => 420.00, 'instalacion' => 450.00, 'tipo' => 'TV+INTERNET'],
            ['key' => 'combo_standard',    'nombre' => 'TV + INTERNET 30 MBPS', 'mensualidad' => 480.00, 'instalacion' => 450.00, 'tipo' => 'TV+INTERNET'],
            ['key' => 'combo_plus',        'nombre' => 'TV + INTERNET 50 MBPS', 'mensualidad' => 580.00, 'instalacion' => 450.00, 'tipo' => 'TV+INTERNET'],
        ];
    }

    // ── Navegación ────────────────────────────────────────────────────────────

    public function reiniciar(): void
    {
        $this->reset([
            'tipoReconexion', 'paso', 'busqueda', 'resultados', 'busquedaHecha', 'suscriptor',
            'formaPagoAdeudo', 'mostrarDescuento', 'montoDescuentoInput', 'passwordDescuento',
            'descuentoAplicado', 'montoDescuento', 'totalAdeudo',
            'formaPagoDias', 'servicioSeleccionado', 'cargoInstalacion', 'costoNuevaMensual',
            'tecnicoAsignado', 'comodatoFirmado', 'notasTecnico', 'resultado',
        ]);
        $this->paso = 1;
        $this->recalcularDias();
        $this->serviciosCatalogo = $this->getCatalogo();
    }

    // ── Render ────────────────────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.gestion-clientes.reconexion-cliente');
    }
}

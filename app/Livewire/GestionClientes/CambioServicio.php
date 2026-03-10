<?php

namespace App\Livewire\GestionClientes;

use App\Models\Financiero\TarifaPrincipal;
use App\Models\Infraestructura\Calle;
use App\Models\Infraestructura\Sucursal;
use App\Models\RRHH\Empleado;
use App\Traits\WithToasts;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class CambioServicio extends Component
{
    use WithFileUploads, WithToasts;

    public int $paso = 1;

    // ─── PASO 1: Identificación + Selección de Nueva Tarifa ──────────────────
    public $fotoIdentificacion = null;
    public ?int   $tarifaId          = null;
    public array  $tarifaSeleccionada = [];
    public string $tipoServicioNuevo  = '';   // TV | INTERNET | TV+INTERNET

    // Cálculo del primer pago
    public int   $diasRestantes   = 0;
    public float $tarifaNuevaDiaria = 0.0;
    public float $tarifaViejaDiaria = 0.0;
    public float $saldoFavorCliente = 0.0;    // crédito del mes pagado en tarifa vieja
    public float $costoDiasNueva    = 0.0;    // costo proporcional en tarifa nueva
    public float $diferenciaDias    = 0.0;    // positivo = debe pagar; negativo = saldo a favor
    public float $instalacion       = 0.0;
    public float $subtotal          = 0.0;
    public float $iva               = 0.0;
    public float $totalPagar        = 0.0;

    // Descuento con contraseña de supervisor
    public bool   $mostrarDescuento    = false;
    public float  $montoDescuentoInput = 0.0;
    public string $passwordDescuento   = '';
    public bool   $descuentoAplicado   = false;
    public float  $montoDescuento      = 0.0;
    public float  $subtotalDescuento   = 0.0;
    public float  $ivaDescuento        = 0.0;
    public float  $totalConDescuento   = 0.0;

    // ─── PASO 2: Búsqueda del Suscriptor Activo ──────────────────────────────
    public string $busqueda          = '';
    public array  $resultados        = [];
    public bool   $busquedaRealizada = false;
    public array  $suscriptor        = [];   // suscriptor existente confirmado

    // ─── PASO 3: Cobro ────────────────────────────────────────────────────────
    public string $metodoPago = 'efectivo';

    // ─── PASO 4: Recibo ───────────────────────────────────────────────────────
    public string $folioRecibo = '';

    // ─── PASO 5: Firma ────────────────────────────────────────────────────────
    public string $firmaBase64 = '';

    // ─── PASO 6: Equipo + Técnico ─────────────────────────────────────────────
    public string $equipoNuevoId      = '';    // seleccionado del catálogo
    public bool   $recuperarEquipo    = true;  // ¿recuperar equipo anterior?
    public string $notaRecuperacion   = '';    // nota si no puede recuperarse (cobrar valor)
    public bool   $notificarSms       = true;
    public ?int   $tecnicoId          = null;
    public string $folioReporte       = '';

    // ─── Catálogos ────────────────────────────────────────────────────────────
    public array $tarifas   = [];
    public array $tecnicos  = [];
    public array $equipos   = [];   // catálogo de equipos disponibles para asignar

    // ─────────────────────────────────────────────────────────────────────────

    public function mount(): void
    {
        $this->tarifas = TarifaPrincipal::where('estado', 'VIGENTE_CONTRATAR')
            ->get(['id', 'nombre_comercial', 'descripcion', 'precio_instalacion', 'precio_mensualidad'])
            ->toArray();

        $this->tecnicos = Empleado::where('activo', true)
            ->whereIn('area', ['TECNICO_CAMPO', 'TECNICO_INSTALACIONES'])
            ->get(['id', 'clave_empleado', 'nombre', 'apellido_paterno', 'apellido_materno', 'area', 'puesto'])
            ->map(fn ($e) => [
                'id'     => $e->id,
                'clave'  => $e->clave_empleado,
                'nombre' => $e->nombre_completo,
                'area'   => $e->area,
                'puesto' => $e->puesto,
            ])
            ->toArray();

        // TODO: Cargar desde catálogo de equipos (ONUs, mininodos, etc.)
        $this->equipos = [
            ['id' => 'ONU-001', 'descripcion' => 'ONU Huawei HG8310M — SN: HW1234567', 'tipo' => 'ONU'],
            ['id' => 'ONU-002', 'descripcion' => 'ONU ZTE F601 — SN: ZT9876543', 'tipo' => 'ONU'],
            ['id' => 'MN-001',  'descripcion' => 'Mininodo 4 salidas — Serie: MN2024001', 'tipo' => 'MININODO'],
            ['id' => 'MN-002',  'descripcion' => 'Mininodo 8 salidas — Serie: MN2024002', 'tipo' => 'MININODO'],
        ];

        $diaActual          = (int) now()->format('d');
        $this->diasRestantes = max(1, 30 - $diaActual + 1);
    }

    // ─── PASO 1: Selección de tarifa ─────────────────────────────────────────

    public function seleccionarTarifa(int $id): void
    {
        $tarifa = collect($this->tarifas)->firstWhere('id', $id);
        if (! $tarifa) return;

        $this->tarifaId          = $id;
        $this->tarifaSeleccionada = $tarifa;
        $this->tipoServicioNuevo  = $this->inferirTipoServicio($tarifa['nombre_comercial']);

        $this->quitarDescuento();
        $this->recalcular();
    }

    public function inferirTipoServicio(string $nombre): string
    {
        $n             = mb_strtoupper($nombre);
        $tieneTV       = str_contains($n, 'TV') || str_contains($n, 'TELEVISION') || str_contains($n, 'CABLE') || str_contains($n, 'DIGITAL');
        $tieneInternet = str_contains($n, 'INTERNET') || str_contains($n, 'FIBRA') || str_contains($n, 'DATOS') || str_contains($n, 'MEGA');

        if ($tieneTV && $tieneInternet) return 'TV+INTERNET';
        if ($tieneInternet) return 'INTERNET';
        return 'TV';
    }

    private function recalcular(): void
    {
        if (empty($this->tarifaSeleccionada)) return;

        $mensualidadNueva       = (float) $this->tarifaSeleccionada['precio_mensualidad'];
        $this->instalacion      = (float) $this->tarifaSeleccionada['precio_instalacion'];
        $this->tarifaNuevaDiaria = round($mensualidadNueva / 30, 4);
        $this->costoDiasNueva   = round($this->tarifaNuevaDiaria * $this->diasRestantes, 2);

        // Si el suscriptor está identificado, calcular saldo a favor/en contra
        if (! empty($this->suscriptor)) {
            $mensualidadVieja       = (float) ($this->suscriptor['tarifa_actual_precio'] ?? 0);
            $this->tarifaViejaDiaria = round($mensualidadVieja / 30, 4);

            if ($this->suscriptor['pagado_mes'] ?? false) {
                // Lo que ya pagó (crédito por días restantes en tarifa vieja)
                $this->saldoFavorCliente = round($this->tarifaViejaDiaria * $this->diasRestantes, 2);
                // Diferencia: positivo = debe pagar más; negativo = tiene crédito
                $this->diferenciaDias    = round($this->costoDiasNueva - $this->saldoFavorCliente, 2);
            } else {
                // No pagó mes actual → paga proporcional normal en tarifa nueva
                $this->saldoFavorCliente = 0.0;
                $this->diferenciaDias    = $this->costoDiasNueva;
            }
        } else {
            $this->saldoFavorCliente = 0.0;
            $this->diferenciaDias    = $this->costoDiasNueva;
        }

        $costoProporcional  = max(0, $this->diferenciaDias);  // nunca cobrar negativo
        $this->subtotal     = round($this->instalacion + $costoProporcional, 2);
        $this->iva          = round($this->subtotal * 0.16, 2);
        $this->totalPagar   = round($this->subtotal + $this->iva, 2);

        if ($this->descuentoAplicado) {
            $this->calcularDescuento();
        }
    }

    public function irPaso2(): void
    {
        if (! $this->tarifaId) {
            $this->toastError('Selecciona un servicio para continuar.');
            return;
        }
        $this->paso = 2;
    }

    // ─── PASO 2: Búsqueda de suscriptor ──────────────────────────────────────

    public function buscar(): void
    {
        $this->busquedaRealizada = true;
        $this->resultados        = [];

        if (mb_strlen(trim($this->busqueda)) < 2) {
            $this->toastError('Ingresa al menos 2 caracteres para buscar.');
            return;
        }

        // TODO: Cliente::where(fn ($q) => $q
        //           ->where('nombre', 'like', "%{$this->busqueda}%")
        //           ->orWhere('telefono', 'like', "%{$this->busqueda}%")
        //           ->orWhere('id', $this->busqueda))
        //       ->whereHas('suscripcionActiva', fn($q) => $q->where('estado', 'ACTIVO'))
        //       ->with(['suscripcionActiva.tarifa', 'sucursal', 'calle', 'equipoActivo'])
        //       ->limit(5)->get()
        //       Mapear a: id, clave, nombre, telefono, sucursal, domicilio, tipo_servicio,
        //                 estado, tarifa_actual, tarifa_actual_precio, pagado_mes, equipo_activo

        $this->resultados = [
            [
                'id'                  => 5432,
                'clave'               => '01-005432',
                'nombre'              => 'JUAN PÉREZ GARCÍA',
                'telefono'            => '9511234567',
                'sucursal'            => 'Oaxaca Centro',
                'domicilio'           => 'AV. INDEPENDENCIA 102 #45, COL. CENTRO',
                'tipo_servicio'       => 'TV',
                'estado'              => 'ACTIVO',
                'tarifa_actual'       => 'Retro TV',
                'tarifa_actual_precio'=> 299.00,
                'pagado_mes'          => true,
                'equipo_activo'       => 'Mininodo 4 salidas — Serie: MN2019045',
            ],
            [
                'id'                  => 3817,
                'clave'               => '01-003817',
                'nombre'              => 'MARÍA LÓPEZ HERNÁNDEZ',
                'telefono'            => '9519876543',
                'sucursal'            => 'Oaxaca Centro',
                'domicilio'           => 'CALLE HIDALGO 55, COL. REFORMA',
                'tipo_servicio'       => 'INTERNET',
                'estado'              => 'ACTIVO',
                'tarifa_actual'       => '20 Megas Fibra',
                'tarifa_actual_precio'=> 399.00,
                'pagado_mes'          => false,
                'equipo_activo'       => 'ONU ZTE F601 — SN: ZT2020998',
            ],
        ];
    }

    public function seleccionarSuscriptor(int $id): void
    {
        $s = collect($this->resultados)->firstWhere('id', $id);
        if (! $s) return;

        $this->suscriptor = $s;
        $this->resultados = [];

        // Recalcular con datos del suscriptor (saldo favor/contra)
        $this->recalcular();
    }

    public function limpiarSuscriptor(): void
    {
        $this->suscriptor        = [];
        $this->busqueda          = '';
        $this->busquedaRealizada = false;
        $this->resultados        = [];
        $this->saldoFavorCliente = 0.0;
        $this->diferenciaDias    = $this->costoDiasNueva;
    }

    public function irPaso3(): void
    {
        if (empty($this->suscriptor)) {
            $this->toastError('Identifica al suscriptor para continuar.');
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
        if ($this->montoDescuentoInput >= $this->subtotal) {
            $this->toastError('El descuento no puede ser mayor o igual al subtotal ($' . number_format($this->subtotal, 2) . ').');
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
        $this->subtotalDescuento = round($this->subtotal - $this->montoDescuento, 2);
        $this->ivaDescuento      = round($this->subtotalDescuento * 0.16, 2);
        $this->totalConDescuento = round($this->subtotalDescuento + $this->ivaDescuento, 2);
    }

    public function quitarDescuento(): void
    {
        $this->descuentoAplicado   = false;
        $this->montoDescuentoInput = 0.0;
        $this->montoDescuento      = 0.0;
        $this->subtotalDescuento   = 0.0;
        $this->ivaDescuento        = 0.0;
        $this->totalConDescuento   = 0.0;
        $this->passwordDescuento   = '';
    }

    public function getTotalEfectivoProperty(): float
    {
        return $this->descuentoAplicado ? $this->totalConDescuento : $this->totalPagar;
    }

    public function seleccionarMetodoPago(string $metodo): void
    {
        $this->metodoPago = $metodo;
    }

    public function cambiarServicio(int $id): void
    {
        $tarifa = collect($this->tarifas)->firstWhere('id', $id);
        if (! $tarifa) return;

        $this->tarifaId          = $id;
        $this->tarifaSeleccionada = $tarifa;
        $this->tipoServicioNuevo  = $this->inferirTipoServicio($tarifa['nombre_comercial']);
        $this->quitarDescuento();
        $this->recalcular();
        $this->toastInfo('Servicio actualizado. Revisa el resumen de pago.');
    }

    // ─── PASO 3 → 4: Confirmar ingreso ────────────────────────────────────────

    public function confirmarIngreso(): void
    {
        // TODO: DB::transaction() {
        //   $totalFinal = $this->totalEfectivo;
        //   // Actualizar tarifa del suscriptor:
        //   // SuscriptorServicio::where('cliente_id', $this->suscriptor['id'])->update(['tarifa_id' => $this->tarifaId, 'tipo_servicio' => $this->tipoServicioNuevo, 'estado' => 'PENDIENTE_INSTALACION'])
        //   // Registrar ingreso en caja:
        //   // IngresoEgreso::create([sucursal_id, tipo => 'INGRESO', concepto => 'Cambio de Servicio: '.$tarifaSeleccionada['nombre_comercial'], monto => $totalFinal, metodo_pago => $metodoPago, user_id => auth()->id()])
        //   // Si hay saldo a favor del cliente: registrar crédito o aplicar a siguiente mensualidad
        //   // Si $descuentoAplicado: DescuentoAplicado::create([...])
        // }

        $this->folioRecibo = 'REC-' . now()->format('Y') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $this->paso        = 4;
    }

    // ─── PASO 5: Firma digital ────────────────────────────────────────────────

    public function guardarFirma(string $base64): void
    {
        if (empty($base64) || $base64 === 'data:,') {
            $this->toastError('Dibuja tu firma para continuar.');
            return;
        }

        $this->firmaBase64 = $base64;

        // TODO: Generar PDF del contrato de cambio de servicio con DomPDF/Browsershot,
        //       adjuntar firma y guardar en storage/expedientes/{clave_suscriptor}/contrato_cambio_{fecha}.pdf
        //       Registrar en tabla expedientes: {cliente_id, tipo => 'CONTRATO_CAMBIO', path, firmado_en, user_id}

        $this->toastExito('Contrato firmado. Continúa con la asignación de equipo.');
        $this->paso = 6;
    }

    // ─── PASO 6: Equipo + Técnico → Reporte ───────────────────────────────────

    public function generarReporte(): void
    {
        if (empty($this->equipoNuevoId)) {
            $this->toastError('Asigna un equipo al suscriptor para generar el reporte.');
            return;
        }
        if (! $this->tecnicoId) {
            $this->toastError('Selecciona un técnico responsable para generar el reporte.');
            return;
        }

        $this->folioReporte = 'REP-' . now()->format('Y') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);

        // TODO: DB::transaction() {
        //   ReporteServicio::create([
        //     folio           => $this->folioReporte,
        //     tipo_reporte    => 'CAMBIO_SERVICIO',
        //     tipo_servicio   => $this->tipoServicioNuevo,
        //     suscriptor_id   => $this->suscriptor['id'],
        //     tecnico_id      => $this->tecnicoId,
        //     sucursal_id     => $this->suscriptor['sucursal_id'],
        //     estado          => 'PENDIENTE',
        //     responsable_ini => 'SUCURSAL',
        //     equipo_nuevo_id => $this->equipoNuevoId,
        //     recuperar_equipo => $this->recuperarEquipo,
        //     nota_recuperacion => $this->notaRecuperacion,
        //   ]);
        //   // Afectar inventario del equipo nuevo (marcar como asignado)
        //   // Equipo::find($equipoNuevoId)->update(['estado' => 'ASIGNADO', 'suscriptor_id' => $suscriptor['id']])
        //   // Si $recuperarEquipo: crear RecuperacionPendiente para el técnico
        //   // Si $notificarSms: SmsService::enviar($tecnico->telefono, "Nuevo reporte: {$folioReporte} — CAMBIO DE SERVICIO para {$suscriptor['nombre']}")
        // }

        $this->toastExito("Reporte {$this->folioReporte} generado. El técnico fue notificado.");
        $this->paso = 7;
    }

    public function finalizar(): void
    {
        $this->redirect(route('reportes.servicio'));
    }

    // ─────────────────────────────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.gestion-clientes.cambio-servicio');
    }
}

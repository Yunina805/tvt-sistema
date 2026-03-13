<?php

namespace App\Livewire\GestionClientes;

use App\Traits\WithToasts;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class PagoMensualidad extends Component
{
    use WithToasts;

    // ── Folio de la transacción ───────────────────────────────────────────────
    public string $folioRecibo = '';

    // ── Paso del flujo ────────────────────────────────────────────────────────
    public int $paso = 1;

    // ── Búsqueda y selección ──────────────────────────────────────────────────
    public string $busqueda            = '';
    public array  $resultados          = [];
    public ?array $clienteSeleccionado = null;

    // ── Detalle del cobro ─────────────────────────────────────────────────────
    public string $concepto     = '';
    public string $periodoLabel = '';     // Período que se está pagando (ej. "MARZO 2026")
    public float  $tarifaMonto  = 0.0;
    public float  $montoCobro   = 0.0;
    public string $formaPago    = 'efectivo';

    // ── Ajuste manual de monto ────────────────────────────────────────────────
    public bool   $modificarMonto       = false;
    public float  $montoManual          = 0.0;
    public string $motivoAjuste         = '';
    public string $passwordAutorizacion = '';

    // ── Facturación (Facturama) ───────────────────────────────────────────────
    public bool  $requiereFactura = false;
    public array $datosFactura    = [
        'nombre'   => '',
        'rfc'      => '',
        'cp'       => '',
        'uso_cfdi' => 'G03',
        'correo'   => '',
    ];

    // ── WhatsApp ──────────────────────────────────────────────────────────────
    public bool   $enviarWhatsapp   = true;
    public string $telefonoWhatsapp = '';
    public bool   $whatsappEnviado  = false;

    // ── Liquidación total (mensualidad + adeudo) ──────────────────────────────
    public float $montoMensualidadLiq = 0.0;   // parte mensualidad del desglose
    public float $montoAdeudoLiq      = 0.0;   // parte adeudo del desglose

    // ── Datos del recibo generado (paso 3) ────────────────────────────────────
    public float  $montoFinal      = 0.0;
    public float  $subtotalRecibo  = 0.0;
    public float  $ivaRecibo       = 0.0;
    public float  $totalRecibo     = 0.0;
    public bool   $montoAjustado   = false;
    public string $fechaPago       = '';

    // ─────────────────────────────────────────────────────────────────────────

    public function mount(): void
    {
        $this->folioRecibo = $this->generarFolio();
    }

    private function generarFolio(): string
    {
        return 'PAG-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(3)));
    }

    // ── Búsqueda ──────────────────────────────────────────────────────────────

    public function buscarCliente(): void
    {
        if (strlen(trim($this->busqueda)) < 3) {
            $this->resultados = [];
            return;
        }

        // TODO: Reemplazar con Eloquent real:
        // $this->resultados = Cliente::with(['suscripcionActiva.tarifa', 'sucursal'])
        //     ->where(fn($q) =>
        //         $q->where('nombre',      'LIKE', "%{$this->busqueda}%")
        //           ->orWhere('telefono',  'LIKE', "%{$this->busqueda}%")
        //           ->orWhere('id_cliente','LIKE', "%{$this->busqueda}%")
        //           ->orWhere('direccion', 'LIKE', "%{$this->busqueda}%")
        //     )
        //     ->where('estado', '!=', 'CANCELADO')
        //     ->limit(8)->get()->toArray();

        $mock = [
            [
                'id'           => '01-0001234',
                'nombre'       => 'JUAN PÉREZ GARCÍA',
                'telefono'     => '9511234567',
                'direccion'    => 'AV. INDEPENDENCIA 102, COL. CENTRO',
                'servicio'     => 'RETRO TV + INTERNET 30 MBPS',
                'tarifa'       => 480.00,
                'saldo'        => 960.00,
                'estado'       => 'ACTIVO',
                'sucursal'     => 'Oaxaca Centro',
                'meses_adeudo' => 2,
            ],
            [
                'id'           => '02-0005678',
                'nombre'       => 'MARÍA LÓPEZ CRUZ',
                'telefono'     => '9519876543',
                'direccion'    => 'CALLE MORELOS 45, COL. REFORMA',
                'servicio'     => 'RETRO TV',
                'tarifa'       => 250.00,
                'saldo'        => 250.00,
                'estado'       => 'ACTIVO',
                'sucursal'     => 'San Pedro Amuzgos',
                'meses_adeudo' => 1,
            ],
            [
                'id'           => '03-0009012',
                'nombre'       => 'CARLOS RUIZ MENDOZA',
                'telefono'     => '9514561234',
                'direccion'    => 'PERIFÉRICO NORTE 301, COL. VOLCANES',
                'servicio'     => 'INTERNET 50 MBPS',
                'tarifa'       => 350.00,
                'saldo'        => 700.00,
                'estado'       => 'SUSPENDIDO',
                'sucursal'     => 'Oaxaca Norte',
                'meses_adeudo' => 2,
            ],
            [
                'id'           => '01-0007890',
                'nombre'       => 'ANA LAURA SÁNCHEZ RUIZ',
                'telefono'     => '9516543210',
                'direccion'    => 'CALLE HIDALGO 23, COL. JALATLACO',
                'servicio'     => 'INTERNET 100 MBPS',
                'tarifa'       => 550.00,
                'saldo'        => 550.00,
                'estado'       => 'ACTIVO',
                'sucursal'     => 'Oaxaca Centro',
                'meses_adeudo' => 1,
            ],
        ];

        $term = mb_strtolower(trim($this->busqueda));
        $this->resultados = array_values(array_filter($mock, fn($c) =>
            str_contains(mb_strtolower($c['nombre']),    $term) ||
            str_contains($c['telefono'],                 $term) ||
            str_contains(mb_strtolower($c['id']),        $term) ||
            str_contains(mb_strtolower($c['direccion']), $term)
        ));
    }

    public function seleccionarCliente(array $cliente): void
    {
        $this->clienteSeleccionado    = $cliente;
        $this->tarifaMonto            = $cliente['tarifa'];
        $this->montoCobro             = $cliente['tarifa'];   // default: 1 mensualidad
        $this->montoManual            = $cliente['tarifa'];
        $this->telefonoWhatsapp       = $cliente['telefono'];
        $this->datosFactura['nombre'] = $cliente['nombre'];
        $this->busqueda               = '';
        $this->resultados             = [];
        $this->concepto               = 'MENSUALIDAD';        // pre-seleccionar concepto principal
        $this->actualizarPeriodo();
        $this->paso                   = 2;
    }

    public function limpiarCliente(): void
    {
        $this->clienteSeleccionado  = null;
        $this->concepto             = '';
        $this->periodoLabel         = '';
        $this->tarifaMonto          = 0.0;
        $this->montoCobro           = 0.0;
        $this->montoManual          = 0.0;
        $this->montoMensualidadLiq  = 0.0;
        $this->montoAdeudoLiq       = 0.0;
        $this->modificarMonto       = false;
        $this->motivoAjuste         = '';
        $this->passwordAutorizacion = '';
        $this->requiereFactura      = false;
        $this->formaPago            = 'efectivo';
        $this->whatsappEnviado      = false;
        $this->paso                 = 1;
    }

    // ── Watcher concepto: actualiza monto + período ───────────────────────────

    public function updatedConcepto(string $value): void
    {
        // TODO: Cargar desde tabla tarifas según concepto y servicio del cliente
        $tarifa = $this->clienteSeleccionado['tarifa'] ?? 0;
        $saldo  = $this->clienteSeleccionado['saldo']  ?? 0;

        $this->montoMensualidadLiq = 0.0;
        $this->montoAdeudoLiq      = 0.0;

        $this->montoCobro = match ($value) {
            'MENSUALIDAD'        => $tarifa,
            'ADEUDO'             => $saldo,
            'MENSUALIDAD_Y_ADEUDO' => (function() use ($tarifa, $saldo) {
                $this->montoMensualidadLiq = $tarifa;
                $this->montoAdeudoLiq      = $saldo;
                return $tarifa + $saldo;
            })(),
            'DIAS_USO'    => round($tarifa / 30 * now()->day, 2),
            'RECONEXION'  => 150.00,   // TODO: cargar de catálogo de tarifas
            'INSTALACION' => 300.00,   // TODO: cargar de catálogo de tarifas
            default       => 0.0,
        };

        $this->montoManual = $this->montoCobro;
        $this->actualizarPeriodo();
    }

    private function actualizarPeriodo(): void
    {
        $meses = [
            1  => 'ENERO', 2  => 'FEBRERO', 3  => 'MARZO',    4  => 'ABRIL',
            5  => 'MAYO',  6  => 'JUNIO',   7  => 'JULIO',    8  => 'AGOSTO',
            9  => 'SEPTIEMBRE', 10 => 'OCTUBRE', 11 => 'NOVIEMBRE', 12 => 'DICIEMBRE',
        ];
        $mesActual  = (int) now()->format('n');
        $anioActual = now()->format('Y');
        $mesesAdeudo = $this->clienteSeleccionado['meses_adeudo'] ?? 1;

        $mesAnterior = $mesActual > 1 ? $mesActual - 1 : 12;

        $this->periodoLabel = match ($this->concepto) {
            'MENSUALIDAD' => $meses[$mesActual] . ' ' . $anioActual,
            'ADEUDO'      => $mesesAdeudo > 1
                ? $meses[max(1, $mesActual - $mesesAdeudo + 1)] . ' — ' . $meses[$mesActual] . ' ' . $anioActual
                : $meses[$mesActual] . ' ' . $anioActual,
            'MENSUALIDAD_Y_ADEUDO' => $meses[$mesActual] . ' ' . $anioActual
                . ($mesesAdeudo > 0
                    ? ' + Adeudo (' . $mesesAdeudo . ($mesesAdeudo === 1 ? ' mes' : ' meses') . ')'
                    : ''),
            'DIAS_USO'    => '1 al ' . now()->day . ' de ' . $meses[$mesActual] . ' ' . $anioActual,
            'RECONEXION'  => 'Cargo de reconexión · ' . now()->format('d/m/Y'),
            'INSTALACION' => 'Cargo de instalación · ' . now()->format('d/m/Y'),
            default       => '',
        };
    }

    // ── Procesar pago ─────────────────────────────────────────────────────────

    public function procesarPago(): void
    {
        $this->validate([
            'concepto'  => 'required',
            'formaPago' => 'required',
        ], [
            'concepto.required'  => 'Seleccione el concepto de cobro.',
            'formaPago.required' => 'Seleccione la forma de pago.',
        ]);

        if ($this->modificarMonto) {
            $rules = [
                'montoManual'          => 'required|numeric|min:1',
                'motivoAjuste'         => 'required|min:5',
                'passwordAutorizacion' => 'required',
            ];
            $messages = [
                'montoManual.required'          => 'Ingrese el monto.',
                'montoManual.min'               => 'El monto debe ser mayor a $0.',
                'motivoAjuste.required'         => 'Registre el motivo del ajuste.',
                'motivoAjuste.min'              => 'El motivo debe tener al menos 5 caracteres.',
                'passwordAutorizacion.required' => 'Se requiere contraseña gerencial para ajustar el monto.',
            ];
            $this->validate($rules, $messages);

            // TODO: Validar password contra tabla de usuarios con rol gerencial
            // $valida = Hash::check($this->passwordAutorizacion,
            //     User::where('rol', 'gerente')->where('sucursal_id', auth()->user()->sucursal_id)->first()?->password
            // );
            if ($this->passwordAutorizacion !== config('tvt.password_supervisor', 'supervisor123')) {
                $this->addError('passwordAutorizacion', 'Contraseña gerencial incorrecta.');
                return;
            }
        }

        if ($this->requiereFactura) {
            $this->validate([
                'datosFactura.nombre'   => 'required',
                'datosFactura.rfc'      => 'required|min:12|max:13',
                'datosFactura.cp'       => 'required|digits:5',
                'datosFactura.uso_cfdi' => 'required',
                'datosFactura.correo'   => 'required|email',
            ], [
                'datosFactura.nombre.required'   => 'Ingrese la razón social.',
                'datosFactura.rfc.required'      => 'Ingrese el RFC.',
                'datosFactura.rfc.min'           => 'El RFC debe tener mínimo 12 caracteres.',
                'datosFactura.cp.required'       => 'Ingrese el código postal fiscal.',
                'datosFactura.cp.digits'         => 'El C.P. debe tener exactamente 5 dígitos.',
                'datosFactura.uso_cfdi.required' => 'Seleccione el uso del CFDI.',
                'datosFactura.correo.required'   => 'Ingrese el correo para envío de factura.',
                'datosFactura.correo.email'      => 'El correo no es válido.',
            ]);
        }

        // Monto final
        $monto = $this->modificarMonto
            ? (float) $this->montoManual
            : (float) $this->montoCobro;

        // Desglose IVA: el monto del contrato incluye IVA, lo desglosamos para el recibo
        $this->montoFinal     = $monto;
        $this->subtotalRecibo = round($monto / 1.16, 2);
        $this->ivaRecibo      = round($monto - $this->subtotalRecibo, 2);
        $this->totalRecibo    = $monto;
        $this->montoAjustado  = $this->modificarMonto;
        $this->fechaPago      = now()->format('d/m/Y H:i');

        // TODO: DB::transaction(function () use ($monto) {
        //
        //   1. Generar folio correlativo por año
        //      $seq = Ingreso::whereYear('created_at', now()->year)->count() + 1;
        //      $this->folioRecibo = 'PAG-' . now()->format('Y') . '-' . str_pad($seq, 5, '0', STR_PAD_LEFT);
        //
        //   2. Registrar ingreso en caja
        //      $ingreso = Ingreso::create([
        //          'folio'        => $this->folioRecibo,
        //          'concepto'     => $this->concepto,
        //          'periodo'      => $this->periodoLabel,
        //          'monto'        => $monto,
        //          'forma_pago'   => $this->formaPago,
        //          'cliente_id'   => $this->clienteSeleccionado['id'],
        //          'sucursal_id'  => auth()->user()->sucursal_id,
        //          'usuario_id'   => auth()->id(),
        //          'tipo'         => 'INGRESO',
        //          'categoria'    => 'MENSUALIDAD',
        //          'monto_ajuste' => $this->modificarMonto,
        //          'motivo_ajuste'=> $this->motivoAjuste ?: null,
        //          'autorizo_id'  => $this->modificarMonto ? auth()->id() : null,
        //      ]);
        //
        //   3. Actualizar saldo del cliente
        //      $cliente = Cliente::findOrFail($this->clienteSeleccionado['id']);
        //      $nuevoSaldo = max(0, $cliente->saldo_pendiente - $monto);
        //      $cliente->update(['saldo_pendiente' => $nuevoSaldo]);
        //      if ($nuevoSaldo == 0 && $cliente->estado === 'SUSPENDIDO') {
        //          $cliente->update(['estado' => 'ACTIVO']);
        //      }
        //
        //   4. Registrar movimiento en estado de cuenta
        //      EstadoCuenta::create([
        //          'cliente_id'  => $cliente->id,
        //          'tipo'        => 'PAGO',
        //          'concepto'    => $this->concepto,
        //          'periodo'     => $this->periodoLabel,
        //          'monto'       => $monto,
        //          'saldo_prev'  => $cliente->saldo_pendiente,
        //          'saldo_nuevo' => $nuevoSaldo,
        //          'ingreso_id'  => $ingreso->id,
        //      ]);
        //
        //   5. Afectar corte de caja del día
        //      CorteCaja::firstOrCreate(
        //          ['sucursal_id' => auth()->user()->sucursal_id, 'fecha' => today()],
        //      )->increment('total_ingresos', $monto);
        //
        //   6. Si requiere factura → llamar API Facturama
        //      if ($this->requiereFactura) {
        //          FacturamaService::emitirCFDI([
        //              'receptor'   => $this->datosFactura,
        //              'concepto'   => $this->concepto,
        //              'subtotal'   => $this->subtotalRecibo,
        //              'iva'        => $this->ivaRecibo,
        //              'total'      => $monto,
        //              'ingreso_id' => $ingreso->id,
        //          ]);
        //      }
        // });

        $this->toastExito('Pago de $' . number_format($monto, 2) . ' registrado correctamente.');
        $this->paso = 3;
    }

    // ── Enviar recibo por WhatsApp ────────────────────────────────────────────

    public function enviarWhatsappRecibo(): void
    {
        $this->validate([
            'telefonoWhatsapp' => 'required|digits:10',
        ], [
            'telefonoWhatsapp.required' => 'Ingrese el número de WhatsApp.',
            'telefonoWhatsapp.digits'   => 'El teléfono debe tener 10 dígitos.',
        ]);

        // TODO: Envío vía API de Meta (WhatsApp Business Cloud API)
        // MetaWhatsappService::enviarPlantilla('recibo_pago', [
        //     'telefono' => '52' . $this->telefonoWhatsapp,
        //     'params'   => [
        //         $this->clienteSeleccionado['nombre'],
        //         $this->folioRecibo,
        //         '$' . number_format($this->totalRecibo, 2),
        //         $this->fechaPago,
        //         $this->concepto . ' · ' . $this->periodoLabel,
        //     ],
        // ]);

        $this->whatsappEnviado = true;
        $this->toastExito('Recibo enviado por WhatsApp al +52 ' . $this->telefonoWhatsapp);
    }

    // ── Nuevo pago (reinicio completo) ────────────────────────────────────────

    public function nuevoPago(): void
    {
        $this->folioRecibo          = $this->generarFolio();
        $this->clienteSeleccionado  = null;
        $this->busqueda             = '';
        $this->resultados           = [];
        $this->concepto             = '';
        $this->periodoLabel         = '';
        $this->tarifaMonto          = 0.0;
        $this->montoCobro           = 0.0;
        $this->montoManual          = 0.0;
        $this->montoMensualidadLiq  = 0.0;
        $this->montoAdeudoLiq       = 0.0;
        $this->formaPago            = 'efectivo';
        $this->modificarMonto       = false;
        $this->motivoAjuste         = '';
        $this->passwordAutorizacion = '';
        $this->requiereFactura      = false;
        $this->datosFactura         = ['nombre' => '', 'rfc' => '', 'cp' => '', 'uso_cfdi' => 'G03', 'correo' => ''];
        $this->enviarWhatsapp       = true;
        $this->telefonoWhatsapp     = '';
        $this->whatsappEnviado      = false;
        $this->montoFinal           = 0.0;
        $this->subtotalRecibo       = 0.0;
        $this->ivaRecibo            = 0.0;
        $this->totalRecibo          = 0.0;
        $this->montoAjustado        = false;
        $this->fechaPago            = '';
        $this->paso                 = 1;
    }

    // ── Render ────────────────────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.gestion-clientes.pago-mensualidad', [
            'conceptos' => [
                'MENSUALIDAD'          => 'Mensualidad del servicio',
                'MENSUALIDAD_Y_ADEUDO' => 'Mensualidad + Liquidar Adeudo',
                'ADEUDO'               => 'Liquidación de adeudo',
                'DIAS_USO'             => 'Proporcional / días de uso',
                'RECONEXION'           => 'Cargo por reconexión',
                'INSTALACION'          => 'Cargo de instalación',
            ],
            'usoCfdiOpciones' => [
                'G01' => 'G01 – Adquisición de mercancias',
                'G03' => 'G03 – Gastos en general',
                'I01' => 'I01 – Construcciones',
                'P01' => 'P01 – Por definir',
                'S01' => 'S01 – Sin efectos fiscales',
            ],
        ]);
    }
}

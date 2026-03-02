<?php

namespace App\Livewire\GestionClientes;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class PagoMensualidad extends Component
{
    // ── Folio de la transacción ───────────────────────────────────────
    public string $folioRecibo = '';

    // ── Paso del flujo (1=Búsqueda · 2=Cobro · 3=Recibo) ────────────
    public int $paso = 1;

    // ── Búsqueda y selección ─────────────────────────────────────────
    public string $busqueda           = '';
    public array  $resultados         = [];
    public ?array $clienteSeleccionado = null;

    // ── Detalle del cobro ─────────────────────────────────────────────
    public string $concepto    = '';
    public float  $tarifaMonto = 0.0;
    public float  $montoCobro  = 0.0;
    public string $formaPago   = 'efectivo';

    // ── Ajuste manual de monto ────────────────────────────────────────
    public bool   $modificarMonto        = false;
    public float  $montoManual           = 0.0;
    public string $passwordAutorizacion  = '';

    // ── Facturación (Facturama) ───────────────────────────────────────
    public bool  $requiereFactura = false;
    public array $datosFactura    = [
        'nombre'   => '',
        'rfc'      => '',
        'cp'       => '',
        'uso_cfdi' => 'G03',
        'correo'   => '',
    ];

    // ── WhatsApp ──────────────────────────────────────────────────────
    public bool   $enviarWhatsapp   = true;
    public string $telefonoWhatsapp = '';
    public bool   $whatsappEnviado  = false;

    // ── Datos del recibo generado ─────────────────────────────────────
    public float  $montoFinal = 0.0;
    public string $fechaPago  = '';

    // ─────────────────────────────────────────────────────────────────
    public function mount(): void
    {
        $this->folioRecibo = $this->generarFolio();
    }

    private function generarFolio(): string
    {
        return 'PAG-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(3)));
    }

    // ── Búsqueda ──────────────────────────────────────────────────────
    public function buscarCliente(): void
    {
        if (strlen(trim($this->busqueda)) < 3) {
            $this->resultados = [];
            return;
        }

        // TODO: Reemplazar con Eloquent real:
        // $this->resultados = Cliente::with(['servicio', 'sucursal'])
        //     ->where(fn($q) =>
        //         $q->where('nombre',     'LIKE', "%{$this->busqueda}%")
        //           ->orWhere('telefono', 'LIKE', "%{$this->busqueda}%")
        //           ->orWhere('id_cliente','LIKE', "%{$this->busqueda}%")
        //           ->orWhere('direccion','LIKE', "%{$this->busqueda}%")
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
                'estado'       => 'Activo',
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
                'estado'       => 'Activo',
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
                'estado'       => 'Suspendido',
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
                'estado'       => 'Activo',
                'sucursal'     => 'Oaxaca Centro',
                'meses_adeudo' => 1,
            ],
        ];

        $term = strtolower(trim($this->busqueda));
        $this->resultados = array_values(array_filter($mock, fn($c) =>
            str_contains(strtolower($c['nombre']),   $term) ||
            str_contains($c['telefono'],              $term) ||
            str_contains(strtolower($c['id']),        $term) ||
            str_contains(strtolower($c['direccion']), $term)
        ));
    }

    public function seleccionarCliente(array $cliente): void
    {
        $this->clienteSeleccionado    = $cliente;
        $this->tarifaMonto            = $cliente['tarifa'];
        $this->montoCobro             = $cliente['saldo'];
        $this->montoManual            = $cliente['saldo'];
        $this->telefonoWhatsapp       = $cliente['telefono'];
        $this->datosFactura['nombre'] = $cliente['nombre'];
        $this->busqueda               = '';
        $this->resultados             = [];
        $this->paso                   = 2;
    }

    public function limpiarCliente(): void
    {
        $this->clienteSeleccionado  = null;
        $this->concepto             = '';
        $this->tarifaMonto          = 0.0;
        $this->montoCobro           = 0.0;
        $this->montoManual          = 0.0;
        $this->modificarMonto       = false;
        $this->passwordAutorizacion = '';
        $this->requiereFactura      = false;
        $this->formaPago            = 'efectivo';
        $this->whatsappEnviado      = false;
        $this->paso                 = 1;
    }

    // ── Watcher concepto: actualiza monto automáticamente ─────────────
    public function updatedConcepto(string $value): void
    {
        // TODO: Cargar desde tabla tarifas según concepto y servicio del cliente
        $tarifa = $this->clienteSeleccionado['tarifa'] ?? 0;
        $saldo  = $this->clienteSeleccionado['saldo']  ?? 0;

        $this->montoCobro = match ($value) {
            'MENSUALIDAD' => $tarifa,
            'ADEUDO'      => $saldo,
            'DIAS_USO'    => round($tarifa / 30 * now()->day, 2),
            'RECONEXION'  => 150.00,   // TODO: cargar de catálogo de tarifas
            'INSTALACION' => 300.00,   // TODO: cargar de catálogo de tarifas
            default       => 0.0,
        };

        $this->montoManual = $this->montoCobro;
    }

    // ── Procesar pago (valida y pasa a paso 3 — recibo) ──────────────
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
            $this->validate([
                'montoManual'          => 'required|numeric|min:1',
                'passwordAutorizacion' => 'required',
            ], [
                'montoManual.required'          => 'Ingrese el monto.',
                'montoManual.min'               => 'El monto debe ser mayor a $0.',
                'passwordAutorizacion.required' => 'Se requiere contraseña gerencial para ajustar el monto.',
            ]);

            // TODO: Validar password contra tabla de usuarios con rol gerencial
            // $valida = Hash::check($this->passwordAutorizacion,
            //     User::where('rol', 'gerente')->where('sucursal_id', auth()->user()->sucursal_id)->first()?->password
            // );
            if ($this->passwordAutorizacion !== 'admin123') {
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

        $this->montoFinal = $this->modificarMonto
            ? (float) $this->montoManual
            : (float) $this->montoCobro;

        $this->fechaPago = now()->format('d/m/Y H:i');

        // TODO: DB::transaction(function () {
        //
        //   1. Generar folio correlativo por año
        //      $seq = Ingreso::whereYear('created_at', now()->year)->count() + 1;
        //      $this->folioRecibo = 'PAG-' . now()->format('Y') . '-' . str_pad($seq, 5, '0', STR_PAD_LEFT);
        //
        //   2. Registrar ingreso en caja
        //      $ingreso = Ingreso::create([
        //          'folio'        => $this->folioRecibo,
        //          'concepto'     => $this->concepto,
        //          'monto'        => $this->montoFinal,
        //          'forma_pago'   => $this->formaPago,
        //          'cliente_id'   => $this->clienteSeleccionado['id'],
        //          'sucursal_id'  => auth()->user()->sucursal_id,
        //          'usuario_id'   => auth()->id(),
        //          'tipo'         => 'INGRESO',
        //          'categoria'    => 'MENSUALIDAD',
        //          'monto_ajuste' => $this->modificarMonto,
        //          'autorizo_id'  => $this->modificarMonto ? auth()->id() : null,
        //      ]);
        //
        //   3. Actualizar saldo del cliente
        //      $cliente = Cliente::findOrFail($this->clienteSeleccionado['id']);
        //      $nuevoSaldo = max(0, $cliente->saldo_pendiente - $this->montoFinal);
        //      $cliente->update(['saldo_pendiente' => $nuevoSaldo]);
        //      if ($nuevoSaldo == 0 && $cliente->estado === 'Suspendido') {
        //          $cliente->update(['estado' => 'Activo']);
        //      }
        //
        //   4. Afectar estado de cuenta del cliente
        //      EstadoCuenta::create([
        //          'cliente_id'  => $cliente->id,
        //          'tipo'        => 'PAGO',
        //          'concepto'    => $this->concepto,
        //          'monto'       => $this->montoFinal,
        //          'saldo_prev'  => $cliente->saldo_pendiente,
        //          'saldo_nuevo' => $nuevoSaldo,
        //          'ingreso_id'  => $ingreso->id,
        //      ]);
        //
        //   5. Afectar corte de caja del día
        //      CorteCaja::firstOrCreate(
        //          ['sucursal_id' => auth()->user()->sucursal_id, 'fecha' => today()],
        //      )->increment('total_ingresos', $this->montoFinal);
        //
        //   6. Si requiere factura → llamar API Facturama
        //      if ($this->requiereFactura) {
        //          FacturamaService::emitirCFDI([
        //              'receptor'   => $this->datosFactura,
        //              'concepto'   => $this->concepto,
        //              'monto'      => $this->montoFinal,
        //              'ingreso_id' => $ingreso->id,
        //          ]);
        //      }
        // });

        $this->paso = 3;
    }

    // ── Confirmar y enviar recibo por WhatsApp (API Meta) ─────────────
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
        //         '$' . number_format($this->montoFinal, 2),
        //         $this->fechaPago,
        //         $this->concepto,
        //     ],
        // ]);

        $this->whatsappEnviado = true;
    }

    // ── Nuevo pago (reinicio completo) ────────────────────────────────
    public function nuevoPago(): void
    {
        $this->folioRecibo          = $this->generarFolio();
        $this->clienteSeleccionado  = null;
        $this->busqueda             = '';
        $this->resultados           = [];
        $this->concepto             = '';
        $this->tarifaMonto          = 0.0;
        $this->montoCobro           = 0.0;
        $this->montoManual          = 0.0;
        $this->formaPago            = 'efectivo';
        $this->modificarMonto       = false;
        $this->passwordAutorizacion = '';
        $this->requiereFactura      = false;
        $this->datosFactura         = ['nombre' => '', 'rfc' => '', 'cp' => '', 'uso_cfdi' => 'G03', 'correo' => ''];
        $this->enviarWhatsapp       = true;
        $this->telefonoWhatsapp     = '';
        $this->whatsappEnviado      = false;
        $this->montoFinal           = 0.0;
        $this->fechaPago            = '';
        $this->paso                 = 1;
    }

    // ── Render ────────────────────────────────────────────────────────
    public function render()
    {
        return view('livewire.gestion-clientes.pago-mensualidad', [
            'conceptos' => [
                'MENSUALIDAD' => 'Mensualidad ordinaria',
                'ADEUDO'      => 'Liquidación de adeudo',
                'DIAS_USO'    => 'Proporcional / días de uso',
                'RECONEXION'  => 'Cargo por reconexión',
                'INSTALACION' => 'Cargo de instalación',
            ],
            'formasPago' => [
                'efectivo'      => 'Efectivo',
                'transferencia' => 'Transferencia bancaria',
                'tarjeta'       => 'Tarjeta débito / crédito',
                'deposito'      => 'Depósito bancario',
            ],
            'usoCfdiOpciones' => [
                'G01' => 'G01 – Adquisición de mercancias',
                'G03' => 'G03 – Gastos en general',
                'I01' => 'I01 – Construcciones',
                'P01' => 'P01 – Por definir',
                'S01' => 'S01 – Sin efectos fiscales',
            ],
            'iconoFormaPago' => [
                'efectivo'      => 'ri-money-dollar-box-line',
                'transferencia' => 'ri-bank-line',
                'tarjeta'       => 'ri-bank-card-line',
                'deposito'      => 'ri-building-2-line',
            ],
        ]);
    }
}

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
class ContratacionNueva extends Component
{
    use WithFileUploads, WithToasts;

    public int $paso = 1;

    // ─── PASO 1: Identificación + Selección de Servicio ──────────────────────
    public $fotoIdentificacion = null;
    public ?int $tarifaId      = null;
    public array $tarifaSeleccionada = [];
    public string $tipoServicio = ''; // TV | INTERNET | TV+INTERNET

    // Cálculo del primer pago
    public int   $diasRestantes  = 0;
    public float $costoProrrateo = 0.0;
    public float $subtotal       = 0.0;
    public float $iva            = 0.0;
    public float $totalPagar     = 0.0;

    // ─── PASO 2: Alta del Suscriptor ─────────────────────────────────────────
    // Facturación
    public bool   $requiereFactura  = false;
    public string $razonSocial      = '';
    public string $rfc              = '';
    public string $cpFiscal         = '';
    public string $correoFactura    = '';
    public string $metodoPagoFiscal = 'PUE';
    public string $usoCfdi          = 'G03';
    public string $direccionFiscal  = '';

    // Datos personales
    public string $nombre          = '';
    public string $apellidoPaterno = '';
    public string $apellidoMaterno = '';
    public string $curp            = '';
    public string $telefono        = '';
    public string $correo          = '';

    // Dirección del servicio
    public ?int   $sucursalId     = null;
    public ?int   $calleId        = null;
    public string $numExt         = '';
    public string $numInt         = '';
    public string $referencias    = '';

    // Cascade read-only
    public string $estadoNombre    = '';
    public string $municipioNombre = '';
    public string $localidadNombre = '';

    // ─── PASO 3: Confirmación de Pago ────────────────────────────────────────
    public string $metodoPago = 'efectivo';

    // Descuento con contraseña de supervisor
    public bool   $mostrarDescuento    = false;
    public float  $montoDescuentoInput = 0.0;   // monto fijo a descontar (antes de IVA)
    public string $passwordDescuento   = '';
    public bool   $descuentoAplicado   = false;
    public float  $montoDescuento      = 0.0;
    public float  $subtotalDescuento   = 0.0;
    public float  $ivaDescuento        = 0.0;
    public float  $totalConDescuento   = 0.0;

    // ─── PASO 4: Recibo | PASO 5: Firma ──────────────────────────────────────
    public string $numeroSuscriptor = '';
    public string $folioRecibo      = '';
    public string $firmaBase64      = '';

    // ─── PASO 6: Selección de Técnico + Datos previos del Reporte ───────────
    public ?int   $tecnicoId    = null;
    public string $folioReporte = '';

    // ─── Catálogos cargados ──────────────────────────────────────────────────
    public array $sucursales = [];
    public array $calles     = [];
    public array $tarifas    = [];
    public array $tecnicos   = [];

    // ─────────────────────────────────────────────────────────────────────────

    public function mount(): void
    {
        $this->sucursales = Sucursal::where('activa', true)
            ->get(['id', 'clave', 'nombre', 'estado_id', 'municipio_id', 'localidad_id'])
            ->toArray();

        $this->tarifas = TarifaPrincipal::where('estado', 'VIGENTE_CONTRATAR')
            ->get(['id', 'nombre_comercial', 'descripcion', 'precio_instalacion', 'precio_mensualidad'])
            ->toArray();

        $this->tecnicos = Empleado::where('activo', true)
            ->whereIn('area', ['TECNICO_CAMPO', 'TECNICO_INSTALACIONES'])
            ->get(['id', 'clave_empleado', 'nombre', 'apellido_paterno', 'apellido_materno', 'area', 'puesto'])
            ->map(fn($e) => [
                'id'     => $e->id,
                'clave'  => $e->clave_empleado,
                'nombre' => $e->nombre_completo,
                'area'   => $e->area,
                'puesto' => $e->puesto,
            ])
            ->toArray();

        $diaActual = (int) now()->format('d');
        $this->diasRestantes = max(1, 30 - $diaActual + 1);
    }

    // ─── Selección y recálculo de tarifa ─────────────────────────────────────

    public function seleccionarTarifa(int $id): void
    {
        $tarifa = collect($this->tarifas)->firstWhere('id', $id);
        if (! $tarifa) return;

        $this->tarifaId          = $id;
        $this->tarifaSeleccionada = $tarifa;
        $this->tipoServicio      = $this->inferirTipoServicio($tarifa['nombre_comercial']);

        // Resetear descuento al cambiar tarifa
        $this->quitarDescuento();
        $this->recalcular();
    }

    public function inferirTipoServicio(string $nombre): string
    {
        $n = mb_strtoupper($nombre);
        $tieneTV       = str_contains($n, 'TV') || str_contains($n, 'TELEVISION') || str_contains($n, 'CABLE') || str_contains($n, 'DIGITAL');
        $tieneInternet = str_contains($n, 'INTERNET') || str_contains($n, 'FIBRA') || str_contains($n, 'DATOS') || str_contains($n, 'MEGA');

        if ($tieneTV && $tieneInternet) return 'TV+INTERNET';
        if ($tieneInternet) return 'INTERNET';
        return 'TV';
    }

    private function recalcular(): void
    {
        if (empty($this->tarifaSeleccionada)) return;

        $mensualidad = (float) $this->tarifaSeleccionada['precio_mensualidad'];
        $instalacion = (float) $this->tarifaSeleccionada['precio_instalacion'];

        $this->costoProrrateo = round($mensualidad / 30 * $this->diasRestantes, 2);
        $this->subtotal       = round($instalacion + $this->costoProrrateo, 2);
        $this->iva            = round($this->subtotal * 0.16, 2);
        $this->totalPagar     = round($this->subtotal + $this->iva, 2);

        // Recalcular descuento si estaba aplicado
        if ($this->descuentoAplicado) {
            $this->calcularDescuento();
        }
    }

    // ─── Normalización de texto a mayúsculas sin Ñ ───────────────────────────

    public function updatedNombre(string $v): void          { $this->nombre          = $this->mayus($v); }
    public function updatedApellidoPaterno(string $v): void { $this->apellidoPaterno = $this->mayus($v); }
    public function updatedApellidoMaterno(string $v): void { $this->apellidoMaterno = $this->mayus($v); }
    public function updatedRazonSocial(string $v): void     { $this->razonSocial     = $this->mayus($v); }
    public function updatedRfc(string $v): void             { $this->rfc             = mb_strtoupper($v); }

    private function mayus(string $val): string
    {
        return str_replace('Ñ', 'N', mb_strtoupper(trim($val)));
    }

    // ─── Cascade sucursal → calles ────────────────────────────────────────────

    public function updatedSucursalId(): void
    {
        $this->calleId         = null;
        $this->calles          = [];
        $this->estadoNombre    = '';
        $this->municipioNombre = '';
        $this->localidadNombre = '';

        if (! $this->sucursalId) return;

        $sucursal = Sucursal::with(['estado', 'municipio', 'localidad'])->find($this->sucursalId);
        if ($sucursal) {
            $this->estadoNombre    = $sucursal->estado?->nombre    ?? '';
            $this->municipioNombre = $sucursal->municipio?->nombre ?? '';
            $this->localidadNombre = $sucursal->localidad?->nombre ?? '';

            $this->calles = Calle::where('sucursal_id', $this->sucursalId)
                ->where('activa', true)
                ->orderBy('nombre_calle')
                ->get(['id', 'nombre_calle'])
                ->toArray();
        }
    }

    // ─── Método de pago ───────────────────────────────────────────────────────

    public function seleccionarMetodoPago(string $metodo): void
    {
        $this->metodoPago = $metodo;
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
        // El descuento se aplica sobre el subtotal (antes de IVA), criterio fiscal
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

    // Total efectivo a cobrar (con o sin descuento)
    public function getTotalEfectivoProperty(): float
    {
        return $this->descuentoAplicado ? $this->totalConDescuento : $this->totalPagar;
    }

    // ─── Navegación entre pasos ───────────────────────────────────────────────

    public function irPaso2(): void
    {
        if (! $this->tarifaId) {
            $this->toastError('Selecciona un servicio para continuar.');
            return;
        }
        $this->paso = 2;
    }

    public function cambiarServicio(int $id): void
    {
        $tarifa = collect($this->tarifas)->firstWhere('id', $id);
        if (! $tarifa) return;

        $this->tarifaId          = $id;
        $this->tarifaSeleccionada = $tarifa;
        $this->tipoServicio      = $this->inferirTipoServicio($tarifa['nombre_comercial']);
        $this->quitarDescuento();
        $this->recalcular();
        $this->toastInfo('Servicio actualizado. Revisa el resumen de pago.');
    }

    public function irPaso3(): void
    {
        $rules = [
            'nombre'          => 'required|min:2',
            'apellidoPaterno' => 'required|min:2',
            'telefono'        => 'required|digits:10',
            'sucursalId'      => 'required',
            'calleId'         => 'required',
            'numExt'          => 'required',
        ];

        $messages = [
            'nombre.required'          => 'El nombre es obligatorio.',
            'nombre.min'               => 'El nombre debe tener al menos 2 caracteres.',
            'apellidoPaterno.required' => 'El apellido paterno es obligatorio.',
            'apellidoPaterno.min'      => 'El apellido paterno debe tener al menos 2 caracteres.',
            'telefono.required'        => 'El teléfono es obligatorio.',
            'telefono.digits'          => 'El teléfono debe tener exactamente 10 dígitos.',
            'sucursalId.required'      => 'Selecciona una sucursal.',
            'calleId.required'         => 'Selecciona una calle.',
            'numExt.required'          => 'El número exterior es obligatorio.',
        ];

        if ($this->requiereFactura) {
            $rules['rfc']           = 'required|min:12|max:13';
            $rules['cpFiscal']      = 'required|digits:5';
            $rules['correoFactura'] = 'required|email';
            $messages['rfc.required']           = 'El RFC es obligatorio para facturación.';
            $messages['cpFiscal.required']      = 'El código postal fiscal es obligatorio.';
            $messages['correoFactura.required'] = 'El correo para factura es obligatorio.';
            $messages['correoFactura.email']    = 'El correo no tiene un formato válido.';
        }

        $this->validate($rules, $messages);
        $this->paso = 3;
    }

    // ─── Confirmar ingreso (genera suscriptor + guarda) ───────────────────────

    public function confirmarIngreso(): void
    {
        // TODO: DB::transaction() {
        //   $totalFinal = $this->totalEfectivo;
        //   $cliente = Cliente::create([nombre, apellido_paterno, apellido_materno, telefono, correo, curp, sucursal_id, calle_id, numero_exterior, numero_interior, referencias, activo => true]);
        //   SuscriptorServicio::create([cliente_id, tarifa_id, estado => 'PENDIENTE_INSTALACION', tarifa_contratada => json($tarifaSeleccionada)]);
        //   IngresoEgreso::create([sucursal_id => $sucursalId, tipo => 'INGRESO', concepto => 'Contratación '.$tarifaSeleccionada['nombre_comercial'], monto => $totalFinal, metodo_pago => $metodoPago, user_id => auth()->id()]);
        //   if ($descuentoAplicado) {
        //       DescuentoAplicado::create([cliente_id, monto => $montoDescuento, user_id => auth()->id()]);
        //   }
        // }

        $sucursal = Sucursal::find($this->sucursalId);
        $clave    = strtoupper($sucursal?->clave ?? 'TVT');

        // TODO: Obtener siguiente # real: SuscriptorServicio::where('sucursal_id', $this->sucursalId)->max('numero') + 1
        $siguiente = str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);

        $this->numeroSuscriptor = $clave . $siguiente;
        $this->folioRecibo      = 'REC-' . now()->format('Y') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);

        $this->paso = 4;
    }

    // ─── Firma digital ────────────────────────────────────────────────────────

    public function guardarFirma(string $base64): void
    {
        if (empty($base64) || $base64 === 'data:,') {
            $this->toastError('Dibuja tu firma para continuar.');
            return;
        }

        $this->firmaBase64 = $base64;

        // TODO: Generar PDF del contrato con DomPDF/Browsershot, adjuntar firma,
        //       guardar en storage/expedientes/{numeroSuscriptor}/contrato.pdf
        //       Registrar en tabla expedientes: {cliente_id, tipo => 'CONTRATO', path, firmado_en, user_id, sucursal_id, ip => request()->ip()}

        $this->toastExito('Contrato firmado. Continúa con la asignación de equipo.');
        $this->paso = 6;
    }

    // ─── PASO 6: Asignación de equipo + técnico → Genera reporte ─────────────

    public function generarReporte(): void
    {
        if (! $this->tecnicoId) {
            $this->toastError('Selecciona un técnico para generar el reporte.');
            return;
        }

        $this->folioReporte = 'REP-' . now()->format('Y') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);

        // TODO: DB::transaction() {
        //   ReporteServicio::create([
        //     folio         => $this->folioReporte,
        //     tipo_reporte  => 'INSTALACION',
        //     tipo_servicio => $this->tipoServicio,   // TV | INTERNET | TV+INTERNET
        //     suscriptor_id => (id del suscriptor recién creado),
        //     sucursal_id   => $this->sucursalId,
        //     tecnico_id    => $this->tecnicoId,
        //     estado        => 'PENDIENTE',
        //     // La asignación de ONU/Mininodo se realiza dentro del reporte de servicio (AtenderReporte)
        //   ]);
        //   // Notificar por SMS al técnico: SmsService::enviar($tecnico->telefono, "Nuevo reporte: $folioReporte — Instalación $tipoServicio para $numeroSuscriptor");
        // }

        $this->toastExito("Reporte {$this->folioReporte} generado y enviado al técnico.");
        $this->paso = 7;
    }

    public function render()
    {
        return view('livewire.gestion-clientes.contratacion-nueva');
    }
}

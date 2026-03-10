<?php

namespace App\Livewire\GestionClientes;

use App\Models\RRHH\Empleado;
use App\Traits\WithToasts;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ServiciosAdicionales extends Component
{
    use WithToasts;

    public int $paso = 1;

    // ─── PASO 1: Selección de Servicio ────────────────────────────────────────
    public array $servicios        = [];
    public ?int  $servicioId       = null;
    public array $servicioSel      = [];   // servicio seleccionado

    // ─── PASO 2: Identificar Suscriptor ──────────────────────────────────────
    public string $busqueda          = '';
    public array  $resultados        = [];
    public bool   $busquedaRealizada = false;
    public array  $suscriptor        = [];

    // ─── PASO 3: Cobro ────────────────────────────────────────────────────────
    public string $metodoPago       = 'efectivo';
    public bool   $confirmacionCaja = false;

    // ─── PASO 4: Técnico ──────────────────────────────────────────────────────
    public array $tecnicos      = [];
    public ?int  $tecnicoId     = null;
    public bool  $notificarSms  = true;

    // ─── RESULTADO ────────────────────────────────────────────────────────────
    public string $folioReporte = '';

    // ─────────────────────────────────────────────────────────────────────────

    public function mount(): void
    {
        // TODO: TarifaAdicional::where('estado', 'VIGENTE')->get() y mapear a este formato
        $this->servicios = [
            [
                'id'          => 1,
                'tipo'        => 'TV_ADICIONAL',
                'nombre'      => 'TELEVISIÓN ADICIONAL',
                'descripcion' => 'Boca adicional de señal TV para el mismo domicilio vía mininodo',
                'icon'        => 'ri-tv-2-line',
                'color'       => 'orange',
                'instalacion' => 150.00,
                'mensualidad' => 60.00,
                'tags'        => ['Mininodo', 'NAP', 'Potencia óptica'],
            ],
            [
                'id'          => 2,
                'tipo'        => 'AUMENTO_VELOCIDAD',
                'nombre'      => 'AUMENTO DE VELOCIDAD',
                'descripcion' => 'Cambio de plan de datos en equipo ONU del suscriptor vía Winbox',
                'icon'        => 'ri-speed-up-line',
                'color'       => 'blue',
                'instalacion' => 0.00,
                'mensualidad' => 100.00,
                'tags'        => ['ONU', 'Winbox', 'OLT / PON'],
            ],
        ];

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
    }

    // ─── PASO 1: Selección de servicio ────────────────────────────────────────

    public function seleccionarServicio(int $id): void
    {
        $s = collect($this->servicios)->firstWhere('id', $id);
        if (! $s) return;

        $this->servicioId  = $id;
        $this->servicioSel = $s;
    }

    public function irPaso2(): void
    {
        if (! $this->servicioId) {
            $this->toastError('Selecciona un servicio adicional para continuar.');
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
        //       ->with(['suscripcionActiva.tarifa', 'sucursal'])
        //       ->limit(5)->get()
        //       Mapear a: id, clave, nombre, telefono, sucursal, domicilio, tipo_servicio, estado, tarifa_actual

        $this->resultados = [
            [
                'id'            => 5432,
                'clave'         => '01-005432',
                'nombre'        => 'JUAN PÉREZ GARCÍA',
                'telefono'      => '9511234567',
                'sucursal'      => 'Oaxaca Centro',
                'domicilio'     => 'AV. INDEPENDENCIA 102, COL. CENTRO',
                'tipo_servicio' => 'TV',
                'estado'        => 'ACTIVO',
                'tarifa_actual' => 'Retro TV',
            ],
            [
                'id'            => 3817,
                'clave'         => '01-003817',
                'nombre'        => 'MARÍA LÓPEZ HERNÁNDEZ',
                'telefono'      => '9519876543',
                'sucursal'      => 'Oaxaca Centro',
                'domicilio'     => 'CALLE HIDALGO 55, COL. REFORMA',
                'tipo_servicio' => 'TV+INTERNET',
                'estado'        => 'ACTIVO',
                'tarifa_actual' => 'Combo 30 Megas',
            ],
        ];
    }

    public function seleccionarSuscriptor(int $id): void
    {
        $s = collect($this->resultados)->firstWhere('id', $id);
        if (! $s) return;

        $this->suscriptor = $s;
        $this->resultados = [];
    }

    public function limpiarSuscriptor(): void
    {
        $this->suscriptor        = [];
        $this->busqueda          = '';
        $this->busquedaRealizada = false;
        $this->resultados        = [];
    }

    public function irPaso3(): void
    {
        if (empty($this->suscriptor)) {
            $this->toastError('Selecciona un suscriptor para continuar.');
            return;
        }
        $this->paso = 3;
    }

    // ─── PASO 3: Cobro ────────────────────────────────────────────────────────

    public function confirmarCobro(): void
    {
        if (! $this->confirmacionCaja) {
            $this->toastError('Confirma la recepción del pago en caja antes de continuar.');
            return;
        }

        // TODO: DB::transaction() {
        //   IngresoEgreso::create([
        //     sucursal_id  => $this->suscriptor['sucursal_id'],
        //     tipo         => 'INGRESO',
        //     concepto     => 'Servicio Adicional: '.$this->servicioSel['nombre'],
        //     monto        => $this->servicioSel['instalacion'] + $this->servicioSel['mensualidad'],
        //     metodo_pago  => $this->metodoPago,
        //     user_id      => auth()->id(),
        //   ]);
        //   // Asociar servicio adicional al suscriptor:
        //   // SuscriptorServicioAdicional::create([suscriptor_id, tarifa_adicional_id, estado => 'ACTIVO', fecha_inicio => now()])
        //   // Ajustar mensualidad: SuscriptorServicio::find($suscriptor_id)->increment('mensualidad', $servicioSel['mensualidad'])
        // }

        $this->paso = 4;
    }

    // ─── PASO 4: Técnico + Reporte ────────────────────────────────────────────

    public function generarReporte(): void
    {
        if (! $this->tecnicoId) {
            $this->toastError('Selecciona un técnico para generar el reporte de servicio.');
            return;
        }

        $this->folioReporte = 'REP-' . now()->format('Y') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);

        // TODO: DB::transaction() {
        //   $tipoReporte = $this->servicioSel['tipo'] === 'TV_ADICIONAL'
        //       ? 'SERVICIO_ADICIONAL_TV'
        //       : 'AUMENTO_VELOCIDAD';
        //   $tipoServicio = $this->servicioSel['tipo'] === 'TV_ADICIONAL' ? 'TV' : 'INTERNET';
        //
        //   ReporteServicio::create([
        //     folio           => $this->folioReporte,
        //     tipo_reporte    => $tipoReporte,
        //     tipo_servicio   => $tipoServicio,
        //     suscriptor_id   => $this->suscriptor['id'],
        //     tecnico_id      => $this->tecnicoId,
        //     sucursal_id     => $this->suscriptor['sucursal_id'],
        //     estado          => 'PENDIENTE',
        //     responsable_ini => 'SUCURSAL',
        //   ]);
        //   if ($this->notificarSms) SmsService::enviar($tecnico->telefono, "Nuevo reporte: {$this->folioReporte} — {$tipoReporte} para {$this->suscriptor['nombre']}");
        // }

        $this->toastExito("Reporte {$this->folioReporte} generado y asignado al técnico.");
        $this->paso = 5;
    }

    public function finalizar(): void
    {
        $this->redirect(route('reportes.servicio'));
    }

    // ─────────────────────────────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.gestion-clientes.servicios-adicionales');
    }
}

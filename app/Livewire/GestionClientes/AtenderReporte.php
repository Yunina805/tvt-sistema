<?php

namespace App\Livewire\GestionClientes;

use App\Traits\WithToasts;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('layouts.app')]
class AtenderReporte extends Component
{
    use WithToasts;
    // ── Datos del reporte (cargados en mount) ─────────────────────────
    public array $reporte = [];

    // ── Estado de UI ──────────────────────────────────────────────────
    public bool $cambioEquipo     = false;
    public bool $mostrarCambioTecnico = false;

    // ── Cambio de equipo ──────────────────────────────────────────────
    public string $equipoNuevo            = '';
    public string $wifiNuevo              = '';
    public string $passwordNuevo          = '';
    public string $vlanNueva              = '';
    public string $encapsulamientoNuevo   = '';

    // ── Lecturas técnicas generales ───────────────────────────────────
    public string $napSeleccionada   = '';
    public string $salidaNap         = '';
    public string $potenciaNap       = '';
    public string $potenciaEquipo    = '';
    public string $metrosAcometida   = '';

    // ── Checklist TV ─────────────────────────────────────────────────
    public bool   $pruebaCanalesOk  = false;
    public string $cantidadCanales  = '';

    // ── Checklist ONU / Internet ──────────────────────────────────────
    public bool   $onuEncendio          = false;
    public bool   $ponVerde             = false;
    public bool   $wifiConecta          = false;
    public bool   $ledsVerdes           = false;
    public bool   $detectoWifiOriginal  = false;
    public bool   $configuroWifiDefault = false;
    public string $asignoNuevaPass      = '';
    public bool   $accesoInternet       = false;
    public string $velocidadRegistrada  = '';
    public bool   $confirmoOlt          = false;
    public bool   $confirmoPon          = false;
    public string $oltAsignada          = '';
    public string $ponAsignado          = '';
    public string $ipAsignada           = '';
    public bool   $actualizoWinbox      = false;
    public bool   $asignoPlanWinbox     = false;
    public bool   $actualizoOLT         = false;

    // ── Cambio de domicilio ────────────────────────────────────────────
    public string $metodoPagoCambioDom  = 'efectivo';
    public string $nuevaDireccion       = '';
    public string $nuevasDirReferencias = '';

    // ── Suspensión ────────────────────────────────────────────────────
    public bool   $confirmacionDesconexionFisica = false;
    public bool   $confirmacionWinbox            = false;
    public bool   $confirmacionOLT               = false;
    public string $salidaNapLibre                = '';

    // ── Cancelación ───────────────────────────────────────────────────
    public bool   $equipoRecuperado   = false;
    public string $serieConfirmada    = '';
    public bool   $equipoPerdido      = false;  // cliente paga por pérdida
    public bool   $desconexionFisica  = false;
    public bool   $bajaWinbox         = false;
    public bool   $bajaOLT            = false;

    // ── Recuperación de equipo ────────────────────────────────────────
    public string $serieRecuperada        = '';
    public bool   $equipoEntregado        = false;
    public bool   $desconexionFisicaRec   = false;
    public bool   $desconexionWinboxRec   = false;
    public bool   $desconexionOLTRec      = false;

    // ── Instalación — comodato ────────────────────────────────────────
    public bool   $comodatoFirmado        = false;

    // ── Aumento de Velocidad ──────────────────────────────────────────
    public bool   $confirmaCambioWinbox   = false;
    public bool   $confirmaCambioOLT      = false;
    public string $velocidadNueva         = '';

    // ── Cambio de técnico ─────────────────────────────────────────────
    public string $nuevoTecnico      = '';
    public string $motivoCambio      = '';
    public string $passwordCambio    = '';

    // ── Cierre general ────────────────────────────────────────────────
    public string $solucionOpcion       = '';
    public string $descripcionSolucion  = '';
    public string $calificacion         = 'Excelente';
    public string|int|null $horasAtencion = null;

    // ─────────────────────────────────────────────────────────────────
    public function mount(string $folio = 'REP-2026-0001'): void
    {
        // ─────────────────────────────────────────────────────────────
        // TODO: Reemplazar con Eloquent real
        // $data = ReporteServicio::with(['cliente','tecnico','nap','equipo'])->where('folio',$folio)->firstOrFail();
        // $this->reporte = $data->toArray();
        //
        // CLAVE: 'tipo_reporte' determina qué secciones se muestran
        //   INSTALACION | FALLA_TV | FALLA_INTERNET | FALLA_TV_INTERNET
        //   CAMBIO_DOMICILIO | SUSPENSION | CANCELACION | RECUPERACION
        //
        // 'tipo_servicio' determina qué checklist técnico se muestra
        //   TV | INTERNET | TV+INTERNET
        // ─────────────────────────────────────────────────────────────
        $mockData = [
            'REP-2026-0001' => [
                'folio'          => 'REP-2026-0001',
                'fecha'          => '2026-02-18 08:00',
                'sucursal'       => 'Oaxaca Centro',
                'cliente'        => 'JUAN PÉREZ GARCÍA',
                'id_cliente'     => 'OAX-0012345',
                'domicilio'      => 'Av. Independencia 102, Col. Centro. Ref: Casa azul, junto a la tortillería.',
                'estado_cliente' => 'Pendiente Instalación',
                'quien_reporto'  => 'Sistema',
                'tipo_reporte'   => 'INSTALACION',
                'tipo_servicio'  => 'TV+INTERNET',
                'servicio'       => 'TV + Internet 30 Mbps',
                'tecnico'        => 'Ing. Roberto Gómez',
                'nap'            => 'NAP-OAX-05',
                'dir_nap'        => 'Poste 12, Esquina Reforma con Independencia',
                'salidas_nap_disponibles' => ['1','3','4','6','7'],
                'info_equipo'    => 'ONU Huawei HG8010M (Serie: HW2024-001)',
                'ip'             => '—',
                'wifi'           => '—',
                'password_wifi'  => '—',
                'olt'            => '—',
                'pon'            => '—',
                'vlan'           => '100',
                'encapsulamiento'=> 'IPoE',
                'ultima_potencia_nap'    => '—',
                'ultima_potencia_equipo' => '—',
            ],
            'REP-2026-0002' => [
                'folio'          => 'REP-2026-0002',
                'fecha'          => '2026-02-20 09:30',
                'sucursal'       => 'San Pedro Amuzgos',
                'cliente'        => 'MARÍA LÓPEZ CRUZ',
                'id_cliente'     => 'SPA-0007891',
                'domicilio'      => 'Calle Morelos 45. Ref: Portón café frente al parque.',
                'estado_cliente' => 'Activo',
                'quien_reporto'  => 'Cliente',
                'tipo_reporte'   => 'FALLA_TV',
                'tipo_servicio'  => 'TV',
                'falla_reportada'=> 'FALLA EN SERVICIO DE TELEVISIÓN',
                'servicio'       => 'Retro TV',
                'tecnico'        => 'Cuadrilla 2',
                'nap'            => 'NAP-SPA-02',
                'dir_nap'        => 'Poste 7, Calle Juárez',
                'salidas_nap_disponibles' => ['2','5','8'],
                'info_equipo'    => 'Mininodo ARRIS (Serie: ARR2023-012)',
                'ip'             => '—',
                'wifi'           => '—',
                'password_wifi'  => '—',
                'olt'            => '—',
                'pon'            => '—',
                'vlan'           => '—',
                'encapsulamiento'=> '—',
                'ultima_potencia_nap'    => '-18.5',
                'ultima_potencia_equipo' => '-22.3',
            ],
            'REP-2026-0004' => [
                'folio'          => 'REP-2026-0004',
                'fecha'          => '2026-02-22 11:15',
                'sucursal'       => 'Oaxaca Norte',
                'cliente'        => 'ROSA MARTÍNEZ DÍAZ',
                'id_cliente'     => 'OAN-0055234',
                'domicilio'      => 'Periférico Norte 301, Col. Volcanes. Ref: Casa blanca frente al Oxxo.',
                'estado_cliente' => 'Activo',
                'quien_reporto'  => 'Cliente',
                'tipo_reporte'   => 'FALLA_TV_INTERNET',
                'tipo_servicio'  => 'TV+INTERNET',
                'falla_reportada'=> 'FALLA EN SERVICIO DE TELEVISIÓN + INTERNET',
                'servicio'       => 'Combo TV + Internet',
                'tecnico'        => 'Cuadrilla 1',
                'nap'            => 'NAP-OAN-09',
                'dir_nap'        => 'Poste 21, Av. Ferrocarril',
                'salidas_nap_disponibles' => ['2','4'],
                'info_equipo'    => 'ONU ZTE F660 (Serie: ZTE2024-045)',
                'ip'             => '192.168.10.55',
                'wifi'           => 'TuVision_Rosa',
                'password_wifi'  => 'casa12345',
                'olt'            => 'OLT-02',
                'pon'            => 'PON/0/4',
                'vlan'           => '200',
                'encapsulamiento'=> 'PPPoE',
                'ultima_potencia_nap'    => '-17.2',
                'ultima_potencia_equipo' => '-21.0',
            ],
            'REP-2026-0006' => [
                'folio'          => 'REP-2026-0006',
                'fecha'          => '2026-02-24 08:45',
                'sucursal'       => 'San Pedro Amuzgos',
                'cliente'        => 'LUIS HERNÁNDEZ VEGA',
                'id_cliente'     => 'SPA-0003312',
                'domicilio'      => 'Calle Hidalgo 67. Ref: Casa verde, escalón de cantera.',
                'estado_cliente' => 'Activo',
                'quien_reporto'  => 'Sistema',
                'tipo_reporte'   => 'SUSPENSION',
                'tipo_servicio'  => 'TV',
                'servicio'       => 'Retro TV',
                'falla_reportada'=> 'SUSPENSION POR FALTA DE PAGO',
                'tecnico'        => 'Cuadrilla 3',
                'nap'            => 'NAP-SPA-01',
                'dir_nap'        => 'Poste 3, Esquina Hidalgo',
                'salidas_nap_disponibles' => [],
                'info_equipo'    => 'Mininodo CALIX (Serie: CAL2023-089)',
                'ip'             => '—',
                'wifi'           => '—',
                'password_wifi'  => '—',
                'olt'            => '—',
                'pon'            => '—',
                'vlan'           => '—',
                'encapsulamiento'=> '—',
                'ultima_potencia_nap'    => '-19.1',
                'ultima_potencia_equipo' => '-23.0',
                'saldo_pendiente'=> 700.00,
                'dias_suspension'=> 35,
            ],
            'REP-2026-0007' => [
                'folio'          => 'REP-2026-0007',
                'fecha'          => '2026-02-24 09:00',
                'sucursal'       => 'Oaxaca Centro',
                'cliente'        => 'PATRICIA GÓMEZ RIVAS',
                'id_cliente'     => 'OAX-0099012',
                'domicilio'      => 'Av. Juárez 552, Col. Reforma. Ref: Edificio 3 pisos, despacho.',
                'estado_cliente' => 'Suspendido',
                'quien_reporto'  => 'Sistema',
                'tipo_reporte'   => 'RECUPERACION',
                'tipo_servicio'  => 'INTERNET',
                'servicio'       => 'Internet 30 Mbps',
                'falla_reportada'=> 'RECUPERACION DE EQUIPO POR FALTA DE PAGO',
                'tecnico'        => 'Cuadrilla 1',
                'nap'            => 'NAP-OAX-03',
                'dir_nap'        => 'Poste 8, Calle Alcalá',
                'salidas_nap_disponibles' => [],
                'info_equipo'    => 'ONU Huawei HG8310M (Serie: HW2023-089)',
                'ip'             => '192.168.1.200',
                'wifi'           => 'TuVision_Paty',
                'password_wifi'  => 'pat45678',
                'olt'            => 'OLT-01',
                'pon'            => 'PON/0/1',
                'vlan'           => '100',
                'encapsulamiento'=> 'IPoE',
                'ultima_potencia_nap'    => '-19.5',
                'ultima_potencia_equipo' => '-23.4',
                'saldo_pendiente'=> 1400.00,
                'dias_suspension'=> 65,
            ],
            'REP-2026-0008' => [
                'folio'          => 'REP-2026-0008',
                'fecha'          => '2026-02-25 10:00',
                'sucursal'       => 'Oaxaca Centro',
                'cliente'        => 'MANUEL ORTIZ CAMPOS',
                'id_cliente'     => 'OAX-0011234',
                'domicilio'      => 'Calle Macedonio Alcalá 302. Ref: Casa colonial patio central.',
                'estado_cliente' => 'Activo',
                'quien_reporto'  => 'Cliente',
                'tipo_reporte'   => 'CANCELACION',
                'tipo_servicio'  => 'TV+INTERNET',
                'servicio'       => 'Combo TV + Internet',
                'falla_reportada'=> 'CANCELACION DEL SERVICIO',
                'tecnico'        => 'Ing. Ana Martínez',
                'nap'            => 'NAP-OAX-07',
                'dir_nap'        => 'Poste 15, Calle Alcalá',
                'salidas_nap_disponibles' => [],
                'info_equipo'    => 'ONU Huawei HG8010M (Serie: HW2022-456)',
                'ip'             => '192.168.2.33',
                'wifi'           => 'TuVision_Manuel',
                'password_wifi'  => 'man98765',
                'olt'            => 'OLT-01',
                'pon'            => 'PON/0/2',
                'vlan'           => '100',
                'encapsulamiento'=> 'IPoE',
                'ultima_potencia_nap'    => '-17.8',
                'ultima_potencia_equipo' => '-21.5',
                'saldo_pendiente'=> 0,
            ],
            'REP-2026-0010' => [
                'folio'          => 'REP-2026-0010',
                'fecha'          => '2026-02-26 10:00',
                'sucursal'       => 'Oaxaca Centro',
                'cliente'        => 'SOFÍA RAMÍREZ LUNA',
                'id_cliente'     => 'OAX-0022345',
                'domicilio'      => 'Calle García Vigil 88, Col. Centro. Ref: Casa amarilla esquina norte.',
                'estado_cliente' => 'Activo',
                'quien_reporto'  => 'Sistema',
                'tipo_reporte'   => 'SERVICIO_ADICIONAL_TV',
                'tipo_servicio'  => 'TV',
                'falla_reportada'=> 'ALTA DE SERVICIO DE TELEVISIÓN ADICIONAL',
                'servicio'       => 'Retro TV (servicio adicional)',
                'tecnico'        => 'Cuadrilla 2',
                'nap'            => 'NAP-OAX-02',
                'dir_nap'        => 'Poste 9, Av. Independencia',
                'salidas_nap_disponibles' => ['2', '5', '9'],
                'info_equipo'    => 'Mininodo ARRIS (Sin asignar)',
                'ip'             => '—',
                'wifi'           => '—',
                'password_wifi'  => '—',
                'olt'            => '—',
                'pon'            => '—',
                'vlan'           => '—',
                'encapsulamiento'=> '—',
                'ultima_potencia_nap'    => '—',
                'ultima_potencia_equipo' => '—',
            ],
            'REP-2026-0011' => [
                'folio'          => 'REP-2026-0011',
                'fecha'          => '2026-02-27 09:00',
                'sucursal'       => 'Oaxaca Norte',
                'cliente'        => 'ANDRÉS VILLA RUIZ',
                'id_cliente'     => 'OAN-0077654',
                'domicilio'      => 'Periférico Norte 455, Col. Industrial. Ref: Portón negro, frente a la refaccionaria.',
                'estado_cliente' => 'Activo',
                'quien_reporto'  => 'Sistema',
                'tipo_reporte'   => 'AUMENTO_VELOCIDAD',
                'tipo_servicio'  => 'INTERNET',
                'falla_reportada'=> 'CAMBIO DE PLAN — AUMENTO DE VELOCIDAD',
                'servicio'       => 'Internet 30 Mbps → 100 Mbps',
                'tecnico'        => 'Ing. Ana Martínez',
                'nap'            => 'NAP-OAN-03',
                'dir_nap'        => 'Poste 22, Av. Ferrocarril Norte',
                'salidas_nap_disponibles' => [],
                'info_equipo'    => 'ONU Huawei HG8310M (Serie: HW2023-210)',
                'ip'             => '192.168.5.100',
                'wifi'           => 'TuVision_Andres',
                'password_wifi'  => 'andr12345',
                'olt'            => 'OLT-02',
                'pon'            => 'PON/0/6',
                'vlan'           => '100',
                'encapsulamiento'=> 'IPoE',
                'ultima_potencia_nap'    => '-17.5',
                'ultima_potencia_equipo' => '-21.8',
                'plan_anterior'  => '30 Mbps',
                'plan_nuevo'     => '100 Mbps',
            ],
        ];

        $this->reporte = $mockData[$folio]
            ?? $mockData['REP-2026-0001'];

        // Calcular horas transcurridas desde apertura (automático)
        try {
            $this->horasAtencion = Carbon::parse($this->reporte['fecha'])->diffInHours(now());
        } catch (\Throwable) {
            $this->horasAtencion = '—';
        }
    }

    // ── Helpers de tipo ───────────────────────────────────────────────

    public function tieneTV(): bool
    {
        return in_array($this->reporte['tipo_servicio'] ?? '', ['TV', 'TV+INTERNET']);
    }

    public function tieneInternet(): bool
    {
        return in_array($this->reporte['tipo_servicio'] ?? '', ['INTERNET', 'TV+INTERNET']);
    }

    public function esFalla(): bool
    {
        return in_array($this->reporte['tipo_reporte'] ?? '', ['FALLA_TV', 'FALLA_INTERNET', 'FALLA_TV_INTERNET']);
    }

    public function esInstalacion(): bool
    {
        return ($this->reporte['tipo_reporte'] ?? '') === 'INSTALACION';
    }

    public function esCambioDomicilio(): bool
    {
        return ($this->reporte['tipo_reporte'] ?? '') === 'CAMBIO_DOMICILIO';
    }

    public function esSuspension(): bool
    {
        return ($this->reporte['tipo_reporte'] ?? '') === 'SUSPENSION';
    }

    public function esCancelacion(): bool
    {
        return ($this->reporte['tipo_reporte'] ?? '') === 'CANCELACION';
    }

    public function esRecuperacion(): bool
    {
        return ($this->reporte['tipo_reporte'] ?? '') === 'RECUPERACION';
    }

    public function esServicioAdicional(): bool
    {
        return ($this->reporte['tipo_reporte'] ?? '') === 'SERVICIO_ADICIONAL_TV';
    }

    public function esAumentoVelocidad(): bool
    {
        return ($this->reporte['tipo_reporte'] ?? '') === 'AUMENTO_VELOCIDAD';
    }

    // ── Guardar asignación de equipo nuevo ────────────────────────────

    public function guardarCambioEquipo(): void
    {
        $this->validate([
            'equipoNuevo' => 'required',
        ], ['equipoNuevo.required' => 'Seleccione un equipo del almacén.']);

        // TODO: BD
        // DB::transaction(function () {
        //     $old = Equipo::where('cliente_id', $this->reporte['cliente_id'])->first();
        //     if ($old) { $old->update(['estado' => 'DEVUELTO', 'cliente_id' => null]); }
        //     $new = Equipo::find($this->equipoNuevo);
        //     $new->update(['cliente_id' => $this->reporte['cliente_id'], 'estado' => 'ASIGNADO',
        //                   'wifi' => $this->wifiNuevo, 'password' => $this->passwordNuevo,
        //                   'vlan' => $this->vlanNueva, 'encapsulamiento' => $this->encapsulamientoNuevo]);
        //     Comodato::generarAutomatico($this->reporte['cliente_id'], $new->id);
        //     SmsService::enviar($responsable->telefono, "Equipo listo para técnico {$this->reporte['folio']}");
        // });

        $this->toastExito('Equipo asignado. Comodato generado automáticamente. SMS enviado al responsable de sucursal.');
    }

    // ── Precierre (guarda avance sin cerrar el reporte) ───────────────

    public function guardarPrecierre(): void
    {
        // TODO: ReporteServicio::where('folio', $this->reporte['folio'])->update(['estado' => 'En Proceso', ...]);
        $this->toastInfo('Avance guardado. El reporte permanece en proceso (Precierre).');
        $this->redirect(route('reportes.servicio'));
    }

    // ── Cambio de técnico ─────────────────────────────────────────────

    public function cambiarTecnico(): void
    {
        $this->validate([
            'nuevoTecnico'   => 'required',
            'motivoCambio'   => 'required',
            'passwordCambio' => 'required',
        ]);

        // TODO: validar password de sucursal/admin y registrar cambio con motivo
        // ReporteServicio::where('folio', $this->reporte['folio'])->update([
        //     'tecnico_id' => $this->nuevoTecnico,
        //     'motivo_cambio_tecnico' => $this->motivoCambio,
        // ]);
        // SmsService::enviar($nuevoTecnico->telefono, "Nuevo reporte asignado: {$this->reporte['folio']}");

        $this->mostrarCambioTecnico = false;
        $this->toastExito('Técnico reasignado. SMS enviado al nuevo responsable.');
    }

    // ── Cierre de suspensión ──────────────────────────────────────────

    public function cerrarSuspension(): void
    {
        // TODO:
        // Cliente::where('id', $this->reporte['cliente_id'])->update(['estado' => 'SUSPENDIDO']);
        // NapSalida::where(...)->update(['estado' => 'LIBRE']);
        // ReporteServicio::where('folio',...)->update(['estado' => 'Cerrado', 'horas' => $this->horasAtencion]);
        // SmsService::enviar($cliente->telefono, "Su servicio fue suspendido por falta de pago...");

        $this->toastExito('Suspensión registrada. Estado del cliente actualizado. SMS enviado.');
        $this->redirect(route('reportes.servicio'));
    }

    // ── Cierre de cancelación ─────────────────────────────────────────

    public function cerrarCancelacion(): void
    {
        // TODO:
        // Cliente::where('id', $this->reporte['cliente_id'])->update(['estado' => 'CANCELADO']);
        // Equipo::where('cliente_id', $this->reporte['cliente_id'])->update(['estado' => 'EN_ALMACEN', 'cliente_id' => null]);
        // NapSalida::where(...)->update(['estado' => 'LIBRE']);
        // SmsService::enviar($cliente->telefono, "Su servicio fue cancelado...");

        $this->toastExito('Cancelación registrada. Inventarios actualizados. SMS enviado al cliente.');
        $this->redirect(route('reportes.servicio'));
    }

    // ── Cierre de recuperación de equipo ──────────────────────────────

    public function cerrarRecuperacion(): void
    {
        if (!$this->equipoEntregado) {
            $this->addError('equipoEntregado', 'Debe confirmar la recuperación del equipo para cerrar el reporte.');
            return;
        }

        // TODO:
        // Equipo::where('serie', $this->serieRecuperada)->update(['estado' => 'EN_ALMACEN', 'cliente_id' => null]);
        // NapSalida::where(...)->update(['estado' => 'LIBRE']);
        // Cliente::where('id', $this->reporte['cliente_id'])->update(['estado' => 'CANCELADO']);

        $this->toastExito('Recuperación registrada. Equipo ingresado al inventario.');
        $this->redirect(route('reportes.servicio'));
    }

    // ── Cierre total del reporte (fallas, instalación, cambio domicilio, servicioAdicional, aumentoVelocidad) ──

    public function cerrarReporte(): void
    {
        $rules = ['solucionOpcion' => 'required'];

        // Instalación y ServicioAdicional requieren potencias y NAP/salida
        if ($this->esInstalacion() || $this->esServicioAdicional()) {
            $rules['napSeleccionada'] = 'required';
            $rules['salidaNap']       = 'required';
            $rules['potenciaNap']     = 'required';
            $rules['potenciaEquipo']  = 'required';
        }

        // Fallas y cambio domicilio requieren potencias
        if ($this->esFalla() || $this->esCambioDomicilio()) {
            $rules['potenciaNap']    = 'required';
            $rules['potenciaEquipo'] = 'required';
        }

        $this->validate($rules, [
            'potenciaNap.required'     => 'Registre la potencia óptica en la salida de la NAP.',
            'potenciaEquipo.required'  => 'Registre la potencia óptica antes del equipo.',
            'solucionOpcion.required'  => 'Seleccione la resolución del problema.',
            'napSeleccionada.required' => 'Seleccione la NAP de conexión.',
            'salidaNap.required'       => 'Seleccione la salida de la NAP.',
        ]);

        // Instalación: comodato debe estar firmado antes del cierre total
        if ($this->esInstalacion() && !$this->comodatoFirmado) {
            $this->addError('comodatoFirmado', 'El comodato debe estar firmado para cerrar el reporte de instalación.');
            return;
        }

        // TODO: DB::transaction(function() {
        //   ReporteServicio::where('folio',...)->update([
        //     'estado' => 'Cerrado', 'potencia_nap' => $this->potenciaNap,
        //     'potencia_equipo' => $this->potenciaEquipo, 'solucion' => $this->solucionOpcion,
        //     'descripcion_solucion' => $this->descripcionSolucion,
        //     'calificacion' => $this->calificacion,
        //     'horas_atencion' => $this->horasAtencion,
        //     ...checkboxes...
        //   ]);
        //
        //   Si esInstalacion():
        //     Cliente::where('id',...)->update(['estado' => 'ACTIVO', 'tarifa' => ..., 'servicio' => ...]);
        //     NapSalida::where(...)->update(['estado' => 'OCUPADA', 'cliente_id' => ...]);
        //     SmsService::enviar($cliente->telefono, "Bienvenido a Tu Visión Telecable. Su servicio está activo.");
        //
        //   Si esFalla() || esCambioDomicilio():
        //     SmsService::enviar($cliente->telefono, "Su reporte fue atendido. ¡Gracias por su preferencia!");
        // });

        $this->toastExito('Reporte cerrado exitosamente. Cliente notificado por SMS.');
        $this->redirect(route('reportes.servicio'));
    }

    // ── Exportar PDF del reporte ──────────────────────────────────────

    public function exportarPDF(): void
    {
        // TODO: return Pdf::loadView('pdf.reporte', ['reporte' => $this->reporte])->download("reporte-{$this->reporte['folio']}.pdf");
        $this->toastExito("Generando PDF del reporte {$this->reporte['folio']}...");
    }

    // ── Render ────────────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.gestion-clientes.atender-reporte', [
            'tieneTV'              => $this->tieneTV(),
            'tieneInternet'        => $this->tieneInternet(),
            'esFalla'              => $this->esFalla(),
            'esInstalacion'        => $this->esInstalacion(),
            'esCambioDomicilio'    => $this->esCambioDomicilio(),
            'esSuspension'         => $this->esSuspension(),
            'esCancelacion'        => $this->esCancelacion(),
            'esRecuperacion'       => $this->esRecuperacion(),
            'esServicioAdicional'  => $this->esServicioAdicional(),
            'esAumentoVelocidad'   => $this->esAumentoVelocidad(),
            // Catálogos (TODO: cargar desde BD)
            'catalogoNaps'    => [
                ['id' => 'NAP-OAX-01', 'nombre' => 'NAP-OAX-01', 'dir' => 'Poste 5, Juárez y Reforma'],
                ['id' => 'NAP-OAX-03', 'nombre' => 'NAP-OAX-03', 'dir' => 'Poste 8, Calle Alcalá'],
                ['id' => 'NAP-OAX-05', 'nombre' => 'NAP-OAX-05', 'dir' => 'Poste 12, Esquina Reforma'],
                ['id' => 'NAP-SPA-01', 'nombre' => 'NAP-SPA-01', 'dir' => 'Poste 3, Esquina Hidalgo'],
            ],
            'catalogoOlts'    => [
                ['id' => 'OLT-01', 'nombre' => 'OLT-01 PRINCIPAL'],
                ['id' => 'OLT-02', 'nombre' => 'OLT-02 NORTE'],
            ],
            'catalogoTecnicos'=> [
                ['id' => 'tec-1', 'nombre' => 'ING. ROBERTO GÓMEZ'],
                ['id' => 'tec-2', 'nombre' => 'ING. ANA MARTÍNEZ'],
                ['id' => 'cua-1', 'nombre' => 'CUADRILLA 1'],
                ['id' => 'cua-2', 'nombre' => 'CUADRILLA 2'],
                ['id' => 'cua-3', 'nombre' => 'CUADRILLA 3'],
            ],
            'catalogoEquipos' => [
                ['id' => 'onu-h1', 'label' => 'ONU Huawei HG8010M — Serie: HW2024-099'],
                ['id' => 'onu-h2', 'label' => 'ONU Huawei HG8310M — Serie: HW2024-100'],
                ['id' => 'onu-z1', 'label' => 'ONU ZTE F660 — Serie: ZTE2024-078'],
                ['id' => 'min-1',  'label' => 'Mininodo ARRIS — Serie: ARR2024-030'],
                ['id' => 'min-2',  'label' => 'Mininodo CALIX — Serie: CAL2024-015'],
            ],
            'solucionesOpciones' => [
                'Conector dañado — cambio de conector',
                'Fibra rota — empalme/reparación',
                'Cambio de equipo — daño eléctrico',
                'Reconfiguración de ONU/router',
                'Splitter dañado — cambio de splitter',
                'Reasignación de puerto NAP',
                'Instalación completada exitosamente',
                'Actualización de firmware ONU',
                'Problema resuelto en OLT',
                'Otro — ver notas del técnico',
            ],
        ]);
    }
}
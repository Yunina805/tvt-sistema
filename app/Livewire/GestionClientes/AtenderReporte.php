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
    public bool   $tecnicoCompletado             = false;
    public string $notasSuspension               = '';

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
    public bool   $acometidaLiberada      = false;
    public string $salidaNapLibreRec      = '';
    public string $recuperaEquipoRec      = '';   // 'si' | 'no'
    public bool   $desconexionWinboxRec   = false;
    public bool   $desconexionOLTRec      = false;
    public bool   $ipLiberada             = false;
    public bool   $vlanLiberada           = false;
    public bool   $sesionLiberada         = false;
    public bool   $pagoPerdidaRec         = false;
    public bool   $pagoDanoRec            = false;
    public string $motivoPrecierreRec     = '';
    public bool   $tecnicoCompletadoRec   = false;

    // ── Instalación — comodato ────────────────────────────────────────
    public bool   $comodatoFirmado        = false;

    // ── Cambio de Servicio — recuperación equipo anterior ────────────
    public string $recuperoEquipoCambio  = '';   // 'si' | 'no'
    public string $serieCambioRecuperada = '';
    public bool   $pagoPerdidaCambio     = false;
    public bool   $pagoDanoCambio        = false;

    // ── Reconexión — validación equipo ────────────────────────────────
    public string $conservaEquipo   = '';   // 'si' | 'no'
    public string $serieConservada  = '';

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

    // ── Precierre (dd.) ───────────────────────────────────────────────
    public string $motivoPrecierre       = '';

    // ── Precierre de cancelación ──────────────────────────────────────
    public string $motivoPrecierreCancel = '';

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
                'telefono'       => '9511234567',
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
                'telefono'       => '9524567890',
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
            // CAMBIO DE SERVICIO — Cliente activo
            'REP-2026-0006' => [
                'folio'             => 'REP-2026-0006',
                'fecha'             => '2026-03-11 12:00',
                'sucursal'          => 'Oaxaca Centro',
                'cliente'           => 'ROBERTO DÍAZ LUNA',
                'id_cliente'        => 'OAX-0015678',
                'telefono'          => '9512468135',
                'domicilio'         => 'Calle Armenta y López 88, Col. Centro. Ref: Casa azul con reja blanca.',
                'estado_cliente'    => 'Activo',
                'quien_reporto'     => 'Sistema',
                'tipo_reporte'      => 'CAMBIO_SERVICIO',
                'tipo_servicio'     => 'TV+INTERNET',           // nuevo servicio
                'servicio'          => 'TV + Internet 100 Mbps', // nuevo servicio contratado
                'servicio_anterior' => 'Internet 30 Mbps',      // servicio que tenía
                'equipo_anterior'   => 'ONU ZTE F660 (Serie: ZTE2024-012)',
                'tecnico'           => 'Cuadrilla 1',
                'nap'               => '—',
                'dir_nap'           => '— Asignar al instalar —',
                'salidas_nap_disponibles' => ['1', '2', '3', '5'],
                'info_equipo'       => 'ONU Huawei HG8010M (Sin asignar)',
                'ip'                => '—',
                'wifi'              => '—',
                'password_wifi'     => '—',
                'olt'               => '—',
                'pon'               => '—',
                'vlan'              => '100',
                'encapsulamiento'   => 'IPoE',
                'ultima_potencia_nap'    => '—',
                'ultima_potencia_equipo' => '—',
                'saldo_favor'       => 120.00,
            ],
            // INSTALACION — Solo Internet (ONU)
            'REP-2026-0005' => [
                'folio'          => 'REP-2026-0005',
                'fecha'          => '2026-03-11 11:00',
                'sucursal'       => 'Oaxaca Norte',
                'cliente'        => 'GABRIELA RÍOS SANTOS',
                'id_cliente'     => 'OAN-PENDIENTE',
                'telefono'       => '9513579246',
                'domicilio'      => 'Periférico Norte 455, Col. Industrial. Ref: Portón negro, frente a la refaccionaria.',
                'estado_cliente' => 'Pendiente de Instalación',
                'quien_reporto'  => 'Sistema',
                'tipo_reporte'   => 'INSTALACION',
                'tipo_servicio'  => 'INTERNET',
                'servicio'       => 'Internet 100 Mbps',
                'tecnico'        => 'Cuadrilla 2',
                'nap'            => '—',
                'dir_nap'        => '— Asignar al instalar —',
                'salidas_nap_disponibles' => ['1', '2', '3', '4', '5', '6'],
                'info_equipo'    => 'ONU Huawei HG8310M (Serie: HW2026-010)',
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
            // INSTALACION — Solo Televisión (mininodo)
            'REP-2026-0003' => [
                'folio'          => 'REP-2026-0003',
                'fecha'          => '2026-03-11 09:00',
                'sucursal'       => 'Oaxaca Centro',
                'cliente'        => 'CARMEN FUENTES ALBA',
                'id_cliente'     => 'OAX-PENDIENTE',
                'telefono'       => '9519876543',
                'domicilio'      => 'Calle Bustamante 34, Col. Centro. Ref: Casa verde con portón de madera.',
                'estado_cliente' => 'Pendiente de Instalación',
                'quien_reporto'  => 'Sistema',
                'tipo_reporte'   => 'INSTALACION',
                'tipo_servicio'  => 'TV',
                'servicio'       => 'Retro TV',
                'tecnico'        => 'Cuadrilla 2',
                'nap'            => '—',
                'dir_nap'        => '— Asignar al instalar —',
                'salidas_nap_disponibles' => ['1', '2', '3', '4', '5', '6'],
                'info_equipo'    => 'Mininodo ARRIS (Serie: ARR2026-045)',
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
            'REP-2026-0004' => [
                'folio'          => 'REP-2026-0004',
                'fecha'          => '2026-02-22 11:15',
                'sucursal'       => 'Oaxaca Norte',
                'cliente'        => 'ROSA MARTÍNEZ DÍAZ',
                'id_cliente'     => 'OAN-0055234',
                'telefono'       => '9513456789',
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
            'REC-2026-0001' => [
                'folio'          => 'REC-2026-0001',
                'fecha'          => '2026-03-10 06:00',
                'sucursal'       => 'Oaxaca Centro',
                'cliente'        => 'PEDRO ARMENDÁRIZ RUIZ',
                'id_cliente'     => '01-0004567',
                'telefono'       => '9518765432',
                'domicilio'      => 'Av. Juárez 145, Col. Centro. Ref: Casa de dos pisos, portón negro.',
                'estado_cliente' => 'Suspendido por falta de pago',
                'quien_reporto'  => 'Sistema Automático',
                'tipo_reporte'   => 'RECUPERACION',
                'tipo_servicio'  => 'TV+INTERNET',
                'servicio'       => 'TV + Internet 30 Mbps',
                'falla_reportada'=> 'RECUPERACIÓN DE EQUIPO — 91 DÍAS DE ADEUDO',
                'tecnico'        => 'Cuadrilla 1',
                'nap'            => 'NAP-OAX-03',
                'dir_nap'        => 'Poste 8, Calle Alcalá',
                'salidas_nap_disponibles' => [],
                'info_equipo'    => 'ONU ZTE F660 (Serie: ZTE2023-001)',
                'ip'             => '192.168.10.45',
                'wifi'           => 'TuVision_Pedro',
                'password_wifi'  => 'ped12345',
                'olt'            => 'OLT-01',
                'pon'            => 'PON/0/3',
                'vlan'           => '100',
                'encapsulamiento'=> 'IPoE',
                'ultima_potencia_nap'    => '-18.0',
                'ultima_potencia_equipo' => '-22.1',
                'saldo_pendiente'=> 1920.00,
                'dias_suspension'=> 91,
                'soporta_remoto' => false,
            ],
            'SUP-2026-0001' => [
                'folio'          => 'SUP-2026-0001',
                'fecha'          => '2026-03-10 07:30',
                'sucursal'       => 'Oaxaca Centro',
                'cliente'        => 'PEDRO ARMENDÁRIZ RUIZ',
                'id_cliente'     => '01-0004567',
                'telefono'       => '9518765432',
                'domicilio'      => 'Av. Juárez 145, Col. Centro. Ref: Casa de dos pisos, portón negro.',
                'estado_cliente' => 'Activo',
                'quien_reporto'  => 'Sistema Automático',
                'tipo_reporte'   => 'SUSPENSION',
                'tipo_servicio'  => 'TV+INTERNET',
                'servicio'       => 'TV + Internet 30 Mbps',
                'falla_reportada'=> 'SUSPENSIÓN POR FALTA DE PAGO — 54 DÍAS DE ADEUDO',
                'tecnico'        => 'Sucursal (corte remoto)',
                'nap'            => 'NAP-OAX-03',
                'dir_nap'        => 'Poste 8, Calle Alcalá',
                'salidas_nap_disponibles' => [],
                'info_equipo'    => 'ONU ZTE F660 (Serie: ZTE2023-045)',
                'ip'             => '192.168.10.45',
                'wifi'           => 'TuVision_Pedro',
                'password_wifi'  => 'ped12345',
                'olt'            => 'OLT-01',
                'pon'            => 'PON/0/3',
                'vlan'           => '100',
                'encapsulamiento'=> 'IPoE',
                'ultima_potencia_nap'    => '-18.0',
                'ultima_potencia_equipo' => '-22.1',
                'saldo_pendiente'=> 960.00,
                'dias_suspension'=> 54,
                'soporta_remoto' => true,
            ],
            'SUP-2026-0002' => [
                'folio'          => 'SUP-2026-0002',
                'fecha'          => '2026-02-24 08:45',
                'sucursal'       => 'San Pedro Amuzgos',
                'cliente'        => 'LUIS HERNÁNDEZ VEGA',
                'id_cliente'     => 'SPA-0003312',
                'telefono'       => '9527654321',
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
                'telefono'       => '9512345678',
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
                'telefono'       => '9516543210',
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
                'telefono'       => '9519012345',
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
            'REP-2026-0013' => [
                'folio'          => 'REP-2026-0013',
                'fecha'          => '2026-03-11 17:00',
                'sucursal'       => 'Oaxaca Norte',
                'cliente'        => 'MARCOS SANTILLÁN RUIZ',
                'id_cliente'     => 'OAN-0031045',
                'telefono'       => '9514321098',
                'domicilio'      => 'Periférico Norte 201, Col. Volcanes. Ref: Casa blanca con reja verde, frente a la primaria.',
                'estado_cliente' => 'Pendiente de Cambio de Servicio',
                'quien_reporto'  => 'Administración',
                'tipo_reporte'   => 'RECONEXION_CAMBIO',
                'tipo_servicio'  => 'TV+INTERNET',             // nuevo servicio
                'falla_reportada'=> 'RECONEXIÓN — CAMBIO A OTRO SERVICIO',
                'servicio'       => 'TV + Internet 50 Mbps',   // nuevo servicio
                'servicio_anterior'  => 'Internet 30 Mbps',    // servicio previo
                'tarifa_anterior'    => '$280.00 / mes',
                'tarifa_nueva'       => '$420.00 / mes',
                'equipo_anterior'    => 'ONU Huawei HG8310M (Serie: HW2023-155)',
                'tecnico'        => 'Cuadrilla 2',
                'nap'            => '—',
                'dir_nap'        => '— Asignar al instalar —',
                'nap_anterior'   => 'NAP-OAN-05',
                'dir_nap_anterior'   => 'Poste 18, Av. Ferrocarril Norte',
                'salida_nap_anterior'=> '4',
                'metros_acometida_anterior' => '22',
                'salidas_nap_disponibles' => ['1', '2', '3', '4', '5'],
                'info_equipo'    => 'ONU Huawei HG8310M (Sin asignar)',
                'ip'             => '—',
                'wifi'           => '—',
                'password_wifi'  => '—',
                'olt'            => '—',
                'pon'            => '—',
                'vlan'           => '100',
                'encapsulamiento'=> 'IPoE',
                'ultima_potencia_nap'    => '-17.8',
                'ultima_potencia_equipo' => '-21.5',
                'saldo_pendiente'=> 0.00,
                'dias_suspension'=> 45,
                'fecha_suspension'=> '2026-01-25',
            ],
            'REP-2026-0012' => [
                'folio'          => 'REP-2026-0012',
                'fecha'          => '2026-03-11 16:00',
                'sucursal'       => 'Oaxaca Centro',
                'cliente'        => 'ELENA VARGAS MORALES',
                'id_cliente'     => 'OAX-0008901',
                'telefono'       => '9516789012',
                'domicilio'      => 'Calle Murguía 55, Col. Centro. Ref: Casa rosa, escalones de cantera.',
                'estado_cliente' => 'Suspendido',
                'quien_reporto'  => 'Administración',
                'tipo_reporte'   => 'RECONEXION',
                'tipo_servicio'  => 'TV+INTERNET',
                'falla_reportada'=> 'RECONEXIÓN — MISMO SERVICIO',
                'servicio'       => 'TV + Internet 30 Mbps',
                'tecnico'        => 'Cuadrilla 1',
                'nap'            => 'NAP-OAX-02',
                'dir_nap'        => 'Poste 9, Av. Independencia',
                'salidas_nap_disponibles' => ['2', '5', '6'],
                'info_equipo'    => 'ONU ZTE F660 (Serie: ZTE2024-099)',
                'ip'             => '192.168.10.71',
                'wifi'           => 'TuVision_Elena',
                'password_wifi'  => 'ele56789',
                'olt'            => 'OLT-01',
                'pon'            => 'PON/0/5',
                'vlan'           => '100',
                'encapsulamiento'=> 'IPoE',
                'ultima_potencia_nap'    => '-18.3',
                'ultima_potencia_equipo' => '-22.0',
                'saldo_pendiente'=> 0.00,
                'dias_suspension'=> 32,
                'fecha_suspension'=> '2026-02-08',
            ],
            'REP-2026-0011' => [
                'folio'          => 'REP-2026-0011',
                'fecha'          => '2026-02-27 09:00',
                'sucursal'       => 'Oaxaca Norte',
                'cliente'        => 'ANDRÉS VILLA RUIZ',
                'id_cliente'     => 'OAN-0077654',
                'telefono'       => '9513579246',
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

    public function esCambioServicio(): bool
    {
        return ($this->reporte['tipo_reporte'] ?? '') === 'CAMBIO_SERVICIO';
    }

    public function esReconexion(): bool
    {
        return ($this->reporte['tipo_reporte'] ?? '') === 'RECONEXION';
    }

    public function esReconexionCambio(): bool
    {
        return ($this->reporte['tipo_reporte'] ?? '') === 'RECONEXION_CAMBIO';
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
        if (!$this->motivoPrecierre) {
            $this->addError('motivoPrecierre', 'Seleccione el motivo del precierre.');
            return;
        }

        // TODO: ReporteServicio::where('folio', $this->reporte['folio'])->update([
        //     'estado' => 'En Proceso',
        //     'motivo_precierre' => $this->motivoPrecierre,
        //     'descripcion_solucion' => $this->descripcionSolucion,
        // ]);
        $this->toastInfo('Avance guardado. El reporte permanece en proceso (Precierre: ' . $this->motivoPrecierre . ').');
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

    // ── Cierre TÉCNICO de suspensión (guarda avance, habilita cierre admin) ──

    public function guardarAvanceSuspension(): void
    {
        $soloTV = $this->tieneTV() && !$this->tieneInternet();
        $usaFisica = $soloTV
            || ($this->tieneTV() && $this->tieneInternet() && !($this->reporte['soporta_remoto'] ?? false));
        $usaLogica = !$usaFisica;

        if ($usaLogica && !$this->confirmacionWinbox) {
            $this->addError('confirmacionWinbox', 'Confirme la desconexión en Winbox.');
            return;
        }
        if ($usaLogica && !$this->confirmacionOLT) {
            $this->addError('confirmacionOLT', 'Confirme el bloqueo en OLT.');
            return;
        }
        if ($usaFisica && !$this->confirmacionDesconexionFisica) {
            $this->addError('confirmacionDesconexionFisica', 'Confirme la desconexión física en NAP.');
            return;
        }

        // TODO: ReporteServicio::where('folio', $this->reporte['folio'])->update([
        //     'estado'               => 'En Proceso',
        //     'tecnico_completado'   => true,
        //     'notas_suspension'     => $this->notasSuspension,
        //     'salida_nap_liberada'  => $this->salidaNapLibre,
        // ]);
        // if ($usaFisica && $this->salidaNapLibre) {
        //     NapSalida::where('nap', $this->reporte['nap'])
        //               ->where('numero', $this->salidaNapLibre)
        //               ->update(['estado' => 'LIBRE', 'cliente_id' => null]);
        // }

        $this->tecnicoCompletado = true;
        $this->toastInfo('Acciones técnicas confirmadas. El cierre administrativo ya está disponible.');
    }

    // ── Cierre ADMINISTRATIVO de suspensión (requiere confirmación técnica) ──

    public function cerrarSuspension(): void
    {
        if (!$this->tecnicoCompletado) {
            $this->addError('tecnicoCompletado', 'El técnico debe guardar el avance técnico antes del cierre administrativo.');
            return;
        }

        // TODO: DB::transaction(function() {
        //   Cliente::where('id', $this->reporte['cliente_id'])
        //          ->update(['estado' => 'SUSPENDIDO']);
        //
        //   // Liberar recursos de red
        //   if ($usaFisica && $this->salidaNapLibre) {
        //       NapSalida::where(...)->update(['estado' => 'LIBRE', 'cliente_id' => null]);
        //   }
        //   if ($usaLogica) {
        //       // Marcar sesiones/puertos como libres en el sistema
        //       SesionRed::where('cliente_id', $this->reporte['cliente_id'])->update(['activa' => false]);
        //   }
        //
        //   ReporteServicio::where('folio', $this->reporte['folio'])->update([
        //       'estado'          => 'Cerrado',
        //       'horas_atencion'  => $this->horasAtencion,
        //       'calificacion'    => $this->calificacion,
        //       'notas'           => $this->notasSuspension,
        //       'cerrado_admin'   => now(),
        //   ]);
        //
        //   // Pausar ciclo de facturación — no generar cargos mientras esté SUSPENDIDO
        //   CicloFacturacion::where('cliente_id', $this->reporte['cliente_id'])
        //                    ->update(['pausado' => true, 'motivo_pausa' => 'SUSPENSION_ADEUDO']);
        //
        //   SmsService::enviar($cliente->telefono,
        //       "Tu Vision Telecable: Su servicio ha sido SUSPENDIDO por falta de pago. " .
        //       "Para reactivar liquide su adeudo y contacte a su sucursal.");
        // });

        $this->toastExito('Suspensión aplicada. Estado → SUSPENDIDO. Recursos liberados. Facturación pausada. SMS enviado.');
        $this->redirect(route('reportes.servicio'));
    }

    // ── Precierre de cancelación ──────────────────────────────────────

    public function guardarPrecierreCancel(string $motivo = ''): void
    {
        if (!$motivo) {
            $this->addError('precierreCancel', 'Seleccione el motivo del precierre.');
            return;
        }

        // TODO: ReporteServicio::where('folio', $this->reporte['folio'])->update([
        //     'estado'           => 'En Proceso',
        //     'motivo_precierre' => $motivo,
        // ]);

        $this->toastInfo('Avance guardado. Reporte en proceso — Precierre: ' . $motivo);
        $this->redirect(route('reportes.servicio'));
    }

    // ── Cierre de cancelación ─────────────────────────────────────────

    public function cerrarCancelacion(
        string $recupera     = '',
        string $serie        = '',
        bool   $pagoPerdida  = false,
        bool   $desconFisica = false,
        bool   $bdWinbox     = false,
        bool   $bdOLT        = false,
        string $calificacion = 'Excelente'
    ): void {
        // Condición cierre admin: equipo recuperado O pago por pérdida confirmado
        $equipoOk = ($recupera === 'si') || ($recupera === 'no' && $pagoPerdida);
        if (!$equipoOk) {
            $this->addError('cerrarCancelacion',
                'Para cerrar: el equipo debe estar físicamente en sucursal o el suscriptor debe haber pagado por la pérdida.');
            return;
        }

        // TODO: DB::transaction(function() {
        //   // 1. Cambiar estado del suscriptor → CANCELADO
        //   Cliente::where('id', $this->reporte['cliente_id'])->update(['estado' => 'CANCELADO']);
        //
        //   // 2. Liberar salida NAP
        //   NapSalida::where('nap', $this->reporte['nap'])
        //             ->where('cliente_id', $this->reporte['cliente_id'])
        //             ->update(['estado' => 'LIBRE', 'cliente_id' => null]);
        //
        //   // 3. Actualizar inventario del equipo
        //   if ($this->equipoRecuperado) {
        //       Equipo::where('serie', $this->serieConfirmada ?: $this->reporte['info_equipo'])
        //             ->update(['estado' => 'EN_ALMACEN', 'cliente_id' => null]);
        //   } elseif ($this->equipoPerdido) {
        //       Equipo::where('cliente_id', $this->reporte['cliente_id'])
        //             ->update(['estado' => 'PAGADO_POR_PERDIDA', 'cliente_id' => null]);
        //   }
        //
        //   // 4. Detener facturación
        //   CicloFacturacion::where('cliente_id', $this->reporte['cliente_id'])
        //                    ->update(['activo' => false, 'motivo_baja' => 'CANCELACION_VOLUNTARIA']);
        //
        //   // 5. Cerrar reporte
        //   ReporteServicio::where('folio', $this->reporte['folio'])->update([
        //       'estado'        => 'Cerrado',
        //       'calificacion'  => $this->calificacion,
        //       'horas_atencion'=> $this->horasAtencion,
        //       'cerrado_admin' => now(),
        //   ]);
        //
        //   // 6. SMS al suscriptor
        //   SmsService::enviar($cliente->telefono,
        //       "Tu Vision Telecable: Su servicio ha sido CANCELADO exitosamente. " .
        //       "Gracias por haber sido nuestro suscriptor.");
        // });

        $this->toastExito('Cancelación aplicada. Estado → CANCELADO. NAP liberada. Inventario actualizado. SMS enviado.');
        $this->redirect(route('reportes.servicio'));
    }

    // ── Guardar precierre de recuperación ────────────────────────────

    public function guardarPrecierreRecuperacion(): void
    {
        if (!$this->motivoPrecierreRec) {
            $this->addError('motivoPrecierreRec', 'Seleccione el motivo del precierre.');
            return;
        }

        // TODO: ReporteServicio::where('folio', $this->reporte['folio'])->update([
        //     'estado'           => 'En Proceso',
        //     'motivo_precierre' => $this->motivoPrecierreRec,
        // ]);

        $this->toastInfo('Avance guardado. Reporte en proceso — Precierre: ' . $this->motivoPrecierreRec);
    }

    // ── Cierre TÉCNICO de recuperación ───────────────────────────────

    public function guardarAvanceRecuperacion(string $equipoRecVal = ''): void
    {
        // Recibir el valor del radio desde Alpine (no wire:model para evitar re-renders)
        $this->recuperaEquipoRec = $equipoRecVal;

        if (!$this->desconexionFisicaRec) {
            $this->addError('desconexionFisicaRec', 'Confirme la desconexión física del servicio en NAP.');
            return;
        }

        if (!$this->recuperaEquipoRec) {
            $this->addError('recuperaEquipoRec', 'Indique si el equipo fue recuperado.');
            return;
        }

        if ($this->recuperaEquipoRec === 'no' && !$this->pagoPerdidaRec && !$this->pagoDanoRec) {
            $this->addError('pagoPerdidaRec',
                'El suscriptor debe pagar por pérdida o daño del equipo. Si no paga, use Guardar Precierre.');
            return;
        }

        if ($this->tieneInternet()) {
            if (!$this->desconexionWinboxRec || !$this->desconexionOLTRec) {
                $this->addError('desconexionOLTRec', 'Complete las bajas lógicas (Winbox y OLT) antes de continuar.');
                return;
            }
        }

        // ─────────────────────────────────────────────────────────────
        // TODO: DB::transaction(function() {
        //   ReporteServicio::where('folio', $this->reporte['folio'])->update([
        //       'estado'                => 'En Proceso',
        //       'tecnico_completado'    => true,
        //       'desconexion_fisica'    => $this->desconexionFisicaRec,
        //       'acometida_liberada'    => $this->acometidaLiberada,
        //       'salida_nap_libre'      => $this->salidaNapLibreRec,
        //       'recupero_equipo'       => $this->recuperaEquipoRec === 'si',
        //       'serie_recuperada'      => $this->serieRecuperada,
        //       'baja_winbox'           => $this->desconexionWinboxRec,
        //       'baja_olt'              => $this->desconexionOLTRec,
        //       'ip_liberada'           => $this->ipLiberada,
        //       'vlan_liberada'         => $this->vlanLiberada,
        //       'sesion_liberada'       => $this->sesionLiberada,
        //   ]);
        //
        //   // Actualizar inventario según caso
        //   if ($this->recuperaEquipoRec === 'si') {
        //       Equipo::where('serie', $this->serieRecuperada)
        //             ->update(['estado' => 'EN_ALMACEN', 'cliente_id' => null]);
        //   } elseif ($this->pagoPerdidaRec || $this->pagoDanoRec) {
        //       $estado = $this->pagoPerdidaRec ? 'PAGADO_POR_PERDIDA' : 'PAGADO_POR_DANO';
        //       Equipo::where('serie', $this->reporte['info_equipo'])
        //             ->update(['estado' => $estado, 'cliente_id' => null]);
        //   }
        //
        //   // Liberar salida NAP
        //   if ($this->salidaNapLibreRec) {
        //       NapSalida::where('nap', $this->reporte['nap'])
        //                 ->where('numero', $this->salidaNapLibreRec)
        //                 ->update(['estado' => 'LIBRE', 'cliente_id' => null]);
        //   }
        // });
        // ─────────────────────────────────────────────────────────────

        $this->tecnicoCompletadoRec = true;
        $this->toastInfo('Cierre técnico guardado. El cierre administrativo ya está disponible.');
    }

    // ── Cierre ADMINISTRATIVO de recuperación ─────────────────────────

    public function cerrarRecuperacion(): void
    {
        if (!$this->tecnicoCompletadoRec) {
            $this->addError('tecnicoCompletadoRec', 'Complete el cierre técnico antes del cierre administrativo.');
            return;
        }

        // ─────────────────────────────────────────────────────────────
        // TODO: DB::transaction(function() {
        //   // 1. Cambiar estado del suscriptor → CANCELADO POR MOROSIDAD
        //   Cliente::where('id', $this->reporte['cliente_id'])
        //          ->update(['estado' => 'CANCELADO_MOROSIDAD']);
        //
        //   // 2. Cancelar servicio activo + bloquear reactivación automática
        //   Servicio::where('cliente_id', $this->reporte['cliente_id'])
        //           ->update(['activo' => false, 'bloqueado' => true, 'fecha_baja' => now()]);
        //
        //   // 3. Detener facturación — cancelar ciclos futuros
        //   CicloFacturacion::where('cliente_id', $this->reporte['cliente_id'])
        //                    ->update(['activo' => false, 'motivo_baja' => 'CANCELADO_MOROSIDAD']);
        //
        //   // 4. Liberar recursos de red
        //   NapSalida::where('cliente_id', $this->reporte['cliente_id'])->update(['estado' => 'LIBRE', 'cliente_id' => null]);
        //   // IP, VLAN, sesión ya liberadas en cierre técnico
        //
        //   // 5. Cerrar reporte
        //   ReporteServicio::where('folio', $this->reporte['folio'])->update([
        //       'estado'        => 'Cerrado',
        //       'calificacion'  => $this->calificacion,
        //       'horas_atencion'=> $this->horasAtencion,
        //       'cerrado_admin' => now(),
        //   ]);
        //
        //   // 6. SMS al suscriptor
        //   SmsService::enviar($cliente->telefono,
        //       "Tu Vision Telecable: Su servicio ha sido CANCELADO por morosidad. " .
        //       "El equipo ha sido recuperado. Su historial queda registrado en nuestro sistema.");
        // });
        // ─────────────────────────────────────────────────────────────

        $this->toastExito('Recuperación aplicada. Estado → CANCELADO POR MOROSIDAD. Recursos liberados. SMS enviado.');
        $this->redirect(route('reportes.servicio'));
    }

    // ── Cierre total del reporte (fallas, instalación, cambio domicilio, servicioAdicional, aumentoVelocidad) ──

    public function cerrarReporte(): void
    {
        $rules = ['solucionOpcion' => 'required'];

        // Instalación, ServicioAdicional, CambioServicio, Reconexión y ReconexiónCambio requieren potencias y NAP/salida
        if ($this->esInstalacion() || $this->esServicioAdicional() || $this->esCambioServicio() || $this->esReconexion() || $this->esReconexionCambio()) {
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

        // Instalación, CambioServicio, Reconexión y ReconexiónCambio: comodato requerido
        if (($this->esInstalacion() || $this->esCambioServicio() || $this->esReconexion() || $this->esReconexionCambio()) && !$this->comodatoFirmado) {
            $this->addError('comodatoFirmado', 'El comodato del nuevo equipo debe estar firmado para cerrar el reporte.');
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
            'esCambioServicio'     => $this->esCambioServicio(),
            'esReconexion'         => $this->esReconexion(),
            'esReconexionCambio'   => $this->esReconexionCambio(),
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
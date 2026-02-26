<?php

namespace App\Livewire\GestionClientes;

use Livewire\Component;

class AtenderReporte extends Component
{
    // Datos del reporte (cargados desde BD en mount)
    public $reporte = [];

    // Variables generales de Cierre
    public $cambioEquipo     = false;
    public $equipoNuevo      = '';
    public $wifiNuevo        = '';
    public $passwordNuevo    = '';
    public $vlanNueva        = '';
    public $encapsulamientoNuevo = '';
    public $potenciaNap      = '';
    public $potenciaEquipo   = '';
    public $solucionOpcion   = '';
    public $descripcionSolucion = '';
    public $calificacion     = 'Excelente';
    public $horasAtencion    = null;

    // Variables específicas (TV)
    public $pruebaCanales    = false;
    public $cantidadCanales  = '';

    // Variables específicas (Internet / TV+Internet)
    public $ledsVerdes            = false;
    public $detectoWifiOriginal   = false;
    public $configuroWifiDefault  = false;
    public $asignoNuevaPass       = '';
    public $accesoInternet        = false;
    public $velocidadRegistrada   = '';
    public $confirmoOlt           = false;
    public $confirmoPon           = false;

    // Variables específicas (Cambio Domicilio)
    public $metrosAcometida      = '';
    public $metodoPagoCambioDom  = 'efectivo';

    // Variables específicas (Suspensión)
    public $confirmacionDesconexionFisica = false;
    public $confirmacionWinbox            = false;
    public $confirmacionOLT               = false;
    public $salidaNapLibre                = '';

    public function mount($folio = 'REP-2026-0001')
    {
        // ---------------------------------------------------------------
        // SIMULACIÓN: Reemplaza este array con:
        //   $data = Reporte::with('cliente','tecnico','equipo','nap')
        //                  ->where('folio', $folio)->firstOrFail();
        //   $this->reporte = $data->toArray();   (y ajusta claves)
        //
        // CLAVE IMPORTANTE: 'tipo_reporte' define QUÉ tipo de operación es
        //   (FALLA_TV | FALLA_INTERNET | FALLA_TV_INTERNET | CAMBIO_DOMICILIO
        //    | INSTALACION | SUSPENSION | CANCELACION | RECUPERACION)
        //
        // 'tipo_servicio' define QUÉ servicio tiene el cliente
        //   (TV | INTERNET | TV+INTERNET)
        // ---------------------------------------------------------------
        $this->reporte = [
            // ── Identificación ─────────────────────────────────────────
            'folio'            => $folio,
            'fecha'            => '2026-02-25 09:00',
            'sucursal'         => 'Oaxaca Centro',

            // ── Cliente ────────────────────────────────────────────────
            'cliente'          => 'JUAN PÉREZ GARCÍA',
            'domicilio'        => 'Av. Independencia 102. Ref: Casa Azul',
            'estado_cliente'   => 'Activo',
            'quien_reporto'    => 'Cliente',            // 'Cliente' | 'Personal TVT'

            // ── Tipo de operación (cambia esto para probar distintas vistas)
            //   FALLA_TV | FALLA_INTERNET | FALLA_TV_INTERNET
            //   CAMBIO_DOMICILIO | INSTALACION | SUSPENSION | CANCELACION | RECUPERACION
            'tipo_reporte'     => 'FALLA_TV_INTERNET',

            // ── Servicio del cliente (determina qué checklist se muestra)
            //   TV | INTERNET | TV+INTERNET
            'tipo_servicio'    => 'TV+INTERNET',
            'servicio'         => 'Combo Total (TV + Internet)',
            'falla_reportada'  => 'FALLA EN SERVICIO DE TELEVISIÓN + INTERNET',

            // ── Técnico asignado ───────────────────────────────────────
            'tecnico'          => 'Cuadrilla 1',

            // ── Red / Infraestructura ──────────────────────────────────
            'nap'              => 'NAP-OAX-05',
            'dir_nap'          => 'Poste 12, Esquina Reforma',

            // ── Equipo asignado al cliente ─────────────────────────────
            'info_equipo'      => 'ONU Huawei HG8010 (Serie: 45678)',

            // ── Configuración Internet (solo cuando tipo_servicio incluye Internet)
            'ip'               => '192.168.1.100',
            'wifi'             => 'TuVision_102',
            'password_wifi'    => 'pass1234',
            'olt'              => 'OLT-01',
            'pon'              => 'PON-03',
            'vlan'             => '100',
            'encapsulamiento'  => 'IPoE',

            // ── Últimas potencias registradas (historial) ──────────────
            'ultima_potencia_nap'    => '-18 dBm',
            'ultima_potencia_equipo' => '-22 dBm',

            // ── Cambio de domicilio (solo cuando tipo_reporte = CAMBIO_DOMICILIO)
            'costo_cambio_dom' => 300.00,
        ];

        // Calcular horas desde apertura (automático)
        try {
            $apertura = \Carbon\Carbon::parse($this->reporte['fecha']);
            $this->horasAtencion = $apertura->diffInHours(now());
        } catch (\Exception $e) {
            $this->horasAtencion = '—';
        }
    }

    public function guardarCambioEquipo()
    {
        $this->validate([
            'equipoNuevo' => 'required',
        ], [
            'equipoNuevo.required' => 'Debe seleccionar un equipo del almacén.',
        ]);

        // Lógica:
        // 1. Actualizar equipo asignado al cliente en BD
        // 2. Registrar devolución del equipo dañado en inventario
        // 3. Generar comodato automático con nuevos datos
        // 4. Enviar SMS al responsable de sucursal: "Equipo listo para técnico"
        // 5. Al confirmar entrega: enviar SMS al técnico

        session()->flash('mensaje', 'Equipo asignado. Comodato generado. SMS enviado al responsable de sucursal.');
    }

    public function guardarPrecierre()
    {
        // Guardar avance manteniendo estado "En Proceso"
        session()->flash('mensaje', 'Avance guardado. El reporte sigue en proceso (Precierre).');
        return redirect()->route('reportes.servicio');
    }

    public function cerrarReporte()
    {
        $this->validate([
            'potenciaNap'    => 'required',
            'potenciaEquipo' => 'required',
            'solucionOpcion' => 'required',
        ], [
            'potenciaNap.required'    => 'Registre la potencia óptica de salida NAP.',
            'potenciaEquipo.required' => 'Registre la potencia óptica antes del equipo.',
            'solucionOpcion.required' => 'Seleccione la resolución del problema.',
        ]);

        // Lógica de cierre:
        // 1. Actualizar estado del reporte a CERRADO
        // 2. Registrar horas de atención (automático desde apertura hasta ahora)
        // 3. Guardar potencias, checklist y solución
        // 4. Enviar SMS al cliente: "Su servicio ha sido atendido"
        // 5. Si notificarSms: enviar también al técnico

        session()->flash('mensaje', 'Reporte cerrado exitosamente. SMS enviado al cliente.');
        return redirect()->route('reportes.servicio');
    }

    public function cerrarSuspension()
    {
        // Lógica del manual:
        // 1. Afectar estado del cliente a SUSPENDIDO en BD
        // 2. Si Internet: deshabilitar en Winbox y OLT
        // 3. Liberar puerto en la NAP (registrar salida libre)
        // 4. Registrar horas transcurridas desde apertura
        // 5. Enviar SMS al cliente con aviso de suspensión

        session()->flash('mensaje', 'Suspensión procesada correctamente.');
        return redirect()->route('reportes.servicio');
    }

    public function render()
    {
        return view('livewire.gestion-clientes.atender-reporte')
            ->layout('layouts.app');
    }
}
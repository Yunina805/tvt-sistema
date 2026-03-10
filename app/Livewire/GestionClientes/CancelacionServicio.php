<?php

namespace App\Livewire\GestionClientes;

use App\Traits\WithToasts;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('layouts.app')]
class CancelacionServicio extends Component
{
    use WithToasts;

    // ── Pasos ─────────────────────────────────────────────────────────
    public int $paso = 1;

    // ── Búsqueda ──────────────────────────────────────────────────────
    public string $busqueda   = '';
    public array  $resultados = [];
    public ?array $cliente    = null;

    // ── Datos del reporte ─────────────────────────────────────────────
    public string $folioReporte    = '';
    public string $fechaReporte    = '';
    public int|string $horasAtencion = '—';

    // ── Asignación operativa ──────────────────────────────────────────
    public string $tecnicoAsignado = '';

    // ── Recuperación de equipos ───────────────────────────────────────
    public string $recuperaEquipo   = '';   // 'si' | 'no'
    public string $serieConfirmada  = '';
    public bool   $pagoPerdida      = false;

    // ── Infraestructura NAP ───────────────────────────────────────────
    public bool   $desconexionFisica = false;
    public string $salidaNapLibre    = '';

    // ── Red (Internet) ────────────────────────────────────────────────
    public bool $bajaWinboxNombre = false;
    public bool $bajaWinboxPlan   = false;
    public bool $bajaOLT          = false;

    // ── Precierre ─────────────────────────────────────────────────────
    public string $motivoPrecierre = '';

    // ── Cierre ────────────────────────────────────────────────────────
    public bool   $tecnicoCompletado = false;
    public string $calificacion      = 'Excelente';

    // ── Helper ────────────────────────────────────────────────────────
    public function tieneInternet(): bool
    {
        return in_array($this->cliente['tipo_servicio'] ?? '', ['INTERNET', 'TV+INTERNET']);
    }

    // ── Paso 1: Búsqueda ─────────────────────────────────────────────

    public function buscarCliente(): void
    {
        if (strlen(trim($this->busqueda)) < 2) {
            $this->toastError('Ingrese al menos 2 caracteres para buscar.');
            return;
        }

        // TODO: $this->resultados = Cliente::with(['servicio','equipo','nap'])
        //     ->where('estado', 'ACTIVO')
        //     ->where(fn($q) =>
        //         $q->where('nombre', 'LIKE', "%{$this->busqueda}%")
        //           ->orWhere('id_cliente', 'LIKE', "%{$this->busqueda}%")
        //           ->orWhere('telefono', 'LIKE', "%{$this->busqueda}%")
        //     )
        //     ->limit(5)->get()->toArray();

        $this->resultados = [
            [
                'id'               => '01-0004455',
                'nombre'           => 'JUAN PÉREZ GARCÍA',
                'sucursal'         => 'Oaxaca Centro',
                'tipo_servicio'    => 'TV+INTERNET',
                'servicio_actual'  => 'TV + Internet 30 Mbps',
                'domicilio'        => 'Av. Independencia 102, Col. Centro',
                'referencias'      => 'Frente a la escuela primaria, casa azul.',
                'estado_actual'    => 'Activo',
                'saldo'            => 0.00,
                'equipo_asignado'  => 'ONU Huawei HG8010M',
                'serie_registrada' => 'SN-HUA-998877',
                'nap'              => 'NAP-OAX-05',
                'dir_nap'          => 'Poste 14, Esquina Reforma con Independencia',
                'ip_asignada'      => '10.20.30.150',
                'olt'              => 'OLT-01',
                'pon'              => 'PON/0/3',
            ],
            [
                'id'               => '01-0008910',
                'nombre'           => 'LUCÍA MÉNDEZ SANTOS',
                'sucursal'         => 'Oaxaca Norte',
                'tipo_servicio'    => 'TV',
                'servicio_actual'  => 'Retro TV',
                'domicilio'        => 'Calle Morelos 45, Col. Reforma',
                'referencias'      => 'Portón café frente al parque.',
                'estado_actual'    => 'Activo',
                'saldo'            => 480.00,
                'equipo_asignado'  => 'Mininodo ARRIS',
                'serie_registrada' => 'ARR-2022-0089',
                'nap'              => 'NAP-OAN-02',
                'dir_nap'          => 'Poste 7, Calle Juárez',
                'ip_asignada'      => '—',
                'olt'              => '—',
                'pon'              => '—',
            ],
        ];
    }

    public function seleccionarCliente(array $c): void
    {
        $this->cliente         = $c;
        $this->serieConfirmada = $c['serie_registrada'];
        $this->resultados      = [];
        $this->paso            = 2;
    }

    // ── Paso 2 → 3: Generar reporte de baja ──────────────────────────

    public function generarReporteBaja(): void
    {
        if (!$this->tecnicoAsignado) {
            $this->toastError('Seleccione el técnico responsable.');
            return;
        }

        // ─────────────────────────────────────────────────────────────
        // TODO: DB::transaction(function() {
        //   $seq   = ReporteServicio::whereYear('created_at', now()->year)->count() + 1;
        //   $folio = 'CAN-' . now()->format('Y') . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);
        //   ReporteServicio::create([
        //       'folio'         => $folio,
        //       'tipo_reporte'  => 'CANCELACION',
        //       'tipo_servicio' => $this->cliente['tipo_servicio'],
        //       'cliente_id'    => $this->cliente['id'],
        //       'tecnico_id'    => $this->tecnicoAsignado,
        //       'sucursal_id'   => auth()->user()->sucursal_id,
        //       'estado'        => 'Pendiente',
        //       'fecha'         => now(),
        //   ]);
        //   // SMS al técnico al generar el reporte
        //   SmsService::enviar($tecnico->telefono,
        //       "NUEVO REPORTE DE CANCELACION: {$folio}. " .
        //       "Cliente: {$this->cliente['nombre']}. " .
        //       "Domicilio: {$this->cliente['domicilio']}.");
        // });
        // ─────────────────────────────────────────────────────────────

        $this->folioReporte  = 'CAN-' . date('Y') . '-' . rand(1000, 9999);
        $this->fechaReporte  = now()->format('d/m/Y H:i');
        $this->horasAtencion = 0;
        $this->paso          = 3;

        $this->toastInfo('Reporte generado. SMS de notificación enviado al técnico.');
    }

    // ── Guardar precierre (mantiene reporte En Proceso) ───────────────

    public function guardarPrecierre(): void
    {
        if (!$this->motivoPrecierre) {
            $this->addError('motivoPrecierre', 'Seleccione el motivo del precierre.');
            return;
        }

        // TODO: ReporteServicio::where('folio', $this->folioReporte)->update([
        //     'estado'           => 'En Proceso',
        //     'motivo_precierre' => $this->motivoPrecierre,
        // ]);

        $this->toastInfo('Avance guardado. Reporte en proceso — Precierre: ' . $this->motivoPrecierre);
    }

    // ── Cierre TÉCNICO (técnico en campo + sucursal) ──────────────────

    public function confirmarCierreTecnico(): void
    {
        if (!$this->recuperaEquipo) {
            $this->addError('recuperaEquipo', 'Indique si el equipo fue recuperado.');
            return;
        }

        if ($this->recuperaEquipo === 'no' && !$this->pagoPerdida) {
            $this->addError('pagoPerdida',
                'El suscriptor debe pagar el equipo perdido para continuar. Si no paga, use Guardar Precierre.');
            return;
        }

        if (!$this->desconexionFisica) {
            $this->addError('desconexionFisica', 'Confirme la desconexión física del servicio en NAP.');
            return;
        }

        if ($this->tieneInternet() && (!$this->bajaWinboxNombre || !$this->bajaWinboxPlan || !$this->bajaOLT)) {
            $this->addError('bajaOLT', 'Complete las bajas de red (Winbox nombre, plan y OLT) antes de continuar.');
            return;
        }

        // ─────────────────────────────────────────────────────────────
        // TODO: DB::transaction(function() {
        //   // Actualizar reporte
        //   ReporteServicio::where('folio', $this->folioReporte)->update([
        //       'estado'             => 'En Proceso',
        //       'tecnico_completado' => true,
        //       'desconexion_fisica' => $this->desconexionFisica,
        //       'salida_nap_libre'   => $this->salidaNapLibre,
        //       'baja_winbox_nombre' => $this->bajaWinboxNombre,
        //       'baja_winbox_plan'   => $this->bajaWinboxPlan,
        //       'baja_olt'           => $this->bajaOLT,
        //       'serie_recuperada'   => $this->serieConfirmada,
        //       'recupero_equipo'    => $this->recuperaEquipo === 'si',
        //   ]);
        //
        //   // Actualizar inventario según caso
        //   if ($this->recuperaEquipo === 'si') {
        //       Equipo::where('serie', $this->serieConfirmada)
        //             ->update(['estado' => 'EN_ALMACEN', 'cliente_id' => null]);
        //   } elseif ($this->pagoPerdida) {
        //       Equipo::where('serie', $this->cliente['serie_registrada'])
        //             ->update(['estado' => 'PAGADO_POR_PERDIDA', 'cliente_id' => null]);
        //   }
        //
        //   // Liberar salida NAP si fue especificada
        //   if ($this->salidaNapLibre) {
        //       NapSalida::where('nap', $this->cliente['nap'])
        //                 ->where('numero', $this->salidaNapLibre)
        //                 ->update(['estado' => 'LIBRE', 'cliente_id' => null]);
        //   }
        // });
        // ─────────────────────────────────────────────────────────────

        $this->tecnicoCompletado = true;
        $this->toastInfo('Cierre técnico guardado. El cierre administrativo ya está disponible.');
    }

    // ── Cierre ADMINISTRATIVO (requiere cierre técnico previo) ─────────

    public function finalizarCancelacion(): void
    {
        if (!$this->tecnicoCompletado) {
            $this->addError('tecnicoCompletado', 'Complete el cierre técnico antes del cierre administrativo.');
            return;
        }

        // ─────────────────────────────────────────────────────────────
        // TODO: DB::transaction(function() {
        //   // 1. Cambiar estado del suscriptor a CANCELADO
        //   Cliente::where('id', $this->cliente['id'])->update(['estado' => 'CANCELADO']);
        //
        //   // 2. Liberar salida de NAP
        //   NapSalida::where('nap', $this->cliente['nap'])
        //             ->where('cliente_id', $this->cliente['id'])
        //             ->update(['estado' => 'LIBRE', 'cliente_id' => null]);
        //
        //   // 3. Cerrar ciclo de facturación
        //   CicloFacturacion::where('cliente_id', $this->cliente['id'])
        //                    ->update(['activo' => false, 'fecha_baja' => now()]);
        //
        //   // 4. Cerrar reporte
        //   ReporteServicio::where('folio', $this->folioReporte)->update([
        //       'estado'        => 'Cerrado',
        //       'calificacion'  => $this->calificacion,
        //       'horas_atencion'=> now()->diffInHours(Carbon::parse($this->fechaReporte)),
        //       'cerrado_admin' => now(),
        //   ]);
        //
        //   // 5. SMS de confirmación al suscriptor
        //   SmsService::enviar($this->cliente['telefono'],
        //       "Tu Vision Telecable: Su servicio ha sido CANCELADO satisfactoriamente. " .
        //       "Gracias por su preferencia. Para volver a contratar llame al {$sucursal->telefono}.");
        // });
        // ─────────────────────────────────────────────────────────────

        $this->toastExito('Cancelación aplicada. Estado → CANCELADO. NAP liberada. SMS enviado al suscriptor.');
        $this->redirect(route('reportes.servicio'));
    }

    public function render()
    {
        return view('livewire.gestion-clientes.cancelacion-servicio', [
            'tieneInternet'    => $this->tieneInternet(),
            'catalogoTecnicos' => [
                ['id' => 'Roberto', 'nombre' => 'ING. ROBERTO GÓMEZ'],
                ['id' => 'Ana',     'nombre' => 'ING. ANA MARTÍNEZ'],
                ['id' => 'cua-1',   'nombre' => 'CUADRILLA 1'],
                ['id' => 'cua-2',   'nombre' => 'CUADRILLA 2'],
                ['id' => 'cua-3',   'nombre' => 'CUADRILLA 3'],
            ],
        ]);
    }
}

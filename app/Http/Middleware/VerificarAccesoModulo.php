<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarAccesoModulo
{
    /**
     * Mapa: nombre-de-ruta → [módulo, submodulo]
     * Sólo se incluyen rutas que requieren verificación de permisos.
     */
    private const ROUTE_MAP = [
        // ── Gestión al Cliente ────────────────────────────────────────────────
        'contrataciones.nuevas'  => ['GestionClientes', 'contrataciones-nuevas'],
        'servicios.adicionales'  => ['GestionClientes', 'servicios-adicionales'],
        'contratacion.promocion' => ['GestionClientes', 'contratacion-promocion'],
        'pago.mensualidad'       => ['GestionClientes', 'pago-mensualidad'],
        'estado.cuenta'          => ['GestionClientes', 'estado-cuenta'],
        'suspension.clientes'    => ['GestionClientes', 'suspension-falta-pago'],
        'reconexion.cliente'     => ['GestionClientes', 'reconexion-cliente'],
        'cancelacion.servicio'   => ['GestionClientes', 'cancelacion-servicio'],
        'recuperacion.equipos'   => ['GestionClientes', 'recuperacion-equipos'],
        'reportes.servicio'      => ['GestionClientes', 'reportes-servicio'],
        'reportes.atender'       => ['GestionClientes', 'reportes-servicio'],

        // ── Infraestructura ───────────────────────────────────────────────────
        'infraestructura.geografia'  => ['Infraestructura', 'infraestructura.geografia'],
        'infraestructura.sucursales' => ['Infraestructura', 'infraestructura.sucursales'],
        'infraestructura.calles'     => ['Infraestructura', 'infraestructura.calles'],
        'infraestructura.postes'     => ['Infraestructura', 'infraestructura.postes'],

        // ── Recursos Humanos ──────────────────────────────────────────────────
        'rrhh.empleados'  => ['RRHH', 'rrhh.empleados'],
        'rrhh.vacaciones' => ['RRHH', 'rrhh.vacaciones'],
        'rrhh.descanso'   => ['RRHH', 'rrhh.descanso'],
        'rrhh.accesos'    => ['RRHH', 'rrhh.accesos'],
        'rrhh.permisos'   => ['RRHH', 'rrhh.permisos'],

        // ── Financiero ────────────────────────────────────────────────────────
        'financiero.tarifas.principales' => ['Financiero', 'financiero.tarifas.principales'],
        'financiero.tarifas.adicionales' => ['Financiero', 'financiero.tarifas.adicionales'],
        'financiero.promociones'         => ['Financiero', 'financiero.promociones'],
        'financiero.descuentos'          => ['Financiero', 'financiero.descuentos'],
        'financiero.ingresos.egresos'    => ['Financiero', 'financiero.ingresos.egresos'],
        'financiero.proveedores'         => ['Financiero', 'financiero.proveedores'],

        // ── Clientes ──────────────────────────────────────────────────────────
        'clientes.registro' => ['Clientes', 'clientes.registro'],

        // ── Servicios ─────────────────────────────────────────────────────────
        'cat.servicios.tarifas-principales' => ['Servicios', 'cat.servicios.tarifas-principales'],
        'cat.servicios.tarifas-adicionales' => ['Servicios', 'cat.servicios.tarifas-adicionales'],
        'cat.servicios.fallas'              => ['Servicios', 'cat.servicios.fallas'],
        'cat.servicios.personal'            => ['Servicios', 'cat.servicios.personal'],

        // ── Actividades ───────────────────────────────────────────────────────
        'cat.actividades.tarifas-principales' => ['Servicios', 'cat.actividades.tarifas-principales'],
        'cat.actividades.tarifas-adicionales' => ['Servicios', 'cat.actividades.tarifas-adicionales'],
        'cat.actividades.fallas'              => ['Servicios', 'cat.actividades.fallas'],
        'cat.actividades.personal'            => ['Servicios', 'cat.actividades.personal'],

        // ── Planta Externa ────────────────────────────────────────────────────
        'planta.tipo-fibra'     => ['PlantaExterna', 'planta.tipo-fibra'],
        'planta.amplificadores' => ['PlantaExterna', 'planta.amplificadores'],
        'planta.nodos-opticos'  => ['PlantaExterna', 'planta.nodos-opticos'],

        // ── Televisión ────────────────────────────────────────────────────────
        'tv.mininodos'  => ['Television', 'tv.mininodos'],
        'tv.canales'    => ['Television', 'tv.canales'],
        'tv.moduladores'=> ['Television', 'tv.moduladores'],
        'tv.transmisores'=> ['Television', 'tv.transmisores'],
        'tv.pon-edfa'   => ['Television', 'tv.pon-edfa'],

        // ── Red / Internet ────────────────────────────────────────────────────
        'red.onus'     => ['Red', 'red.onus'],
        'red.naps'     => ['Red', 'red.naps'],
        'red.olt'      => ['Red', 'red.olt'],
        'red.starlinks'=> ['Red', 'red.starlinks'],
        'red.ccr'      => ['Red', 'red.ccr'],
        'red.vlans'    => ['Red', 'red.vlans'],

        // ── Energía ───────────────────────────────────────────────────────────
        'energia.ctc'  => ['Energia', 'energia.ctc'],
        'energia.ups'  => ['Energia', 'energia.ups'],
        'energia.fibra'=> ['Energia', 'energia.fibra'],
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $routeName = $request->route()?->getName();

        // Si la ruta no está en el mapa, no requiere verificación de módulo
        if (!$routeName || !isset(self::ROUTE_MAP[$routeName])) {
            return $next($request);
        }

        $user   = $request->user();
        $acceso = $user?->accesoSistema;

        // Sin registro de acceso o inactivo → sin acceso
        if (!$acceso || !$acceso->activo) {
            abort(403, 'No tienes acceso al sistema.');
        }

        // ADMINISTRADOR tiene acceso total
        if ($acceso->rol === 'ADMINISTRADOR') {
            return $next($request);
        }

        [$modulo, $submodulo] = self::ROUTE_MAP[$routeName];

        $modulos = $acceso->modulos ?? [];

        // El módulo no fue asignado al usuario
        if (!array_key_exists($modulo, $modulos)) {
            abort(403, 'No tienes acceso a este módulo.');
        }

        // Verificar submodulo si hay lista específica (vacía = todos permitidos)
        $submodulos = $modulos[$modulo] ?? [];
        if (!empty($submodulos) && !in_array($submodulo, $submodulos)) {
            abort(403, 'No tienes acceso a esta sección.');
        }

        return $next($request);
    }
}

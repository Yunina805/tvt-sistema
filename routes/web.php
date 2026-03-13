<?php

use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────────────────────────────────────────────
// GESTIÓN AL CLIENTE
// ─────────────────────────────────────────────────────────────────────────────
use App\Livewire\GestionClientes\ContratacionNueva;
use App\Livewire\GestionClientes\PagoMensualidad;
use App\Livewire\GestionClientes\ReportesServicio;
use App\Livewire\GestionClientes\AtenderReporte;
use App\Livewire\GestionClientes\ServiciosAdicionales;
use App\Livewire\GestionClientes\ReconexionCliente;
use App\Livewire\GestionClientes\SuspensionClientes;
use App\Livewire\GestionClientes\CancelacionServicio;
use App\Livewire\GestionClientes\RecuperacionEquipos;
use App\Livewire\GestionClientes\EstadoCuenta;
use App\Livewire\GestionClientes\ContratacionPromocion;
use App\Livewire\GestionClientes\CambioServicio;

// ─────────────────────────────────────────────────────────────────────────────
// CATÁLOGOS — SEDES E INFRAESTRUCTURA
// ─────────────────────────────────────────────────────────────────────────────
use App\Livewire\Catalogos\Infraestructura\GeografiaInegi;
use App\Livewire\Catalogos\Infraestructura\Sucursales;
use App\Livewire\Catalogos\Infraestructura\RegistroCalles;
use App\Livewire\Catalogos\Infraestructura\InventarioPostes;

// ─────────────────────────────────────────────────────────────────────────────
// CATÁLOGOS — RECURSOS HUMANOS
// ─────────────────────────────────────────────────────────────────────────────
use App\Livewire\Catalogos\RecursosHumanos\RegistroEmpleados;
use App\Livewire\Catalogos\RecursosHumanos\Vacaciones;
use App\Livewire\Catalogos\RecursosHumanos\DescansoMensual;
use App\Livewire\Catalogos\RecursosHumanos\Permisos;
use App\Livewire\Catalogos\RecursosHumanos\AccesosSistema;
use App\Livewire\Catalogos\RecursosHumanos\Nomina;
use App\Livewire\Catalogos\RecursosHumanos\Prestamos;
use App\Livewire\Catalogos\RecursosHumanos\DescuentosPersonal;
use App\Livewire\Catalogos\RecursosHumanos\ProductividadTecnicos;

// ─────────────────────────────────────────────────────────────────────────────
// CATÁLOGOS — FINANCIERO
// ─────────────────────────────────────────────────────────────────────────────
use App\Livewire\Catalogos\Financiero\TarifasPrincipales;
use App\Livewire\Catalogos\Financiero\TarifasAdicionales;
use App\Livewire\Catalogos\Financiero\Promociones;
use App\Livewire\Catalogos\Financiero\Descuentos;
use App\Livewire\Catalogos\Financiero\IngresosEgresos;
use App\Livewire\Catalogos\Financiero\ProveedoresBancos;
use App\Livewire\Catalogos\Financiero\Bancos;
use App\Livewire\Catalogos\Financiero\Facturas;
use App\Livewire\Catalogos\Financiero\MotivosTraspaso;

// ─────────────────────────────────────────────────────────────────────────────
// CATÁLOGOS — RED E INTERNET
// ─────────────────────────────────────────────────────────────────────────────
use App\Livewire\Catalogos\Red\AdministrarNaps;
use App\Livewire\Catalogos\Red\OltAdmin;
use App\Livewire\Catalogos\Red\OltInterna;
use App\Livewire\Catalogos\Red\AdministracionOnus;
use App\Livewire\Catalogos\Red\WinboxVlans;
use App\Livewire\Catalogos\Red\Winbox;
use App\Livewire\Catalogos\Red\Vlans;
use App\Livewire\Catalogos\Red\CcrSwitches;
use App\Livewire\Catalogos\Red\Ccr1;
use App\Livewire\Catalogos\Red\Switches;
use App\Livewire\Catalogos\Red\StarlinksIsp;
use App\Livewire\Catalogos\Red\IspTelmex;
use App\Livewire\Catalogos\Red\Encapsulamiento;
use App\Livewire\Catalogos\Red\ZeroTier;

// ─────────────────────────────────────────────────────────────────────────────
// CATÁLOGOS — TELEVISIÓN
// ─────────────────────────────────────────────────────────────────────────────
use App\Livewire\Catalogos\Television\CanalesSatelites;
use App\Livewire\Catalogos\Television\Moduladores;
use App\Livewire\Catalogos\Television\ModuladoresDigitales;
use App\Livewire\Catalogos\Television\Transmisores;
use App\Livewire\Catalogos\Television\Transmisor1310;
use App\Livewire\Catalogos\Television\Transmisor1550;
use App\Livewire\Catalogos\Television\TransmisorEdfa;
use App\Livewire\Catalogos\Television\PonEdfa;
use App\Livewire\Catalogos\Television\MiniNodosAntenas;
use App\Livewire\Catalogos\Television\Antenas;
use App\Livewire\Catalogos\Television\Satelites;
use App\Livewire\Catalogos\Television\ProveedoresSenal;
use App\Livewire\Catalogos\Television\Receptores;
use App\Livewire\Catalogos\Television\Divisores;

// ─────────────────────────────────────────────────────────────────────────────
// CATÁLOGOS — CLIENTES
// ─────────────────────────────────────────────────────────────────────────────
use App\Livewire\Catalogos\Clientes\RegistroClientes;

// ─────────────────────────────────────────────────────────────────────────────
// CATÁLOGOS — SERVICIOS / TAREAS
// ─────────────────────────────────────────────────────────────────────────────
use App\Livewire\Catalogos\Servicios\ServiciosDisponibles;
use App\Livewire\Catalogos\Servicios\ServiciosTarifasPrincipales;
use App\Livewire\Catalogos\Servicios\ServiciosTarifasAdicionales;
use App\Livewire\Catalogos\Servicios\ServiciosFallas;
use App\Livewire\Catalogos\Servicios\ServiciosPersonal;
use App\Livewire\Catalogos\Servicios\ServiciosClientes;
use App\Livewire\Catalogos\Servicios\ActividadesTarifasPrincipales;
use App\Livewire\Catalogos\Servicios\ActividadesTarifasAdicionales;
use App\Livewire\Catalogos\Servicios\ActividadesFallas;
use App\Livewire\Catalogos\Servicios\ActividadesPersonal;

// ─────────────────────────────────────────────────────────────────────────────
// CATÁLOGOS — PLAN DE TRABAJO
// ─────────────────────────────────────────────────────────────────────────────
use App\Livewire\Catalogos\PlanTrabajo\Actividades as PlanActividades;
use App\Livewire\Catalogos\PlanTrabajo\AsignacionPlan;
use App\Livewire\Catalogos\Servicios\MatrizActividades;

// ─────────────────────────────────────────────────────────────────────────────
// CATÁLOGOS — PLANTA EXTERNA
// ─────────────────────────────────────────────────────────────────────────────
use App\Livewire\Catalogos\PlantaExterna\TipoFibra;
use App\Livewire\Catalogos\PlantaExterna\Amplificadores;
use App\Livewire\Catalogos\PlantaExterna\NodosOpticos;
use App\Livewire\Catalogos\PlantaExterna\Dfo;

// ─────────────────────────────────────────────────────────────────────────────
// CATÁLOGOS — ENERGÍA Y ENLACES
// ─────────────────────────────────────────────────────────────────────────────
use App\Livewire\Catalogos\Energia\EnlacesFibra;
use App\Livewire\Catalogos\Energia\CatalogoCTC;
use App\Livewire\Catalogos\Energia\UpsPlanta;
use App\Livewire\Catalogos\Energia\PlantasEmergencia;

// ─────────────────────────────────────────────────────────────────────────────
// SERVICIOS FINANCIEROS POR SUCURSAL
// ─────────────────────────────────────────────────────────────────────────────
use App\Livewire\FinancieroSucursal\CorteSaldosNuevos;
use App\Livewire\FinancieroSucursal\CorteSaldosActivos;
use App\Livewire\FinancieroSucursal\CajasSucursal;
use App\Livewire\FinancieroSucursal\Ingresos as IngresosSucursal;
use App\Livewire\FinancieroSucursal\Egresos as EgresosSucursal;
use App\Livewire\FinancieroSucursal\ConciliacionWeb;
use App\Livewire\FinancieroSucursal\ConciliacionSpei;
use App\Livewire\FinancieroSucursal\TraspasosCajas;

// ─────────────────────────────────────────────────────────────────────────────
// KPIs
// ─────────────────────────────────────────────────────────────────────────────
use App\Livewire\Kpis\ReportesGenerales;
use App\Livewire\Kpis\RepPorSucursal;
use App\Livewire\Kpis\RepAdeudos;
use App\Livewire\Kpis\RepSuspendidos;
use App\Livewire\Kpis\RepCancelados;
use App\Livewire\Kpis\RepCrecimiento;
use App\Livewire\Kpis\RepIngresosTipo;
use App\Livewire\Kpis\RepMayorAdeudo;
use App\Livewire\Kpis\RepIndicadores;
use App\Livewire\Kpis\KpisComerciales;
use App\Livewire\Kpis\KpisFinancieros;
use App\Livewire\Kpis\KpisArpu;
use App\Livewire\Kpis\KpisCobranza;

// ─────────────────────────────────────────────────────────────────────────────
// CATÁLOGOS — REGULATORIO (legacy)
// ─────────────────────────────────────────────────────────────────────────────
use App\Livewire\Catalogos\Regulatorio\EntidadesRegulatorias;
use App\Livewire\Catalogos\Regulatorio\Documentos as DocumentosReg;
use App\Livewire\Catalogos\Regulatorio\EnvioObligaciones;

// ─────────────────────────────────────────────────────────────────────────────
// GESTIÓN REGULATORIA Y LEGAL
// ─────────────────────────────────────────────────────────────────────────────
use App\Livewire\Regulatorio\Cfe;
use App\Livewire\Regulatorio\Compranet;
use App\Livewire\Regulatorio\Crt;
use App\Livewire\Regulatorio\JovenesConstruyendo;
use App\Livewire\Regulatorio\Profeco;
use App\Livewire\Regulatorio\Impi;
use App\Livewire\Regulatorio\Promtel;
use App\Livewire\Regulatorio\Ine;
use App\Livewire\Regulatorio\Renapo;
use App\Livewire\Regulatorio\Cofece;
use App\Livewire\Regulatorio\Reiniecyt;
use App\Livewire\Regulatorio\Sat;
use App\Livewire\Regulatorio\Imss;
use App\Livewire\Regulatorio\CamarasComercio;
use App\Livewire\Regulatorio\ProveedoresSignal;
use App\Livewire\Regulatorio\ProveedoresMateriales;
use App\Livewire\Regulatorio\ProyectosTerceros;
use App\Livewire\Regulatorio\ConstitucionTVT;

// =============================================================================

Route::view('/', 'welcome');

Route::middleware(['auth', 'verified', 'acceso'])->group(function () {

    Route::view('dashboard', 'dashboard')->name('dashboard');

    // =========================================================================
    // GESTIÓN AL CLIENTE
    // =========================================================================

    // Altas y Promociones
    Route::get('/contrataciones-nuevas', ContratacionNueva::class)->name('contrataciones.nuevas');
    Route::get('/servicios-adicionales', ServiciosAdicionales::class)->name('servicios.adicionales');
    Route::get('/contratacion-promocion', ContratacionPromocion::class)->name('contratacion.promocion');
    Route::get('/cambio-servicio', CambioServicio::class)->name('cambio.servicio');

    // Pagos y Estados de Cuenta
    Route::get('/pago-mensualidad', PagoMensualidad::class)->name('pago.mensualidad');
    Route::get('/estado-cuenta', EstadoCuenta::class)->name('estado.cuenta');

    // Operativa de Estatus
    Route::get('/suspension-falta-pago', SuspensionClientes::class)->name('suspension.clientes');
    Route::get('/reconexion-cliente', ReconexionCliente::class)->name('reconexion.cliente');
    Route::get('/cancelacion-servicio', CancelacionServicio::class)->name('cancelacion.servicio');
    Route::get('/recuperacion-equipos', RecuperacionEquipos::class)->name('recuperacion.equipos');

    // Bandeja de Reportes Técnicos
    Route::get('/reportes-servicio', ReportesServicio::class)->name('reportes.servicio');
    Route::get('/reportes-servicio/atender/{folio?}', AtenderReporte::class)->name('reportes.atender');

    // =========================================================================
    // CATÁLOGOS: SEDES E INFRAESTRUCTURA
    // =========================================================================
    Route::prefix('infraestructura')->name('infraestructura.')->group(function () {
        Route::get('/geografia', GeografiaInegi::class)->name('geografia');
        Route::get('/sucursales', Sucursales::class)->name('sucursales');
        Route::get('/calles', RegistroCalles::class)->name('calles');
        Route::get('/postes', InventarioPostes::class)->name('postes');
    });

    // =========================================================================
    // CATÁLOGOS: RECURSOS HUMANOS
    // =========================================================================
    Route::prefix('rrhh')->name('rrhh.')->group(function () {
        Route::get('/empleados', RegistroEmpleados::class)->name('empleados');
        Route::get('/vacaciones', Vacaciones::class)->name('vacaciones');
        Route::get('/descanso', DescansoMensual::class)->name('descanso');
        Route::get('/permisos', Permisos::class)->name('permisos');
        Route::get('/accesos', AccesosSistema::class)->name('accesos');
        Route::get('/nomina', Nomina::class)->name('nomina');
        Route::get('/prestamos', Prestamos::class)->name('prestamos');
        Route::get('/descuentos', DescuentosPersonal::class)->name('descuentos');
        Route::get('/productividad', ProductividadTecnicos::class)->name('productividad');
    });

    // =========================================================================
    // CATÁLOGOS: FINANCIERO
    // =========================================================================
    Route::prefix('financiero')->name('financiero.')->group(function () {
        Route::get('/tarifas-principales', TarifasPrincipales::class)->name('tarifas.principales');
        Route::get('/tarifas-adicionales', TarifasAdicionales::class)->name('tarifas.adicionales');
        Route::get('/promociones', Promociones::class)->name('promociones');
        Route::get('/descuentos', Descuentos::class)->name('descuentos');
        Route::get('/ingresos-egresos', IngresosEgresos::class)->name('ingresos.egresos');
        Route::get('/proveedores', ProveedoresBancos::class)->name('proveedores');
        Route::get('/bancos', Bancos::class)->name('bancos');
        Route::get('/facturas', Facturas::class)->name('facturas');
        Route::get('/motivos-traspaso', MotivosTraspaso::class)->name('motivos-traspaso');
    });

    // =========================================================================
    // CATÁLOGOS: RED E INTERNET
    // =========================================================================
    Route::prefix('red')->name('red.')->group(function () {
        Route::get('/naps', AdministrarNaps::class)->name('naps');
        Route::get('/olt', OltAdmin::class)->name('olt');
        Route::get('/olt-interna', OltInterna::class)->name('olt-interna');
        Route::get('/onus', AdministracionOnus::class)->name('onus');
        Route::get('/vlans', WinboxVlans::class)->name('vlans');
        Route::get('/winbox', Winbox::class)->name('winbox');
        Route::get('/catalogo-vlans', Vlans::class)->name('catalogo-vlans');
        Route::get('/ccr', CcrSwitches::class)->name('ccr');
        Route::get('/ccr1', Ccr1::class)->name('ccr1');
        Route::get('/switches', Switches::class)->name('switches');
        Route::get('/starlinks', StarlinksIsp::class)->name('starlinks');
        Route::get('/isp-telmex', IspTelmex::class)->name('isp-telmex');
        Route::get('/encapsulamiento', Encapsulamiento::class)->name('encapsulamiento');
        Route::get('/zerotier', ZeroTier::class)->name('zerotier');
    });

    // =========================================================================
    // CATÁLOGOS: TELEVISIÓN
    // =========================================================================
    Route::prefix('television')->name('tv.')->group(function () {
        Route::get('/mininodos', MiniNodosAntenas::class)->name('mininodos');
        Route::get('/antenas', Antenas::class)->name('antenas');
        Route::get('/satelites', Satelites::class)->name('satelites');
        Route::get('/proveedores-senal', ProveedoresSenal::class)->name('proveedores-senal');
        Route::get('/receptores', Receptores::class)->name('receptores');
        Route::get('/canales', CanalesSatelites::class)->name('canales');
        Route::get('/moduladores', Moduladores::class)->name('moduladores');
        Route::get('/moduladores-digitales', ModuladoresDigitales::class)->name('moduladores-digitales');
        Route::get('/transmisores', Transmisores::class)->name('transmisores');
        Route::get('/transmisor-1310', Transmisor1310::class)->name('transmisor-1310');
        Route::get('/transmisor-1550', Transmisor1550::class)->name('transmisor-1550');
        Route::get('/transmisor-edfa', TransmisorEdfa::class)->name('transmisor-edfa');
        Route::get('/pon-edfa', PonEdfa::class)->name('pon-edfa');
        Route::get('/divisores', Divisores::class)->name('divisores');
    });

    // =========================================================================
    // CATÁLOGOS: CLIENTES
    // =========================================================================
    Route::prefix('clientes')->name('clientes.')->group(function () {
        Route::get('/registro', RegistroClientes::class)->name('registro');
    });

    // =========================================================================
    // CATÁLOGOS: SERVICIOS / TAREAS
    // =========================================================================
    Route::prefix('servicios')->name('cat.servicios.')->group(function () {
        Route::get('/disponibles', ServiciosDisponibles::class)->name('disponibles');
        Route::get('/tarifas-principales', ServiciosTarifasPrincipales::class)->name('tarifas-principales');
        Route::get('/tarifas-adicionales', ServiciosTarifasAdicionales::class)->name('tarifas-adicionales');
        Route::get('/fallas',              ServiciosFallas::class)->name('fallas');
        Route::get('/personal',            ServiciosPersonal::class)->name('personal');
        Route::get('/clientes',            ServiciosClientes::class)->name('clientes');
    });

    // =========================================================================
    // CATÁLOGOS: ACTIVIDADES
    // =========================================================================
    Route::prefix('actividades')->name('cat.actividades.')->group(function () {
        Route::get('/tarifas-principales', ActividadesTarifasPrincipales::class)->name('tarifas-principales');
        Route::get('/tarifas-adicionales', ActividadesTarifasAdicionales::class)->name('tarifas-adicionales');
        Route::get('/fallas',              ActividadesFallas::class)->name('fallas');
        Route::get('/personal',            ActividadesPersonal::class)->name('personal');
        Route::get('/clientes',            MatrizActividades::class)->name('clientes');
    });

    // =========================================================================
    // CATÁLOGOS: PLANTA EXTERNA
    // =========================================================================
    Route::prefix('planta-externa')->name('planta.')->group(function () {
        Route::get('/tipo-fibra', TipoFibra::class)->name('tipo-fibra');
        Route::get('/amplificadores', Amplificadores::class)->name('amplificadores');
        Route::get('/nodos-opticos', NodosOpticos::class)->name('nodos-opticos');
        Route::get('/dfo', Dfo::class)->name('dfo');
    });

    // =========================================================================
    // CATÁLOGOS: ENERGÍA Y ENLACES
    // =========================================================================
    Route::prefix('energia')->name('energia.')->group(function () {
        Route::get('/fibra', EnlacesFibra::class)->name('fibra');
        Route::get('/ctc', CatalogoCTC::class)->name('ctc');
        Route::get('/ups', UpsPlanta::class)->name('ups');
        Route::get('/plantas', PlantasEmergencia::class)->name('plantas');
    });

    // =========================================================================
    // CATÁLOGOS: PLAN DE TRABAJO
    // =========================================================================
    Route::prefix('plan')->name('plan.')->group(function () {
        Route::get('/actividades', PlanActividades::class)->name('actividades');
        Route::get('/asignacion', AsignacionPlan::class)->name('trabajo');
    });

    // =========================================================================
    // SERVICIOS FINANCIEROS POR SUCURSAL
    // =========================================================================
    Route::prefix('svc-financiero')->name('svc.fin.')->group(function () {
        Route::get('/corte-saldos-nuevos', CorteSaldosNuevos::class)->name('corte-nuevos');
        Route::get('/corte-saldos-activos', CorteSaldosActivos::class)->name('corte-activos');
        Route::get('/cajas', CajasSucursal::class)->name('cajas');
        Route::get('/ingresos', IngresosSucursal::class)->name('ingresos');
        Route::get('/egresos', EgresosSucursal::class)->name('egresos');
        Route::get('/conciliacion-web', ConciliacionWeb::class)->name('conciliacion-web');
        Route::get('/conciliacion-spei', ConciliacionSpei::class)->name('conciliacion-spei');
        Route::get('/traspasos', TraspasosCajas::class)->name('traspasos');
    });

    // =========================================================================
    // KPIs — INDICADORES DE GESTIÓN
    // =========================================================================
    Route::prefix('kpis')->name('kpi.')->group(function () {
        Route::get('/reportes-generales', ReportesGenerales::class)->name('reportes-generales');
        Route::get('/rep-por-sucursal', RepPorSucursal::class)->name('rep-por-sucursal');
        Route::get('/rep-adeudos', RepAdeudos::class)->name('rep-adeudos');
        Route::get('/rep-suspendidos', RepSuspendidos::class)->name('rep-suspendidos');
        Route::get('/rep-cancelados', RepCancelados::class)->name('rep-cancelados');
        Route::get('/rep-crecimiento', RepCrecimiento::class)->name('rep-crecimiento');
        Route::get('/rep-ingresos-tipo', RepIngresosTipo::class)->name('rep-ingresos-tipo');
        Route::get('/rep-mayor-adeudo', RepMayorAdeudo::class)->name('rep-mayor-adeudo');
        Route::get('/rep-indicadores', RepIndicadores::class)->name('rep-indicadores');
        Route::get('/comerciales', KpisComerciales::class)->name('comerciales');
        Route::get('/financieros', KpisFinancieros::class)->name('financieros');
        Route::get('/arpu', KpisArpu::class)->name('arpu');
        Route::get('/cobranza', KpisCobranza::class)->name('cobranza');
    });

    // =========================================================================
    // CATÁLOGOS: REGULATORIO (legacy)
    // =========================================================================
    Route::prefix('regulatorio')->name('regulatorio.')->group(function () {
        Route::get('/entidades', EntidadesRegulatorias::class)->name('entidades');
        Route::get('/documentos', DocumentosReg::class)->name('documentos');
        Route::get('/obligaciones', EnvioObligaciones::class)->name('obligaciones');
    });

    // =========================================================================
    // GESTIÓN REGULATORIA Y LEGAL — ORGANISMOS EXTERNOS
    // =========================================================================
    Route::prefix('legal')->name('legal.')->group(function () {

        // Regulatorios
        Route::get('/cfe',                Cfe::class)               ->name('cfe');
        Route::get('/compranet',          Compranet::class)          ->name('compranet');
        Route::get('/crt',                Crt::class)                ->name('crt');
        Route::get('/jovenes-construyendo', JovenesConstruyendo::class)->name('jovenes');
        Route::get('/profeco',            Profeco::class)            ->name('profeco');
        Route::get('/impi',               Impi::class)               ->name('impi');
        Route::get('/promtel',            Promtel::class)            ->name('promtel');
        Route::get('/ine',                Ine::class)                ->name('ine');
        Route::get('/renapo',             Renapo::class)             ->name('renapo');
        Route::get('/cofece',             Cofece::class)             ->name('cofece');
        Route::get('/reiniecyt',          Reiniecyt::class)          ->name('reiniecyt');
        Route::get('/sat',                Sat::class)                ->name('sat');
        Route::get('/imss',               Imss::class)               ->name('imss');
        Route::get('/camaras-comercio',   CamarasComercio::class)    ->name('camaras');

        // Proveedores
        Route::get('/proveedores-signal',     ProveedoresSignal::class)    ->name('prov-signal');
        Route::get('/proveedores-materiales', ProveedoresMateriales::class)->name('prov-materiales');

        // Clientes
        Route::get('/proyectos-terceros', ProyectosTerceros::class)->name('proyectos-terceros');

        // Legal TVT
        Route::get('/constitucion-tvt',   ConstitucionTVT::class)   ->name('constitucion-tvt');
    });

});

Route::view('profile', 'profile')->middleware(['auth'])->name('profile');

require __DIR__.'/auth.php';

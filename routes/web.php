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

// ─────────────────────────────────────────────────────────────────────────────
// CATÁLOGOS — FINANCIERO
// ─────────────────────────────────────────────────────────────────────────────
use App\Livewire\Catalogos\Financiero\TarifasPrincipales;
use App\Livewire\Catalogos\Financiero\TarifasAdicionales;
use App\Livewire\Catalogos\Financiero\Promociones;
use App\Livewire\Catalogos\Financiero\Descuentos;
use App\Livewire\Catalogos\Financiero\IngresosEgresos;
use App\Livewire\Catalogos\Financiero\ProveedoresBancos;

// ─────────────────────────────────────────────────────────────────────────────
// CATÁLOGOS — RED E INTERNET
// ─────────────────────────────────────────────────────────────────────────────
use App\Livewire\Catalogos\Red\AdministrarNaps;
use App\Livewire\Catalogos\Red\OltAdmin;
use App\Livewire\Catalogos\Red\AdministracionOnus;
use App\Livewire\Catalogos\Red\WinboxVlans;
use App\Livewire\Catalogos\Red\CcrSwitches;
use App\Livewire\Catalogos\Red\StarlinksIsp;

// ─────────────────────────────────────────────────────────────────────────────
// CATÁLOGOS — TELEVISIÓN
// ─────────────────────────────────────────────────────────────────────────────
use App\Livewire\Catalogos\Television\CanalesSatelites;
use App\Livewire\Catalogos\Television\Moduladores;
use App\Livewire\Catalogos\Television\Transmisores;
use App\Livewire\Catalogos\Television\PonEdfa;
use App\Livewire\Catalogos\Television\MiniNodosAntenas;

// ─────────────────────────────────────────────────────────────────────────────
// CATÁLOGOS — SERVICIOS / TAREAS
// ─────────────────────────────────────────────────────────────────────────────
use App\Livewire\Catalogos\Servicios\RegistroServicios;
use App\Livewire\Catalogos\Servicios\MatrizActividades;

// ─────────────────────────────────────────────────────────────────────────────
// CATÁLOGOS — ENERGÍA Y ENLACES
// ─────────────────────────────────────────────────────────────────────────────
use App\Livewire\Catalogos\Energia\EnlacesFibra;
use App\Livewire\Catalogos\Energia\CatalogoCTC;
use App\Livewire\Catalogos\Energia\UpsPlanta;

// =============================================================================

Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::view('dashboard', 'dashboard')->name('dashboard');

    // =========================================================================
    // GESTIÓN AL CLIENTE
    // =========================================================================

    // Altas y Promociones
    Route::get('/contrataciones-nuevas', ContratacionNueva::class)->name('contrataciones.nuevas');
    Route::get('/servicios-adicionales', ServiciosAdicionales::class)->name('servicios.adicionales');
    Route::get('/contratacion-promocion', ContratacionPromocion::class)->name('contratacion.promocion');

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
    });

    // =========================================================================
    // CATÁLOGOS: RED E INTERNET
    // =========================================================================
    Route::prefix('red')->name('red.')->group(function () {
        Route::get('/naps', AdministrarNaps::class)->name('naps');
        Route::get('/olt', OltAdmin::class)->name('olt');
        Route::get('/onus', AdministracionOnus::class)->name('onus');
        Route::get('/vlans', WinboxVlans::class)->name('vlans');
        Route::get('/ccr', CcrSwitches::class)->name('ccr');
        Route::get('/starlinks', StarlinksIsp::class)->name('starlinks');
    });

    // =========================================================================
    // CATÁLOGOS: TELEVISIÓN
    // =========================================================================
    Route::prefix('television')->name('tv.')->group(function () {
        Route::get('/canales', CanalesSatelites::class)->name('canales');
        Route::get('/moduladores', Moduladores::class)->name('moduladores');
        Route::get('/transmisores', Transmisores::class)->name('transmisores');
        Route::get('/pon-edfa', PonEdfa::class)->name('pon-edfa');
        Route::get('/mininodos', MiniNodosAntenas::class)->name('mininodos');
    });

    // =========================================================================
    // CATÁLOGOS: SERVICIOS / TAREAS
    // =========================================================================
    Route::prefix('servicios')->name('cat.servicios.')->group(function () {
        Route::get('/registro', RegistroServicios::class)->name('registro');
        Route::get('/actividades', MatrizActividades::class)->name('actividades');
    });

    // =========================================================================
    // CATÁLOGOS: ENERGÍA Y ENLACES
    // =========================================================================
    Route::prefix('energia')->name('energia.')->group(function () {
        Route::get('/fibra', EnlacesFibra::class)->name('fibra');
        Route::get('/ctc', CatalogoCTC::class)->name('ctc');
        Route::get('/ups', UpsPlanta::class)->name('ups');
    });

});

Route::view('profile', 'profile')->middleware(['auth'])->name('profile');

require __DIR__.'/auth.php';

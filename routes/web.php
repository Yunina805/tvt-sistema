<?php

use Illuminate\Support\Facades\Route;

// Importaciones de Gestión al Cliente
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
use App\Livewire\GestionClientes\ContratacionPromocion; // Clase exacta de image_6ded60.png

Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    
    // --- SECCIÓN: GESTIÓN AL CLIENTE ---
    
    // Altas y Promociones
    Route::get('/contrataciones-nuevas', ContratacionNueva::class)->name('contrataciones.nuevas');
    Route::get('/servicios-adicionales', ServiciosAdicionales::class)->name('servicios.adicionales');
    // ESTA ES LA RUTA EXACTA PARA TU ARCHIVO ContratacionPromocion.php
    Route::get('/contratacion-promocion', ContratacionPromocion::class)->name('contratacion.promocion');

    // Pagos y Estados de Cuenta
    Route::get('/pago-mensualidad', PagoMensualidad::class)->name('pago.mensualidad');
    // ESTA ES LA RUTA EXACTA PARA TU ARCHIVO EstadoCuenta.php
    Route::get('/estado-cuenta', EstadoCuenta::class)->name('estado.cuenta'); 

    // Operativa de Estatus
    Route::get('/suspension-falta-pago', SuspensionClientes::class)->name('suspension.clientes');
    Route::get('/reconexion-cliente', ReconexionCliente::class)->name('reconexion.cliente');
    Route::get('/cancelacion-servicio', CancelacionServicio::class)->name('cancelacion.servicio');
    Route::get('/recuperacion-equipos', RecuperacionEquipos::class)->name('recuperacion.equipos');

    // Bandeja de Reportes Técnicos
    Route::get('/reportes-servicio', ReportesServicio::class)->name('reportes.servicio');
    Route::get('/reportes-servicio/atender/{folio?}', AtenderReporte::class)->name('reportes.atender');
    
});

Route::view('profile', 'profile')->middleware(['auth'])->name('profile');

require __DIR__.'/auth.php';
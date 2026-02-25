<?php

use Illuminate\Support\Facades\Route;

// Importa las clases correctamente para que Laravel las encuentre
use App\Livewire\GestionClientes\ContratacionNueva;
use App\Livewire\GestionClientes\PagoMensualidad;
use App\Livewire\GestionClientes\ReportesServicio;
use App\Livewire\GestionClientes\AtenderReporte;
use App\Livewire\GestionClientes\ServiciosAdicionales;

Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    
    // Gestión al Cliente
    Route::get('/contrataciones-nuevas', ContratacionNueva::class)->name('contrataciones.nuevas');
    Route::get('/pago-mensualidad', PagoMensualidad::class)->name('pago.mensualidad');
    Route::get('/reconexion-cliente', \App\Livewire\GestionClientes\ReconexionCliente::class)->middleware(['auth', 'verified'])->name('reconexion.cliente');
    Route::get('/reportes-servicio', ReportesServicio::class)->name('reportes.servicio');
    Route::get('/reportes-servicio/atender/{folio?}', AtenderReporte::class)->name('reportes.atender');
    Route::get('/servicios-adicionales', ServiciosAdicionales::class)->name('servicios.adicionales');
    Route::get('/suspension-falta-pago', \App\Livewire\GestionClientes\SuspensionClientes::class)->middleware(['auth', 'verified'])->name('suspension.clientes');
    
    Route::get('/cancelacion-servicio', \App\Livewire\GestionClientes\CancelacionServicio::class)
    ->middleware(['auth', 'verified'])
    ->name('cancelacion.servicio');

    Route::get('/recuperacion-equipos', \App\Livewire\GestionClientes\RecuperacionEquipos::class)
    ->middleware(['auth', 'verified'])
    ->name('recuperacion.equipos');
});

Route::view('profile', 'profile')->middleware(['auth'])->name('profile');

require __DIR__.'/auth.php';
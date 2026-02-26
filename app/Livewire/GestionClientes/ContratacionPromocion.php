<?php

namespace App\Livewire\GestionClientes;

use Livewire\Component;
use Carbon\Carbon;

class ContratacionPromocion extends Component
{
    public $search = '';
    public $cliente = null;
    public $paso = 1; // 1: Buscar, 2: Seleccionar y Calcular
    
    // Promociones disponibles por servicio
    public $promociones = [
        ['id' => 1, 'nombre' => 'PROMO 6X7', 'meses_pago' => 6, 'meses_beneficio' => 7],
        ['id' => 2, 'nombre' => 'ANUALIDAD 12X14', 'meses_pago' => 12, 'meses_beneficio' => 14],
    ];

    public $promoSeleccionada = null;
    public $calculos = [
        'dias_uso' => 0,
        'importe_dias' => 0,
        'importe_promo' => 0,
        'total' => 0,
        'fecha_inicio' => '',
        'fecha_termino' => '',
        'proximo_pago' => ''
    ];

    public function buscarCliente()
    {
        // Simulación de búsqueda
        $this->cliente = [
            'id' => '01-2025',
            'nombre' => 'JUAN PÉREZ GARCÍA',
            'servicio' => 'RETRO TV + INTERNET',
            'tarifa' => 480.00,
            'estado' => 'ACTIVO'
        ];
        $this->paso = 2;
    }

    public function seleccionarPromo($id)
    {
        $promo = collect($this->promociones)->firstWhere('id', $id);
        $this->promoSeleccionada = $promo;

        $hoy = Carbon::now();
        $finMes = Carbon::now()->endOfMonth();
        
        // 1. Calcular Días de Uso (para llegar al día 1 del mes siguiente)
        $diasRestantes = $hoy->diffInDays($finMes) + 1;
        $costoDia = $this->cliente['tarifa'] / 30;
        
        // 2. Calcular Importes
        $importeDias = $costoDia * $diasRestantes;
        $importePromo = $this->cliente['tarifa'] * $promo['meses_pago'];
        
        // 3. Calcular Fechas (Bloques de 30 días)
        $inicio = Carbon::now()->addMonth()->startOfMonth();
        $termino = $inicio->copy()->addDays($promo['meses_beneficio'] * 30);

        $this->calculos = [
            'dias_uso' => $diasRestantes,
            'importe_dias' => $importeDias,
            'importe_promo' => $importePromo,
            'total' => $importeDias + $importePromo,
            'fecha_inicio' => $inicio->format('d/m/Y'),
            'fecha_termino' => $termino->format('d/m/Y'),
            'proximo_pago' => $termino->format('d/m/Y')
        ];
    }

    public function confirmarContratacion()
    {
        // Aquí se afectaría el estatus del cliente y se enviaría el SMS
        session()->flash('mensaje', 'Promoción activada. Próximo pago: ' . $this->calculos['proximo_pago']);
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.gestion-clientes.contratacion-promocion')->layout('layouts.app');
    }
}
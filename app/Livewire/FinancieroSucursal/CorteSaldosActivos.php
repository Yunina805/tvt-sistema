<?php

namespace App\Livewire\FinancieroSucursal;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class CorteSaldosActivos extends Component
{
    public function render()
    {
        return view('livewire.financiero-sucursal.corte-saldos-activos');
    }
}

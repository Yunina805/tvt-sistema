<?php

namespace App\Livewire\Catalogos\RecursosHumanos;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ProductividadTecnicos extends Component
{
    public function render()
    {
        return view('livewire.catalogos.rrhh.productividad-tecnicos');
    }
}

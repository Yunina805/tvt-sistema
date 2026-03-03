<?php

namespace App\Livewire\Catalogos\Servicios;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class RegistroServicios extends Component
{
    public function render()
    {
        return view('livewire.catalogos.servicios.registro-servicios');
    }
}

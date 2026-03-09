<?php

namespace App\Livewire\Catalogos\Servicios;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ServiciosClientes extends Component
{
    public function render()
    {
        return view('livewire.catalogos.servicios.servicios-clientes');
    }
}

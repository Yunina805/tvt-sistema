<?php

namespace App\Livewire\Catalogos\Clientes;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class RegistroClientes extends Component
{
    public function render()
    {
        return view('livewire.catalogos.clientes.registro-clientes');
    }
}

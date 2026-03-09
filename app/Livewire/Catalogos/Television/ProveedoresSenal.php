<?php

namespace App\Livewire\Catalogos\Television;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ProveedoresSenal extends Component
{
    public function render()
    {
        return view('livewire.catalogos.television.proveedores-senal');
    }
}

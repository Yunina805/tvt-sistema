<?php

namespace App\Livewire\Regulatorio;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ProveedoresSignal extends Component
{
    public function render()
    {
        return view('livewire.regulatorio.proveedores-signal');
    }
}

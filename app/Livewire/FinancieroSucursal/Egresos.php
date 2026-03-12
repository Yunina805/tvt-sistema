<?php

namespace App\Livewire\FinancieroSucursal;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Egresos extends Component
{
    public function render()
    {
        return view('livewire.financiero-sucursal.egresos');
    }
}

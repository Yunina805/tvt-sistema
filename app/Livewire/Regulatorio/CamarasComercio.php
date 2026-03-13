<?php

namespace App\Livewire\Regulatorio;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class CamarasComercio extends Component
{
    public function render()
    {
        return view('livewire.regulatorio.camaras-comercio');
    }
}

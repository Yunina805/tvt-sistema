<?php

namespace App\Livewire\Catalogos\Regulatorio;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class EnvioObligaciones extends Component
{
    public function render()
    {
        return view('livewire.catalogos.regulatorio.envio-obligaciones');
    }
}

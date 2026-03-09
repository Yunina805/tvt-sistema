<?php

namespace App\Livewire\Catalogos\Regulatorio;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class EntidadesRegulatorias extends Component
{
    public function render()
    {
        return view('livewire.catalogos.regulatorio.entidades-regulatorias');
    }
}

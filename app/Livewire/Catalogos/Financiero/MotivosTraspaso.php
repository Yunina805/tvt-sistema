<?php

namespace App\Livewire\Catalogos\Financiero;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class MotivosTraspaso extends Component
{
    public function render()
    {
        return view('livewire.catalogos.financiero.motivos-traspaso');
    }
}

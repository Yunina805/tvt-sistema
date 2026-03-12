<?php

namespace App\Livewire\Catalogos\RecursosHumanos;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Nomina extends Component
{
    public function render()
    {
        return view('livewire.catalogos.recursos-humanos.nomina');
    }
}

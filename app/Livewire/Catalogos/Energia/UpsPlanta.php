<?php

namespace App\Livewire\Catalogos\Energia;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class UpsPlanta extends Component
{
    public function render()
    {
        return view('livewire.catalogos.energia.ups-planta');
    }
}

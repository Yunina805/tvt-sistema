<?php

namespace App\Livewire\Catalogos\PlantaExterna;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class TipoFibra extends Component
{
    public function render()
    {
        return view('livewire.catalogos.planta-externa.tipo-fibra');
    }
}

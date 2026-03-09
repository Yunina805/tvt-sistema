<?php

namespace App\Livewire\Catalogos\Energia;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class PlantasEmergencia extends Component
{
    public function render()
    {
        return view('livewire.catalogos.energia.plantas-emergencia');
    }
}

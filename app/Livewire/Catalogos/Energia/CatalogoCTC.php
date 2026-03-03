<?php

namespace App\Livewire\Catalogos\Energia;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class CatalogoCTC extends Component
{
    public function render()
    {
        return view('livewire.catalogos.energia.catalogo-ctc');
    }
}

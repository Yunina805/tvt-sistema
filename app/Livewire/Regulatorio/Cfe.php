<?php

namespace App\Livewire\Regulatorio;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Cfe extends Component
{
    public function render()
    {
        return view('livewire.regulatorio.cfe');
    }
}

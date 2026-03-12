<?php

namespace App\Livewire\Kpis;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class RepMayorAdeudo extends Component
{
    public function render()
    {
        return view('livewire.kpis.rep-mayor-adeudo');
    }
}

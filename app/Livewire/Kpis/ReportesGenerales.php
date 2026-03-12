<?php

namespace App\Livewire\Kpis;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ReportesGenerales extends Component
{
    public function render()
    {
        return view('livewire.kpis.reportes-generales');
    }
}

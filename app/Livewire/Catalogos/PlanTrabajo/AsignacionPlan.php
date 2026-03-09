<?php

namespace App\Livewire\Catalogos\PlanTrabajo;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class AsignacionPlan extends Component
{
    public function render()
    {
        return view('livewire.catalogos.plan-trabajo.asignacion-plan');
    }
}

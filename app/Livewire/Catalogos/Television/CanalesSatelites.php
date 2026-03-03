<?php

namespace App\Livewire\Catalogos\Television;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class CanalesSatelites extends Component
{
    public function render()
    {
        return view('livewire.catalogos.television.canales-satelites');
    }
}

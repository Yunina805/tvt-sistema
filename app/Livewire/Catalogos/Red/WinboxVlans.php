<?php

namespace App\Livewire\Catalogos\Red;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class WinboxVlans extends Component
{
    public function render()
    {
        return view('livewire.catalogos.red.winbox-vlans');
    }
}

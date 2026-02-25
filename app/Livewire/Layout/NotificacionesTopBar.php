<?php

namespace App\Livewire\Layout;

use Livewire\Component;

class NotificacionesTopBar extends Component
{
    public $notificaciones = [
        ['id' => 1, 'titulo' => 'Nuevo Reporte de Instalación', 'mensaje' => 'REQ-2026-0001 asignado a Juan Pérez.', 'tiempo' => 'Hace 2 min', 'leida' => false],
        ['id' => 2, 'titulo' => 'Falla de Internet', 'mensaje' => 'Cliente María López reporta lentitud.', 'tiempo' => 'Hace 1 hora', 'leida' => false],
    ];

    public function marcarComoLeidas()
    {
        foreach ($this->notificaciones as &$notificacion) {
            $notificacion['leida'] = true;
        }
    }

    public function render()
    {
        $noLeidas = collect($this->notificaciones)->where('leida', false)->count();
        return view('livewire.layout.notificaciones-top-bar', compact('noLeidas'));
    }
}
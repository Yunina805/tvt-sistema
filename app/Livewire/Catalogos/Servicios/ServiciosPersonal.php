<?php

namespace App\Livewire\Catalogos\Servicios;

use App\Models\RRHH\Empleado;
use App\Models\Servicios\Servicio;
use App\Traits\WithToasts;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ServiciosPersonal extends Component
{
    use WithPagination, WithToasts;

    public string $modo          = 'lista';
    public string $search        = '';
    public string $filtroActivo  = '';
    public string $filtroPuesto  = '';
    public ?int   $editandoId    = null;

    // Formulario
    public string $nombre         = '';
    public string $puestoAsignado = '';
    public bool   $activo         = true;

    protected function rules(): array
    {
        return [
            'nombre'         => 'required|string|max:150',
            'puestoAsignado' => 'required|string|max:100',
            'activo'         => 'boolean',
        ];
    }

    protected array $validationAttributes = [
        'nombre'         => 'nombre del servicio',
        'puestoAsignado' => 'puesto asignado',
    ];

    public function updatedSearch(): void       { $this->resetPage(); }
    public function updatedFiltroActivo(): void { $this->resetPage(); }
    public function updatedFiltroPuesto(): void { $this->resetPage(); }

    public function nuevo(): void
    {
        $this->resetForm();
        $this->modo = 'crear';
    }

    public function editar(int $id): void
    {
        $s = Servicio::findOrFail($id);
        $this->editandoId     = $id;
        $this->nombre         = $s->nombre;
        $this->puestoAsignado = $s->puesto_asignado ?? '';
        $this->activo         = $s->activo;
        $this->modo = 'editar';
    }

    public function guardar(): void
    {
        $this->validate();

        $data = [
            'tipo'            => 'PERSONAL',
            'nombre'          => $this->nombre,
            'puesto_asignado' => $this->puestoAsignado,
            'activo'          => $this->activo,
        ];

        if ($this->modo === 'crear') {
            $data['user_id'] = Auth::id();
            Servicio::create($data);
            $this->toastExito("Servicio \"{$this->nombre}\" registrado correctamente.");
        } else {
            Servicio::findOrFail($this->editandoId)->update($data);
            $this->toastExito("Servicio \"{$this->nombre}\" actualizado correctamente.");
        }

        $this->cancelar();
    }

    public function toggleActivo(int $id): void
    {
        $s     = Servicio::findOrFail($id);
        $nuevo = !$s->activo;
        $s->update(['activo' => $nuevo]);
        $this->toastInfo($nuevo ? 'Servicio activado.' : 'Servicio desactivado.');
    }

    public function eliminar(int $id): void
    {
        $s      = Servicio::findOrFail($id);
        $nombre = $s->nombre;
        $s->delete();
        $this->toastExito("Servicio \"{$nombre}\" eliminado.");
    }

    public function cancelar(): void
    {
        $this->resetForm();
        $this->modo = 'lista';
    }

    public function limpiarFiltros(): void
    {
        $this->search       = '';
        $this->filtroActivo = '';
        $this->filtroPuesto = '';
        $this->resetPage();
    }

    private function resetForm(): void
    {
        $this->reset(['nombre', 'puestoAsignado', 'editandoId']);
        $this->activo = true;
        $this->resetValidation();
    }

    public function render()
    {
        $total     = Servicio::where('tipo', 'PERSONAL')->count();
        $activos   = Servicio::where('tipo', 'PERSONAL')->where('activo', true)->count();
        $inactivos = Servicio::where('tipo', 'PERSONAL')->where('activo', false)->count();

        $servicios = Servicio::where('tipo', 'PERSONAL')
            ->when($this->search, fn($q) => $q->where('nombre', 'like', "%{$this->search}%"))
            ->when($this->filtroActivo !== '', fn($q) => $q->where('activo', $this->filtroActivo === '1'))
            ->when($this->filtroPuesto, fn($q) => $q->where('puesto_asignado', $this->filtroPuesto))
            ->with(['usuario'])
            ->orderByDesc('created_at')
            ->paginate(15);

        $puestos = Empleado::where('activo', true)->orderBy('puesto')->distinct()->pluck('puesto');

        return view('livewire.catalogos.servicios.personal', compact(
            'servicios', 'total', 'activos', 'inactivos', 'puestos'
        ));
    }
}

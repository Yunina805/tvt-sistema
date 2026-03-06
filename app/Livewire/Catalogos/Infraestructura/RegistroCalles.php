<?php

namespace App\Livewire\Catalogos\Infraestructura;

use App\Models\Infraestructura\Calle;
use App\Models\Infraestructura\Sucursal;
use App\Traits\WithToasts;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class RegistroCalles extends Component
{
    use WithPagination, WithToasts;

    public string $modo = 'lista'; // lista | crear | editar
    public string $search = '';
    public string $filtroSucursalId = '';
    public string $filtroEstado = '';
    public ?int $editandoId = null;

    // Formulario
    public string $nombreCalle = '';
    public string $sucursalId = '';
    public ?array $sucursalInfo = null;

    protected function rules(): array
    {
        return [
            'nombreCalle' => 'required|string|max:120',
            'sucursalId'  => 'required|exists:sucursales,id',
        ];
    }

    protected array $validationAttributes = [
        'nombreCalle' => 'nombre de calle',
        'sucursalId'  => 'sucursal',
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFiltroSucursalId(): void { $this->resetPage(); }
    public function updatedFiltroEstado(): void { $this->resetPage(); }

    public function updatedSucursalId(): void
    {
        if ($this->sucursalId) {
            $s = Sucursal::with(['localidad', 'municipio', 'estado'])->find($this->sucursalId);
            $this->sucursalInfo = $s ? [
                'localidad' => $s->localidad?->nombre_localidad,
                'municipio' => $s->municipio?->nombre_municipio,
                'estado'    => $s->estado?->nombre_estado,
                'cp'        => $s->codigo_postal,
            ] : null;
        } else {
            $this->sucursalInfo = null;
        }
    }

    public function nuevaCalle(): void
    {
        $this->resetForm();
        $this->modo = 'crear';
    }

    public function editar(int $id): void
    {
        $calle = Calle::with(['sucursal.localidad', 'sucursal.municipio', 'sucursal.estado'])->findOrFail($id);
        $this->editandoId = $id;
        $this->nombreCalle = $calle->nombre_calle;
        $this->sucursalId = (string) $calle->sucursal_id;
        $s = $calle->sucursal;
        $this->sucursalInfo = $s ? [
            'localidad' => $s->localidad?->nombre_localidad,
            'municipio' => $s->municipio?->nombre_municipio,
            'estado'    => $s->estado?->nombre_estado,
            'cp'        => $s->codigo_postal,
        ] : null;
        $this->modo = 'editar';
    }

    public function guardar(): void
    {
        $this->validate();

        if ($this->modo === 'crear') {
            Calle::create([
                'nombre_calle' => $this->nombreCalle,
                'sucursal_id'  => $this->sucursalId,
                'activa'       => true,
            ]);
            $this->toastExito("Calle \"{$this->nombreCalle}\" registrada correctamente.");
        } else {
            Calle::findOrFail($this->editandoId)->update([
                'nombre_calle' => $this->nombreCalle,
                'sucursal_id'  => $this->sucursalId,
            ]);
            $this->toastExito("Calle \"{$this->nombreCalle}\" actualizada correctamente.");
        }

        $this->cancelar();
    }

    public function eliminar(int $id): void
    {
        $calle = Calle::findOrFail($id);
        $calle->update(['activa' => false]);
        $this->toastInfo("Calle \"{$calle->nombre_calle}\" desactivada.");
    }

    public function cancelar(): void
    {
        $this->resetForm();
        $this->modo = 'lista';
    }

    private function resetForm(): void
    {
        $this->reset(['nombreCalle', 'sucursalId', 'sucursalInfo', 'editandoId']);
        $this->resetValidation();
    }

    public function limpiarFiltros(): void
    {
        $this->search = '';
        $this->filtroSucursalId = '';
        $this->filtroEstado = '';
        $this->resetPage();
    }

    public function render()
    {
        $sucursales = Sucursal::where('activa', true)->orderBy('nombre')->get(['id', 'clave', 'nombre']);

        $calles = Calle::with(['sucursal.localidad', 'sucursal.municipio', 'sucursal.estado'])
            ->when($this->filtroEstado !== '', fn($q) => $q->where('activa', $this->filtroEstado === '1'), fn($q) => $q->where('activa', true))
            ->when($this->search, fn($q) => $q->where('nombre_calle', 'like', "%{$this->search}%"))
            ->when($this->filtroSucursalId, fn($q) => $q->where('sucursal_id', $this->filtroSucursalId))
            ->orderBy('nombre_calle')
            ->paginate(15);

        return view('livewire.catalogos.infraestructura.registro-calles', [
            'sucursales' => $sucursales,
            'calles'     => $calles,
        ]);
    }
}

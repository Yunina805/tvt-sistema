<?php

namespace App\Livewire\Catalogos\Servicios;

use App\Models\Financiero\TarifaAdicional;
use App\Models\Servicios\Servicio;
use App\Traits\WithToasts;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ServiciosTarifasAdicionales extends Component
{
    use WithPagination, WithToasts;

    public string $modo         = 'lista';
    public string $search       = '';
    public string $filtroActivo = '';
    public ?int   $editandoId   = null;

    // Formulario
    public string $nombre            = '';
    public string $tarifaAdicionalId = '';
    public bool   $activo            = true;

    protected function rules(): array
    {
        return [
            'nombre'            => 'required|string|max:150',
            'tarifaAdicionalId' => 'required|exists:tarifas_adicionales,id',
            'activo'            => 'boolean',
        ];
    }

    protected array $validationAttributes = [
        'nombre'            => 'nombre del servicio',
        'tarifaAdicionalId' => 'tarifa adicional',
    ];

    public function updatedSearch(): void       { $this->resetPage(); }
    public function updatedFiltroActivo(): void { $this->resetPage(); }

    public function nuevo(): void
    {
        $this->resetForm();
        $this->modo = 'crear';
    }

    public function editar(int $id): void
    {
        $s = Servicio::findOrFail($id);
        $this->editandoId        = $id;
        $this->nombre            = $s->nombre;
        $this->tarifaAdicionalId = (string) ($s->tarifa_adicional_id ?? '');
        $this->activo            = $s->activo;
        $this->modo = 'editar';
    }

    public function guardar(): void
    {
        $this->validate();

        $data = [
            'tipo'               => 'TARIFA_ADICIONAL',
            'nombre'             => $this->nombre,
            'tarifa_adicional_id' => $this->tarifaAdicionalId,
            'activo'             => $this->activo,
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
        $this->resetPage();
    }

    private function resetForm(): void
    {
        $this->reset(['nombre', 'tarifaAdicionalId', 'editandoId']);
        $this->activo = true;
        $this->resetValidation();
    }

    public function render()
    {
        $total     = Servicio::where('tipo', 'TARIFA_ADICIONAL')->count();
        $activos   = Servicio::where('tipo', 'TARIFA_ADICIONAL')->where('activo', true)->count();
        $inactivos = Servicio::where('tipo', 'TARIFA_ADICIONAL')->where('activo', false)->count();

        $servicios = Servicio::where('tipo', 'TARIFA_ADICIONAL')
            ->when($this->search, fn($q) => $q->where('nombre', 'like', "%{$this->search}%"))
            ->when($this->filtroActivo !== '', fn($q) => $q->where('activo', $this->filtroActivo === '1'))
            ->with(['tarifaAdicional', 'usuario'])
            ->orderByDesc('created_at')
            ->paginate(15);

        $tarifasAdicionales = TarifaAdicional::where('estado', 'VIGENTE')
            ->orderBy('nombre_comercial')->get(['id', 'nombre_comercial']);

        return view('livewire.catalogos.servicios.tarifas-adicionales', compact(
            'servicios', 'total', 'activos', 'inactivos', 'tarifasAdicionales'
        ));
    }
}

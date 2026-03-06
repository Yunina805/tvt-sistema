<?php

namespace App\Livewire\Catalogos\Servicios;

use App\Models\Financiero\TarifaPrincipal;
use App\Models\Servicios\Servicio;
use App\Traits\WithToasts;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ServiciosTarifasPrincipales extends Component
{
    use WithPagination, WithToasts;

    public string $modo         = 'lista';
    public string $search       = '';
    public string $filtroActivo = '';
    public ?int   $editandoId   = null;

    // Formulario
    public string $nombre            = '';
    public string $tarifaPrincipalId = '';
    public bool   $activo            = true;

    protected function rules(): array
    {
        return [
            'nombre'            => 'required|string|max:150',
            'tarifaPrincipalId' => 'required|exists:tarifas_principales,id',
            'activo'            => 'boolean',
        ];
    }

    protected array $validationAttributes = [
        'nombre'            => 'nombre del servicio',
        'tarifaPrincipalId' => 'tarifa principal',
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
        $this->tarifaPrincipalId = (string) ($s->tarifa_principal_id ?? '');
        $this->activo            = $s->activo;
        $this->modo = 'editar';
    }

    public function guardar(): void
    {
        $this->validate();

        $data = [
            'tipo'                => 'TARIFA_PRINCIPAL',
            'nombre'              => $this->nombre,
            'tarifa_principal_id' => $this->tarifaPrincipalId,
            'activo'              => $this->activo,
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
        $this->reset(['nombre', 'tarifaPrincipalId', 'editandoId']);
        $this->activo = true;
        $this->resetValidation();
    }

    public function render()
    {
        $total    = Servicio::where('tipo', 'TARIFA_PRINCIPAL')->count();
        $activos  = Servicio::where('tipo', 'TARIFA_PRINCIPAL')->where('activo', true)->count();
        $inactivos = Servicio::where('tipo', 'TARIFA_PRINCIPAL')->where('activo', false)->count();

        $servicios = Servicio::where('tipo', 'TARIFA_PRINCIPAL')
            ->when($this->search, fn($q) => $q->where('nombre', 'like', "%{$this->search}%"))
            ->when($this->filtroActivo !== '', fn($q) => $q->where('activo', $this->filtroActivo === '1'))
            ->with(['tarifaPrincipal', 'usuario'])
            ->orderByDesc('created_at')
            ->paginate(15);

        $tarifasPrincipales = TarifaPrincipal::whereIn('estado', ['VIGENTE_CONTRATAR', 'VIGENTE_MENSUALIDAD'])
            ->orderBy('nombre_comercial')->get(['id', 'nombre_comercial']);

        return view('livewire.catalogos.servicios.tarifas-principales', compact(
            'servicios', 'total', 'activos', 'inactivos', 'tarifasPrincipales'
        ));
    }
}

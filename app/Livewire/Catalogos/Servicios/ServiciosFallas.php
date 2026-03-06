<?php

namespace App\Livewire\Catalogos\Servicios;

use App\Models\Servicios\Servicio;
use App\Traits\WithToasts;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ServiciosFallas extends Component
{
    use WithPagination, WithToasts;

    public string $modo            = 'lista';
    public string $search          = '';
    public string $filtroActivo    = '';
    public string $filtroReportado = '';
    public ?int   $editandoId      = null;

    // Formulario
    public string $nombre        = '';
    public string $quienReporta  = '';
    public bool   $activo        = true;

    protected function rules(): array
    {
        return [
            'nombre'       => 'required|string|max:150',
            'quienReporta' => 'required|in:CLIENTE,TU_VISION',
            'activo'       => 'boolean',
        ];
    }

    protected array $validationAttributes = [
        'nombre'       => 'nombre del servicio',
        'quienReporta' => 'quien reporta',
    ];

    public function updatedSearch(): void          { $this->resetPage(); }
    public function updatedFiltroActivo(): void    { $this->resetPage(); }
    public function updatedFiltroReportado(): void { $this->resetPage(); }

    public function nuevo(): void
    {
        $this->resetForm();
        $this->modo = 'crear';
    }

    public function editar(int $id): void
    {
        $s = Servicio::findOrFail($id);
        $this->editandoId   = $id;
        $this->nombre       = $s->nombre;
        $this->quienReporta = $s->quien_reporta ?? '';
        $this->activo       = $s->activo;
        $this->modo = 'editar';
    }

    public function guardar(): void
    {
        $this->validate();

        $data = [
            'tipo'          => 'FALLA_SERVICIO',
            'nombre'        => $this->nombre,
            'quien_reporta' => $this->quienReporta,
            'activo'        => $this->activo,
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
        $this->search          = '';
        $this->filtroActivo    = '';
        $this->filtroReportado = '';
        $this->resetPage();
    }

    private function resetForm(): void
    {
        $this->reset(['nombre', 'quienReporta', 'editandoId']);
        $this->activo = true;
        $this->resetValidation();
    }

    public function render()
    {
        $total    = Servicio::where('tipo', 'FALLA_SERVICIO')->count();
        $activos  = Servicio::where('tipo', 'FALLA_SERVICIO')->where('activo', true)->count();
        $inactivos = Servicio::where('tipo', 'FALLA_SERVICIO')->where('activo', false)->count();
        $porCliente   = Servicio::where('tipo', 'FALLA_SERVICIO')->where('quien_reporta', 'CLIENTE')->count();
        $porTuVision  = Servicio::where('tipo', 'FALLA_SERVICIO')->where('quien_reporta', 'TU_VISION')->count();

        $servicios = Servicio::where('tipo', 'FALLA_SERVICIO')
            ->when($this->search, fn($q) => $q->where('nombre', 'like', "%{$this->search}%"))
            ->when($this->filtroActivo !== '', fn($q) => $q->where('activo', $this->filtroActivo === '1'))
            ->when($this->filtroReportado, fn($q) => $q->where('quien_reporta', $this->filtroReportado))
            ->with(['usuario'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('livewire.catalogos.servicios.fallas', compact(
            'servicios', 'total', 'activos', 'inactivos', 'porCliente', 'porTuVision'
        ));
    }
}

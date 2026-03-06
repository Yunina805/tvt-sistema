<?php

namespace App\Livewire\Catalogos\Servicios;

use App\Models\RRHH\Empleado;
use App\Models\Servicios\Actividad;
use App\Models\Servicios\Servicio;
use App\Traits\WithToasts;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ActividadesFallas extends Component
{
    use WithPagination, WithToasts;

    const TIPO = 'FALLA_SERVICIO';

    public string $modo = 'lista';
    public ?int $editandoId = null;

    public string $search = '';
    public string $filtroPuesto = '';
    public string $filtroActivo = '';

    public string $nombre = '';
    public string $servicioId = '';
    public string $puestoResponsable = '';
    public bool   $activo = true;

    protected function rules(): array
    {
        return [
            'nombre'            => 'required|string|max:150',
            'servicioId'        => 'nullable|exists:servicios,id',
            'puestoResponsable' => 'nullable|string|max:100',
        ];
    }

    protected array $validationAttributes = [
        'nombre'            => 'nombre de la actividad',
        'servicioId'        => 'servicio asociado',
        'puestoResponsable' => 'responsable',
    ];

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedFiltroPuesto(): void { $this->resetPage(); }

    public function nuevo(): void
    {
        $this->resetForm();
        $this->modo = 'crear';
    }

    public function editar(int $id): void
    {
        $act = Actividad::findOrFail($id);
        $this->editandoId        = $id;
        $this->nombre            = $act->nombre;
        $this->servicioId        = $act->servicio_id ? (string) $act->servicio_id : '';
        $this->puestoResponsable = $act->puesto_responsable ?? '';
        $this->activo            = $act->activo;
        $this->modo = 'editar';
    }

    public function guardar(): void
    {
        $this->validate();

        $data = [
            'tipo'               => self::TIPO,
            'nombre'             => strtoupper(trim($this->nombre)),
            'servicio_id'        => $this->servicioId ?: null,
            'puesto_responsable' => $this->puestoResponsable ?: null,
            'activo'             => $this->activo,
        ];

        if ($this->modo === 'crear') {
            $data['user_id'] = Auth::id();
            Actividad::create($data);
            $this->toastExito('Actividad registrada correctamente.');
        } else {
            Actividad::findOrFail($this->editandoId)->update($data);
            $this->toastExito('Actividad actualizada correctamente.');
        }

        $this->cancelar();
    }

    public function toggleActivo(int $id): void
    {
        $act   = Actividad::findOrFail($id);
        $nuevo = !$act->activo;
        $act->update(['activo' => $nuevo]);
        $this->toastInfo('Actividad ' . ($nuevo ? 'activada' : 'desactivada') . '.');
    }

    public function eliminar(int $id): void
    {
        Actividad::findOrFail($id)->delete();
        $this->toastExito('Actividad eliminada.');
    }

    public function cancelar(): void
    {
        $this->resetForm();
        $this->modo = 'lista';
    }

    private function resetForm(): void
    {
        $this->reset(['editandoId', 'nombre', 'servicioId', 'puestoResponsable']);
        $this->activo = true;
        $this->resetValidation();
    }

    public function limpiarFiltros(): void
    {
        $this->search = '';
        $this->filtroPuesto = '';
        $this->filtroActivo = '';
        $this->resetPage();
    }

    public function render()
    {
        $total   = Actividad::where('tipo', self::TIPO)->count();
        $activos = Actividad::where('tipo', self::TIPO)->where('activo', true)->count();

        $servicios = Servicio::where('tipo', self::TIPO)
            ->where('activo', true)
            ->orderBy('nombre')
            ->get(['id', 'nombre']);

        $puestos = Empleado::where('activo', true)
            ->whereNotNull('puesto')
            ->distinct()
            ->orderBy('puesto')
            ->pluck('puesto');

        $actividades = Actividad::with('servicio')
            ->where('tipo', self::TIPO)
            ->when($this->search, fn($q) => $q->where('nombre', 'like', "%{$this->search}%"))
            ->when($this->filtroPuesto, fn($q) => $q->where('puesto_responsable', $this->filtroPuesto))
            ->when($this->filtroActivo !== '', fn($q) => $q->where('activo', $this->filtroActivo === '1'))
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('livewire.catalogos.servicios.actividades-fallas', [
            'total'       => $total,
            'activos'     => $activos,
            'servicios'   => $servicios,
            'puestos'     => $puestos,
            'actividades' => $actividades,
        ]);
    }
}

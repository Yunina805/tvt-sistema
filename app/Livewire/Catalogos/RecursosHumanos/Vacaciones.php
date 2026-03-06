<?php

namespace App\Livewire\Catalogos\RecursosHumanos;

use App\Models\RRHH\Empleado;
use App\Models\RRHH\Vacacion;
use App\Traits\WithToasts;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Vacaciones extends Component
{
    use WithPagination, WithToasts;

    public string $modo = 'lista'; // lista | crear | editar
    public string $search = '';
    public string $filtroEstado = '';
    public string $filtroEmpleadoId = '';
    public ?int $editandoId = null;

    // Formulario
    public string $empleadoId = '';
    public string $anio = '';
    public string $diasAsignados = '6';
    public string $fechaInicio = '';
    public string $fechaFin = '';
    public string $observaciones = '';

    protected function rules(): array
    {
        return [
            'empleadoId'    => 'required|exists:empleados,id',
            'anio'          => 'required|integer|min:2000|max:2099',
            'diasAsignados' => 'required|integer|min:1|max:365',
            'fechaInicio'   => 'nullable|date',
            'fechaFin'      => 'nullable|date|after_or_equal:fechaInicio',
            'observaciones' => 'nullable|string|max:500',
        ];
    }

    protected array $validationAttributes = [
        'empleadoId'    => 'empleado',
        'anio'          => 'año',
        'diasAsignados' => 'días asignados',
        'fechaInicio'   => 'fecha inicio',
        'fechaFin'      => 'fecha fin',
    ];

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedFiltroEstado(): void { $this->resetPage(); }
    public function updatedFiltroEmpleadoId(): void { $this->resetPage(); }

    public function nuevaSolicitud(): void
    {
        $this->resetForm();
        $this->anio = (string) now()->year;
        $this->modo = 'crear';
    }

    public function editar(int $id): void
    {
        $vac = Vacacion::findOrFail($id);
        $this->editandoId    = $id;
        $this->empleadoId    = (string) $vac->empleado_id;
        $this->anio          = (string) $vac->anio;
        $this->diasAsignados = (string) $vac->dias_asignados;
        $this->fechaInicio   = $vac->fecha_inicio?->format('Y-m-d') ?? '';
        $this->fechaFin      = $vac->fecha_fin?->format('Y-m-d') ?? '';
        $this->observaciones = $vac->observaciones ?? '';
        $this->modo = 'editar';
    }

    public function guardar(): void
    {
        $this->validate();

        $data = [
            'empleado_id'    => $this->empleadoId,
            'anio'           => $this->anio,
            'dias_asignados' => $this->diasAsignados,
            'fecha_inicio'   => $this->fechaInicio ?: null,
            'fecha_fin'      => $this->fechaFin ?: null,
            'observaciones'  => $this->observaciones ?: null,
        ];

        if ($this->modo === 'crear') {
            $data['estado'] = 'PENDIENTE';
            Vacacion::create($data);
            $this->toastExito('Solicitud de vacaciones registrada correctamente.');
        } else {
            Vacacion::findOrFail($this->editandoId)->update($data);
            $this->toastExito('Solicitud de vacaciones actualizada correctamente.');
        }

        $this->cancelar();
    }

    public function aprobar(int $id): void
    {
        Vacacion::findOrFail($id)->update(['estado' => 'APROBADO']);
        $this->toastExito('Solicitud aprobada.');
    }

    public function rechazar(int $id): void
    {
        Vacacion::findOrFail($id)->update(['estado' => 'RECHAZADO']);
        $this->toastInfo('Solicitud rechazada.');
    }

    public function marcarTomado(int $id): void
    {
        $vac = Vacacion::findOrFail($id);
        $vac->update([
            'estado'       => 'TOMADO',
            'dias_tomados' => $vac->dias_asignados,
        ]);
        $this->toastExito('Vacaciones marcadas como tomadas.');
    }

    public function cancelar(): void
    {
        $this->resetForm();
        $this->modo = 'lista';
    }

    private function resetForm(): void
    {
        $this->reset(['empleadoId', 'anio', 'fechaInicio', 'fechaFin', 'observaciones', 'editandoId']);
        $this->diasAsignados = '6';
        $this->resetValidation();
    }

    public function eliminar(int $id): void
    {
        Vacacion::findOrFail($id)->delete();
        $this->toastExito('Solicitud de vacaciones eliminada.');
    }

    public function limpiarFiltros(): void
    {
        $this->search = '';
        $this->filtroEstado = '';
        $this->filtroEmpleadoId = '';
        $this->resetPage();
    }

    public function render()
    {
        $totalSolicitudes = Vacacion::count();
        $pendientes       = Vacacion::where('estado', 'PENDIENTE')->count();
        $aprobadas        = Vacacion::where('estado', 'APROBADO')->count();
        $tomadas          = Vacacion::where('estado', 'TOMADO')->count();

        $empleados = Empleado::where('activo', true)->orderBy('apellido_paterno')->get(['id', 'nombre', 'apellido_paterno', 'apellido_materno']);

        $vacaciones = Vacacion::with('empleado')
            ->when($this->search, fn($q) => $q->whereHas('empleado', fn($e) =>
                $e->where('nombre', 'like', "%{$this->search}%")
                  ->orWhere('apellido_paterno', 'like', "%{$this->search}%")
            ))
            ->when($this->filtroEstado, fn($q) => $q->where('estado', $this->filtroEstado))
            ->when($this->filtroEmpleadoId, fn($q) => $q->where('empleado_id', $this->filtroEmpleadoId))
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('livewire.catalogos.recursos-humanos.vacaciones', [
            'totalSolicitudes' => $totalSolicitudes,
            'pendientes'       => $pendientes,
            'aprobadas'        => $aprobadas,
            'tomadas'          => $tomadas,
            'empleados'        => $empleados,
            'vacaciones'       => $vacaciones,
        ]);
    }
}

<?php

namespace App\Livewire\Catalogos\RecursosHumanos;

use App\Models\RRHH\Empleado;
use App\Models\RRHH\Permiso;
use App\Traits\WithToasts;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Permisos extends Component
{
    use WithPagination, WithToasts;

    public string $modo = 'lista'; // lista | crear | editar
    public string $search = '';
    public string $filtroTipo = '';
    public string $filtroEstado = '';
    public ?int $editandoId = null;

    // Formulario
    public string $empleadoId = '';
    public string $tipoPermiso = '';
    public string $fechaInicio = '';
    public string $fechaFin = '';
    public int    $diasTotales = 0;
    public string $motivo = '';
    public string $observaciones = '';

    protected function rules(): array
    {
        return [
            'empleadoId'   => 'required|exists:empleados,id',
            'tipoPermiso'  => 'required|in:PERSONAL,MEDICO,ECONOMICO,LICENCIA',
            'fechaInicio'  => 'required|date',
            'fechaFin'     => 'required|date|after_or_equal:fechaInicio',
            'motivo'       => 'nullable|string|max:500',
            'observaciones' => 'nullable|string|max:500',
        ];
    }

    protected array $validationAttributes = [
        'empleadoId'  => 'empleado',
        'tipoPermiso' => 'tipo de permiso',
        'fechaInicio' => 'fecha inicio',
        'fechaFin'    => 'fecha fin',
    ];

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedFiltroTipo(): void { $this->resetPage(); }
    public function updatedFiltroEstado(): void { $this->resetPage(); }

    public function updatedFechaInicio(): void { $this->calcularDias(); }
    public function updatedFechaFin(): void { $this->calcularDias(); }

    private function calcularDias(): void
    {
        if ($this->fechaInicio && $this->fechaFin) {
            try {
                $inicio = \Carbon\Carbon::parse($this->fechaInicio);
                $fin    = \Carbon\Carbon::parse($this->fechaFin);
                $this->diasTotales = max(1, $inicio->diffInDays($fin) + 1);
            } catch (\Throwable) {
                $this->diasTotales = 0;
            }
        } else {
            $this->diasTotales = 0;
        }
    }

    public function nuevoPermiso(): void
    {
        $this->resetForm();
        $this->modo = 'crear';
    }

    public function editar(int $id): void
    {
        $p = Permiso::findOrFail($id);
        $this->editandoId    = $id;
        $this->empleadoId    = (string) $p->empleado_id;
        $this->tipoPermiso   = $p->tipo_permiso;
        $this->fechaInicio   = $p->fecha_inicio->format('Y-m-d');
        $this->fechaFin      = $p->fecha_fin->format('Y-m-d');
        $this->diasTotales   = $p->dias_totales;
        $this->motivo        = $p->motivo ?? '';
        $this->observaciones = $p->observaciones ?? '';
        $this->modo = 'editar';
    }

    public function guardar(): void
    {
        $this->validate();
        $this->calcularDias();

        $data = [
            'empleado_id'  => $this->empleadoId,
            'tipo_permiso' => $this->tipoPermiso,
            'fecha_inicio' => $this->fechaInicio,
            'fecha_fin'    => $this->fechaFin,
            'dias_totales' => $this->diasTotales,
            'motivo'       => $this->motivo ?: null,
            'observaciones' => $this->observaciones ?: null,
        ];

        if ($this->modo === 'crear') {
            $data['estado'] = 'PENDIENTE';
            Permiso::create($data);
            $this->toastExito('Permiso registrado correctamente.');
        } else {
            Permiso::findOrFail($this->editandoId)->update($data);
            $this->toastExito('Permiso actualizado correctamente.');
        }

        $this->cancelar();
    }

    public function aprobar(int $id): void
    {
        Permiso::findOrFail($id)->update(['estado' => 'APROBADO']);
        $this->toastExito('Permiso aprobado.');
    }

    public function rechazar(int $id): void
    {
        Permiso::findOrFail($id)->update(['estado' => 'RECHAZADO']);
        $this->toastInfo('Permiso rechazado.');
    }

    public function cancelar(): void
    {
        $this->resetForm();
        $this->modo = 'lista';
    }

    private function resetForm(): void
    {
        $this->reset(['empleadoId', 'tipoPermiso', 'fechaInicio', 'fechaFin', 'motivo', 'observaciones', 'editandoId']);
        $this->diasTotales = 0;
        $this->resetValidation();
    }

    public function eliminar(int $id): void
    {
        Permiso::findOrFail($id)->delete();
        $this->toastExito('Permiso eliminado.');
    }

    public function limpiarFiltros(): void
    {
        $this->search = '';
        $this->filtroTipo = '';
        $this->filtroEstado = '';
        $this->resetPage();
    }

    public function render()
    {
        $anioActual  = now()->year;
        $mesActual   = now()->month;

        $totalPermisos = Permiso::count();
        $pendientes    = Permiso::where('estado', 'PENDIENTE')->count();
        $aprobados     = Permiso::where('estado', 'APROBADO')->count();
        $diasMes       = Permiso::where('estado', 'APROBADO')
            ->whereYear('fecha_inicio', $anioActual)
            ->whereMonth('fecha_inicio', $mesActual)
            ->sum('dias_totales');

        $empleados = Empleado::where('activo', true)->orderBy('apellido_paterno')->get(['id', 'nombre', 'apellido_paterno', 'apellido_materno']);

        $permisos = Permiso::with('empleado')
            ->when($this->search, fn($q) => $q->whereHas('empleado', fn($e) =>
                $e->where('nombre', 'like', "%{$this->search}%")
                  ->orWhere('apellido_paterno', 'like', "%{$this->search}%")
            ))
            ->when($this->filtroTipo, fn($q) => $q->where('tipo_permiso', $this->filtroTipo))
            ->when($this->filtroEstado, fn($q) => $q->where('estado', $this->filtroEstado))
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('livewire.catalogos.recursos-humanos.permisos', [
            'totalPermisos' => $totalPermisos,
            'pendientes'    => $pendientes,
            'aprobados'     => $aprobados,
            'diasMes'       => $diasMes,
            'empleados'     => $empleados,
            'permisos'      => $permisos,
        ]);
    }
}

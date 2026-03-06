<?php

namespace App\Livewire\Catalogos\RecursosHumanos;

use App\Models\RRHH\DescansoMensual as DescansoMensualModel;
use App\Models\RRHH\Empleado;
use App\Traits\WithToasts;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class DescansoMensual extends Component
{
    use WithPagination, WithToasts;

    public string $modo = 'lista'; // lista | crear | editar
    public string $search = '';
    public string $filtroAnio = '';
    public string $filtroMes = '';
    public string $filtroEstado = '';
    public ?int $editandoId = null;

    // Formulario
    public string $empleadoId = '';
    public string $anio = '';
    public string $mes = '';
    public string $diasAsignados = '';
    public string $observaciones = '';

    protected function rules(): array
    {
        return [
            'empleadoId'    => 'required|exists:empleados,id',
            'anio'          => 'required|integer|min:2000|max:2099',
            'mes'           => 'required|integer|min:1|max:12',
            'diasAsignados' => 'required|integer|min:1|max:31',
            'observaciones' => 'nullable|string|max:500',
        ];
    }

    protected array $validationAttributes = [
        'empleadoId'    => 'empleado',
        'anio'          => 'año',
        'mes'           => 'mes',
        'diasAsignados' => 'días asignados',
    ];

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedFiltroAnio(): void { $this->resetPage(); }
    public function updatedFiltroMes(): void { $this->resetPage(); }
    public function updatedFiltroEstado(): void { $this->resetPage(); }

    public function nuevoDescanso(): void
    {
        $this->resetForm();
        $this->anio = (string) now()->year;
        $this->mes  = (string) now()->month;
        $this->modo = 'crear';
    }

    public function editar(int $id): void
    {
        $d = DescansoMensualModel::findOrFail($id);
        $this->editandoId    = $id;
        $this->empleadoId    = (string) $d->empleado_id;
        $this->anio          = (string) $d->anio;
        $this->mes           = (string) $d->mes;
        $this->diasAsignados = (string) $d->dias_asignados;
        $this->observaciones = $d->observaciones ?? '';
        $this->modo = 'editar';
    }

    public function guardar(): void
    {
        $this->validate();

        $data = [
            'empleado_id'    => $this->empleadoId,
            'anio'           => $this->anio,
            'mes'            => $this->mes,
            'dias_asignados' => $this->diasAsignados,
            'observaciones'  => $this->observaciones ?: null,
        ];

        if ($this->modo === 'crear') {
            $data['estado'] = 'PENDIENTE';
            DescansoMensualModel::create($data);
            $this->toastExito('Registro de descanso creado correctamente.');
        } else {
            DescansoMensualModel::findOrFail($this->editandoId)->update($data);
            $this->toastExito('Registro de descanso actualizado correctamente.');
        }

        $this->cancelar();
    }

    public function aprobar(int $id): void
    {
        DescansoMensualModel::findOrFail($id)->update(['estado' => 'APROBADO']);
        $this->toastExito('Descanso aprobado.');
    }

    public function rechazar(int $id): void
    {
        DescansoMensualModel::findOrFail($id)->update(['estado' => 'RECHAZADO']);
        $this->toastInfo('Descanso rechazado.');
    }

    public function registrarDescanso(int $id): void
    {
        $d = DescansoMensualModel::findOrFail($id);
        $d->update(['dias_tomados' => min($d->dias_tomados + 1, $d->dias_asignados)]);
        $this->toastExito('Día de descanso registrado.');
    }

    public function cancelar(): void
    {
        $this->resetForm();
        $this->modo = 'lista';
    }

    private function resetForm(): void
    {
        $this->reset(['empleadoId', 'anio', 'mes', 'diasAsignados', 'observaciones', 'editandoId']);
        $this->resetValidation();
    }

    public function eliminar(int $id): void
    {
        DescansoMensualModel::findOrFail($id)->delete();
        $this->toastExito('Registro de descanso eliminado.');
    }

    public function limpiarFiltros(): void
    {
        $this->search = '';
        $this->filtroAnio = '';
        $this->filtroMes = '';
        $this->filtroEstado = '';
        $this->resetPage();
    }

    public function render()
    {
        $anioActual = now()->year;
        $mesActual  = now()->month;

        $totalRegistros = DescansoMensualModel::count();
        $pendientes     = DescansoMensualModel::where('estado', 'PENDIENTE')->count();
        $aprobadosMes   = DescansoMensualModel::where('estado', 'APROBADO')
            ->where('anio', $anioActual)
            ->where('mes', $mesActual)
            ->count();

        $empleados = Empleado::where('activo', true)->orderBy('apellido_paterno')->get(['id', 'nombre', 'apellido_paterno', 'apellido_materno']);

        $descansos = DescansoMensualModel::with('empleado')
            ->when($this->search, fn($q) => $q->whereHas('empleado', fn($e) => $e->where('nombre', 'like', "%{$this->search}%")->orWhere('apellido_paterno', 'like', "%{$this->search}%")->orWhere('apellido_materno', 'like', "%{$this->search}%")))
            ->when($this->filtroAnio, fn($q) => $q->where('anio', $this->filtroAnio))
            ->when($this->filtroMes, fn($q) => $q->where('mes', $this->filtroMes))
            ->when($this->filtroEstado, fn($q) => $q->where('estado', $this->filtroEstado))
            ->orderByDesc('anio')
            ->orderByDesc('mes')
            ->paginate(15);

        return view('livewire.catalogos.recursos-humanos.descanso-mensual', [
            'totalRegistros' => $totalRegistros,
            'pendientes'     => $pendientes,
            'aprobadosMes'   => $aprobadosMes,
            'empleados'      => $empleados,
            'descansos'      => $descansos,
            'anioActual'     => $anioActual,
            'mesActual'      => $mesActual,
        ]);
    }
}

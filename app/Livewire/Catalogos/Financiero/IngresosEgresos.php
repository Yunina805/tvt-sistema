<?php

namespace App\Livewire\Catalogos\Financiero;

use App\Models\Financiero\TipoIngresoEgreso;
use App\Traits\WithToasts;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class IngresosEgresos extends Component
{
    use WithPagination, WithToasts;

    public string $modo       = 'lista';
    public string $search      = '';
    public string $filtroTipo  = '';
    public string $filtroActivo = '';
    public ?int $editandoId   = null;

    // Formulario
    public string $tipo              = '';
    public string $nombre            = '';
    public string $fechaHabilitacion = '';
    public bool   $sinTermino        = false;
    public string $fechaTermino      = '';
    public string $perfilAutorizado  = '';
    public bool   $activo            = true;

    const ROLES = [
        'ADMINISTRADOR' => 'Administrador',
        'GERENTE'       => 'Gerente',
        'SUPERVISOR'    => 'Supervisor',
        'OPERADOR'      => 'Operador',
        'CAJA'          => 'Caja',
        'LECTURA'       => 'Solo Lectura',
    ];

    protected function rules(): array
    {
        return [
            'tipo'             => 'required|in:INGRESO,EGRESO',
            'nombre'           => 'required|string|max:200',
            'fechaHabilitacion'=> 'required|date',
            'fechaTermino'     => $this->sinTermino ? 'nullable' : 'required|date|after_or_equal:fechaHabilitacion',
            'perfilAutorizado' => 'nullable|in:ADMINISTRADOR,GERENTE,SUPERVISOR,OPERADOR,CAJA,LECTURA',
        ];
    }

    protected array $validationAttributes = [
        'tipo'              => 'tipo',
        'nombre'            => 'nombre',
        'fechaHabilitacion' => 'fecha de habilitación',
        'fechaTermino'      => 'fecha de término',
        'perfilAutorizado'  => 'perfil autorizado',
    ];

    public function updatedSearch(): void    { $this->resetPage(); }
    public function updatedFiltroTipo(): void { $this->resetPage(); }

    public function nuevo(): void
    {
        $this->resetForm();
        $this->modo = 'crear';
    }

    public function editar(int $id): void
    {
        $t = TipoIngresoEgreso::findOrFail($id);
        $this->editandoId        = $id;
        $this->tipo              = $t->tipo;
        $this->nombre            = $t->nombre;
        $this->fechaHabilitacion = $t->fecha_habilitacion->format('Y-m-d');
        $this->sinTermino        = is_null($t->fecha_termino);
        $this->fechaTermino      = $t->fecha_termino ? $t->fecha_termino->format('Y-m-d') : '';
        $this->perfilAutorizado  = $t->perfil_autorizado ?? '';
        $this->activo            = $t->activo;
        $this->modo = 'editar';
    }

    public function guardar(): void
    {
        $this->validate();

        $data = [
            'tipo'              => $this->tipo,
            'nombre'            => $this->nombre,
            'fecha_habilitacion'=> $this->fechaHabilitacion,
            'fecha_termino'     => $this->sinTermino ? null : $this->fechaTermino,
            'perfil_autorizado' => $this->perfilAutorizado ?: null,
            'activo'            => $this->activo,
            'user_id'           => Auth::id(),
        ];

        if ($this->modo === 'crear') {
            TipoIngresoEgreso::create($data);
            $this->toastExito('Tipo de ' . strtolower($this->tipo) . ' registrado correctamente.');
        } else {
            TipoIngresoEgreso::findOrFail($this->editandoId)->update($data);
            $this->toastExito('Registro actualizado correctamente.');
        }

        $this->cancelar();
    }

    public function toggleActivo(int $id): void
    {
        $t = TipoIngresoEgreso::findOrFail($id);
        $t->update(['activo' => !$t->activo]);
        $this->toastInfo($t->activo ? 'Registro desactivado.' : 'Registro activado.');
    }

    public function eliminar(int $id): void
    {
        TipoIngresoEgreso::findOrFail($id)->delete();
        $this->toastExito('Registro eliminado.');
    }

    public function cancelar(): void
    {
        $this->resetForm();
        $this->modo = 'lista';
    }

    private function resetForm(): void
    {
        $this->reset([
            'tipo', 'nombre', 'fechaHabilitacion', 'fechaTermino',
            'perfilAutorizado', 'editandoId',
        ]);
        $this->sinTermino = false;
        $this->activo     = true;
        $this->resetValidation();
    }

    public function limpiarFiltros(): void
    {
        $this->search = '';
        $this->filtroTipo = '';
        $this->filtroActivo = '';
        $this->resetPage();
    }

    public function render()
    {
        $registros = TipoIngresoEgreso::with('usuario')
            ->when($this->search, fn($q) => $q->where('nombre', 'like', "%{$this->search}%"))
            ->when($this->filtroTipo, fn($q) => $q->where('tipo', $this->filtroTipo))
            ->when($this->filtroActivo !== '', fn($q) => $q->where('activo', $this->filtroActivo === '1'))
            ->orderBy('tipo')->orderBy('nombre')
            ->paginate(15);

        $kpis = [
            'total'    => TipoIngresoEgreso::count(),
            'ingresos' => TipoIngresoEgreso::where('tipo', 'INGRESO')->count(),
            'egresos'  => TipoIngresoEgreso::where('tipo', 'EGRESO')->count(),
            'activos'  => TipoIngresoEgreso::where('activo', true)->count(),
        ];

        return view('livewire.catalogos.financiero.ingresos-egresos', [
            'registros' => $registros,
            'kpis'      => $kpis,
            'roles'     => self::ROLES,
        ]);
    }
}

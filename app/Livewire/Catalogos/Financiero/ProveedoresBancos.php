<?php

namespace App\Livewire\Catalogos\Financiero;

use App\Models\Financiero\Proveedor;
use App\Traits\WithToasts;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ProveedoresBancos extends Component
{
    use WithPagination, WithToasts;

    public string $modo             = 'lista';
    public string $search           = '';
    public string $filtroTipo       = '';
    public string $filtroPago       = '';
    public string $filtroActivo     = '';
    public ?int $editandoId         = null;

    // Formulario
    public string $nombre          = '';
    public string $rfcCuentaMotivo = '';
    public string $correo          = '';
    public string $telefono        = '';
    public string $tipoServicio    = '';
    public string $tipoPago        = 'CONTADO';
    public string $especificacion  = '';
    public bool   $activo          = true;

    const TIPOS_SERVICIO = [
        'Mecánico', 'Señales', 'CFE', 'Gasolina',
        'Materiales', 'Banco', 'Traspaso', 'Otro',
    ];

    protected function rules(): array
    {
        return [
            'nombre'         => 'required|string|max:200',
            'rfcCuentaMotivo'=> 'nullable|string|max:200',
            'correo'         => 'nullable|email|max:200',
            'telefono'       => 'nullable|string|max:20',
            'tipoServicio'   => 'required|string|max:100',
            'tipoPago'       => 'required|in:CONTADO,CREDITO',
            'especificacion' => 'nullable|string',
        ];
    }

    protected array $validationAttributes = [
        'nombre'         => 'nombre',
        'rfcCuentaMotivo'=> 'RFC / cuenta / motivo',
        'correo'         => 'correo electrónico',
        'tipoServicio'   => 'tipo de servicio',
        'tipoPago'       => 'tipo de pago',
    ];

    public function updatedSearch(): void     { $this->resetPage(); }
    public function updatedFiltroTipo(): void { $this->resetPage(); }
    public function updatedFiltroPago(): void { $this->resetPage(); }

    public function nuevo(): void
    {
        $this->resetForm();
        $this->modo = 'crear';
    }

    public function editar(int $id): void
    {
        $p = Proveedor::findOrFail($id);
        $this->editandoId      = $id;
        $this->nombre          = $p->nombre;
        $this->rfcCuentaMotivo = $p->rfc_cuenta_motivo ?? '';
        $this->correo          = $p->correo ?? '';
        $this->telefono        = $p->telefono ?? '';
        $this->tipoServicio    = $p->tipo_servicio;
        $this->tipoPago        = $p->tipo_pago;
        $this->especificacion  = $p->especificacion ?? '';
        $this->activo          = $p->activo;
        $this->modo = 'editar';
    }

    public function guardar(): void
    {
        $this->validate();

        $data = [
            'nombre'           => $this->nombre,
            'rfc_cuenta_motivo'=> $this->rfcCuentaMotivo ?: null,
            'correo'           => $this->correo ?: null,
            'telefono'         => $this->telefono ?: null,
            'tipo_servicio'    => $this->tipoServicio,
            'tipo_pago'        => $this->tipoPago,
            'especificacion'   => $this->especificacion ?: null,
            'activo'           => $this->activo,
            'user_id'          => Auth::id(),
        ];

        if ($this->modo === 'crear') {
            Proveedor::create($data);
            $this->toastExito('Proveedor / banco registrado correctamente.');
        } else {
            Proveedor::findOrFail($this->editandoId)->update($data);
            $this->toastExito('Proveedor / banco actualizado correctamente.');
        }

        $this->cancelar();
    }

    public function toggleActivo(int $id): void
    {
        $p = Proveedor::findOrFail($id);
        $nuevo = !$p->activo;
        $p->update(['activo' => $nuevo]);
        $this->toastInfo($nuevo ? 'Proveedor activado.' : 'Proveedor desactivado.');
    }

    public function eliminar(int $id): void
    {
        Proveedor::findOrFail($id)->delete();
        $this->toastExito('Proveedor eliminado.');
    }

    public function cancelar(): void
    {
        $this->resetForm();
        $this->modo = 'lista';
    }

    private function resetForm(): void
    {
        $this->reset([
            'nombre', 'rfcCuentaMotivo', 'correo', 'telefono',
            'tipoServicio', 'especificacion', 'editandoId',
        ]);
        $this->tipoPago = 'CONTADO';
        $this->activo   = true;
        $this->resetValidation();
    }

    public function limpiarFiltros(): void
    {
        $this->search = '';
        $this->filtroTipo = '';
        $this->filtroPago = '';
        $this->filtroActivo = '';
        $this->resetPage();
    }

    public function render()
    {
        $proveedores = Proveedor::with('usuario')
            ->when($this->search, fn($q) => $q->where('nombre', 'like', "%{$this->search}%")
                ->orWhere('rfc_cuenta_motivo', 'like', "%{$this->search}%"))
            ->when($this->filtroTipo, fn($q) => $q->where('tipo_servicio', $this->filtroTipo))
            ->when($this->filtroPago, fn($q) => $q->where('tipo_pago', $this->filtroPago))
            ->when($this->filtroActivo !== '', fn($q) => $q->where('activo', $this->filtroActivo === '1'))
            ->orderBy('nombre')
            ->paginate(15);

        $kpis = [
            'total'   => Proveedor::count(),
            'activos' => Proveedor::where('activo', true)->count(),
            'contado' => Proveedor::where('tipo_pago', 'CONTADO')->count(),
            'credito' => Proveedor::where('tipo_pago', 'CREDITO')->count(),
        ];

        $tiposExistentes = Proveedor::distinct()->orderBy('tipo_servicio')
            ->pluck('tipo_servicio')->toArray();

        return view('livewire.catalogos.financiero.proveedores-bancos', [
            'proveedores'     => $proveedores,
            'kpis'            => $kpis,
            'tiposServicio'   => self::TIPOS_SERVICIO,
            'tiposExistentes' => $tiposExistentes,
        ]);
    }
}

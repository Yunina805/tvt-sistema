<?php

namespace App\Livewire\Catalogos\Financiero;

use App\Models\Financiero\DescuentoTarifa;
use App\Models\Financiero\TarifaPrincipal;
use App\Traits\WithToasts;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Descuentos extends Component
{
    use WithPagination, WithToasts;

    public string $modo            = 'lista';
    public string $search          = '';
    public string $filtroEstado    = '';
    public string $filtroPrincipal = '';
    public ?int $editandoId = null;

    // Formulario
    public string $tarifaPrincipalId   = '';
    public string $descripcion         = '';
    public string $porcentajeDescuento = '';
    public string $montoDescuento      = '';
    public string $fechaHabilitacion   = '';
    public bool   $sinTermino          = false;
    public string $fechaTermino        = '';
    public string $estado              = 'VIGENTE';

    protected function rules(): array
    {
        return [
            'tarifaPrincipalId'   => 'required|exists:tarifas_principales,id',
            'descripcion'         => 'required|string|max:300',
            'porcentajeDescuento' => 'nullable|numeric|min:0|max:100',
            'montoDescuento'      => 'nullable|numeric|min:0',
            'fechaHabilitacion'   => 'required|date',
            'fechaTermino'        => $this->sinTermino ? 'nullable' : 'required|date|after_or_equal:fechaHabilitacion',
            'estado'              => 'required|in:VIGENTE,VENCIDA,CANCELADA',
        ];
    }

    protected array $validationAttributes = [
        'tarifaPrincipalId'   => 'tarifa principal',
        'descripcion'         => 'descripción del descuento',
        'porcentajeDescuento' => 'porcentaje de descuento',
        'montoDescuento'      => 'monto de descuento',
        'fechaHabilitacion'   => 'fecha de habilitación',
        'fechaTermino'        => 'fecha de término',
    ];

    public function updatedSearch(): void          { $this->resetPage(); }
    public function updatedFiltroEstado(): void    { $this->resetPage(); }
    public function updatedFiltroPrincipal(): void { $this->resetPage(); }

    public function nuevo(): void
    {
        $this->resetForm();
        $this->modo = 'crear';
    }

    public function editar(int $id): void
    {
        $d = DescuentoTarifa::findOrFail($id);
        $this->editandoId          = $id;
        $this->tarifaPrincipalId   = (string) $d->tarifa_principal_id;
        $this->descripcion         = $d->descripcion;
        $this->porcentajeDescuento = $d->porcentaje_descuento !== null ? (string) $d->porcentaje_descuento : '';
        $this->montoDescuento      = $d->monto_descuento !== null ? (string) $d->monto_descuento : '';
        $this->fechaHabilitacion   = $d->fecha_habilitacion->format('Y-m-d');
        $this->sinTermino          = is_null($d->fecha_termino);
        $this->fechaTermino        = $d->fecha_termino ? $d->fecha_termino->format('Y-m-d') : '';
        $this->estado              = $d->estado;
        $this->modo = 'editar';
    }

    public function guardar(): void
    {
        $this->validate();

        $data = [
            'tarifa_principal_id'  => $this->tarifaPrincipalId,
            'descripcion'          => $this->descripcion,
            'porcentaje_descuento' => $this->porcentajeDescuento !== '' ? $this->porcentajeDescuento : null,
            'monto_descuento'      => $this->montoDescuento !== '' ? $this->montoDescuento : null,
            'fecha_habilitacion'   => $this->fechaHabilitacion,
            'fecha_termino'        => $this->sinTermino ? null : $this->fechaTermino,
            'estado'               => $this->estado,
            'user_id'              => Auth::id(),
        ];

        if ($this->modo === 'crear') {
            DescuentoTarifa::create($data);
            $this->toastExito('Descuento registrado correctamente.');
        } else {
            DescuentoTarifa::findOrFail($this->editandoId)->update($data);
            $this->toastExito('Descuento actualizado correctamente.');
        }

        $this->cancelar();
    }

    public function eliminar(int $id): void
    {
        DescuentoTarifa::findOrFail($id)->delete();
        $this->toastExito('Descuento eliminado.');
    }

    public function cancelar(): void
    {
        $this->resetForm();
        $this->modo = 'lista';
    }

    private function resetForm(): void
    {
        $this->reset([
            'tarifaPrincipalId', 'descripcion', 'porcentajeDescuento', 'montoDescuento',
            'fechaHabilitacion', 'fechaTermino', 'editandoId',
        ]);
        $this->sinTermino = false;
        $this->estado     = 'VIGENTE';
        $this->resetValidation();
    }

    public function render()
    {
        $descuentos = DescuentoTarifa::with(['tarifaPrincipal', 'usuario'])
            ->when($this->search, fn($q) => $q->where('descripcion', 'like', "%{$this->search}%"))
            ->when($this->filtroEstado, fn($q) => $q->where('estado', $this->filtroEstado))
            ->when($this->filtroPrincipal, fn($q) => $q->where('tarifa_principal_id', $this->filtroPrincipal))
            ->orderByDesc('created_at')
            ->paginate(15);

        $kpis = [
            'total'     => DescuentoTarifa::count(),
            'vigentes'  => DescuentoTarifa::where('estado', 'VIGENTE')->count(),
            'vencidas'  => DescuentoTarifa::where('estado', 'VENCIDA')->count(),
            'cancelados'=> DescuentoTarifa::where('estado', 'CANCELADA')->count(),
        ];

        $principalesSelect = TarifaPrincipal::whereIn('estado', ['VIGENTE_CONTRATAR', 'VIGENTE_MENSUALIDAD'])
            ->orderBy('nombre_comercial')->get(['id', 'nombre_comercial']);

        $principalesFiltro = TarifaPrincipal::orderBy('nombre_comercial')
            ->get(['id', 'nombre_comercial']);

        return view('livewire.catalogos.financiero.descuentos', [
            'descuentos'        => $descuentos,
            'kpis'              => $kpis,
            'principalesSelect' => $principalesSelect,
            'principalesFiltro' => $principalesFiltro,
        ]);
    }
}

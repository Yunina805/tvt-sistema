<?php

namespace App\Livewire\Catalogos\Financiero;

use App\Models\Financiero\PromocionEstacional;
use App\Models\Financiero\TarifaPrincipal;
use App\Traits\WithToasts;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Promociones extends Component
{
    use WithPagination, WithToasts;

    public string $modo            = 'lista';
    public string $search          = '';
    public string $filtroEstado    = '';
    public string $filtroPrincipal = '';
    public ?int $editandoId = null;

    // Formulario
    public string $tarifaPrincipalId   = '';
    public string $nombreComercial     = '';
    public string $descripcion         = '';
    public string $porcentajeDescuento = '';
    public string $montoDescuento      = '';
    public string $precioMensualidad   = '';
    public string $fechaHabilitacion   = '';
    public bool   $sinTermino          = false;
    public string $fechaTermino        = '';
    public string $fechaRegistroCrt    = '';
    public string $folioCrt            = '';
    public string $estado              = 'VIGENTE';

    protected function rules(): array
    {
        return [
            'tarifaPrincipalId'   => 'required|exists:tarifas_principales,id',
            'nombreComercial'     => 'required|string|max:200',
            'descripcion'         => 'nullable|string',
            'porcentajeDescuento' => 'nullable|numeric|min:0|max:100',
            'montoDescuento'      => 'nullable|numeric|min:0',
            'precioMensualidad'   => 'required|numeric|min:0',
            'fechaHabilitacion'   => 'required|date',
            'fechaTermino'        => $this->sinTermino ? 'nullable' : 'required|date|after_or_equal:fechaHabilitacion',
            'fechaRegistroCrt'    => 'nullable|date',
            'folioCrt'            => 'nullable|string|max:100',
            'estado'              => 'required|in:VIGENTE,VENCIDA,CANCELADA',
        ];
    }

    protected array $validationAttributes = [
        'tarifaPrincipalId'   => 'tarifa principal',
        'nombreComercial'     => 'nombre comercial',
        'porcentajeDescuento' => 'porcentaje de descuento',
        'montoDescuento'      => 'monto de descuento',
        'precioMensualidad'   => 'precio de mensualidad',
        'fechaHabilitacion'   => 'fecha de habilitación',
        'fechaTermino'        => 'fecha de término',
    ];

    public function updatedSearch(): void          { $this->resetPage(); }
    public function updatedFiltroEstado(): void    { $this->resetPage(); }
    public function updatedFiltroPrincipal(): void { $this->resetPage(); }

    public function nueva(): void
    {
        $this->resetForm();
        $this->modo = 'crear';
    }

    public function editar(int $id): void
    {
        $p = PromocionEstacional::findOrFail($id);
        $this->editandoId          = $id;
        $this->tarifaPrincipalId   = (string) $p->tarifa_principal_id;
        $this->nombreComercial     = $p->nombre_comercial;
        $this->descripcion         = $p->descripcion ?? '';
        $this->porcentajeDescuento = $p->porcentaje_descuento !== null ? (string) $p->porcentaje_descuento : '';
        $this->montoDescuento      = $p->monto_descuento !== null ? (string) $p->monto_descuento : '';
        $this->precioMensualidad   = (string) $p->precio_mensualidad;
        $this->fechaHabilitacion   = $p->fecha_habilitacion->format('Y-m-d');
        $this->sinTermino          = is_null($p->fecha_termino);
        $this->fechaTermino        = $p->fecha_termino ? $p->fecha_termino->format('Y-m-d') : '';
        $this->fechaRegistroCrt    = $p->fecha_registro_crt ? $p->fecha_registro_crt->format('Y-m-d') : '';
        $this->folioCrt            = $p->folio_crt ?? '';
        $this->estado              = $p->estado;
        $this->modo = 'editar';
    }

    public function guardar(): void
    {
        $this->validate();

        $data = [
            'tarifa_principal_id'  => $this->tarifaPrincipalId,
            'nombre_comercial'     => $this->nombreComercial,
            'descripcion'          => $this->descripcion ?: null,
            'porcentaje_descuento' => $this->porcentajeDescuento !== '' ? $this->porcentajeDescuento : null,
            'monto_descuento'      => $this->montoDescuento !== '' ? $this->montoDescuento : null,
            'precio_mensualidad'   => $this->precioMensualidad,
            'fecha_habilitacion'   => $this->fechaHabilitacion,
            'fecha_termino'        => $this->sinTermino ? null : $this->fechaTermino,
            'fecha_registro_crt'   => $this->fechaRegistroCrt ?: null,
            'folio_crt'            => $this->folioCrt ?: null,
            'estado'               => $this->estado,
            'user_id'              => Auth::id(),
        ];

        if ($this->modo === 'crear') {
            PromocionEstacional::create($data);
            $this->toastExito('Promoción estacional registrada correctamente.');
        } else {
            PromocionEstacional::findOrFail($this->editandoId)->update($data);
            $this->toastExito('Promoción estacional actualizada correctamente.');
        }

        $this->cancelar();
    }

    public function eliminar(int $id): void
    {
        PromocionEstacional::findOrFail($id)->delete();
        $this->toastExito('Promoción eliminada.');
    }

    public function cancelar(): void
    {
        $this->resetForm();
        $this->modo = 'lista';
    }

    private function resetForm(): void
    {
        $this->reset([
            'tarifaPrincipalId', 'nombreComercial', 'descripcion',
            'porcentajeDescuento', 'montoDescuento', 'precioMensualidad',
            'fechaHabilitacion', 'fechaTermino', 'fechaRegistroCrt', 'folioCrt', 'editandoId',
        ]);
        $this->sinTermino = false;
        $this->estado     = 'VIGENTE';
        $this->resetValidation();
    }

    public function limpiarFiltros(): void
    {
        $this->search = '';
        $this->filtroEstado = '';
        $this->filtroPrincipal = '';
        $this->resetPage();
    }

    public function render()
    {
        $promociones = PromocionEstacional::with('tarifaPrincipal')
            ->when($this->search, fn($q) => $q->where('nombre_comercial', 'like', "%{$this->search}%"))
            ->when($this->filtroEstado, fn($q) => $q->where('estado', $this->filtroEstado))
            ->when($this->filtroPrincipal, fn($q) => $q->where('tarifa_principal_id', $this->filtroPrincipal))
            ->orderByDesc('created_at')
            ->paginate(15);

        $kpis = [
            'total'     => PromocionEstacional::count(),
            'vigentes'  => PromocionEstacional::where('estado', 'VIGENTE')->count(),
            'vencidas'  => PromocionEstacional::where('estado', 'VENCIDA')->count(),
            'canceladas'=> PromocionEstacional::where('estado', 'CANCELADA')->count(),
        ];

        $principalesSelect = TarifaPrincipal::whereIn('estado', ['VIGENTE_CONTRATAR', 'VIGENTE_MENSUALIDAD'])
            ->orderBy('nombre_comercial')->get(['id', 'nombre_comercial']);

        $principalesFiltro = TarifaPrincipal::orderBy('nombre_comercial')
            ->get(['id', 'nombre_comercial']);

        return view('livewire.catalogos.financiero.promociones', [
            'promociones'       => $promociones,
            'kpis'              => $kpis,
            'principalesSelect' => $principalesSelect,
            'principalesFiltro' => $principalesFiltro,
        ]);
    }
}

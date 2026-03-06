<?php

namespace App\Livewire\Catalogos\Financiero;

use App\Models\Financiero\TarifaAdicional;
use App\Models\Financiero\TarifaPrincipal;
use App\Traits\WithToasts;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class TarifasAdicionales extends Component
{
    use WithPagination, WithToasts;

    public string $modo            = 'lista';
    public string $search          = '';
    public string $filtroEstado    = '';
    public string $filtroPrincipal = '';
    public ?int $editandoId = null;

    // Formulario
    public string $tarifaPrincipalId = '';
    public string $nombreComercial   = '';
    public string $descripcion       = '';
    public string $precioInstalacion = '';
    public string $precioMensualidad = '';
    public string $fechaHabilitacion = '';
    public bool   $sinTermino        = false;
    public string $fechaTermino      = '';
    public string $fechaRegistroCrt  = '';
    public string $folioCrt          = '';
    public string $estado            = 'VIGENTE';

    protected function rules(): array
    {
        return [
            'tarifaPrincipalId' => 'required|exists:tarifas_principales,id',
            'nombreComercial'   => 'required|string|max:200',
            'descripcion'       => 'nullable|string',
            'precioInstalacion' => 'required|numeric|min:0',
            'precioMensualidad' => 'required|numeric|min:0.01',
            'fechaHabilitacion' => 'required|date',
            'fechaTermino'      => $this->sinTermino ? 'nullable' : 'required|date|after_or_equal:fechaHabilitacion',
            'fechaRegistroCrt'  => 'nullable|date',
            'folioCrt'          => 'nullable|string|max:100',
            'estado'            => 'required|in:VIGENTE,VENCIDA,CANCELADA',
        ];
    }

    protected array $validationAttributes = [
        'tarifaPrincipalId' => 'tarifa principal',
        'nombreComercial'   => 'nombre comercial',
        'precioInstalacion' => 'precio de instalación',
        'precioMensualidad' => 'precio de mensualidad',
        'fechaHabilitacion' => 'fecha de habilitación',
        'fechaTermino'      => 'fecha de término',
    ];

    public function updatedSearch(): void         { $this->resetPage(); }
    public function updatedFiltroEstado(): void   { $this->resetPage(); }
    public function updatedFiltroPrincipal(): void { $this->resetPage(); }

    public function nueva(): void
    {
        $this->resetForm();
        $this->modo = 'crear';
    }

    public function editar(int $id): void
    {
        $t = TarifaAdicional::findOrFail($id);
        $this->editandoId        = $id;
        $this->tarifaPrincipalId = (string) $t->tarifa_principal_id;
        $this->nombreComercial   = $t->nombre_comercial;
        $this->descripcion       = $t->descripcion ?? '';
        $this->precioInstalacion = (string) $t->precio_instalacion;
        $this->precioMensualidad = (string) $t->precio_mensualidad;
        $this->fechaHabilitacion = $t->fecha_habilitacion->format('Y-m-d');
        $this->sinTermino        = is_null($t->fecha_termino);
        $this->fechaTermino      = $t->fecha_termino ? $t->fecha_termino->format('Y-m-d') : '';
        $this->fechaRegistroCrt  = $t->fecha_registro_crt ? $t->fecha_registro_crt->format('Y-m-d') : '';
        $this->folioCrt          = $t->folio_crt ?? '';
        $this->estado            = $t->estado;
        $this->modo = 'editar';
    }

    public function guardar(): void
    {
        $this->validate();

        $data = [
            'tarifa_principal_id' => $this->tarifaPrincipalId,
            'nombre_comercial'    => $this->nombreComercial,
            'descripcion'         => $this->descripcion ?: null,
            'precio_instalacion'  => $this->precioInstalacion,
            'precio_mensualidad'  => $this->precioMensualidad,
            'fecha_habilitacion'  => $this->fechaHabilitacion,
            'fecha_termino'       => $this->sinTermino ? null : $this->fechaTermino,
            'fecha_registro_crt'  => $this->fechaRegistroCrt ?: null,
            'folio_crt'           => $this->folioCrt ?: null,
            'estado'              => $this->estado,
            'user_id'             => Auth::id(),
        ];

        if ($this->modo === 'crear') {
            TarifaAdicional::create($data);
            $this->toastExito('Tarifa adicional registrada correctamente.');
        } else {
            TarifaAdicional::findOrFail($this->editandoId)->update($data);
            $this->toastExito('Tarifa adicional actualizada correctamente.');
        }

        $this->cancelar();
    }

    public function eliminar(int $id): void
    {
        TarifaAdicional::findOrFail($id)->delete();
        $this->toastExito('Tarifa adicional eliminada.');
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
            'precioInstalacion', 'precioMensualidad',
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
        $tarifas = TarifaAdicional::with('tarifaPrincipal')
            ->when($this->search, fn($q) => $q->where('nombre_comercial', 'like', "%{$this->search}%"))
            ->when($this->filtroEstado, fn($q) => $q->where('estado', $this->filtroEstado))
            ->when($this->filtroPrincipal, fn($q) => $q->where('tarifa_principal_id', $this->filtroPrincipal))
            ->orderByDesc('created_at')
            ->paginate(15);

        $kpis = [
            'total'     => TarifaAdicional::count(),
            'vigentes'  => TarifaAdicional::where('estado', 'VIGENTE')->count(),
            'vencidas'  => TarifaAdicional::where('estado', 'VENCIDA')->count(),
            'canceladas'=> TarifaAdicional::where('estado', 'CANCELADA')->count(),
        ];

        $principalesSelect = TarifaPrincipal::whereIn('estado', ['VIGENTE_CONTRATAR', 'VIGENTE_MENSUALIDAD'])
            ->orderBy('nombre_comercial')->get(['id', 'nombre_comercial']);

        $principalesFiltro = TarifaPrincipal::orderBy('nombre_comercial')
            ->get(['id', 'nombre_comercial']);

        return view('livewire.catalogos.financiero.tarifas-adicionales', [
            'tarifas'           => $tarifas,
            'kpis'              => $kpis,
            'principalesSelect' => $principalesSelect,
            'principalesFiltro' => $principalesFiltro,
        ]);
    }
}

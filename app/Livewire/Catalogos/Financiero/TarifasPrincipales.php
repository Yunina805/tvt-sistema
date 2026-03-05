<?php

namespace App\Livewire\Catalogos\Financiero;

use App\Models\Financiero\TarifaPrincipal;
use App\Traits\WithToasts;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class TarifasPrincipales extends Component
{
    use WithPagination, WithToasts;

    public string $modo  = 'lista'; // lista | crear | editar
    public string $search = '';
    public string $filtroEstado = '';
    public ?int $editandoId = null;

    // Formulario
    public string $nombreComercial    = '';
    public string $descripcion        = '';
    public string $precioInstalacion  = '';
    public string $precioMensualidad  = '';
    public string $fechaHabilitacion  = '';
    public bool   $sinTermino         = false;
    public string $fechaTermino       = '';
    public string $fechaRegistroCrt   = '';
    public string $folioCrt           = '';
    public string $estado             = 'VIGENTE_CONTRATAR';

    const ESTADOS = [
        'VIGENTE_CONTRATAR'   => 'Vigente para contratar',
        'VIGENTE_MENSUALIDAD' => 'Vigente para mensualidad',
        'VENCIDA'             => 'Vencida',
        'CANCELADA'           => 'Cancelada',
    ];

    protected function rules(): array
    {
        return [
            'nombreComercial'   => 'required|string|max:200',
            'descripcion'       => 'nullable|string',
            'precioInstalacion' => 'required|numeric|min:0',
            'precioMensualidad' => 'required|numeric|min:0.01',
            'fechaHabilitacion' => 'required|date',
            'fechaTermino'      => $this->sinTermino ? 'nullable' : 'required|date|after_or_equal:fechaHabilitacion',
            'fechaRegistroCrt'  => 'nullable|date',
            'folioCrt'          => 'nullable|string|max:100',
            'estado'            => 'required|in:VIGENTE_CONTRATAR,VIGENTE_MENSUALIDAD,VENCIDA,CANCELADA',
        ];
    }

    protected array $validationAttributes = [
        'nombreComercial'   => 'nombre comercial',
        'precioInstalacion' => 'precio de instalación',
        'precioMensualidad' => 'precio de mensualidad',
        'fechaHabilitacion' => 'fecha de habilitación',
        'fechaTermino'      => 'fecha de término',
        'fechaRegistroCrt'  => 'fecha de registro CRT',
        'folioCrt'          => 'folio CRT',
    ];

    public function updatedSearch(): void  { $this->resetPage(); }
    public function updatedFiltroEstado(): void { $this->resetPage(); }

    public function nueva(): void
    {
        $this->resetForm();
        $this->modo = 'crear';
    }

    public function editar(int $id): void
    {
        $t = TarifaPrincipal::findOrFail($id);
        $this->editandoId        = $id;
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
            'nombre_comercial'   => $this->nombreComercial,
            'descripcion'        => $this->descripcion ?: null,
            'precio_instalacion' => $this->precioInstalacion,
            'precio_mensualidad' => $this->precioMensualidad,
            'fecha_habilitacion' => $this->fechaHabilitacion,
            'fecha_termino'      => $this->sinTermino ? null : $this->fechaTermino,
            'fecha_registro_crt' => $this->fechaRegistroCrt ?: null,
            'folio_crt'          => $this->folioCrt ?: null,
            'estado'             => $this->estado,
            'user_id'            => Auth::id(),
        ];

        if ($this->modo === 'crear') {
            TarifaPrincipal::create($data);
            $this->toastExito('Tarifa principal registrada correctamente.');
        } else {
            TarifaPrincipal::findOrFail($this->editandoId)->update($data);
            $this->toastExito('Tarifa principal actualizada correctamente.');
        }

        $this->cancelar();
    }

    public function cambiarEstado(int $id, string $nuevoEstado): void
    {
        TarifaPrincipal::findOrFail($id)->update(['estado' => $nuevoEstado]);
        $this->toastInfo('Estado de la tarifa actualizado.');
    }

    public function eliminar(int $id): void
    {
        $t = TarifaPrincipal::withCount(['adicionales', 'promociones', 'descuentos'])->findOrFail($id);

        if ($t->adicionales_count + $t->promociones_count + $t->descuentos_count > 0) {
            $this->toastError('No se puede eliminar: tiene tarifas adicionales, promociones o descuentos asociados.');
            return;
        }

        $t->delete();
        $this->toastExito('Tarifa eliminada.');
    }

    public function cancelar(): void
    {
        $this->resetForm();
        $this->modo = 'lista';
    }

    private function resetForm(): void
    {
        $this->reset([
            'nombreComercial', 'descripcion', 'precioInstalacion', 'precioMensualidad',
            'fechaHabilitacion', 'fechaTermino', 'fechaRegistroCrt', 'folioCrt', 'editandoId',
        ]);
        $this->sinTermino = false;
        $this->estado     = 'VIGENTE_CONTRATAR';
        $this->resetValidation();
    }

    public function render()
    {
        $query = TarifaPrincipal::query()
            ->when($this->search, fn($q) => $q->where('nombre_comercial', 'like', "%{$this->search}%"))
            ->when($this->filtroEstado, fn($q) => $q->where('estado', $this->filtroEstado))
            ->orderByDesc('created_at');

        $tarifas = $query->paginate(15);

        $kpis = [
            'total'               => TarifaPrincipal::count(),
            'vigente_contratar'   => TarifaPrincipal::where('estado', 'VIGENTE_CONTRATAR')->count(),
            'vigente_mensualidad' => TarifaPrincipal::where('estado', 'VIGENTE_MENSUALIDAD')->count(),
            'vencidas_canceladas' => TarifaPrincipal::whereIn('estado', ['VENCIDA', 'CANCELADA'])->count(),
        ];

        return view('livewire.catalogos.financiero.tarifas-principales', [
            'tarifas' => $tarifas,
            'kpis'    => $kpis,
            'estados' => self::ESTADOS,
        ]);
    }
}

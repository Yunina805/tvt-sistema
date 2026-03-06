<?php

namespace App\Livewire\Catalogos\Servicios;

use App\Models\Financiero\TarifaAdicional;
use App\Models\Financiero\TarifaPrincipal;
use App\Models\RRHH\Empleado;
use App\Models\Servicios\Servicio;
use App\Traits\WithToasts;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class RegistroServicios extends Component
{
    use WithPagination, WithToasts;

    public string  $tab          = 'TARIFA_PRINCIPAL';
    public string  $modo         = 'lista';
    public string  $search       = '';
    public string  $filtroActivo = '';
    public ?int    $editandoId   = null;

    // Campos del formulario
    public string $nombre            = '';
    public string $tarifaPrincipalId = '';
    public string $tarifaAdicionalId = '';
    public string $quienReporta      = '';
    public string $puestoAsignado    = '';
    public bool   $activo            = true;

    // ────────────────────────────────────────────────────────────────────────
    protected function rules(): array
    {
        $base = [
            'nombre' => 'required|string|max:150',
            'activo' => 'boolean',
        ];

        if ($this->tab === 'TARIFA_PRINCIPAL') {
            $base['tarifaPrincipalId'] = 'required|exists:tarifas_principales,id';
        } elseif ($this->tab === 'TARIFA_ADICIONAL') {
            $base['tarifaAdicionalId'] = 'required|exists:tarifas_adicionales,id';
        } elseif ($this->tab === 'FALLA_SERVICIO') {
            $base['quienReporta'] = 'required|in:CLIENTE,TU_VISION';
        } elseif ($this->tab === 'PERSONAL') {
            $base['puestoAsignado'] = 'required|string|max:100';
        }

        return $base;
    }

    protected array $validationAttributes = [
        'nombre'             => 'nombre del servicio',
        'tarifaPrincipalId'  => 'tarifa principal',
        'tarifaAdicionalId'  => 'tarifa adicional',
        'quienReporta'       => 'quien reporta',
        'puestoAsignado'     => 'puesto asignado',
    ];

    // ────────────────────────────────────────────────────────────────────────
    public function updatedSearch(): void       { $this->resetPage(); }
    public function updatedFiltroActivo(): void { $this->resetPage(); }

    public function cambiarTab(string $tab): void
    {
        $this->tab = $tab;
        $this->cancelar();
        $this->search       = '';
        $this->filtroActivo = '';
        $this->resetPage();
    }

    public function nuevo(): void
    {
        $this->resetForm();
        $this->modo = 'crear';
    }

    public function editar(int $id): void
    {
        $s = Servicio::findOrFail($id);
        $this->editandoId        = $id;
        $this->nombre            = $s->nombre;
        $this->tarifaPrincipalId = (string) ($s->tarifa_principal_id ?? '');
        $this->tarifaAdicionalId = (string) ($s->tarifa_adicional_id ?? '');
        $this->quienReporta      = $s->quien_reporta ?? '';
        $this->puestoAsignado    = $s->puesto_asignado ?? '';
        $this->activo            = $s->activo;
        $this->modo = 'editar';
    }

    public function guardar(): void
    {
        $this->validate();

        $data = [
            'tipo'               => $this->tab,
            'nombre'             => $this->nombre,
            'activo'             => $this->activo,
            'tarifa_principal_id' => $this->tab === 'TARIFA_PRINCIPAL' ? $this->tarifaPrincipalId : null,
            'tarifa_adicional_id' => $this->tab === 'TARIFA_ADICIONAL' ? $this->tarifaAdicionalId : null,
            'quien_reporta'      => $this->tab === 'FALLA_SERVICIO'   ? $this->quienReporta      : null,
            'puesto_asignado'    => $this->tab === 'PERSONAL'          ? $this->puestoAsignado    : null,
        ];

        if ($this->modo === 'crear') {
            $data['user_id'] = Auth::id();
            Servicio::create($data);
            $this->toastExito("Servicio \"{$this->nombre}\" registrado correctamente.");
        } else {
            Servicio::findOrFail($this->editandoId)->update($data);
            $this->toastExito("Servicio \"{$this->nombre}\" actualizado correctamente.");
        }

        $this->cancelar();
    }

    public function toggleActivo(int $id): void
    {
        $s     = Servicio::findOrFail($id);
        $nuevo = !$s->activo;
        $s->update(['activo' => $nuevo]);
        $this->toastInfo($nuevo ? 'Servicio activado.' : 'Servicio desactivado.');
    }

    public function eliminar(int $id): void
    {
        $s      = Servicio::findOrFail($id);
        $nombre = $s->nombre;
        $s->delete();
        $this->toastExito("Servicio \"{$nombre}\" eliminado.");
    }

    public function cancelar(): void
    {
        $this->resetForm();
        $this->modo = 'lista';
    }

    public function limpiarFiltros(): void
    {
        $this->search       = '';
        $this->filtroActivo = '';
        $this->resetPage();
    }

    private function resetForm(): void
    {
        $this->reset([
            'nombre', 'tarifaPrincipalId', 'tarifaAdicionalId',
            'quienReporta', 'puestoAsignado', 'editandoId',
        ]);
        $this->activo = true;
        $this->resetValidation();
    }

    // ────────────────────────────────────────────────────────────────────────
    public function render()
    {
        $totalTab    = Servicio::where('tipo', $this->tab)->count();
        $activosTab  = Servicio::where('tipo', $this->tab)->where('activo', true)->count();
        $inactivosTab = Servicio::where('tipo', $this->tab)->where('activo', false)->count();

        $servicios = Servicio::where('tipo', $this->tab)
            ->when($this->search, fn($q) => $q->where('nombre', 'like', "%{$this->search}%"))
            ->when($this->filtroActivo !== '', fn($q) => $q->where('activo', $this->filtroActivo === '1'))
            ->with(['tarifaPrincipal', 'tarifaAdicional', 'usuario'])
            ->orderByDesc('created_at')
            ->paginate(15);

        $tarifasPrincipales = $this->tab === 'TARIFA_PRINCIPAL'
            ? TarifaPrincipal::whereIn('estado', ['VIGENTE_CONTRATAR', 'VIGENTE_MENSUALIDAD'])
                ->orderBy('nombre_comercial')->get(['id', 'nombre_comercial'])
            : collect();

        $tarifasAdicionales = $this->tab === 'TARIFA_ADICIONAL'
            ? TarifaAdicional::where('estado', 'VIGENTE')
                ->orderBy('nombre_comercial')->get(['id', 'nombre_comercial'])
            : collect();

        $puestos = $this->tab === 'PERSONAL'
            ? Empleado::where('activo', true)->orderBy('puesto')->distinct()->pluck('puesto')
            : collect();

        return view('livewire.catalogos.servicios.registro-servicios', compact(
            'servicios', 'totalTab', 'activosTab', 'inactivosTab',
            'tarifasPrincipales', 'tarifasAdicionales', 'puestos',
        ));
    }
}

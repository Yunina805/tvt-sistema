<?php

namespace App\Livewire\Catalogos\Infraestructura;

use App\Models\Infraestructura\InegEstado;
use App\Models\Infraestructura\InegLocalidad;
use App\Models\Infraestructura\InegMunicipio;
use App\Models\Infraestructura\Sucursal;
use App\Traits\WithToasts;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Sucursales extends Component
{
    use WithPagination, WithToasts;

    public string $modo = 'lista'; // lista | crear | editar
    public string $search = '';
    public ?int $editandoId = null;

    // Formulario
    public string $nombre = '';
    public string $tipoRed = '';
    public string $estadoId = '';
    public string $municipioId = '';
    public string $localidadId = '';
    public string $codigoPostal = '';

    public array $municipiosFiltrados = [];
    public array $localidadesFiltradas = [];

    protected function rules(): array
    {
        return [
            'nombre'       => 'required|string|max:120',
            'tipoRed'      => 'required|in:COBRE,HIBRIDA,FIBRA',
            'estadoId'     => 'required|exists:inegi_estados,id',
            'municipioId'  => 'required|exists:inegi_municipios,id',
            'localidadId'  => 'required|exists:inegi_localidades,id',
            'codigoPostal' => 'nullable|digits:5',
        ];
    }

    protected array $validationAttributes = [
        'nombre'       => 'nombre',
        'tipoRed'      => 'tipo de red',
        'estadoId'     => 'estado',
        'municipioId'  => 'municipio',
        'localidadId'  => 'localidad',
        'codigoPostal' => 'código postal',
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedEstadoId(): void
    {
        $this->municipioId = '';
        $this->localidadId = '';
        $this->codigoPostal = '';
        $this->localidadesFiltradas = [];
        $this->municipiosFiltrados = $this->estadoId
            ? InegMunicipio::where('estado_id', $this->estadoId)
                ->orderBy('nombre_municipio')
                ->get(['id', 'nombre_municipio'])
                ->toArray()
            : [];
    }

    public function updatedMunicipioId(): void
    {
        $this->localidadId = '';
        $this->codigoPostal = '';
        $this->localidadesFiltradas = $this->municipioId
            ? InegLocalidad::where('municipio_id', $this->municipioId)
                ->orderBy('nombre_localidad')
                ->get(['id', 'nombre_localidad', 'codigo_postal'])
                ->toArray()
            : [];
    }

    public function updatedLocalidadId(): void
    {
        if ($this->localidadId) {
            $localidad = InegLocalidad::find($this->localidadId);
            $this->codigoPostal = $localidad?->codigo_postal ?? '';
        } else {
            $this->codigoPostal = '';
        }
    }

    public function nuevaSucursal(): void
    {
        $this->resetForm();
        $this->modo = 'crear';
    }

    public function editar(int $id): void
    {
        $sucursal = Sucursal::findOrFail($id);
        $this->editandoId = $id;
        $this->nombre = $sucursal->nombre;
        $this->tipoRed = $sucursal->tipo_red;
        $this->estadoId = (string) $sucursal->estado_id;
        $this->municipiosFiltrados = InegMunicipio::where('estado_id', $this->estadoId)
            ->orderBy('nombre_municipio')
            ->get(['id', 'nombre_municipio'])
            ->toArray();
        $this->municipioId = (string) $sucursal->municipio_id;
        $this->localidadesFiltradas = InegLocalidad::where('municipio_id', $this->municipioId)
            ->orderBy('nombre_localidad')
            ->get(['id', 'nombre_localidad', 'codigo_postal'])
            ->toArray();
        $this->localidadId = (string) $sucursal->localidad_id;
        $this->codigoPostal = $sucursal->codigo_postal ?? '';
        $this->modo = 'editar';
    }

    public function guardar(): void
    {
        $this->validate();

        if ($this->modo === 'crear') {
            $count = Sucursal::withoutGlobalScopes()->count();
            $clave = 'SUC-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);

            Sucursal::create([
                'clave'        => $clave,
                'nombre'       => $this->nombre,
                'tipo_red'     => $this->tipoRed,
                'estado_id'    => $this->estadoId,
                'municipio_id' => $this->municipioId,
                'localidad_id' => $this->localidadId,
                'codigo_postal' => $this->codigoPostal ?: null,
                'activa'       => true,
            ]);

            $this->toastExito("Sucursal \"{$this->nombre}\" creada correctamente.");
        } else {
            Sucursal::findOrFail($this->editandoId)->update([
                'nombre'       => $this->nombre,
                'tipo_red'     => $this->tipoRed,
                'estado_id'    => $this->estadoId,
                'municipio_id' => $this->municipioId,
                'localidad_id' => $this->localidadId,
                'codigo_postal' => $this->codigoPostal ?: null,
            ]);

            $this->toastExito("Sucursal \"{$this->nombre}\" actualizada correctamente.");
        }

        $this->cancelar();
    }

    public function eliminar(int $id): void
    {
        $sucursal = Sucursal::findOrFail($id);
        $sucursal->update(['activa' => false]);
        $this->toastInfo("Sucursal \"{$sucursal->nombre}\" desactivada.");
    }

    public function cancelar(): void
    {
        $this->resetForm();
        $this->modo = 'lista';
    }

    private function resetForm(): void
    {
        $this->reset(['nombre', 'tipoRed', 'estadoId', 'municipioId', 'localidadId',
            'codigoPostal', 'editandoId', 'municipiosFiltrados', 'localidadesFiltradas']);
        $this->resetValidation();
    }

    public function render()
    {
        $estados = InegEstado::orderBy('nombre_estado')->get(['id', 'clave_estado', 'nombre_estado']);

        $sucursales = Sucursal::with(['localidad', 'municipio', 'estado'])
            ->when($this->search, fn($q) => $q->where(function ($sub) {
                $sub->where('nombre', 'like', "%{$this->search}%")
                    ->orWhere('clave', 'like', "%{$this->search}%");
            }))
            ->orderBy('clave')
            ->paginate(15);

        return view('livewire.catalogos.infraestructura.sucursales', [
            'estados'    => $estados,
            'sucursales' => $sucursales,
        ]);
    }
}

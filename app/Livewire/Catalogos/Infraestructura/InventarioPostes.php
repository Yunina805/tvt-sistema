<?php

namespace App\Livewire\Catalogos\Infraestructura;

use App\Models\Calle;
use App\Models\Poste;
use App\Models\Sucursal;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class InventarioPostes extends Component
{
    use WithPagination;

    public string $modo = 'lista'; // lista | crear | editar
    public string $search = '';
    public string $filtroSucursalId = '';
    public string $filtroTipo = '';
    public ?int $editandoId = null;

    // Formulario
    public string $sucursalId = '';
    public string $numeroPoste = '';
    public string $tipoPoste = '';
    public string $calleId = '';
    public string $entreCalle1Id = '';
    public string $entreCalle2Id = '';
    public string $latitudUtm = '';
    public string $longitudUtm = '';
    public string $latitudGrados = '';
    public string $longitudGrados = '';
    public string $zona = '';

    public array $callesDisponibles = [];

    protected function rules(): array
    {
        return [
            'sucursalId'    => 'required|exists:sucursales,id',
            'numeroPoste'   => 'required|string|max:30',
            'tipoPoste'     => 'required|in:CFE,TELMEX,PROPIO_TVT',
            'calleId'       => 'nullable|exists:calles,id',
            'entreCalle1Id' => 'nullable|exists:calles,id',
            'entreCalle2Id' => 'nullable|exists:calles,id',
            'latitudUtm'    => 'nullable|numeric',
            'longitudUtm'   => 'nullable|numeric',
            'latitudGrados' => 'nullable|numeric|between:-90,90',
            'longitudGrados' => 'nullable|numeric|between:-180,180',
            'zona'          => 'nullable|string|max:30',
        ];
    }

    protected array $validationAttributes = [
        'sucursalId'    => 'sucursal',
        'numeroPoste'   => 'número de poste',
        'tipoPoste'     => 'tipo de poste',
        'calleId'       => 'calle principal',
        'entreCalle1Id' => 'entre calle 1',
        'entreCalle2Id' => 'entre calle 2',
        'latitudUtm'    => 'latitud UTM',
        'longitudUtm'   => 'longitud UTM',
        'latitudGrados' => 'latitud en grados',
        'longitudGrados' => 'longitud en grados',
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFiltroSucursalId(): void
    {
        $this->resetPage();
    }

    public function updatedSucursalId(): void
    {
        $this->calleId = '';
        $this->entreCalle1Id = '';
        $this->entreCalle2Id = '';
        $this->callesDisponibles = $this->sucursalId
            ? Calle::where('sucursal_id', $this->sucursalId)
                ->where('activa', true)
                ->orderBy('nombre_calle')
                ->get(['id', 'nombre_calle'])
                ->toArray()
            : [];
    }

    public function nuevoPoste(): void
    {
        $this->resetForm();
        $this->modo = 'crear';
    }

    public function editar(int $id): void
    {
        $poste = Poste::findOrFail($id);
        $this->editandoId = $id;
        $this->sucursalId = (string) $poste->sucursal_id;
        $this->callesDisponibles = Calle::where('sucursal_id', $this->sucursalId)
            ->where('activa', true)
            ->orderBy('nombre_calle')
            ->get(['id', 'nombre_calle'])
            ->toArray();
        $this->numeroPoste   = $poste->numero_poste;
        $this->tipoPoste     = $poste->tipo_poste;
        $this->calleId       = (string) ($poste->calle_id ?? '');
        $this->entreCalle1Id = (string) ($poste->entre_calle_1_id ?? '');
        $this->entreCalle2Id = (string) ($poste->entre_calle_2_id ?? '');
        $this->latitudUtm    = (string) ($poste->latitud_utm ?? '');
        $this->longitudUtm   = (string) ($poste->longitud_utm ?? '');
        $this->latitudGrados = (string) ($poste->latitud_grados ?? '');
        $this->longitudGrados = (string) ($poste->longitud_grados ?? '');
        $this->zona          = $poste->zona ?? '';
        $this->modo = 'editar';
    }

    public function guardar(): void
    {
        $this->validate();

        $payload = [
            'sucursal_id'      => $this->sucursalId,
            'numero_poste'     => $this->numeroPoste,
            'tipo_poste'       => $this->tipoPoste,
            'calle_id'         => $this->calleId ?: null,
            'entre_calle_1_id' => $this->entreCalle1Id ?: null,
            'entre_calle_2_id' => $this->entreCalle2Id ?: null,
            'latitud_utm'      => $this->latitudUtm !== '' ? $this->latitudUtm : null,
            'longitud_utm'     => $this->longitudUtm !== '' ? $this->longitudUtm : null,
            'latitud_grados'   => $this->latitudGrados !== '' ? $this->latitudGrados : null,
            'longitud_grados'  => $this->longitudGrados !== '' ? $this->longitudGrados : null,
            'zona'             => $this->zona ?: null,
        ];

        if ($this->modo === 'crear') {
            $year    = now()->year;
            $count   = Poste::withoutGlobalScopes()->count();
            $idPoste = 'POST-' . $year . '-' . str_pad($count + 1, 6, '0', STR_PAD_LEFT);
            $payload['id_poste'] = $idPoste;
            $payload['activo']   = true;

            Poste::create($payload);
            session()->flash('exito', "Poste \"{$idPoste}\" registrado correctamente.");
        } else {
            Poste::findOrFail($this->editandoId)->update($payload);
            session()->flash('exito', 'Poste actualizado correctamente.');
        }

        $this->cancelar();
    }

    public function eliminar(int $id): void
    {
        $poste = Poste::findOrFail($id);
        $poste->update(['activo' => false]);
        session()->flash('info', "Poste \"{$poste->id_poste}\" desactivado.");
    }

    public function cancelar(): void
    {
        $this->resetForm();
        $this->modo = 'lista';
    }

    private function resetForm(): void
    {
        $this->reset(['sucursalId', 'numeroPoste', 'tipoPoste', 'calleId', 'entreCalle1Id',
            'entreCalle2Id', 'latitudUtm', 'longitudUtm', 'latitudGrados', 'longitudGrados',
            'zona', 'editandoId', 'callesDisponibles']);
        $this->resetValidation();
    }

    public function render()
    {
        $totalPostes = Poste::where('activo', true)->count();
        $totalCfe    = Poste::where('activo', true)->where('tipo_poste', 'CFE')->count();
        $totalTelmex = Poste::where('activo', true)->where('tipo_poste', 'TELMEX')->count();
        $totalPropio = Poste::where('activo', true)->where('tipo_poste', 'PROPIO_TVT')->count();

        $sucursales = Sucursal::where('activa', true)->orderBy('nombre')->get(['id', 'clave', 'nombre']);

        $postes = Poste::with(['sucursal', 'calle'])
            ->where('activo', true)
            ->when($this->search, fn($q) => $q->where(function ($sub) {
                $sub->where('id_poste', 'like', "%{$this->search}%")
                    ->orWhere('numero_poste', 'like', "%{$this->search}%")
                    ->orWhere('zona', 'like', "%{$this->search}%");
            }))
            ->when($this->filtroSucursalId, fn($q) => $q->where('sucursal_id', $this->filtroSucursalId))
            ->when($this->filtroTipo, fn($q) => $q->where('tipo_poste', $this->filtroTipo))
            ->orderBy('id_poste')
            ->paginate(15);

        return view('livewire.catalogos.infraestructura.inventario-postes', [
            'totalPostes' => $totalPostes,
            'totalCfe'    => $totalCfe,
            'totalTelmex' => $totalTelmex,
            'totalPropio' => $totalPropio,
            'sucursales'  => $sucursales,
            'postes'      => $postes,
        ]);
    }
}

<?php

namespace App\Livewire\Catalogos\Clientes;

use App\Models\Clientes\Cliente;
use App\Models\Infraestructura\Calle;
use App\Models\Infraestructura\Sucursal;
use App\Traits\WithToasts;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class RegistroClientes extends Component
{
    use WithPagination, WithToasts;

    public string $modo = 'lista'; // lista | crear | editar
    public ?int $editandoId = null;

    // Filtros
    public string $search = '';
    public string $filtroSucursal = '';
    public string $filtroActivo = '';

    // Formulario
    public string $nombre = '';
    public string $apellidoPaterno = '';
    public string $apellidoMaterno = '';
    public string $telefono = '';
    public string $correo = '';
    public string $curp = '';
    public string $sucursalId = '';
    public string $calleId = '';
    public string $numeroExterior = '';
    public string $numeroInterior = 'NA';
    public string $referencias = '';
    public bool $activo = true;

    // Auto-completados desde Sucursal (solo display)
    public string $estadoNombre = '';
    public string $municipioNombre = '';
    public string $localidadNombre = '';
    public string $codigoPostal = '';

    // Calles disponibles para el select (filtradas por sucursal)
    public array $callesDisponibles = [];

    protected function rules(): array
    {
        $base = [
            'nombre'          => 'required|string|max:100',
            'apellidoPaterno' => 'required|string|max:100',
            'apellidoMaterno' => 'nullable|string|max:100',
            'telefono'        => 'required|string|max:20',
            'correo'          => 'nullable|email|max:150',
            'curp'            => 'nullable|string|size:18',
            'sucursalId'      => 'required|exists:sucursales,id',
            'calleId'         => 'nullable|exists:calles,id',
            'numeroExterior'  => 'required|string|max:20',
            'numeroInterior'  => 'required|string|max:20',
            'referencias'     => 'nullable|string|max:500',
        ];

        if ($this->modo === 'crear') {
            $base['curp'] = 'nullable|string|size:18|unique:clientes,curp';
        } elseif ($this->modo === 'editar' && $this->editandoId) {
            $base['curp'] = "nullable|string|size:18|unique:clientes,curp,{$this->editandoId}";
        }

        return $base;
    }

    protected array $validationAttributes = [
        'nombre'          => 'nombre',
        'apellidoPaterno' => 'apellido paterno',
        'apellidoMaterno' => 'apellido materno',
        'telefono'        => 'teléfono',
        'correo'          => 'correo electrónico',
        'curp'            => 'CURP',
        'sucursalId'      => 'sucursal',
        'calleId'         => 'calle',
        'numeroExterior'  => 'número exterior',
        'numeroInterior'  => 'número interior',
        'referencias'     => 'referencias',
    ];

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedFiltroSucursal(): void { $this->resetPage(); }
    public function updatedFiltroActivo(): void { $this->resetPage(); }

    /**
     * Al cambiar sucursal: auto-rellenar Estado/Municipio/Localidad/CP
     * y filtrar calles disponibles.
     */
    public function updatedSucursalId(): void
    {
        $this->calleId = '';
        $this->callesDisponibles = [];
        $this->estadoNombre = '';
        $this->municipioNombre = '';
        $this->localidadNombre = '';
        $this->codigoPostal = '';

        if (!$this->sucursalId) {
            return;
        }

        $sucursal = Sucursal::with(['estado', 'municipio', 'localidad'])
            ->find($this->sucursalId);

        if (!$sucursal) {
            return;
        }

        $this->estadoNombre    = $sucursal->estado?->nombre ?? '';
        $this->municipioNombre = $sucursal->municipio?->nombre ?? '';
        $this->localidadNombre = $sucursal->localidad?->nombre ?? '';
        $this->codigoPostal    = $sucursal->codigo_postal ?? '';

        $this->callesDisponibles = Calle::where('sucursal_id', $this->sucursalId)
            ->where('activa', true)
            ->orderBy('nombre_calle')
            ->get(['id', 'nombre_calle'])
            ->toArray();
    }

    public function nuevo(): void
    {
        $this->resetForm();
        $this->modo = 'crear';
    }

    public function editar(int $id): void
    {
        $cliente = Cliente::with(['sucursal.estado', 'sucursal.municipio', 'sucursal.localidad'])
            ->findOrFail($id);

        $this->editandoId      = $id;
        $this->nombre          = $cliente->nombre;
        $this->apellidoPaterno = $cliente->apellido_paterno;
        $this->apellidoMaterno = $cliente->apellido_materno ?? '';
        $this->telefono        = $cliente->telefono;
        $this->correo          = $cliente->correo ?? '';
        $this->curp            = $cliente->curp ?? '';
        $this->sucursalId      = (string) $cliente->sucursal_id;
        $this->calleId         = $cliente->calle_id ? (string) $cliente->calle_id : '';
        $this->numeroExterior  = $cliente->numero_exterior;
        $this->numeroInterior  = $cliente->numero_interior;
        $this->referencias     = $cliente->referencias ?? '';
        $this->activo          = $cliente->activo;

        // Auto-fill desde sucursal
        $sucursal = $cliente->sucursal;
        if ($sucursal) {
            $this->estadoNombre    = $sucursal->estado?->nombre ?? '';
            $this->municipioNombre = $sucursal->municipio?->nombre ?? '';
            $this->localidadNombre = $sucursal->localidad?->nombre ?? '';
            $this->codigoPostal    = $sucursal->codigo_postal ?? '';

            $this->callesDisponibles = Calle::where('sucursal_id', $sucursal->id)
                ->where('activa', true)
                ->orderBy('nombre_calle')
                ->get(['id', 'nombre_calle'])
                ->toArray();
        }

        $this->modo = 'editar';
    }

    public function guardar(): void
    {
        $this->validate();

        $data = [
            'nombre'          => strtoupper(trim($this->nombre)),
            'apellido_paterno'=> strtoupper(trim($this->apellidoPaterno)),
            'apellido_materno'=> $this->apellidoMaterno ? strtoupper(trim($this->apellidoMaterno)) : null,
            'telefono'        => trim($this->telefono),
            'correo'          => $this->correo ?: null,
            'curp'            => $this->curp ? strtoupper(trim($this->curp)) : null,
            'sucursal_id'     => $this->sucursalId,
            'calle_id'        => $this->calleId ?: null,
            'numero_exterior' => strtoupper(trim($this->numeroExterior)),
            'numero_interior' => strtoupper(trim($this->numeroInterior)) ?: 'NA',
            'referencias'     => $this->referencias ?: null,
            'activo'          => $this->activo,
        ];

        if ($this->modo === 'crear') {
            Cliente::create($data);
            $this->toastExito('Cliente registrado correctamente.');
        } else {
            Cliente::findOrFail($this->editandoId)->update($data);
            $this->toastExito('Cliente actualizado correctamente.');
        }

        $this->cancelar();
    }

    public function toggleActivo(int $id): void
    {
        $cliente = Cliente::findOrFail($id);
        $nuevo   = !$cliente->activo;
        $cliente->update(['activo' => $nuevo]);
        $estado = $nuevo ? 'activado' : 'desactivado';
        $this->toastInfo("Cliente {$estado}.");
    }

    public function eliminar(int $id): void
    {
        Cliente::findOrFail($id)->delete();
        $this->toastExito('Cliente eliminado permanentemente.');
    }

    public function cancelar(): void
    {
        $this->resetForm();
        $this->modo = 'lista';
    }

    private function resetForm(): void
    {
        $this->reset([
            'editandoId', 'nombre', 'apellidoPaterno', 'apellidoMaterno',
            'telefono', 'correo', 'curp', 'sucursalId', 'calleId',
            'numeroExterior', 'referencias',
            'estadoNombre', 'municipioNombre', 'localidadNombre', 'codigoPostal',
            'callesDisponibles',
        ]);
        $this->numeroInterior = 'NA';
        $this->activo = true;
        $this->resetValidation();
    }

    public function limpiarFiltros(): void
    {
        $this->search = '';
        $this->filtroSucursal = '';
        $this->filtroActivo = '';
        $this->resetPage();
    }

    public function render()
    {
        $totalClientes = Cliente::count();
        $totalActivos  = Cliente::where('activo', true)->count();

        $sucursales = Sucursal::where('activa', true)
            ->orderBy('nombre')
            ->get(['id', 'nombre']);

        $clientes = Cliente::with(['sucursal', 'calle'])
            ->when($this->search, function ($q) {
                $q->where(function ($inner) {
                    $inner->where('nombre', 'like', "%{$this->search}%")
                          ->orWhere('apellido_paterno', 'like', "%{$this->search}%")
                          ->orWhere('apellido_materno', 'like', "%{$this->search}%")
                          ->orWhere('telefono', 'like', "%{$this->search}%")
                          ->orWhere('curp', 'like', "%{$this->search}%");
                });
            })
            ->when($this->filtroSucursal, fn($q) => $q->where('sucursal_id', $this->filtroSucursal))
            ->when($this->filtroActivo !== '', fn($q) => $q->where('activo', (bool) $this->filtroActivo))
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('livewire.catalogos.clientes.registro-clientes', [
            'totalClientes' => $totalClientes,
            'totalActivos'  => $totalActivos,
            'sucursales'    => $sucursales,
            'clientes'      => $clientes,
        ]);
    }
}

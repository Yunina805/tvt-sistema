<?php

namespace App\Livewire\Catalogos\RecursosHumanos;

use App\Models\Infraestructura\Sucursal;
use App\Models\RRHH\Empleado;
use App\Traits\WithToasts;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class RegistroEmpleados extends Component
{
    use WithPagination, WithToasts;

    public string $modo = 'lista'; // lista | crear | editar
    public string $search = '';
    public string $filtroSucursal = '';
    public string $filtroArea = '';
    public ?int $editandoId = null;

    // Formulario
    public string $nombre = '';
    public string $apellidoPaterno = '';
    public string $apellidoMaterno = '';
    public string $curp = '';
    public string $rfc = '';
    public string $nss = '';
    public string $fechaNacimiento = '';
    public string $sexo = '';
    public string $telefono = '';
    public string $email = '';
    public string $sucursalId = '';
    public string $area = '';
    public string $puesto = '';
    public string $tipoContrato = '';
    public string $fechaIngreso = '';
    public string $salarioBase = '';
    public string $observaciones = '';

    protected function rules(): array
    {
        return [
            'nombre'          => 'required|string|max:80',
            'apellidoPaterno' => 'required|string|max:80',
            'apellidoMaterno' => 'nullable|string|max:80',
            'curp'            => 'nullable|string|size:18|unique:empleados,curp' . ($this->editandoId ? ",{$this->editandoId}" : ''),
            'rfc'             => 'nullable|string|max:13',
            'nss'             => 'nullable|string|max:11',
            'fechaNacimiento' => 'nullable|date',
            'sexo'            => 'nullable|in:M,F',
            'telefono'        => 'nullable|string|max:15',
            'email'           => 'nullable|email|max:120',
            'sucursalId'      => 'nullable|exists:sucursales,id',
            'area'            => 'required|in:DIRECCION,ADMINISTRACION,TECNICO_CAMPO,TECNICO_INSTALACIONES,ATENCION_CLIENTE,CAJA_COBRANZA,RRHH,SISTEMAS',
            'puesto'          => 'required|string|max:100',
            'tipoContrato'    => 'required|in:PLANTA,CONFIANZA,TEMPORAL,HONORARIOS',
            'fechaIngreso'    => 'required|date',
            'salarioBase'     => 'required|numeric|min:0',
        ];
    }

    protected array $validationAttributes = [
        'nombre'          => 'nombre',
        'apellidoPaterno' => 'apellido paterno',
        'apellidoMaterno' => 'apellido materno',
        'curp'            => 'CURP',
        'fechaNacimiento' => 'fecha de nacimiento',
        'sucursalId'      => 'sucursal',
        'area'            => 'área',
        'puesto'          => 'puesto',
        'tipoContrato'    => 'tipo de contrato',
        'fechaIngreso'    => 'fecha de ingreso',
        'salarioBase'     => 'salario base',
    ];

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedFiltroSucursal(): void { $this->resetPage(); }
    public function updatedFiltroArea(): void { $this->resetPage(); }

    public function nuevoEmpleado(): void
    {
        $this->resetForm();
        $this->modo = 'crear';
    }

    public function editar(int $id): void
    {
        $emp = Empleado::findOrFail($id);
        $this->editandoId      = $id;
        $this->nombre          = $emp->nombre;
        $this->apellidoPaterno = $emp->apellido_paterno;
        $this->apellidoMaterno = $emp->apellido_materno ?? '';
        $this->curp            = $emp->curp ?? '';
        $this->rfc             = $emp->rfc ?? '';
        $this->nss             = $emp->nss ?? '';
        $this->fechaNacimiento = $emp->fecha_nacimiento?->format('Y-m-d') ?? '';
        $this->sexo            = $emp->sexo ?? '';
        $this->telefono        = $emp->telefono ?? '';
        $this->email           = $emp->email ?? '';
        $this->sucursalId      = (string) ($emp->sucursal_id ?? '');
        $this->area            = $emp->area;
        $this->puesto          = $emp->puesto;
        $this->tipoContrato    = $emp->tipo_contrato;
        $this->fechaIngreso    = $emp->fecha_ingreso->format('Y-m-d');
        $this->salarioBase     = (string) $emp->salario_base;
        $this->observaciones   = $emp->observaciones ?? '';
        $this->modo = 'editar';
    }

    public function guardar(): void
    {
        $this->validate();

        $data = [
            'nombre'           => $this->nombre,
            'apellido_paterno' => $this->apellidoPaterno,
            'apellido_materno' => $this->apellidoMaterno ?: null,
            'curp'             => $this->curp ?: null,
            'rfc'              => $this->rfc ?: null,
            'nss'              => $this->nss ?: null,
            'fecha_nacimiento' => $this->fechaNacimiento ?: null,
            'sexo'             => $this->sexo ?: null,
            'telefono'         => $this->telefono ?: null,
            'email'            => $this->email ?: null,
            'sucursal_id'      => $this->sucursalId ?: null,
            'area'             => $this->area,
            'puesto'           => $this->puesto,
            'tipo_contrato'    => $this->tipoContrato,
            'fecha_ingreso'    => $this->fechaIngreso,
            'salario_base'     => $this->salarioBase,
            'observaciones'    => $this->observaciones ?: null,
        ];

        if ($this->modo === 'crear') {
            $count = Empleado::withoutGlobalScopes()->count();
            $data['clave_empleado'] = 'EMP-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
            $data['activo'] = true;

            Empleado::create($data);
            $this->toastExito("Empleado \"{$this->nombre} {$this->apellidoPaterno}\" registrado correctamente.");
        } else {
            Empleado::findOrFail($this->editandoId)->update($data);
            $this->toastExito("Empleado \"{$this->nombre} {$this->apellidoPaterno}\" actualizado correctamente.");
        }

        $this->cancelar();
    }

    public function eliminar(int $id): void
    {
        $emp = Empleado::findOrFail($id);
        $emp->update(['activo' => false]);
        $this->toastInfo("Empleado \"{$emp->nombre_completo}\" desactivado.");
    }

    public function cancelar(): void
    {
        $this->resetForm();
        $this->modo = 'lista';
    }

    private function resetForm(): void
    {
        $this->reset([
            'nombre', 'apellidoPaterno', 'apellidoMaterno', 'curp', 'rfc', 'nss',
            'fechaNacimiento', 'sexo', 'telefono', 'email', 'sucursalId', 'area',
            'puesto', 'tipoContrato', 'fechaIngreso', 'salarioBase', 'observaciones',
            'editandoId',
        ]);
        $this->resetValidation();
    }

    public function render()
    {
        $totalEmpleados = Empleado::count();
        $totalActivos   = Empleado::where('activo', true)->count();

        $porArea = Empleado::where('activo', true)
            ->selectRaw('area, count(*) as total')
            ->groupBy('area')
            ->pluck('total', 'area')
            ->toArray();

        $sucursales = Sucursal::where('activa', true)->orderBy('nombre')->get(['id', 'nombre', 'clave']);

        $empleados = Empleado::with('sucursal')
            ->when($this->search, fn($q) => $q->where(function ($sub) {
                $sub->where('nombre', 'like', "%{$this->search}%")
                    ->orWhere('apellido_paterno', 'like', "%{$this->search}%")
                    ->orWhere('clave_empleado', 'like', "%{$this->search}%")
                    ->orWhere('puesto', 'like', "%{$this->search}%");
            }))
            ->when($this->filtroSucursal, fn($q) => $q->where('sucursal_id', $this->filtroSucursal))
            ->when($this->filtroArea, fn($q) => $q->where('area', $this->filtroArea))
            ->orderBy('apellido_paterno')
            ->paginate(15);

        return view('livewire.catalogos.recursos-humanos.registro-empleados', [
            'totalEmpleados' => $totalEmpleados,
            'totalActivos'   => $totalActivos,
            'porArea'        => $porArea,
            'sucursales'     => $sucursales,
            'empleados'      => $empleados,
        ]);
    }
}

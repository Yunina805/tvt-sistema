<?php

namespace App\Livewire\Catalogos\RecursosHumanos;

use App\Models\RRHH\AccesoSistema;
use App\Models\RRHH\Empleado;
use App\Models\User;
use App\Traits\WithToasts;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AccesosSistema extends Component
{
    use WithPagination, WithToasts;

    public string $modo = 'lista'; // lista | crear | editar
    public string $search = '';
    public ?int $editandoId = null;

    // Formulario base
    public string $empleadoId = '';
    public string $rol = '';
    public array  $modulos = [];
    public bool   $activo = true;

    // Credenciales nuevas (modo crear)
    public string $email = '';
    public string $password = '';
    public string $passwordConfirmation = '';

    // Cambio de contraseña opcional (modo editar)
    public string $newPassword = '';
    public string $newPasswordConfirmation = '';

    // Info del usuario actual (modo editar, read-only)
    public ?string $userEmail = null;
    public ?string $userName  = null;

    const MODULOS_DISPONIBLES = [
        'GestionClientes', 'Infraestructura', 'RRHH',
        'Financiero', 'Red', 'Television', 'Energia',
    ];

    protected function rules(): array
    {
        $base = [
            'empleadoId' => 'required|exists:empleados,id',
            'rol'        => 'required|in:ADMINISTRADOR,GERENTE,SUPERVISOR,OPERADOR,CAJA,LECTURA',
            'modulos'    => 'nullable|array',
            'modulos.*'  => 'in:GestionClientes,Infraestructura,RRHH,Financiero,Red,Television,Energia',
        ];

        if ($this->modo === 'crear') {
            $base['email']    = 'required|email|unique:users,email';
            $base['password'] = 'required|min:8';
        }

        return $base;
    }

    protected array $validationAttributes = [
        'empleadoId' => 'empleado',
        'rol'        => 'rol',
        'email'      => 'correo electrónico',
        'password'   => 'contraseña',
    ];

    public function updatedSearch(): void { $this->resetPage(); }

    public function nuevoAcceso(): void
    {
        $this->resetForm();
        $this->modo = 'crear';
    }

    public function editar(int $id): void
    {
        $acc = AccesoSistema::with('usuario')->findOrFail($id);
        $this->editandoId = $id;
        $this->empleadoId = (string) $acc->empleado_id;
        $this->rol        = $acc->rol;
        $this->modulos    = $acc->modulos ?? [];
        $this->activo     = $acc->activo;
        $this->userEmail  = $acc->usuario?->email;
        $this->userName   = $acc->usuario?->name;
        $this->modo = 'editar';
    }

    public function guardar(): void
    {
        $this->validate();

        if ($this->modo === 'crear') {
            if ($this->password !== $this->passwordConfirmation) {
                $this->addError('passwordConfirmation', 'Las contraseñas no coinciden.');
                return;
            }

            if (AccesoSistema::where('empleado_id', $this->empleadoId)->exists()) {
                $this->addError('empleadoId', 'Este empleado ya tiene un acceso asignado.');
                return;
            }

            $emp = Empleado::findOrFail($this->empleadoId);

            $user = User::create([
                'name'              => $emp->nombre_completo,
                'email'             => $this->email,
                'password'          => Hash::make($this->password),
                'email_verified_at' => now(),
            ]);

            AccesoSistema::create([
                'empleado_id' => $this->empleadoId,
                'user_id'     => $user->id,
                'rol'         => $this->rol,
                'modulos'     => $this->modulos ?: null,
                'activo'      => $this->activo,
            ]);

            $this->toastExito('Acceso al sistema y credenciales creadas correctamente.');
        } else {
            $acc = AccesoSistema::findOrFail($this->editandoId);

            if ($this->newPassword !== '') {
                if ($this->newPassword !== $this->newPasswordConfirmation) {
                    $this->addError('newPasswordConfirmation', 'Las contraseñas no coinciden.');
                    return;
                }
                if (strlen($this->newPassword) < 8) {
                    $this->addError('newPassword', 'La contraseña debe tener al menos 8 caracteres.');
                    return;
                }
                if ($acc->usuario) {
                    $acc->usuario->update(['password' => Hash::make($this->newPassword)]);
                }
            }

            $acc->update([
                'rol'     => $this->rol,
                'modulos' => $this->modulos ?: null,
                'activo'  => $this->activo,
            ]);

            $this->toastExito('Acceso al sistema actualizado correctamente.');
        }

        $this->cancelar();
    }

    public function toggleActivo(int $id): void
    {
        $acc = AccesoSistema::findOrFail($id);
        $nuevo = !$acc->activo;
        $acc->update(['activo' => $nuevo]);
        $estado = $nuevo ? 'activado' : 'desactivado';
        $this->toastInfo("Acceso {$estado}.");
    }

    public function cancelar(): void
    {
        $this->resetForm();
        $this->modo = 'lista';
    }

    private function resetForm(): void
    {
        $this->reset([
            'empleadoId', 'rol', 'modulos', 'editandoId',
            'email', 'password', 'passwordConfirmation',
            'newPassword', 'newPasswordConfirmation',
            'userEmail', 'userName',
        ]);
        $this->activo = true;
        $this->resetValidation();
    }

    public function render()
    {
        $totalAccesos = AccesoSistema::count();
        $totalActivos = AccesoSistema::where('activo', true)->count();
        $porRol       = AccesoSistema::selectRaw('rol, count(*) as total')
            ->groupBy('rol')
            ->pluck('total', 'rol')
            ->toArray();

        $empleadosSinAcceso = Empleado::where('activo', true)
            ->whereDoesntHave('acceso')
            ->orderBy('apellido_paterno')
            ->get(['id', 'nombre', 'apellido_paterno', 'apellido_materno']);

        $empleados = Empleado::where('activo', true)
            ->orderBy('apellido_paterno')
            ->get(['id', 'nombre', 'apellido_paterno', 'apellido_materno']);

        $accesos = AccesoSistema::with(['empleado', 'usuario'])
            ->when($this->search, fn($q) => $q->whereHas('empleado', fn($e) =>
                $e->where('nombre', 'like', "%{$this->search}%")
                  ->orWhere('apellido_paterno', 'like', "%{$this->search}%")
            ))
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('livewire.catalogos.recursos-humanos.accesos-sistema', [
            'totalAccesos'       => $totalAccesos,
            'totalActivos'       => $totalActivos,
            'porRol'             => $porRol,
            'empleadosSinAcceso' => $empleadosSinAcceso,
            'empleados'          => $empleados,
            'accesos'            => $accesos,
            'modulosDisponibles' => self::MODULOS_DISPONIBLES,
        ]);
    }
}

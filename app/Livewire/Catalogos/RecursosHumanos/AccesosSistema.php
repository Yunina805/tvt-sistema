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
    public string $filtroRol    = '';
    public string $filtroActivo = '';
    public ?int $editandoId = null;

    // Formulario base
    public string $empleadoId = '';
    public string $rol = '';
    public array  $modulos = [];     // módulos habilitados: ['GestionClientes', 'Financiero']
    public array  $submodulos = [];  // sub-links por módulo: ['Financiero' => ['financiero.tarifas.principales']]
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

    /** Módulos disponibles (orden del sidebar) */
    const MODULOS_DISPONIBLES = [
        'GestionClientes', 'Infraestructura', 'RRHH',
        'Financiero', 'Clientes', 'Servicios', 'PlantaExterna',
        'Television', 'Red', 'Energia',
    ];

    /** Labels amigables para cada módulo */
    const MODULOS_LABELS = [
        'GestionClientes' => 'Gestión al Cliente',
        'Infraestructura' => 'Infraestructura',
        'RRHH'            => 'Recursos Humanos',
        'Financiero'      => 'Financiero',
        'Clientes'        => 'Clientes',
        'Servicios'       => 'Servicios',
        'PlantaExterna'   => 'Planta Externa',
        'Television'      => 'Televisión',
        'Red'             => 'Internet / Red',
        'Energia'         => 'Energía',
    ];

    /** Sub-links disponibles por módulo (clave de ruta => label) */
    const SUBMODULOS_DISPONIBLES = [
        'GestionClientes' => [
            'contrataciones-nuevas'   => 'Contrataciones Nuevas',
            'servicios-adicionales'   => 'Servicios Adicionales',
            'contratacion-promocion'  => 'Contratación Promocional',
            'pago-mensualidad'        => 'Pago Mensualidad',
            'estado-cuenta'           => 'Estado de Cuenta',
            'suspension-falta-pago'   => 'Suspensión por Falta de Pago',
            'reconexion-cliente'      => 'Reconexión de Cliente',
            'cancelacion-servicio'    => 'Cancelación de Servicio',
            'recuperacion-equipos'    => 'Recuperación de Equipos',
            'reportes-servicio'       => 'Reportes de Servicio',
        ],
        'Infraestructura' => [
            'infraestructura.geografia'  => 'Geografía INEGI',
            'infraestructura.sucursales' => 'Sucursales',
            'infraestructura.calles'     => 'Registro de Calles',
            'infraestructura.postes'     => 'Inventario de Postes',
        ],
        'RRHH' => [
            'rrhh.empleados'  => 'Registro Empleados',
            'rrhh.vacaciones' => 'Vacaciones',
            'rrhh.descanso'   => 'Descanso Mensual',
            'rrhh.accesos'    => 'Accesos al Sistema',
            'rrhh.permisos'   => 'Permisos',
        ],
        'Financiero' => [
            'financiero.tarifas.principales' => 'Tarifas Principales',
            'financiero.tarifas.adicionales' => 'Tarifas Adicionales',
            'financiero.promociones'         => 'Promociones Estacionales',
            'financiero.descuentos'          => 'Descuentos',
            'financiero.ingresos.egresos'    => 'Tipo Ingresos / Egresos',
            'financiero.proveedores'         => 'Proveedores / Bancos',
        ],
        'Clientes' => [
            'clientes.registro' => 'Registro de Clientes',
        ],
        'Servicios' => [
            'cat.servicios.tarifas-principales'   => 'Servicios para Tarifas Principales',
            'cat.servicios.tarifas-adicionales'   => 'Servicios para Tarifas Adicionales',
            'cat.servicios.fallas'                => 'Servicios para Fallas',
            'cat.servicios.personal'              => 'Servicios para Personal TV',
            'cat.actividades.tarifas-principales' => 'Actividades para Tarifas Principales',
            'cat.actividades.tarifas-adicionales' => 'Actividades para Tarifas Adicionales',
            'cat.actividades.fallas'              => 'Actividades para Fallas',
            'cat.actividades.personal'            => 'Actividades para Personal TV',
        ],
        'PlantaExterna' => [
            'planta.tipo-fibra'      => 'Tipo de Fibra',
            'planta.amplificadores'  => 'Amplificadores',
            'planta.nodos-opticos'   => 'Nodos Ópticos',
        ],
        'Television' => [
            'tv.mininodos'   => 'Mininodos y Antenas',
            'tv.canales'     => 'Canales y Satélites',
            'tv.moduladores' => 'Moduladores',
            'tv.transmisores'=> 'Transmisores',
            'tv.pon-edfa'    => 'PON EDFA',
        ],
        'Red' => [
            'red.onus'     => 'ONUs',
            'red.naps'     => 'NAPs',
            'red.olt'      => 'OLT',
            'red.starlinks'=> 'Starlinks / ISP Telmex',
            'red.ccr'      => 'CCR / Switches',
            'red.vlans'    => 'Winbox / VLANs',
        ],
        'Energia' => [
            'energia.ctc'   => 'Catálogo CTC',
            'energia.ups'   => 'UPS / Plantas de Emergencia',
            'energia.fibra' => 'Enlaces de Fibra',
        ],
    ];

    protected function rules(): array
    {
        $valoresModulos = implode(',', self::MODULOS_DISPONIBLES);

        $base = [
            'empleadoId' => 'required|exists:empleados,id',
            'rol'        => 'required|in:ADMINISTRADOR,GERENTE,SUPERVISOR,OPERADOR,CAJA,LECTURA',
            'modulos'    => 'nullable|array',
            'modulos.*'  => "in:{$valoresModulos}",
            'submodulos' => 'nullable|array',
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

    /**
     * Cuando cambia el array de módulos habilitados:
     * - Inicializa sub-array vacío para módulos recién marcados
     * - Elimina sub-links de módulos desmarcados
     */
    public function updatedModulos(): void
    {
        foreach ($this->modulos as $mod) {
            if (!isset($this->submodulos[$mod])) {
                $this->submodulos[$mod] = [];
            }
        }

        foreach (array_keys($this->submodulos) as $mod) {
            if (!in_array($mod, $this->modulos)) {
                unset($this->submodulos[$mod]);
            }
        }
    }

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
        $this->activo     = $acc->activo;
        $this->userEmail  = $acc->usuario?->email;
        $this->userName   = $acc->usuario?->name;

        // Cargar permisos: soporta formato viejo (array plano) y nuevo (objeto)
        $permisos = $acc->modulos ?? [];
        if (!empty($permisos) && array_is_list($permisos)) {
            // Formato viejo: ['GestionClientes', 'Financiero'] → convertir
            $this->modulos    = $permisos;
            $this->submodulos = array_fill_keys($permisos, []);
        } else {
            // Formato nuevo: ['GestionClientes' => [], 'Financiero' => [...]]
            $this->modulos    = array_keys($permisos);
            $this->submodulos = $permisos;
        }

        $this->modo = 'editar';
    }

    public function guardar(): void
    {
        $this->validate();

        // Construir objeto de permisos: módulo => [sub-links] (vacío = todos)
        $permisos = null;
        if (!empty($this->modulos)) {
            $permisos = [];
            foreach ($this->modulos as $modulo) {
                $permisos[$modulo] = array_values($this->submodulos[$modulo] ?? []);
            }
        }

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
                'modulos'     => $permisos,
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
                'modulos' => $permisos,
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
            'empleadoId', 'rol', 'modulos', 'submodulos', 'editandoId',
            'email', 'password', 'passwordConfirmation',
            'newPassword', 'newPasswordConfirmation',
            'userEmail', 'userName',
        ]);
        $this->activo = true;
        $this->resetValidation();
    }

    public function eliminar(int $id): void
    {
        $acc = AccesoSistema::with('usuario')->findOrFail($id);
        $acc->usuario?->delete();
        $acc->delete();
        $this->toastExito('Acceso al sistema eliminado.');
    }

    public function limpiarFiltros(): void
    {
        $this->search = '';
        $this->filtroRol = '';
        $this->filtroActivo = '';
        $this->resetPage();
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
            ->when($this->filtroRol, fn($q) => $q->where('rol', $this->filtroRol))
            ->when($this->filtroActivo !== '', fn($q) => $q->where('activo', $this->filtroActivo === '1'))
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('livewire.catalogos.recursos-humanos.accesos-sistema', [
            'totalAccesos'          => $totalAccesos,
            'totalActivos'          => $totalActivos,
            'porRol'                => $porRol,
            'empleadosSinAcceso'    => $empleadosSinAcceso,
            'empleados'             => $empleados,
            'accesos'               => $accesos,
            'modulosDisponibles'    => self::MODULOS_DISPONIBLES,
            'modulosLabels'         => self::MODULOS_LABELS,
            'submodulosDisponibles' => self::SUBMODULOS_DISPONIBLES,
        ]);
    }
}

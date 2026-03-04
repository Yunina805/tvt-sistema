<?php

namespace App\Livewire\Catalogos\Infraestructura;

use App\Imports\InegLocalidadesImport;
use App\Models\Infraestructura\InegEstado;
use App\Models\Infraestructura\InegLocalidad;
use App\Models\Infraestructura\InegMunicipio;
use App\Traits\WithToasts;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('layouts.app')]
class GeografiaInegi extends Component
{
    use WithPagination, WithFileUploads, WithToasts;

    public string $search = '';
    public string $filtroEstadoId = '';
    public string $filtroMunicipioId = '';

    /** 'lista' | 'agregar' | 'importar' */
    public string $modo = 'lista';

    // ─── Formulario agregar localidad manual ──────────────────────────────────
    public string $nuevoEstadoId   = '';
    public string $nuevoMunicipioId = '';
    public string $nuevaClave      = '';
    public string $nuevaLocalidad  = '';
    public string $nuevoCp         = '';
    public array  $nuevosMunicipios = [];

    // ─── Import Excel ─────────────────────────────────────────────────────────
    public $archivoExcel = null;
    public ?array $resultadoImport = null;

    // ─── Filtros cascade ──────────────────────────────────────────────────────
    public array $municipiosFiltrados = [];

    // ─── Reglas ───────────────────────────────────────────────────────────────
    protected function rules(): array
    {
        return [
            'nuevoEstadoId'    => 'required|exists:inegi_estados,id',
            'nuevoMunicipioId' => 'required|exists:inegi_municipios,id',
            'nuevaClave'       => 'required|string|size:4',
            'nuevaLocalidad'   => 'required|string|max:120',
            'nuevoCp'          => 'nullable|digits:5',
        ];
    }

    protected function rulesImport(): array
    {
        return [
            'archivoExcel' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ];
    }

    protected array $validationAttributes = [
        'nuevoEstadoId'    => 'estado',
        'nuevoMunicipioId' => 'municipio',
        'nuevaClave'       => 'clave de localidad',
        'nuevaLocalidad'   => 'nombre de localidad',
        'nuevoCp'          => 'código postal',
        'archivoExcel'     => 'archivo Excel',
    ];

    // ─── Watchers ─────────────────────────────────────────────────────────────

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFiltroEstadoId(): void
    {
        $this->filtroMunicipioId = '';
        $this->resetPage();
        $this->municipiosFiltrados = $this->filtroEstadoId
            ? InegMunicipio::where('estado_id', $this->filtroEstadoId)
                ->orderBy('nombre_municipio')
                ->get(['id', 'nombre_municipio'])
                ->toArray()
            : [];
    }

    public function updatedFiltroMunicipioId(): void
    {
        $this->resetPage();
    }

    public function updatedNuevoEstadoId(): void
    {
        $this->nuevoMunicipioId = '';
        $this->nuevosMunicipios = $this->nuevoEstadoId
            ? InegMunicipio::where('estado_id', $this->nuevoEstadoId)
                ->orderBy('nombre_municipio')
                ->get(['id', 'nombre_municipio'])
                ->toArray()
            : [];
    }

    // ─── Modo Agregar manual ──────────────────────────────────────────────────

    public function iniciarAgregar(): void
    {
        $this->resetManual();
        $this->resultadoImport = null;
        $this->modo = 'agregar';
    }

    public function guardarLocalidad(): void
    {
        $this->validate();

        $municipio = InegMunicipio::find($this->nuevoMunicipioId);

        $exists = InegLocalidad::where('municipio_id', $this->nuevoMunicipioId)
            ->where('clave_localidad', $this->nuevaClave)
            ->exists();

        if ($exists) {
            $this->addError('nuevaClave', 'Ya existe una localidad con esa clave en este municipio.');
            return;
        }

        InegLocalidad::create([
            'municipio_id'    => $this->nuevoMunicipioId,
            'estado_id'       => $municipio->estado_id,
            'clave_localidad' => $this->nuevaClave,
            'nombre_localidad' => $this->nuevaLocalidad,
            'codigo_postal'   => $this->nuevoCp ?: null,
        ]);

        $this->toastExito("Localidad \"{$this->nuevaLocalidad}\" agregada correctamente.");
        $this->cancelar();
    }

    // ─── Modo Importar Excel ──────────────────────────────────────────────────

    public function iniciarImportar(): void
    {
        $this->archivoExcel    = null;
        $this->resultadoImport = null;
        $this->resetValidation();
        $this->modo = 'importar';
    }

    public function procesarImport(): void
    {
        $this->validate($this->rulesImport());

        $import = new InegLocalidadesImport();

        try {
            Excel::import($import, $this->archivoExcel->getRealPath());

            $this->resultadoImport = [
                'insertados' => $import->insertados,
                'omitidos'   => $import->omitidos,
                'errores'    => $import->errores,
            ];

            $this->archivoExcel = null;

            if ($import->insertados > 0) {
                $this->toastExito("Importación completada: {$import->insertados} localidades nuevas agregadas.");
            } else {
                $this->toastInfo("El archivo fue procesado. Sin localidades nuevas (pueden existir ya en la base de datos).");
            }

            $this->modo = 'lista';
            $this->resetPage();
        } catch (\Throwable $e) {
            $this->addError('archivoExcel', 'Error al procesar el archivo: ' . $e->getMessage());
        }
    }

    // ─── Cancelar ────────────────────────────────────────────────────────────

    public function cancelar(): void
    {
        $this->modo = 'lista';
        $this->resetManual();
        $this->archivoExcel = null;
        $this->resetValidation();
    }

    private function resetManual(): void
    {
        $this->reset(['nuevoEstadoId', 'nuevoMunicipioId', 'nuevaClave', 'nuevaLocalidad', 'nuevoCp', 'nuevosMunicipios']);
        $this->resetValidation();
    }

    // ─── Render ───────────────────────────────────────────────────────────────

    public function render()
    {
        $totalEstados     = InegEstado::count();
        $totalMunicipios  = InegMunicipio::count();
        $totalLocalidades = InegLocalidad::count();

        $estados = InegEstado::orderBy('nombre_estado')->get(['id', 'clave_estado', 'nombre_estado']);

        $query = InegLocalidad::with(['municipio', 'estado'])
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('nombre_localidad', 'like', "%{$this->search}%")
                        ->orWhere('codigo_postal', 'like', "%{$this->search}%")
                        ->orWhereHas('municipio', fn($m) => $m->where('nombre_municipio', 'like', "%{$this->search}%"))
                        ->orWhereHas('estado', fn($e) => $e->where('nombre_estado', 'like', "%{$this->search}%"));
                });
            })
            ->when($this->filtroEstadoId, fn($q) => $q->where('estado_id', $this->filtroEstadoId))
            ->when($this->filtroMunicipioId, fn($q) => $q->where('municipio_id', $this->filtroMunicipioId))
            ->orderBy('nombre_localidad');

        $localidades = $query->paginate(15);

        return view('livewire.catalogos.infraestructura.geografia-inegi', [
            'totalEstados'     => $totalEstados,
            'totalMunicipios'  => $totalMunicipios,
            'totalLocalidades' => $totalLocalidades,
            'estados'          => $estados,
            'localidades'      => $localidades,
        ]);
    }
}

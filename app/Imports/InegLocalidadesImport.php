<?php

namespace App\Imports;

use App\Models\Infraestructura\InegEstado;
use App\Models\Infraestructura\InegLocalidad;
use App\Models\Infraestructura\InegMunicipio;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

/**
 * Importa el catálogo de localidades de INEGI.
 *
 * Columnas esperadas en el Excel (nombres de cabecera flexibles):
 *   CVE_ENT  / cve_ent  / clave_estado  → clave de estado  (2 dígitos)
 *   NOM_ENT  / nom_ent  / nombre_estado → nombre del estado
 *   CVE_MUN  / cve_mun  / clave_mun     → clave del municipio (3 dígitos)
 *   NOM_MUN  / nom_mun  / nombre_mun    → nombre del municipio
 *   CVE_LOC  / cve_loc  / clave_loc     → clave de localidad (4 dígitos)
 *   NOM_LOC  / nom_loc  / nombre_loc    → nombre de localidad
 *   CP / codigo_postal                   → código postal (opcional)
 *
 * Filas duplicadas (mismo municipio + clave_localidad) son ignoradas.
 */
class InegLocalidadesImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    public int $insertados = 0;
    public int $omitidos   = 0;
    public int $errores    = 0;

    /** Cache para evitar queries repetidas */
    private array $estadosCache    = [];
    private array $municipiosCache = [];

    public function chunkSize(): int
    {
        return 500;
    }

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            try {
                $cveEnt  = $this->limpiar($row, ['cve_ent', 'clave_estado', 'entidad']);
                $nomEnt  = $this->limpiar($row, ['nom_ent', 'nombre_estado', 'entidad_nombre']);
                $cveMun  = $this->limpiar($row, ['cve_mun', 'clave_mun', 'municipio']);
                $nomMun  = $this->limpiar($row, ['nom_mun', 'nombre_mun', 'municipio_nombre']);
                $cveLoc  = $this->limpiar($row, ['cve_loc', 'clave_loc', 'localidad']);
                $nomLoc  = $this->limpiar($row, ['nom_loc', 'nombre_loc', 'localidad_nombre', 'nombre_localidad']);
                $cp      = $this->limpiar($row, ['cp', 'codigo_postal', 'c_p', 'd_cp']);

                // Validar campos mínimos
                if (! $cveEnt || ! $cveMun || ! $cveLoc || ! $nomLoc) {
                    $this->omitidos++;
                    continue;
                }

                // Normalizar claves a longitud fija
                $cveEnt = str_pad((string) $cveEnt, 2, '0', STR_PAD_LEFT);
                $cveMun = str_pad((string) $cveMun, 3, '0', STR_PAD_LEFT);
                $cveLoc = str_pad((string) $cveLoc, 4, '0', STR_PAD_LEFT);

                // Estado
                $estado = $this->obtenerEstado($cveEnt, $nomEnt ?? 'Estado ' . $cveEnt);

                // Municipio
                $municipio = $this->obtenerMunicipio($estado->id, $cveMun, $nomMun ?? 'Municipio ' . $cveMun);

                // Localidad — skip si ya existe
                $existe = InegLocalidad::where('municipio_id', $municipio->id)
                    ->where('clave_localidad', $cveLoc)
                    ->exists();

                if ($existe) {
                    $this->omitidos++;
                    continue;
                }

                InegLocalidad::create([
                    'municipio_id'    => $municipio->id,
                    'estado_id'       => $estado->id,
                    'clave_localidad' => $cveLoc,
                    'nombre_localidad' => $nomLoc,
                    'codigo_postal'   => $cp ? str_pad((string) $cp, 5, '0', STR_PAD_LEFT) : null,
                ]);

                $this->insertados++;
            } catch (\Throwable) {
                $this->errores++;
            }
        }
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function limpiar(Collection $row, array $posibles): ?string
    {
        foreach ($posibles as $key) {
            if (isset($row[$key]) && trim((string) $row[$key]) !== '') {
                return trim((string) $row[$key]);
            }
        }
        return null;
    }

    private function obtenerEstado(string $clave, string $nombre): InegEstado
    {
        if (! isset($this->estadosCache[$clave])) {
            $this->estadosCache[$clave] = InegEstado::firstOrCreate(
                ['clave_estado' => $clave],
                ['nombre_estado' => $nombre]
            );
        }
        return $this->estadosCache[$clave];
    }

    private function obtenerMunicipio(int $estadoId, string $clave, string $nombre): InegMunicipio
    {
        $key = "{$estadoId}_{$clave}";
        if (! isset($this->municipiosCache[$key])) {
            $this->municipiosCache[$key] = InegMunicipio::firstOrCreate(
                ['estado_id' => $estadoId, 'clave_municipio' => $clave],
                ['nombre_municipio' => $nombre]
            );
        }
        return $this->municipiosCache[$key];
    }
}

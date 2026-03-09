<?php

namespace Database\Seeders;

// Se corrigieron las rutas para que apunten a la subcarpeta Infraestructura
use App\Models\Infraestructura\InegEstado;
use App\Models\Infraestructura\InegLocalidad;
use App\Models\Infraestructura\InegMunicipio;
use Illuminate\Database\Seeder;

class InegGeografiaSeeder extends Seeder
{
    public function run(): void
    {
        // Datos de la Costa Chica de Oaxaca — zona de cobertura de TVT
        $estados = [
            [
                'clave_estado' => '20',
                'nombre_estado' => 'Oaxaca',
                'municipios' => [
                    [
                        'clave_municipio' => '071',
                        'nombre_municipio' => 'Santiago Amuzgos',
                        'localidades' => [
                            ['clave_localidad' => '0001', 'nombre_localidad' => 'Santiago Amuzgos', 'codigo_postal' => '71518'],
                            ['clave_localidad' => '0003', 'nombre_localidad' => 'Los Llanos', 'codigo_postal' => '71518'],
                            ['clave_localidad' => '0005', 'nombre_localidad' => 'La Sabana', 'codigo_postal' => '71518'],
                            ['clave_localidad' => '0008', 'nombre_localidad' => 'El Coyul', 'codigo_postal' => '71518'],
                            ['clave_localidad' => '0012', 'nombre_localidad' => 'El Rincón Moreno', 'codigo_postal' => '71518'],
                        ],
                    ],
                    [
                        'clave_municipio' => '261',
                        'nombre_municipio' => 'San Pedro Amuzgos',
                        'localidades' => [
                            ['clave_localidad' => '0001', 'nombre_localidad' => 'San Pedro Amuzgos', 'codigo_postal' => '71517'],
                            ['clave_localidad' => '0004', 'nombre_localidad' => 'Huehuetán', 'codigo_postal' => '71517'],
                            ['clave_localidad' => '0007', 'nombre_localidad' => 'El Aguacate', 'codigo_postal' => '71517'],
                            ['clave_localidad' => '0010', 'nombre_localidad' => 'Cerro Paredes', 'codigo_postal' => '71517'],
                        ],
                    ],
                    [
                        'clave_municipio' => '032',
                        'nombre_municipio' => 'San Pedro Cacahuatepec',
                        'localidades' => [
                            ['clave_localidad' => '0001', 'nombre_localidad' => 'Cacahuatepec', 'codigo_postal' => '71570'],
                            ['clave_localidad' => '0003', 'nombre_localidad' => 'El Limón', 'codigo_postal' => '71570'],
                            ['clave_localidad' => '0006', 'nombre_localidad' => 'La Guadalupe', 'codigo_postal' => '71570'],
                            ['clave_localidad' => '0009', 'nombre_localidad' => 'El Potrero', 'codigo_postal' => '71570'],
                            ['clave_localidad' => '0013', 'nombre_localidad' => 'San Marcos', 'codigo_postal' => '71570'],
                        ],
                    ],
                    [
                        'clave_municipio' => '360',
                        'nombre_municipio' => 'San Andrés Zacatepec',
                        'localidades' => [
                            ['clave_localidad' => '0001', 'nombre_localidad' => 'San Andrés Zacatepec', 'codigo_postal' => '71516'],
                            ['clave_localidad' => '0005', 'nombre_localidad' => 'Pueblo Viejo', 'codigo_postal' => '71516'],
                            ['clave_localidad' => '0008', 'nombre_localidad' => 'El Encanto', 'codigo_postal' => '71516'],
                            ['clave_localidad' => '0011', 'nombre_localidad' => 'Cruz Chica', 'codigo_postal' => '71516'],
                        ],
                    ],
                    [
                        'clave_municipio' => '218',
                        'nombre_municipio' => 'San Juan Ipalapa',
                        'localidades' => [
                            ['clave_localidad' => '0001', 'nombre_localidad' => 'Ipalapa', 'codigo_postal' => '71519'],
                            ['clave_localidad' => '0004', 'nombre_localidad' => 'Río Manos', 'codigo_postal' => '71519'],
                            ['clave_localidad' => '0007', 'nombre_localidad' => 'Las Tinajas', 'codigo_postal' => '71519'],
                            ['clave_localidad' => '0010', 'nombre_localidad' => 'El Porvenir', 'codigo_postal' => '71519'],
                            ['clave_localidad' => '0014', 'nombre_localidad' => 'San Isidro', 'codigo_postal' => '71519'],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($estados as $estadoData) {
            $municipiosData = $estadoData['municipios'];
            unset($estadoData['municipios']);

            $estado = InegEstado::firstOrCreate(
                ['clave_estado' => $estadoData['clave_estado']],
                $estadoData
            );

            foreach ($municipiosData as $municipioData) {
                $localidadesData = $municipioData['localidades'];
                unset($municipioData['localidades']);

                $municipioData['estado_id'] = $estado->id;
                $municipio = InegMunicipio::firstOrCreate(
                    ['estado_id' => $estado->id, 'clave_municipio' => $municipioData['clave_municipio']],
                    $municipioData
                );

                foreach ($localidadesData as $localidadData) {
                    $localidadData['municipio_id'] = $municipio->id;
                    $localidadData['estado_id'] = $estado->id;
                    InegLocalidad::firstOrCreate(
                        ['municipio_id' => $municipio->id, 'clave_localidad' => $localidadData['clave_localidad']],
                        $localidadData
                    );
                }
            }
        }
    }
}
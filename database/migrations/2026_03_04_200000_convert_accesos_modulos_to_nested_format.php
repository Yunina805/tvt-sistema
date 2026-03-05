<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $accesos = DB::table('accesos_sistema')->whereNotNull('modulos')->get();

        foreach ($accesos as $acc) {
            $modulos = json_decode($acc->modulos, true);

            // Solo convertir si es array plano indexado (formato viejo)
            if (is_array($modulos) && !empty($modulos) && array_is_list($modulos)) {
                $newFormat = array_fill_keys($modulos, []);
                DB::table('accesos_sistema')->where('id', $acc->id)->update([
                    'modulos' => json_encode($newFormat, JSON_FORCE_OBJECT),
                ]);
            }
        }
    }

    public function down(): void
    {
        $accesos = DB::table('accesos_sistema')->whereNotNull('modulos')->get();

        foreach ($accesos as $acc) {
            $modulos = json_decode($acc->modulos, true);

            // Revertir: objeto asociativo → array plano
            if (is_array($modulos) && !empty($modulos) && !array_is_list($modulos)) {
                DB::table('accesos_sistema')->where('id', $acc->id)->update([
                    'modulos' => json_encode(array_keys($modulos)),
                ]);
            }
        }
    }
};

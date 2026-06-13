<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSuscripcionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('plan_suscripcion')->insert([
            [
                'nombre'               => 'Plan Básico',
                'descripcion'          => 'Publica hasta 1 establecimiento por 30 días.',
                'precio'               => 29.90,
                'max_establecimientos' => 1,
                'duracion_dias'        => 30,
            ],
            [
                'nombre'               => 'Plan Estándar',
                'descripcion'          => 'Publica hasta 2 establecimientos por 30 días.',
                'precio'               => 49.90,
                'max_establecimientos' => 2,
                'duracion_dias'        => 30,
            ],
            [
                'nombre'               => 'Plan Pro',
                'descripcion'          => 'Publica hasta 5 establecimientos por 30 días.',
                'precio'               => 89.90,
                'max_establecimientos' => 5,
                'duracion_dias'        => 30,
            ],
        ]);
    }
}

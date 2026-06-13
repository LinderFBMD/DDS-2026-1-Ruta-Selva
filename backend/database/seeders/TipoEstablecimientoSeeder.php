<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoEstablecimientoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tipo_establecimiento')->insertOrIgnore([
            ['nombre' => 'Restaurante', 'descripcion' => 'Local de comidas y bebidas'],
            ['nombre' => 'Cafetería',   'descripcion' => 'Local de café y snacks'],
            ['nombre' => 'Bar',         'descripcion' => 'Local de bebidas'],
            ['nombre' => 'Pastelería',  'descripcion' => 'Local de postres y pasteles'],
            ['nombre' => 'Food truck',  'descripcion' => 'Vehículo de comida'],
            ['nombre' => 'Mercado',     'descripcion' => 'Mercado de comidas'],
        ]);
    }
}
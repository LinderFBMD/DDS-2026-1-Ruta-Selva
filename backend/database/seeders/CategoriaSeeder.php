<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            'Cevichería', 'Parrillas', 'Pastelería', 'Pizzería',
            'Heladería', 'Jugería', 'Pollería', 'Chifa',
            'Comida típica', 'Cafetería', 'Mariscos', 'Vegetariano',
        ];

        foreach ($categorias as $nombre) {
            DB::table('categoria')->insertOrIgnore(['nombre' => $nombre]);
        }
    }
}
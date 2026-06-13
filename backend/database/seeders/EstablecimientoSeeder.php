<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstablecimientoSeeder extends Seeder
{
    public function run(): void
    {
        // ─────────────────────────────────────────
        // UBICACIONES (15)
        // ─────────────────────────────────────────
        $u1 = DB::table('ubicacion')->insertGetId([
            'departamento' => 'Lima',
            'provincia'    => 'Lima',
            'distrito'     => 'Miraflores',
            'direccion'    => 'Av. Larco 345',
            'latitud'      => -12.12163,
            'longitud'     => -77.02960,
        ]);

        $u2 = DB::table('ubicacion')->insertGetId([
            'departamento' => 'Lima',
            'provincia'    => 'Lima',
            'distrito'     => 'Barranco',
            'direccion'    => 'Jr. Unión 210',
            'latitud'      => -12.14735,
            'longitud'     => -77.02162,
        ]);

        $u3 = DB::table('ubicacion')->insertGetId([
            'departamento' => 'Lima',
            'provincia'    => 'Lima',
            'distrito'     => 'San Isidro',
            'direccion'    => 'Calle Las Begonias 441',
            'latitud'      => -12.09729,
            'longitud'     => -77.03564,
        ]);

        $u4 = DB::table('ubicacion')->insertGetId([
            'departamento' => 'Cusco',
            'provincia'    => 'Cusco',
            'distrito'     => 'Cusco',
            'direccion'    => 'Av. El Sol 123',
            'latitud'      => -13.53195,
            'longitud'     => -71.96746,
        ]);

        $u5 = DB::table('ubicacion')->insertGetId([
            'departamento' => 'Cusco',
            'provincia'    => 'Cusco',
            'distrito'     => 'San Blas',
            'direccion'    => 'Calle Hatunrumiyoc 456',
            'latitud'      => -13.51741,
            'longitud'     => -71.97835,
        ]);

        $u6 = DB::table('ubicacion')->insertGetId([
            'departamento' => 'Arequipa',
            'provincia'    => 'Arequipa',
            'distrito'     => 'Yanahuara',
            'direccion'    => 'Calle Jerusalén 407',
            'latitud'      => -16.39231,
            'longitud'     => -71.53703,
        ]);

        $u7 = DB::table('ubicacion')->insertGetId([
            'departamento' => 'Arequipa',
            'provincia'    => 'Arequipa',
            'distrito'     => 'Cercado',
            'direccion'    => 'Portal de Flores 116, Plaza de Armas',
            'latitud'      => -16.39880,
            'longitud'     => -71.53690,
        ]);

        $u8 = DB::table('ubicacion')->insertGetId([
            'departamento' => 'Piura',
            'provincia'    => 'Piura',
            'distrito'     => 'Piura',
            'direccion'    => 'Calle Libertad 875',
            'latitud'      => -5.19449,
            'longitud'     => -80.63282,
        ]);

        $u9 = DB::table('ubicacion')->insertGetId([
            'departamento' => 'La Libertad',
            'provincia'    => 'Trujillo',
            'distrito'     => 'Trujillo',
            'direccion'    => 'Jr. Pizarro 688',
            'latitud'      => -8.11198,
            'longitud'     => -79.02783,
        ]);

        $u10 = DB::table('ubicacion')->insertGetId([
            'departamento' => 'Lambayeque',
            'provincia'    => 'Chiclayo',
            'distrito'     => 'Chiclayo',
            'direccion'    => 'Av. Balta 512',
            'latitud'      => -6.77137,
            'longitud'     => -79.83895,
        ]);

        $u11 = DB::table('ubicacion')->insertGetId([
            'departamento' => 'Ica',
            'provincia'    => 'Ica',
            'distrito'     => 'Ica',
            'direccion'    => 'Av. Grau 253',
            'latitud'      => -14.06777,
            'longitud'     => -75.72862,
        ]);

        $u12 = DB::table('ubicacion')->insertGetId([
            'departamento' => 'Loreto',
            'provincia'    => 'Maynas',
            'distrito'     => 'Iquitos',
            'direccion'    => 'Malecón Tarapacá 198',
            'latitud'      => -3.74912,
            'longitud'     => -73.25383,
        ]);

        $u13 = DB::table('ubicacion')->insertGetId([
            'departamento' => 'San Martín',
            'provincia'    => 'San Martín',
            'distrito'     => 'Tarapoto',
            'direccion'    => 'Jr. Shapaja 340',
            'latitud'      => -6.48485,
            'longitud'     => -76.36228,
        ]);

        $u14 = DB::table('ubicacion')->insertGetId([
            'departamento' => 'Puno',
            'provincia'    => 'Puno',
            'distrito'     => 'Puno',
            'direccion'    => 'Jr. Lima 501',
            'latitud'      => -15.84022,
            'longitud'     => -70.02166,
        ]);

        $u15 = DB::table('ubicacion')->insertGetId([
            'departamento' => 'Junín',
            'provincia'    => 'Huancayo',
            'distrito'     => 'Huancayo',
            'direccion'    => 'Calle Real 1042',
            'latitud'      => -12.06513,
            'longitud'     => -75.20486,
        ]);

        // ─────────────────────────────────────────
        // ESTABLECIMIENTOS (15)
        // ─────────────────────────────────────────
        $e1 = DB::table('establecimiento')->insertGetId([
            'tipo_id'          => 1,
            'ubicacion_id'     => $u1,
            'nombre'           => 'El Mirador Gastronómico',
            'descripcion'      => 'Restaurante de alta cocina peruana ubicado en el corazón de Miraflores, con una carta que fusiona técnicas modernas y sabores tradicionales. Cada plato es una experiencia sensorial que celebra los ingredientes de nuestras tres regiones naturales.',
            'tiene_internet'   => true,
            'precio_entrada'   => null,
            'horario_apertura' => '12:00:00',
            'horario_cierre'   => '23:00:00',
            'estado'           => true,
        ]);

        $e2 = DB::table('establecimiento')->insertGetId([
            'tipo_id'          => 2,
            'ubicacion_id'     => $u2,
            'nombre'           => 'Café Barranco',
            'descripcion'      => 'Cafetería artística en el distrito bohemio de Barranco, rodeada de murales y galería de arte local. Sirve especialidades de café de origen peruano, acompañados de postres artesanales elaborados con insumos del Valle del Mantaro.',
            'tiene_internet'   => true,
            'precio_entrada'   => null,
            'horario_apertura' => '08:00:00',
            'horario_cierre'   => '21:00:00',
            'estado'           => true,
        ]);

        $e3 = DB::table('establecimiento')->insertGetId([
            'tipo_id'          => 1,
            'ubicacion_id'     => $u3,
            'nombre'           => 'Nikkei San Isidro',
            'descripcion'      => 'Restaurante especializado en cocina nikkei, fusión entre la gastronomía japonesa y peruana. Ofrece tiraditos, makis tropicales y ceviches con marinado de soya en un ambiente elegante y minimalista, ideal para almuerzos ejecutivos.',
            'tiene_internet'   => true,
            'precio_entrada'   => null,
            'horario_apertura' => '12:00:00',
            'horario_cierre'   => '22:00:00',
            'estado'           => true,
        ]);

        $e4 = DB::table('establecimiento')->insertGetId([
            'tipo_id'          => 1,
            'ubicacion_id'     => $u4,
            'nombre'           => 'La Selva Grill',
            'descripcion'      => 'Parrilla cusqueña que combina cortes de carne andina con leña de eucalipto y especias locales. Ubicado a pasos de la Plaza de Armas, es el favorito de los visitantes que buscan sabores auténticos después de explorar el centro histórico.',
            'tiene_internet'   => true,
            'precio_entrada'   => null,
            'horario_apertura' => '12:00:00',
            'horario_cierre'   => '22:00:00',
            'estado'           => true,
        ]);

        $e5 = DB::table('establecimiento')->insertGetId([
            'tipo_id'          => 2,
            'ubicacion_id'     => $u5,
            'nombre'           => 'Café Amazónico',
            'descripcion'      => 'Cafetería acogedora en el barrio artesanal de San Blas, con vista panorámica al centro histórico del Cusco. Ofrece infusiones de muña, mate de coca y bebidas especiales con ingredientes andinos, perfectas para aclimatarse a la altitud.',
            'tiene_internet'   => true,
            'precio_entrada'   => null,
            'horario_apertura' => '07:00:00',
            'horario_cierre'   => '20:00:00',
            'estado'           => true,
        ]);

        $e6 = DB::table('establecimiento')->insertGetId([
            'tipo_id'          => 1,
            'ubicacion_id'     => $u6,
            'nombre'           => 'Tradición Arequipeña',
            'descripcion'      => 'Picantería tradicional en Yanahuara que mantiene vivas las recetas arequipeñas de más de tres generaciones. Su rocoto relleno, adobo y chupe de camarones del río Chili son considerados referentes de la cocina del sur del Perú.',
            'tiene_internet'   => false,
            'precio_entrada'   => null,
            'horario_apertura' => '11:00:00',
            'horario_cierre'   => '18:00:00',
            'estado'           => true,
        ]);

        $e7 = DB::table('establecimiento')->insertGetId([
            'tipo_id'          => 3,
            'ubicacion_id'     => $u7,
            'nombre'           => 'La Bóveda del Sillar',
            'descripcion'      => 'Bar cultural y espacio de entretenimiento ubicado en un local de sillar blanco del siglo XVIII, en plena Plaza de Armas de Arequipa. Ofrece cócteles con pisco y chicha, además de conciertos de música folclórica los fines de semana.',
            'tiene_internet'   => true,
            'precio_entrada'   => 15.00,
            'horario_apertura' => '18:00:00',
            'horario_cierre'   => '02:00:00',
            'estado'           => true,
        ]);

        $e8 = DB::table('establecimiento')->insertGetId([
            'tipo_id'          => 1,
            'ubicacion_id'     => $u8,
            'nombre'           => 'Cevichería La Playa',
            'descripcion'      => 'Cevichería norteña en el corazón de Piura, famosa por sus ceviches de caballa y conchas negras extraídas frescos cada mañana del litoral piurano. Un referente de la gastronomía costera con recetas heredadas de pescadores artesanales.',
            'tiene_internet'   => false,
            'precio_entrada'   => null,
            'horario_apertura' => '09:00:00',
            'horario_cierre'   => '17:00:00',
            'estado'           => true,
        ]);

        $e9 = DB::table('establecimiento')->insertGetId([
            'tipo_id'          => 1,
            'ubicacion_id'     => $u9,
            'nombre'           => 'El Cántaro Trujillano',
            'descripcion'      => 'Restaurante de cocina liberteña especializado en shambar, sopa teóloga y el infaltable cabrito norteño. Decorado con cerámica mochica y réplicas de huacos, ofrece una experiencia gastronómica y cultural en el centro histórico de Trujillo.',
            'tiene_internet'   => true,
            'precio_entrada'   => null,
            'horario_apertura' => '10:00:00',
            'horario_cierre'   => '21:00:00',
            'estado'           => true,
        ]);

        $e10 = DB::table('establecimiento')->insertGetId([
            'tipo_id'          => 2,
            'ubicacion_id'     => $u10,
            'nombre'           => 'Pastelería Chiclayanas',
            'descripcion'      => 'Pastelería y cafetería fundada en 1985, reconocida en Chiclayo por sus king kong artesanales, alfajores de manjar y tortas de lúcuma. Un lugar imprescindible para llevarse los dulces típicos de Lambayeque como regalo o recuerdo.',
            'tiene_internet'   => true,
            'precio_entrada'   => null,
            'horario_apertura' => '07:30:00',
            'horario_cierre'   => '20:30:00',
            'estado'           => true,
        ]);

        $e11 = DB::table('establecimiento')->insertGetId([
            'tipo_id'          => 3,
            'ubicacion_id'     => $u11,
            'nombre'           => 'Bodega Vista Alegre',
            'descripcion'      => 'Bodega y vinoteca histórica en Ica, productora de pisco quebranta premiado internacionalmente. Ofrece visitas guiadas a los lagares, degustación de piscos y vinos, y una tienda donde adquirir botellas directamente de origen.',
            'tiene_internet'   => true,
            'precio_entrada'   => 25.00,
            'horario_apertura' => '09:00:00',
            'horario_cierre'   => '17:00:00',
            'estado'           => true,
        ]);

        $e12 = DB::table('establecimiento')->insertGetId([
            'tipo_id'          => 1,
            'ubicacion_id'     => $u12,
            'nombre'           => 'El Dorado Amazónico',
            'descripcion'      => 'Restaurante amazónico a orillas del río Itaya en Iquitos, especializado en paiche a la brasa, tacacho con cecina y juanes de yuca. El local cuenta con terraza sobre el río, donde los comensales disfrutan de los sabores únicos de la selva peruana.',
            'tiene_internet'   => false,
            'precio_entrada'   => null,
            'horario_apertura' => '11:00:00',
            'horario_cierre'   => '22:00:00',
            'estado'           => true,
        ]);

        $e13 = DB::table('establecimiento')->insertGetId([
            'tipo_id'          => 2,
            'ubicacion_id'     => $u13,
            'nombre'           => 'Café Selva Verde',
            'descripcion'      => 'Cafetería especializada en café de altura cultivado en los valles del Alto Mayo en Tarapoto. Ofrece métodos de preparación alternativos como chemex, aeropress y cold brew, además de desayunos con frutas exóticas de la selva alta.',
            'tiene_internet'   => true,
            'precio_entrada'   => null,
            'horario_apertura' => '07:00:00',
            'horario_cierre'   => '19:00:00',
            'estado'           => true,
        ]);

        $e14 = DB::table('establecimiento')->insertGetId([
            'tipo_id'          => 1,
            'ubicacion_id'     => $u14,
            'nombre'           => 'Restaurante Lago Azul',
            'descripcion'      => 'Restaurante puneño con vista directa al lago Titicaca, reconocido por su trucha a la plancha extraída de criaderos locales y sus sopas de quinua roja. Un destino imperdible para quienes visitan el lago más alto del mundo navegable.',
            'tiene_internet'   => true,
            'precio_entrada'   => null,
            'horario_apertura' => '10:00:00',
            'horario_cierre'   => '21:00:00',
            'estado'           => true,
        ]);

        $e15 = DB::table('establecimiento')->insertGetId([
            'tipo_id'          => 1,
            'ubicacion_id'     => $u15,
            'nombre'           => 'Huancaína House',
            'descripcion'      => 'Restaurante en el centro de Huancayo que celebra la cocina del Valle del Mantaro. Sus platos estrella son la papa a la huancaína, el mondongo serrano y la pachamanca preparada en horno de piedra los domingos. Ambiente familiar y precios accesibles.',
            'tiene_internet'   => false,
            'precio_entrada'   => null,
            'horario_apertura' => '09:00:00',
            'horario_cierre'   => '20:00:00',
            'estado'           => true,
        ]);

        // ─────────────────────────────────────────
        // FOTOS DE PORTADA (15)
        // ─────────────────────────────────────────
        DB::table('foto')->insert([
            [
                'establecimiento_id' => $e1,
                'url'        => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=800',
                'es_portada' => true,
            ],
            [
                'establecimiento_id' => $e2,
                'url'        => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=800',
                'es_portada' => true,
            ],
            [
                'establecimiento_id' => $e3,
                'url'        => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=800',
                'es_portada' => true,
            ],
            [
                'establecimiento_id' => $e4,
                'url'        => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=800',
                'es_portada' => true,
            ],
            [
                'establecimiento_id' => $e5,
                'url'        => 'https://images.unsplash.com/photo-1445116572660-236099ec97a0?w=800',
                'es_portada' => true,
            ],
            [
                'establecimiento_id' => $e6,
                'url'        => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800',
                'es_portada' => true,
            ],
            [
                'establecimiento_id' => $e7,
                'url'        => 'https://images.unsplash.com/photo-1470337458703-46ad1756a187?w=800',
                'es_portada' => true,
            ],
            [
                'establecimiento_id' => $e8,
                'url'        => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=800',
                'es_portada' => true,
            ],
            [
                'establecimiento_id' => $e9,
                'url'        => 'https://images.unsplash.com/photo-1482049016688-2d3e1b311543?w=800',
                'es_portada' => true,
            ],
            [
                'establecimiento_id' => $e10,
                'url'        => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=800',
                'es_portada' => true,
            ],
            [
                'establecimiento_id' => $e11,
                'url'        => 'https://images.unsplash.com/photo-1510812431401-41d2bd2722f3?w=800',
                'es_portada' => true,
            ],
            [
                'establecimiento_id' => $e12,
                'url'        => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=800',
                'es_portada' => true,
            ],
            [
                'establecimiento_id' => $e13,
                'url'        => 'https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?w=800',
                'es_portada' => true,
            ],
            [
                'establecimiento_id' => $e14,
                'url'        => 'https://images.unsplash.com/photo-1559329007-40df8a9345d8?w=800',
                'es_portada' => true,
            ],
            [
                'establecimiento_id' => $e15,
                'url'        => 'https://images.unsplash.com/photo-1498654896293-37aacf113fd9?w=800',
                'es_portada' => true,
            ],
        ]);

        // ─────────────────────────────────────────
        // CATEGORÍAS
        // ─────────────────────────────────────────
        DB::table('establecimiento_categoria')->insert([
            // e1 - El Mirador Gastronómico (Lima)
            ['establecimiento_id' => $e1, 'categoria_id' => 4],  // Cocina peruana
            ['establecimiento_id' => $e1, 'categoria_id' => 5],  // Alta cocina

            // e2 - Café Barranco
            ['establecimiento_id' => $e2, 'categoria_id' => 10], // Cafetería
            ['establecimiento_id' => $e2, 'categoria_id' => 3],  // Pastelería

            // e3 - Nikkei San Isidro
            ['establecimiento_id' => $e3, 'categoria_id' => 6],  // Fusión
            ['establecimiento_id' => $e3, 'categoria_id' => 1],  // Cevichería

            // e4 - La Selva Grill (Cusco)
            ['establecimiento_id' => $e4, 'categoria_id' => 2],  // Parrillas
            ['establecimiento_id' => $e4, 'categoria_id' => 9],  // Comida típica

            // e5 - Café Amazónico (Cusco)
            ['establecimiento_id' => $e5, 'categoria_id' => 10], // Cafetería
            ['establecimiento_id' => $e5, 'categoria_id' => 7],  // Bebidas

            // e6 - Tradición Arequipeña
            ['establecimiento_id' => $e6, 'categoria_id' => 9],  // Comida típica
            ['establecimiento_id' => $e6, 'categoria_id' => 4],  // Cocina peruana

            // e7 - La Bóveda del Sillar
            ['establecimiento_id' => $e7, 'categoria_id' => 8],  // Bar
            ['establecimiento_id' => $e7, 'categoria_id' => 7],  // Bebidas

            // e8 - Cevichería La Playa (Piura)
            ['establecimiento_id' => $e8, 'categoria_id' => 1],  // Cevichería
            ['establecimiento_id' => $e8, 'categoria_id' => 11], // Mariscos

            // e9 - El Cántaro Trujillano
            ['establecimiento_id' => $e9, 'categoria_id' => 9],  // Comida típica
            ['establecimiento_id' => $e9, 'categoria_id' => 4],  // Cocina peruana

            // e10 - Pastelería Chiclayanas
            ['establecimiento_id' => $e10, 'categoria_id' => 3], // Pastelería
            ['establecimiento_id' => $e10, 'categoria_id' => 10],// Cafetería

            // e11 - Bodega Vista Alegre (Ica)
            ['establecimiento_id' => $e11, 'categoria_id' => 7], // Bebidas
            ['establecimiento_id' => $e11, 'categoria_id' => 12],// Turismo / visita

            // e12 - El Dorado Amazónico (Iquitos)
            ['establecimiento_id' => $e12, 'categoria_id' => 4], // Cocina peruana
            ['establecimiento_id' => $e12, 'categoria_id' => 11],// Mariscos

            // e13 - Café Selva Verde (Tarapoto)
            ['establecimiento_id' => $e13, 'categoria_id' => 10],// Cafetería
            ['establecimiento_id' => $e13, 'categoria_id' => 7], // Bebidas

            // e14 - Restaurante Lago Azul (Puno)
            ['establecimiento_id' => $e14, 'categoria_id' => 4], // Cocina peruana
            ['establecimiento_id' => $e14, 'categoria_id' => 9], // Comida típica

            // e15 - Huancaína House (Huancayo)
            ['establecimiento_id' => $e15, 'categoria_id' => 9], // Comida típica
            ['establecimiento_id' => $e15, 'categoria_id' => 4], // Cocina peruana
        ]);
    }
}
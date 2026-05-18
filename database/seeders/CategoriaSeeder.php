<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        Categoria::query()->truncate();
        
        $categorias = [
            'Electrónica',
            'Ropa y Calzado',
            'Hogar y Jardín',
            'Deportes y Fitness',
            'Juegos y Juguetes',
            'Libros y Revistas',
            'Alimentos y Bebidas',
            'Belleza y Cuidado Personal',
            'Salud y Equipamiento Médico',
            'Automotriz',
            'Muebles',
            'Herramientas y Mejoras del Hogar',
            'Cámaras y Fotografía',
            'Música y Instrumentos',
            'Oficina y Papelería',
            'Bebés y Niños',
            'Mascotas',
            'Artículos de Fiesta',
            'Viajes y Equipaje',
            'Arte y Manualidades',
        ];

        foreach ($categorias as $nombre) {
            Categoria::query()->create(['nombre' => $nombre]);
        }
    }
}

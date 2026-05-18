<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        Producto::query()->truncate();
        
        $categorias = Categoria::query()->pluck('id')->toArray();
        $marcas = Marca::query()->pluck('id')->toArray();
        
        $productos = [
            ['nombre' => 'iPhone 15 Pro Max', 'precio' => 1299.99, 'stock' => 50],
            ['nombre' => 'Samsung Galaxy S24 Ultra', 'precio' => 1199.99, 'stock' => 45],
            ['nombre' => 'Sony WH-1000XM5 Audífonos', 'precio' => 399.99, 'stock' => 30],
            ['nombre' => 'Nike Air Max 270', 'precio' => 150.00, 'stock' => 100],
            ['nombre' => 'Adidas Ultraboost 22', 'precio' => 180.00, 'stock' => 80],
            ['nombre' => 'LG OLED C3 65"', 'precio' => 1799.99, 'stock' => 15],
            ['nombre' => 'PlayStation 5', 'precio' => 499.99, 'stock' => 25],
            ['nombre' => 'Xbox Series X', 'precio' => 499.99, 'stock' => 20],
            ['nombre' => 'MacBook Pro 16" M3', 'precio' => 2499.99, 'stock' => 12],
            ['nombre' => 'Dell XPS 15', 'precio' => 1499.99, 'stock' => 18],
            ['nombre' => 'Canon EOS R6 Mark II', 'precio' => 2499.00, 'stock' => 8],
            ['nombre' => 'Nikon Z8', 'precio' => 3996.95, 'stock' => 5],
            ['nombre' => 'Nintendo Switch OLED', 'precio' => 349.99, 'stock' => 40],
            ['nombre' => 'Sony PlayStation VR2', 'precio' => 549.99, 'stock' => 15],
            ['nombre' => 'L\'Oréal Revitalift Serum', 'precio' => 29.99, 'stock' => 200],
            ['nombre' => 'Colgate Total Whitening', 'precio' => 4.99, 'stock' => 500],
            ['nombre' => 'Nike Dri-Fit Camiseta', 'precio' => 45.00, 'stock' => 150],
            ['nombre' => 'Adidas Predator Football Boots', 'precio' => 120.00, 'stock' => 60],
            ['nombre' => 'HP OfficeJet Pro 9015e', 'precio' => 229.99, 'stock' => 35],
            ['nombre' => 'Lenovo ThinkPad X1 Carbon', 'precio' => 1699.99, 'stock' => 22],
        ];

        foreach ($productos as $producto) {
            Producto::query()->create([
                'nombre' => $producto['nombre'],
                'precio' => $producto['precio'],
                'stock' => $producto['stock'],
                'categoria_id' => $categorias[array_rand($categorias)],
                'marca_id' => $marcas[array_rand($marcas)],
            ]);
        }
    }
}

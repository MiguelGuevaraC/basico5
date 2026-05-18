<?php

namespace Database\Seeders;

use App\Models\Marca;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MarcaSeeder extends Seeder
{
    public function run(): void
    {
        Marca::query()->truncate();
        
        $marcas = [
            'Apple',
            'Samsung',
            'Sony',
            'Nike',
            'Adidas',
            'Puma',
            'LG',
            'Panasonic',
            'Microsoft',
            'Dell',
            'HP',
            'Lenovo',
            'Canon',
            'Nikon',
            'Coca-Cola',
            'Pepsi',
            'Nestlé',
            'L\'Oréal',
            'Unilever',
            'Colgate',
        ];

        foreach ($marcas as $nombre) {
            Marca::query()->create(['nombre' => $nombre]);
        }
    }
}

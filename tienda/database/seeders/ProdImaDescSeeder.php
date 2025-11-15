<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Imagen;
use App\Models\Descuento;
use App\Models\Producto;

class ProdImaDescSeeder extends Seeder
{
    public function run(): void
    {
        $imagenes = Imagen::factory(10)->create();
        $descuentos = Descuento::factory(5)->create();

        for ($i = 0; $i < 50; $i++) {
            Producto::factory()->create([
                'imagen_id' => $imagenes->random()->id,
                'descuento_id' => $descuentos->random()->id,
            ]);
        }
    }
}

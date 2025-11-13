<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

// Importa tus modelos
use App\Models\Imagen;
use App\Models\Descuento;
use App\Models\Producto;

class ProdImaDescSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Opcional: Limpiar las tablas (cuidado si tienes datos que no quieres perder)
        // Producto::truncate();
        // Imagen::truncate();
        // Descuento::truncate();

        // 1. Creamos un "pool" de Imágenes.
        // Creará 10 imágenes falsas en tu BD.
        $imagenes = Imagen::factory(10)->create();

        // 2. Creamos un "pool" de Descuentos.
        // Creará 5 descuentos falsos.
        $descuentos = Descuento::factory(5)->create();

        // 3. Creamos 50 Productos
        // Usaremos un bucle para que cada producto
        // tome una imagen y un descuento aleatorio de los pools que creamos.
        
        for ($i = 0; $i < 50; $i++) {
            Producto::factory()->create([
                'imagen_id' => $imagenes->random()->id,
                'descuento_id' => $descuentos->random()->id,
            ]);
        }
    }
}
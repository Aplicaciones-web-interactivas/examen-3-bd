<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Producto;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Producto>
 */
class ProductoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->words(3, true), // Nombre de producto falso
            'descripcion' => $this->faker->paragraph(), // Descripción falsa
            'precio' => $this->faker->randomFloat(2, 100, 5000), // Precio entre 100.00 y 5000.00
            'stock' => $this->faker->numberBetween(0, 200), // Stock entre 0 y 200
            
            // Dejamos 'imagen_id' y 'descuento_id' para el Seeder
        ];
    }
}
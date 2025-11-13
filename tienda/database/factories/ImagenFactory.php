<?php

namespace Database\Factories;
use App\Models\Imagen;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Imagen>
 */
class ImagenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->sentence(3), // Un nombre falso (ej. "Nisi sit qui.")
            'imagen_url' => $this->faker->imageUrl(640, 480, 'products', true), // Una URL de imagen falsa
        ];
    }
}

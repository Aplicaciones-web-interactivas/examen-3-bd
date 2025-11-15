<?php

namespace Database\Factories;
use App\Models\Descuento;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Descuento>
 */
class DescuentoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fechaInicio = $this->faker->dateTimeBetween('-1 month', '+1 month');
        $fechaFin = Carbon::instance($fechaInicio)->addDays($this->faker->numberBetween(7, 30));

        return [
            'porcentaje' => $this->faker->randomElement([10, 15, 20, 25, 50]), // Porcentajes comunes
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
        ];
    }
}

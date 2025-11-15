<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuario inicial
        User::factory()->create([
            'name' => 'Test',
            'apellido' => 'User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'rol' => 'cliente',
            'id_descuento' => null,
        ]);

        // Sembrar carritos, compras y sus detalles
        $this->call(AllExceptUsersSeeder::class);
    }
}

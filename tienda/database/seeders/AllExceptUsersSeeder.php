<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Producto;
use App\Models\Carrito;
use App\Models\Compra;
use App\Models\DetalleCarrito;
use App\Models\DetalleCompra;

class AllExceptUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Sembrar imágenes, descuentos y productos
        $this->call(ProdImaDescSeeder::class);

        $productos = Producto::all();

        if ($productos->isEmpty()) {
            $this->command->info('No hay productos. Seeder terminado.');
            return;
        }

        if (User::count() === 0) {
            $this->command->info('No hay usuarios. No se crean carritos ni compras.');
            return;
        }

        foreach (User::all() as $user) {

            // Carrito
            $carrito = Carrito::create([
                'user_id' => $user->id,
                'total' => 0,
            ]);

            $seleccion = $productos->random(rand(1, min(5, $productos->count())));

            $totalCarrito = 0;

            foreach ($seleccion as $prod) {
                $cantidad = rand(1, 3);
                $subtotal = round($cantidad * $prod->precio, 2);

                DetalleCarrito::create([
                    'carrito_id' => $carrito->id,
                    'producto_id' => $prod->id,
                    'cantidad' => $cantidad,
                    'subtotal' => $subtotal,
                ]);

                $totalCarrito += $subtotal;
            }

            $carrito->update(['total' => $totalCarrito]);

            // 50% de probabilidad de compra
            if (rand(0, 1)) {
                $compra = Compra::create([
                    'carrito_id' => $carrito->id,
                    'user_id' => $user->id,
                    'total' => $totalCarrito,
                    'fecha_compra' => now(),
                ]);

                foreach ($carrito->detalles as $det) {
                    DetalleCompra::create([
                        'compra_id' => $compra->id,
                        'producto_id' => $det->producto_id,
                        'cantidad' => $det->cantidad,
                        'subtotal' => $det->subtotal,
                    ]);
                }
            }
        }

        $this->command->info('Seeder completado correctamente.');
    }
}

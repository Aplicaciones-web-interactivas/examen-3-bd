<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Imagen;
use App\Models\Descuento;
use App\Models\Producto;
use App\Models\Carrito;
use App\Models\Compra;
use App\Models\DetalleCarrito;
use App\Models\DetalleCompra;

class AllExceptUsersSeeder extends Seeder
{
    /**
     * Seed the application's database except users.
     */
    public function run(): void
    {
        // 1) Sembrar imágenes, descuentos y productos usando el seeder existente
        $this->call(ProdImaDescSeeder::class);

        // 2) Obtener productos disponibles
        $productos = Producto::all();

        if ($productos->isEmpty()) {
            $this->command->info('No hay productos. Seeder terminado (solo ProdImaDescSeeder).');
            return;
        }

        // 3) Sembrar carritos, compras y detalles SOLO si existen usuarios en la tabla users
        $usersCount = User::count();

        if ($usersCount === 0) {
            $this->command->info('No hay usuarios en la tabla `users`. Se omiten `carritos` y `compras`.');
            return;
        }

        $users = User::all();

        foreach ($users as $user) {
            // Crear un carrito para el usuario
            $carrito = Carrito::create([
                'user_id' => $user->id,
                'total' => 0,
            ]);

            // Seleccionar entre 1 y 5 productos aleatorios para el carrito
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

            // Actualizar total del carrito
            $carrito->update(['total' => $totalCarrito]);

            // Aleatoriamente convertir algunos carritos en compras (50% de probabilidad)
            if (rand(0, 1) === 1) {
                $compra = Compra::create([
                    'user_id' => $user->id,
                    'carrito_id' => $carrito->id,
                    'total' => $totalCarrito,
                    'fecha_compra' => now(),
                ]);

                // Copiar detalles del carrito a detalles de compra
                $detallesCarrito = DetalleCarrito::where('carrito_id', $carrito->id)->get();
                foreach ($detallesCarrito as $det) {
                    DetalleCompra::create([
                        'compra_id' => $compra->id,
                        'producto_id' => $det->producto_id,
                        'cantidad' => $det->cantidad,
                        'subtotal' => $det->subtotal,
                    ]);
                }
            }
        }

        $this->command->info('AllExceptUsersSeeder: completado (sembradas imágenes, descuentos, productos y, si había usuarios, carritos/compras).');
    }
}

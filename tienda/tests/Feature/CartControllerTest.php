<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Producto;
use App\Models\Imagen;
use App\Models\Descuento;
use App\Models\Carrito;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    private function makeActiveDiscount(int $porcentaje = 10): Descuento
    {
        return Descuento::create([
            'porcentaje' => $porcentaje,
            'fecha_inicio' => now()->subDay()->toDateString(),
            'fecha_fin' => now()->addDay()->toDateString(),
        ]);
    }

    private function makeImage(): Imagen
    {
        return Imagen::create([
            'nombre' => 'img',
            'imagen_url' => 'http://example.com/img.jpg',
        ]);
    }

    private function makeProduct(float $precio = 100, int $stock = 10, ?Descuento $descuento = null): Producto
    {
        $imagen = $this->makeImage();
        $descuento = $descuento ?? $this->makeActiveDiscount(20);
        return Producto::create([
            'nombre' => 'Prod',
            'descripcion' => null,
            'precio' => $precio,
            'imagen_id' => $imagen->id,
            'descuento_id' => $descuento->id,
            'stock' => $stock,
        ]);
    }

    public function test_get_cart_creates_if_missing()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/cart');
        $response->assertStatus(200)
            ->assertJsonPath('status', 'ok')
            ->assertJsonStructure(['data' => ['id', 'total', 'items']]);

        $this->assertDatabaseHas('carritos', ['user_id' => $user->id]);
    }

    public function test_add_item_respects_stock_and_applies_discount()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $product = $this->makeProduct(precio: 100, stock: 5); // 20% desc -> precio_final 80

        $response = $this->post('/cart/add', [
            'producto_id' => $product->id,
            'cantidad' => 2,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'ok')
            ->assertJsonPath('data.total', 160); // 2 * 80 

        $this->assertDatabaseHas('detalle_carritos', [
            'producto_id' => $product->id,
            'cantidad' => 2,
            'subtotal' => 160,
        ]);
    }

    public function test_update_item_validates_stock()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $product = $this->makeProduct(precio: 50, stock: 3); 
        // add 1
        $this->post('/cart/add', ['producto_id' => $product->id, 'cantidad' => 1])->assertStatus(201);
        $carrito = Carrito::where('user_id', $user->id)->first();
        $detalle = $carrito->detalles()->where('producto_id', $product->id)->first();

        // try to update to 5 > stock(3) -> 422
        $this->put("/cart/item/{$detalle->id}", ['cantidad' => 5])
            ->assertStatus(422)
            ->assertJsonPath('status', 'error');
    }

    public function test_remove_item_and_clear_cart()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $product = $this->makeProduct(precio: 100, stock: 5);
        $this->post('/cart/add', ['producto_id' => $product->id, 'cantidad' => 1])->assertStatus(201);

        $carrito = Carrito::where('user_id', $user->id)->first();
        $detalle = $carrito->detalles()->first();

        // remove item
        $this->delete("/cart/item/{$detalle->id}")
            ->assertStatus(200)
            ->assertJsonPath('status', 'ok');
        $this->assertSoftDeleted('detalle_carritos', ['id' => $detalle->id]);

        $this->delete('/cart/clear')
            ->assertStatus(200)
            ->assertJsonPath('status', 'ok');
        $this->assertDatabaseHas('carritos', ['user_id' => $user->id, 'total' => 0]);
    }

    public function test_checkout_preview_returns_totals_with_discount()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $p1 = $this->makeProduct(precio: 100, stock: 10); // final 80
        $p2 = $this->makeProduct(precio: 50, stock: 10);  // final 40

        $this->post('/cart/add', ['producto_id' => $p1->id, 'cantidad' => 2])->assertStatus(201);
        $this->post('/cart/add', ['producto_id' => $p2->id, 'cantidad' => 1])->assertStatus(201);

        $resp = $this->get('/cart/preview');
        $resp->assertStatus(200)
            ->assertJsonPath('status', 'ok')
            ->assertJsonPath('data.total_preview', 200); // 2*80 + 1*40 
    }
}

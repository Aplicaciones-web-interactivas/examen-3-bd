<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\DetalleCarrito;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
	/**
	 * Obtiene el carrito del usuario autenticado (lo crea si no existe)
	 */
	public function getCart(Request $request)
	{
		$user = Auth::user();
		$carrito = Carrito::firstOrCreate(
			['user_id' => $user->id],
			['total' => 0]
		);

		$carrito->load(['detalles.producto.descuento']);

		return response()->json([
			'status' => 'ok',
			'data' => $this->serializeCart($carrito),
		]);
	}

	/**
	 * Agrega un producto al carrito o incrementa cantidad si ya existe
	 */
	public function addItem(Request $request)
	{
		$validated = $request->validate([
			'producto_id' => 'required|integer|exists:productos,id',
			'cantidad' => 'required|integer|min:1',
		]);

		$user = Auth::user();
		$carrito = Carrito::firstOrCreate(
			['user_id' => $user->id],
			['total' => 0]
		);

		$producto = Producto::findOrFail($validated['producto_id']);

		// Validar stock disponible considerando lo que ya está en el carrito
		$detalleExistente = $carrito->detalles()->where('producto_id', $producto->id)->first();
		$nuevaCantidad = $validated['cantidad'] + ($detalleExistente?->cantidad ?? 0);
		if ($nuevaCantidad > $producto->stock) {
			return response()->json([
				'status' => 'error',
				'message' => 'Stock insuficiente para la cantidad solicitada',
			], 422);
		}

		DB::transaction(function () use ($detalleExistente, $carrito, $producto, $nuevaCantidad, $validated) {
			$precioUnitario = $producto->precio_final; // usa accessor con descuento
			if ($detalleExistente) {
				$detalleExistente->cantidad = $nuevaCantidad;
				$detalleExistente->subtotal = $nuevaCantidad * $precioUnitario;
				$detalleExistente->save();
			} else {
				$carrito->detalles()->create([
					'producto_id' => $producto->id,
					'cantidad' => $validated['cantidad'],
					'subtotal' => $validated['cantidad'] * $precioUnitario,
				]);
			}
			$this->recalcularTotal($carrito);
		});

		$carrito->load('detalles.producto.descuento');
		return response()->json([
			'status' => 'ok',
			'message' => 'Producto agregado al carrito',
			'data' => $this->serializeCart($carrito),
		], 201);
	}

	/**
	 * Actualiza la cantidad de un item específico
	 */
	public function updateItem(Request $request, $id)
	{
		$validated = $request->validate([
			'cantidad' => 'required|integer|min:1',
		]);

		$user = Auth::user();
	$carrito = Carrito::where('user_id', $user->id)->first();
		if (!$carrito) {
			return response()->json([
				'status' => 'error',
				'message' => 'Carrito no existe',
			], 404);
		}

		$detalle = $carrito->detalles()->where('id', $id)->first();
		if (!$detalle) {
			return response()->json([
				'status' => 'error',
				'message' => 'Item no encontrado en el carrito',
			], 404);
		}

		$producto = $detalle->producto;
		if ($validated['cantidad'] > $producto->stock) {
			return response()->json([
				'status' => 'error',
				'message' => 'Stock insuficiente para la cantidad solicitada',
			], 422);
		}

		DB::transaction(function () use ($detalle, $producto, $validated, $carrito) {
			$detalle->cantidad = $validated['cantidad'];
			$detalle->subtotal = $detalle->cantidad * $producto->precio_final;
			$detalle->save();
			$this->recalcularTotal($carrito);
		});

		$carrito->load('detalles.producto.descuento');
		return response()->json([
			'status' => 'ok',
			'message' => 'Item actualizado',
			'data' => $this->serializeCart($carrito),
		]);
	}

	/**
	 * Elimina un item del carrito
	 */
	public function removeItem($id)
	{
		$user = Auth::user();
	$carrito = Carrito::where('user_id', $user->id)->first();
		if (!$carrito) {
			return response()->json([
				'status' => 'error',
				'message' => 'Carrito no existe',
			], 404);
		}
		$detalle = $carrito->detalles()->where('id', $id)->first();
		if (!$detalle) {
			return response()->json([
				'status' => 'error',
				'message' => 'Item no encontrado',
			], 404);
		}

		DB::transaction(function () use ($detalle, $carrito) {
			$detalle->delete();
			$this->recalcularTotal($carrito);
		});

		$carrito->load('detalles.producto.descuento');
		return response()->json([
			'status' => 'ok',
			'message' => 'Item eliminado',
			'data' => $this->serializeCart($carrito),
		]);
	}

	/**
	 * Limpia todos los items del carrito
	 */
	public function clearCart()
	{
		$user = Auth::user();
	$carrito = Carrito::where('user_id', $user->id)->first();
		if (!$carrito) {
			return response()->json([
				'status' => 'error',
				'message' => 'Carrito no existe',
			], 404);
		}

		DB::transaction(function () use ($carrito) {
			$carrito->detalles()->delete();
			$carrito->total = 0;
			$carrito->save();
		});

		return response()->json([
			'status' => 'ok',
			'message' => 'Carrito vaciado',
			'data' => $this->serializeCart($carrito->fresh()),
		]);
	}

	/**
	 * Previsualiza el checkout (subtotal por item y total con descuentos)
	 */
	public function checkoutPreview()
	{
		$user = Auth::user();
	$carrito = Carrito::where('user_id', $user->id)->first();
		if (!$carrito) {
			return response()->json([
				'status' => 'error',
				'message' => 'Carrito no existe',
			], 404);
		}

		$carrito->load('detalles.producto.descuento');
		$items = [];
		$total = 0;
		foreach ($carrito->detalles as $detalle) {
			$precioUnitario = $detalle->producto->precio_final;
			$subtotal = $detalle->cantidad * $precioUnitario;
			$items[] = [
				'id' => $detalle->id,
				'producto' => [
					'id' => $detalle->producto->id,
					'nombre' => $detalle->producto->nombre,
					'precio_original' => $detalle->producto->precio,
					'precio_final' => $precioUnitario,
					'tiene_descuento' => $detalle->producto->descuento?->estaActivo() ?? false,
					'descuento_porcentaje' => $detalle->producto->descuento?->porcentaje,
				],
				'cantidad' => $detalle->cantidad,
				'subtotal' => $subtotal,
			];
			$total += $subtotal;
		}

		return response()->json([
			'status' => 'ok',
			'data' => [
				'items' => $items,
				'total_preview' => $total,
			],
		]);
	}

	/**
	 * Recalcula el total del carrito basándose en los subtotales de los detalles
	 */
	private function recalcularTotal(Carrito $carrito): void
	{
		$total = $carrito->detalles()->sum('subtotal');
		$carrito->total = $total;
		$carrito->save();
	}

	/**
	 * Serializa el carrito para respuesta JSON consistente
	 */
	private function serializeCart(Carrito $carrito): array
	{
		$carrito->loadMissing('detalles.producto.descuento');
		return [
			'id' => $carrito->id,
			'total' => $carrito->total,
			'items' => $carrito->detalles->map(function ($detalle) {
				return [
					'id' => $detalle->id,
					'producto' => [
						'id' => $detalle->producto->id,
						'nombre' => $detalle->producto->nombre,
						'precio_original' => $detalle->producto->precio,
						'precio_final' => $detalle->producto->precio_final ?? $detalle->producto->precio,
						'descuento' => $detalle->producto->descuento?->porcentaje,
					],
					'cantidad' => $detalle->cantidad,
					'subtotal' => $detalle->subtotal,
				];
			})->toArray(),
		];
	}
}


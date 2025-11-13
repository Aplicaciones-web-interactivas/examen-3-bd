<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Compra;
use App\Models\DetalleCarrito;
use App\Models\DetalleCompra;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    //funciones para el carrito jejej
    public function checkout(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();
            
            // jalar el carrito del usuario
            $carrito = Carrito::where('user_id', $user->id)
                ->whereNull('deleted_at')
                ->with('detalles.producto')
                ->first();

            if (!$carrito || $carrito->detalles->isEmpty()) {
                return back()->with('error', 'No hay productos en el carrito');
            }

            // checar el stock de los productos
            foreach ($carrito->detalles as $detalle) {
                $producto = $detalle->producto;
                
                if ($producto->stock < $detalle->cantidad) {
                    DB::rollBack();
                    return back()->with('error', "Stock insuficiente para {$producto->nombre}. Disponible: {$producto->stock}");
                }
            }

            // crear la compra
            $compra = Compra::create([
                'user_id' => $user->id,
                'carrito_id' => $carrito->id,
                'total' => $carrito->total,
                'fecha_compra' => now(),
            ]);

            // crear detalles de compra y actualizar stock
            foreach ($carrito->detalles as $detalle) {
                
                DetalleCompra::create([
                    'compra_id' => $compra->id,
                    'producto_id' => $detalle->producto_id,
                    'cantidad' => $detalle->cantidad,
                    'subtotal' => $detalle->subtotal,
                ]);

                
                $producto = Producto::find($detalle->producto_id);
                $producto->stock -= $detalle->cantidad;
                $producto->save();
            }

            // Soft delete del carrito
            $carrito->delete();

            DB::commit();

            return redirect()->route('orders.show', $compra->id)
                ->with('success', 'Compra realizada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al procesar la compra: ' . $e->getMessage());
        }
    }

    //lista de las compras del usuario
    public function index()
    {
        $user = Auth::user();
        
        $compras = Compra::where('user_id', $user->id)
            ->with(['detalles.producto'])
            ->orderBy('fecha_compra', 'desc')
            ->paginate(10);

        return view('orders.index', compact('compras'));
    }

    //vista de finanzas para admin
    public function finanza()
    {
        
        $compras = Compra::with(['user', 'detalles.producto'])
            ->orderBy('fecha_compra', 'desc')
            ->paginate(20);

        $totalVentas = Compra::sum('total');
        $totalCompras = Compra::count();
        $promedioCompra = $totalCompras > 0 ? $totalVentas / $totalCompras : 0;

        $ventasMensuales = Compra::select(
                DB::raw('DATE_FORMAT(fecha_compra, "%Y-%m") as mes'),
                DB::raw('SUM(total) as total'),
                DB::raw('COUNT(*) as cantidad')
            )
            ->where('fecha_compra', '>=', now()->subMonths(6))
            ->groupBy('mes')
            ->orderBy('mes', 'desc')
            ->get();

        $productosMasVendidos = DetalleCompra::select(
                'producto_id',
                DB::raw('SUM(cantidad) as total_vendido'),
                DB::raw('SUM(subtotal) as ingresos')
            )
            ->with('producto')
            ->groupBy('producto_id')
            ->orderBy('total_vendido', 'desc')
            ->limit(10)
            ->get();

        return view('orders.finanzas', compact(
            'compras',
            'totalVentas',
            'totalCompras',
            'promedioCompra',
            'ventasMensuales',
            'productosMasVendidos'
        ));
    }

    public function show($id)
    {
        $user = Auth::user();
        
        $compra = Compra::with(['detalles.producto.imagen', 'user'])
            ->findOrFail($id);

        if ($compra->user_id !== $user->id && !$user->is_admin) {
            abort(403, 'No tienes permiso para ver esta compra');
        }

        return view('orders.show', compact('compra'));
    }
}

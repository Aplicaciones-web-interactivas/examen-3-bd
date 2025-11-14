<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Compra;
use App\Models\DetalleCompra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketController extends Controller
{
    /**
     * Procesa la compra y genera el ticket PDF
     */
    public function generarTicket(Request $request)
    {
        $user = Auth::user();
        
        // Obtener carrito con sus detalles
        $carrito = Carrito::where('user_id', $user->id)
            ->with(['detalles.producto.imagen', 'detalles.producto.descuento'])
            ->first();

        // Validar que el carrito existe y tiene productos
        if (!$carrito || $carrito->detalles->count() === 0) {
            return redirect()->route('cart.index')
                ->with('error', 'El carrito está vacío');
        }

        // Validar stock disponible
        foreach ($carrito->detalles as $detalle) {
            if ($detalle->cantidad > $detalle->producto->stock) {
                return redirect()->route('cart.index')
                    ->with('error', "Stock insuficiente para el producto: {$detalle->producto->nombre}");
            }
        }

        try {
            DB::beginTransaction();

            // Crear la compra
            $compra = Compra::create([
                'user_id' => $user->id,
                'carrito_id' => $carrito->id,
                'total' => $carrito->total,
                'fecha_compra' => now(),
            ]);

            // Crear detalles de compra y actualizar stock
            foreach ($carrito->detalles as $detalle) {
                // Crear detalle de compra
                DetalleCompra::create([
                    'compra_id' => $compra->id,
                    'producto_id' => $detalle->producto_id,
                    'cantidad' => $detalle->cantidad,
                    'subtotal' => $detalle->subtotal,
                ]);

                // Descontar stock
                $detalle->producto->decrement('stock', $detalle->cantidad);
            }

            // Vaciar el carrito (soft delete de los detalles)
            $carrito->detalles()->delete();
            $carrito->total = 0;
            $carrito->save();

            DB::commit();

            // Generar PDF del ticket
            $pdf = Pdf::loadView('pdf.ticket', [
                'compra' => $compra->load(['detalles.producto.descuento', 'user']),
                'fecha' => $compra->fecha_compra->format('d/m/Y H:i:s'),
            ]);

            // Descargar el PDF
            return $pdf->download('ticket-' . $compra->id . '.pdf');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('cart.index')
                ->with('error', 'Error al procesar la compra: ' . $e->getMessage());
        }
    }
}
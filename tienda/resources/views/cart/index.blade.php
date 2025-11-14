<x-layouts.app>
    <div class="min-h-screen bg-zinc-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-zinc-800">Mi Carrito</h1>
                <p class="mt-2 text-lg text-zinc-600">Revisa y confirma tus productos</p>
            </div>

            {{-- Mensajes --}}
            @if(session('status'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- Items del carrito --}}
                <div class="lg:col-span-2">
                    @if($carrito && $carrito->detalles->count() > 0)
                        <div class="bg-white rounded-lg border border-zinc-200 overflow-hidden">
                            @foreach($carrito->detalles as $detalle)
                                <div class="p-6 border-b border-zinc-200 last:border-b-0">
                                    <div class="flex gap-4">
                                        
                                        {{-- Imagen --}}
                                        <div class="w-24 h-24 bg-zinc-100 rounded-lg flex-shrink-0 flex items-center justify-center">
                                            @if($detalle->producto->imagen && $detalle->producto->imagen->url)
                                                <img 
                                                    src="{{ $detalle->producto->imagen->url }}" 
                                                    alt="{{ $detalle->producto->nombre }}"
                                                    class="w-full h-full object-cover rounded-lg"
                                                >
                                            @else
                                                <svg class="w-12 h-12 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            @endif
                                        </div>

                                        {{-- Información del producto --}}
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-lg font-semibold text-zinc-800 mb-1">
                                                {{ $detalle->producto->nombre }}
                                            </h3>
                                            
                                            {{-- Precio --}}
                                            <div class="mb-3">
                                                @if($detalle->producto->descuento && $detalle->producto->descuento->estaActivo())
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-sm text-zinc-500 line-through">
                                                            ${{ number_format($detalle->producto->precio, 2) }}
                                                        </span>
                                                        <span class="text-lg font-bold text-red-600">
                                                            ${{ number_format($detalle->producto->precio_final, 2) }}
                                                        </span>
                                                        <span class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-0.5 rounded">
                                                            -{{ $detalle->producto->descuento->porcentaje }}%
                                                        </span>
                                                    </div>
                                                @else
                                                    <span class="text-lg font-bold text-zinc-800">
                                                        ${{ number_format($detalle->producto->precio, 2) }}
                                                    </span>
                                                @endif
                                            </div>

                                            {{-- Controles de cantidad y eliminar --}}
                                            <div class="flex items-center gap-4">
                                                {{-- Cantidad --}}
                                                <form action="{{ route('cart.update', $detalle->id) }}" method="POST" class="flex items-center gap-2">
                                                    @csrf
                                                    @method('PUT')
                                                    
                                                    <flux:label class="text-sm font-medium text-zinc-700">Cantidad:</flux:label>
                                                    
                                                    <flux:input 
                                                        type="number" 
                                                        name="cantidad" 
                                                        value="{{ $detalle->cantidad }}"
                                                        min="1"
                                                        max="{{ $detalle->producto->stock }}"
                                                        class="w-20 text-center"
                                                    />
                                                    
                                                    <flux:button type="submit" size="sm" variant="primary">
                                                        Actualizar
                                                    </flux:button>
                                                </form>

                                                {{-- Eliminar --}}
                                                <form action="{{ route('cart.remove', $detalle->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    
                                                    <flux:button type="submit" size="sm" variant="danger">
                                                        Eliminar
                                                    </flux:button>
                                                </form>
                                            </div>

                                            {{-- Subtotal --}}
                                            <div class="mt-3">
                                                <span class="text-sm text-zinc-600">Subtotal: </span>
                                                <span class="text-lg font-bold text-zinc-800">
                                                    ${{ number_format($detalle->subtotal, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Botón vaciar carrito --}}
                        <div class="mt-4">
                            <form action="{{ route('cart.clear') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                
                                <flux:button type="submit" variant="ghost" class="text-red-600">
                                    Vaciar carrito
                                </flux:button>
                            </form>
                        </div>

                    @else
                        {{-- Carrito vacío --}}
                        <div class="bg-white rounded-lg border border-zinc-200 p-12 text-center">
                            <svg class="mx-auto h-16 w-16 text-zinc-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <h3 class="text-xl font-semibold text-zinc-800 mb-2">Tu carrito está vacío</h3>
                            <p class="text-zinc-600 mb-6">Agrega productos para comenzar tu compra</p>
                            <a href="{{ route('productos.catalogo') }}" wire:navigate>
                                <flux:button variant="primary">
                                    Ver productos
                                </flux:button>
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Resumen del pedido --}}
                <div class="lg:col-span-1">
                    @if($carrito && $carrito->detalles->count() > 0)
                        <div class="bg-white rounded-lg border border-zinc-200 p-6 sticky top-6">
                            <h2 class="text-2xl font-bold text-zinc-800 mb-6">Resumen del pedido</h2>
                            
                            {{-- Desglose --}}
                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between text-zinc-600">
                                    <span>Subtotal</span>
                                    <span>${{ number_format($carrito->total, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-zinc-600">
                                    <span>Envío</span>
                                    <span>Gratis</span>
                                </div>
                                <div class="border-t border-zinc-200 pt-3 flex justify-between text-xl font-bold text-zinc-800">
                                    <span>Total</span>
                                    <span>${{ number_format($carrito->total, 2) }}</span>
                                </div>
                            </div>

                            {{-- Botón finalizar compra --}}
                            <form action="{{ route('orders.checkout') }}" method="POST">
                                @csrf
                                <flux:button type="submit" variant="primary" class="w-full">
                                    Finalizar compra
                                </flux:button>
                            </form>

                            <div class="mt-4 text-center">
                                <a href="{{ route('productos.catalogo') }}" class="text-sm text-zinc-600 hover:text-zinc-800" wire:navigate>
                                    Continuar comprando
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-layouts.app>
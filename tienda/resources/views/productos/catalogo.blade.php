<x-layouts.app>
    <div class="min-h-screen bg-zinc-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-zinc-800">Catálogo de Productos</h1>
                <p class="mt-2 text-lg text-zinc-600">Explora nuestros productos disponibles</p>
            </div>

            {{-- Mensajes de éxito --}}
            @if(session('status'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Grid de productos --}}
            @if($productos->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($productos as $producto)
                        <div class="bg-white rounded-lg border border-zinc-200 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                            
                            {{-- Imagen del producto --}}
                            <div class="aspect-square bg-zinc-100 flex items-center justify-center">
                                @if($producto->imagen && $producto->imagen->url)
                                    <img 
                                        src="{{ $producto->imagen->url }}" 
                                        alt="{{ $producto->nombre }}"
                                        class="w-full h-full object-cover"
                                    >
                                @else
                                    <svg class="w-24 h-24 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                @endif
                            </div>

                            {{-- Información del producto --}}
                            <div class="p-5">
                                <h3 class="text-xl font-semibold text-zinc-800 mb-2">{{ $producto->nombre }}</h3>
                                
                                @if($producto->descripcion)
                                    <p class="text-sm text-zinc-600 mb-4 line-clamp-2">{{ $producto->descripcion }}</p>
                                @endif

                                {{-- Precio --}}
                                <div class="mb-4">
                                    @if($producto->descuento && $producto->descuento->estaActivo())
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm text-zinc-500 line-through">${{ number_format($producto->precio, 2) }}</span>
                                            <span class="text-2xl font-bold text-red-600">${{ number_format($producto->precio_final, 2) }}</span>
                                            <span class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded">
                                                -{{ $producto->descuento->porcentaje }}%
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-2xl font-bold text-zinc-800">${{ number_format($producto->precio, 2) }}</span>
                                    @endif
                                </div>

                                {{-- Stock --}}
                                <div class="mb-4">
                                    @if($producto->stock > 10)
                                        <span class="text-sm text-green-600 font-medium">En stock</span>
                                    @elseif($producto->stock > 0)
                                        <span class="text-sm text-orange-600 font-medium">Pocas unidades ({{ $producto->stock }})</span>
                                    @else
                                        <span class="text-sm text-red-600 font-medium">Sin stock</span>
                                    @endif
                                </div>

                                {{-- Botón agregar al carrito --}}
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                                    <input type="hidden" name="cantidad" value="1">
                                    
                                    <flux:button 
                                        type="submit" 
                                        class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold disabled:bg-zinc-300 disabled:cursor-not-allowed"
                                        :disabled="$producto->stock <= 0"
                                    >
                                        @if($producto->stock > 0)
                                            Agregar al carrito
                                        @else
                                            No disponible
                                        @endif
                                    </flux:button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Sin productos --}}
                <div class="bg-white rounded-lg border border-zinc-200 p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-zinc-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-zinc-800 mb-2">No hay productos disponibles</h3>
                    <p class="text-zinc-600">Vuelve pronto para ver nuevos productos</p>
                </div>
            @endif

        </div>
    </div>
</x-layouts.app>
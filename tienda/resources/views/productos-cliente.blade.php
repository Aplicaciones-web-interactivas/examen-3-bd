<x-layouts.app>
    <div >
        <h1 class="text-[#4472ca] font-bold text-3xl">Productos</h1>
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
                @foreach ($productos as $producto)
                    <div class="border border-[#3057A2] rounded-xl p-4 mb-4  shadow-md">
                        <div class="relative inline-block items-center justify-center w-full">
                           @foreach ($imagenes as $imagen)
                               @if ($producto->imagen_id == $imagen->id)
                                <img src="{{ $imagen->imagen_url }}" alt="Imagen de {{ $producto->nombre }}" class="w-full h-48 object-fill mb-4 rounded-xl">  
                               @endif
                           @endforeach
                           @foreach ($descuentos as $descuento)
                           @if ($producto->descuento_id==$descuento->id)
                               <label class="absolute top-2 right-2 bg-[#FFE66D] bg-opacity-60 text-black text-sm px-2 py-1 rounded">{{$descuento->porcentaje }}%</label>
                           @endif
                               
                           @endforeach
                        </div>
                        <h2 class="text-xl text-[#3057A2] font-semibold mb-2">{{ $producto->nombre }}</h2>
                        <p class="text-sm mb-1 text-[#EF4444]">{{$producto->stock}} <span class="text-[#707071]">stock</span></p>
                        <p class="mb-2 line-clamp-5">{{ $producto->descripcion }}</p>
                        <div class="flex items-center justify-between">
                            <p class="font-bold text-[#16a34a] mb-2">${{ number_format($producto->precio, 2) }}</p>
                            <button class="bg-[#F59E0B] px-4 py-1 cursor-pointer rounded-xl font-semibold text-md flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                </svg>
                                Comprar
                            </button>
                        </div>
                    </div>
                @endforeach
        </div>
        
        <div class="mt-8 flex justify-center">
            {{ $productos->links() }}
        </div>
    </div>
</x-layouts.app>
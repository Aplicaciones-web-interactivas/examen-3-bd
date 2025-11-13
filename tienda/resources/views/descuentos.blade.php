@extends('layouts.app')

@section('title', __('Descuentos'))

@section('content')
<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

    {{-- Encabezado --}}
    <div>
        <h1 class="text-3xl font-semibold text-brand tracking-tight">
            Productos en descuento
        </h1>
        <p class="text-neutral-600 text-sm mt-1">
            Ofertas y promociones activas disponibles para ti.
        </p>
    </div>

    {{-- Contenido principal --}}
    <div class="relative h-full flex-1 overflow-y-auto rounded-xl border border-neutral-200 p-4 bg-background">

        @if ($productos->count() > 0)

            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                @foreach ($productos as $producto)
                    <div
                        class="group flex flex-col h-full rounded-2xl border border-neutral-200 bg-surface shadow-sm overflow-hidden
                               transition-transform transition-shadow duration-200 hover:-translate-y-1 hover:shadow-md"
                    >
                        {{-- Imagen del producto --}}
                        <div class="relative aspect-[4/3] w-full bg-surface overflow-hidden">
                            @if ($producto->imagen)
                                <img src="{{ asset($producto->imagen->imagen_url) }}"
                                     class="object-cover w-full h-full"
                                     alt="{{ $producto->nombre }}">
                            @else
                                <x-placeholder-pattern class="size-full stroke-gray-900/15" />
                            @endif

                            {{-- Etiqueta de porcentaje arriba a la izquierda --}}
                            <span
                                class="absolute top-3 left-3 inline-flex items-center rounded-full
                                       bg-accent text-accent-content text-xs font-semibold px-2 py-1 shadow-sm"
                            >
                                -{{ $producto->descuento->porcentaje }}%
                            </span>
                        </div>

                        {{-- Información --}}
                        <div class="p-4 flex flex-col gap-2 flex-1">
                            <h3 class="text-base font-semibold text-text leading-snug">
                                {{ $producto->nombre }}
                            </h3>

                            <p class="text-sm text-neutral-600 line-clamp-2">
                                {{ $producto->descripcion }}
                            </p>

                            {{-- Precios --}}
                            <div class="mt-1 flex items-baseline gap-2">
                                <span class="text-xs line-through text-neutral-400">
                                    ${{ number_format($producto->precio, 2) }}
                                </span>
                                <span class="text-success font-bold text-lg">
                                    ${{ number_format($producto->precio_final, 2) }}
                                </span>
                            </div>

                            {{-- Pie de tarjeta --}}
                            <div class="mt-3 flex items-center justify-between">
                                <span class="text-xs text-neutral-500">
                                    Stock: <span class="font-semibold text-text">{{ $producto->stock }}</span>
                                </span>

                                {{-- Botón Agregar al Carrito --}}
                                <form method="POST" action="{{ route('cart.add') }}">
                                    @csrf
                                    <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                                    <input type="hidden" name="cantidad" value="1">

                                    <button
                                        type="submit"
                                        class="inline-flex items-center rounded-full px-3 py-1.5 text-xs font-semibold
                                               bg-brand text-white hover:bg-brand/90 transition-colors"
                                    >
                                        Agregar al Carrito
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                @endforeach

            </div>

            {{-- Paginación --}}
            <div class="mt-6">
                {{ $productos->links() }}
            </div>

        @else
            <div class="flex items-center justify-center h-full">
                <p class="text-neutral-500 text-lg">
                    No hay productos con descuento vigente en este momento.
                </p>
            </div>
        @endif

    </div>
</div>
@endsection

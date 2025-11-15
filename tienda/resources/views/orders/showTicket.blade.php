@extends('layouts.app')

@section('title', 'Detalle de Compra')

@section('content')
    <div class="min-h-screen bg-zinc-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold text-zinc-800">Compra #{{ $compra->id }}</h1>
                <a href="{{ route('orders.ticket', $compra->id) }}">
                    <flux:button variant="primary" icon-right="arrow-down-tray">
                        Descargar Ticket PDF
                    </flux:button>
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid md:grid-cols-2 gap-6 mb-8">
                <div class="space-y-2">
                    <p class="text-sm text-zinc-600"><span class="font-medium text-zinc-800">Fecha:</span> {{ $compra->fecha_compra->format('d/m/Y H:i') }}</p>
                    @php
                        $u = $compra->user;
                        $nombre = trim($u->name ?? '');
                        $apellido = trim($u->apellido ?? '');
                        $nombreCompleto = $nombre;
                        if ($apellido !== '' && stripos($nombre, $apellido) === false) {
                            $nombreCompleto = trim($nombre.' '.$apellido);
                        }
                    @endphp
                    <p class="text-sm text-zinc-600"><span class="font-medium text-zinc-800">Cliente:</span> {{ $nombreCompleto !== '' ? $nombreCompleto : 'N/D' }}</p>
                    <p class="text-sm text-zinc-600"><span class="font-medium text-zinc-800">Email:</span> {{ $compra->user->email ?? 'N/D' }}</p>
                </div>
                <div class="space-y-2">
                    <p class="text-sm text-zinc-600"><span class="font-medium text-zinc-800">Total:</span> $ {{ number_format($compra->total, 2) }}</p>
                    <p class="text-sm text-zinc-600"><span class="font-medium text-zinc-800">Items:</span> {{ $compra->detalles->count() }}</p>
                </div>
            </div>

            <div class="overflow-hidden rounded-lg border border-zinc-200 bg-white">
                <table class="min-w-full divide-y divide-zinc-200">
                    <thead class="bg-zinc-50">
                    <tr class="text-xs font-semibold uppercase tracking-wider text-zinc-600">
                        <th class="px-4 py-3 text-left">Producto</th>
                        <th class="px-4 py-3 text-right">Unidad</th>
                        <th class="px-4 py-3 text-center">Cant.</th>
                        <th class="px-4 py-3 text-right">Subtotal</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 bg-white text-sm">
                    @foreach($compra->detalles as $detalle)
                        @php($producto = $detalle->producto)
                        <tr class="hover:bg-zinc-50">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if($producto?->imagen?->imagen_url)
                                        <img src="{{ $producto->imagen->imagen_url }}" alt="{{ $producto->nombre }}" class="h-12 w-12 rounded object-cover border"/>
                                    @endif
                                    <div>
                                        <p class="font-medium text-zinc-800">{{ $producto->nombre }}</p>
                                        <p class="text-xs text-zinc-500">ID: {{ $producto->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right">
                                @if($producto?->descuento && $producto->descuento->estaActivo())
                                    <div class="flex flex-col items-end gap-1">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm text-zinc-500 line-through">
                                                ${{ number_format($producto->precio, 2) }}
                                            </span>
                                            <span class="text-lg font-bold text-red-600">
                                                ${{ number_format($producto->precio_final, 2) }}
                                            </span>
                                        </div>
                                        <span class="inline-flex rounded bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-800">
                                            -{{ $producto->descuento->porcentaje }}%
                                        </span>
                                    </div>
                                @else
                                    $ {{ number_format($producto->precio, 2) }}
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">{{ $detalle->cantidad }}</td>
                            <td class="px-4 py-3 text-right font-medium">$ {{ number_format($detalle->subtotal, 2) }}</td>
                            
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-8 flex justify-end">
                <div class="w-full max-w-sm rounded-lg border border-zinc-200 bg-white p-6">
                    <h2 class="mb-4 text-sm font-semibold text-zinc-700">Resumen</h2>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-zinc-600">Total</dt>
                            <dd class="font-semibold text-zinc-800">$ {{ number_format($compra->total, 2) }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection

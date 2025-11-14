@extends('layouts.app')

@section('title', 'Detalle de Compra')

@section('content')
    <div class="max-w-5xl mx-auto py-8 px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Compra #{{ $compra->id }}</h1>
            <a href="{{ route('orders.ticket', $compra->id) }}"
               class="inline-flex items-center gap-2 rounded bg-indigo-600 text-white px-4 py-2 text-sm font-medium hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                Descargar Ticket PDF
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded border border-green-300 bg-green-50 px-4 py-3 text-green-700">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 rounded border border-red-300 bg-red-50 px-4 py-3 text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid md:grid-cols-2 gap-6 mb-8">
            <div class="space-y-2">
                <p class="text-sm text-gray-600"><span class="font-medium text-gray-800">Fecha:</span> {{ $compra->fecha_compra->format('d/m/Y H:i') }}</p>
                @php
                    $u = $compra->user;
                    $nombre = trim($u->name ?? '');
                    $apellido = trim($u->apellido ?? '');
                    $nombreCompleto = $nombre;
                    if ($apellido !== '' && stripos($nombre, $apellido) === false) {
                        $nombreCompleto = trim($nombre.' '.$apellido);
                    }
                @endphp
                <p class="text-sm text-gray-600"><span class="font-medium text-gray-800">Cliente:</span> {{ $nombreCompleto !== '' ? $nombreCompleto : 'N/D' }}</p>
                <p class="text-sm text-gray-600"><span class="font-medium text-gray-800">Email:</span> {{ $compra->user->email ?? 'N/D' }}</p>
            </div>
            <div class="space-y-2">
                <p class="text-sm text-gray-600"><span class="font-medium text-gray-800">Total:</span> $ {{ number_format($compra->total, 2) }}</p>
                <p class="text-sm text-gray-600"><span class="font-medium text-gray-800">Items:</span> {{ $compra->detalles->count() }}</p>
            </div>
        </div>

        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr class="text-xs font-semibold uppercase tracking-wider text-gray-600">
                    <th class="px-4 py-3 text-left">Producto</th>
                    <th class="px-4 py-3 text-right">Unidad</th>
                    <th class="px-4 py-3 text-center">Cant.</th>
                    <th class="px-4 py-3 text-right">Subtotal</th>
                    <th class="px-4 py-3 text-center">Desc.</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white text-sm">
                @foreach($compra->detalles as $detalle)
                    @php($producto = $detalle->producto)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @if($producto?->imagen?->imagen_url)
                                    <img src="{{ $producto->imagen->imagen_url }}" alt="{{ $producto->nombre }}" class="h-12 w-12 rounded object-cover border"/>
                                @endif
                                <div>
                                    <p class="font-medium text-gray-800">{{ $producto->nombre }}</p>
                                    <p class="text-xs text-gray-500">ID: {{ $producto->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-right">
                            $ {{ number_format($producto->precio, 2) }}
                            @if($producto->descuento && $producto->descuento->estaActivo())
                                <span class="block text-xs text-green-600">$ {{ number_format($producto->precio_final,2) }} final</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">{{ $detalle->cantidad }}</td>
                        <td class="px-4 py-3 text-right font-medium">$ {{ number_format($detalle->subtotal, 2) }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($producto->descuento && $producto->descuento->estaActivo())
                                <span class="inline-flex rounded bg-green-100 px-2 py-1 text-xs font-semibold text-green-700">{{ $producto->descuento->porcentaje }}%</span>
                            @else
                                <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-8 flex justify-end">
            <div class="w-full max-w-sm rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                <h2 class="mb-4 text-sm font-semibold text-gray-700">Resumen</h2>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-600">Total</dt>
                        <dd class="font-semibold text-gray-800">$ {{ number_format($compra->total, 2) }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('orders.index') }}" class="text-sm text-indigo-600 hover:underline">&larr; Volver a mis compras</a>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Panel de Finanzas')

@section('content')
<div class="min-h-screen bg-gray-50 py-10 px-6">

    <h1 class="text-3xl font-bold text-center text-gray-800 mb-10">Panel de Finanzas</h1>

    {{-- Estadísticas generales --}}
    <div class="grid md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white shadow-md rounded-2xl p-6 text-center hover:shadow-lg transition">
            <h5 class="text-gray-600 font-semibold">Total de Ventas</h5>
            <h3 class="text-3xl text-green-600 font-bold mt-2">${{ number_format($totalVentas, 2) }}</h3>
        </div>
        <div class="bg-white shadow-md rounded-2xl p-6 text-center hover:shadow-lg transition">
            <h5 class="text-gray-600 font-semibold">Total de Compras</h5>
            <h3 class="text-3xl text-blue-600 font-bold mt-2">{{ $totalCompras }}</h3>
        </div>
        <div class="bg-white shadow-md rounded-2xl p-6 text-center hover:shadow-lg transition">
            <h5 class="text-gray-600 font-semibold">Promedio por Compra</h5>
            <h3 class="text-3xl text-yellow-500 font-bold mt-2">${{ number_format($promedioCompra, 2) }}</h3>
        </div>
    </div>



    {{-- Productos más vendidos --}}
    <div class="bg-white rounded-2xl shadow-md p-6 mb-10">
        <h5 class="text-lg font-semibold text-gray-700 mb-4">Top Productos Más Vendidos</h5>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-800 text-white">
                        <th class="p-3">#</th>
                        <th class="p-3">Producto</th>
                        <th class="p-3">Cantidad Vendida</th>
                        <th class="p-3">Ingresos Generados</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($productosMasVendidos as $index => $producto)
                        <tr class="hover:bg-gray-100">
                            <td class="p-3">{{ $index + 1 }}</td>
                            <td class="p-3">{{ $producto->producto->nombre ?? 'N/A' }}</td>
                            <td class="p-3">{{ $producto->total_vendido }}</td>
                            <td class="p-3 font-semibold text-gray-800">${{ number_format($producto->ingresos, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-md p-6">
        <h5 class="text-lg font-semibold text-gray-700 mb-4">Compras Recientes</h5>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-800 text-white">
                        <th class="p-3">Fecha</th>
                        <th class="p-3">Usuario</th>
                        <th class="p-3">Total</th>
                        <th class="p-3">Productos</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($compras as $compra)
                        <tr class="hover:bg-gray-100">
                            <td class="p-3">{{ \Carbon\Carbon::parse($compra->fecha_compra)->format('d/m/Y H:i') }}</td>
                            <td class="p-3">{{ $compra->user->name ?? 'Sin usuario' }}</td>
                            <td class="p-3 font-semibold text-gray-800">${{ number_format($compra->total, 2) }}</td>
                            <td class="p-3">
                                <ul class="list-disc pl-5">
                                    @foreach($compra->detalles as $detalle)
                                        <li>{{ $detalle->producto->nombre ?? 'N/A' }} (x{{ $detalle->cantidad }})</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="flex justify-center mt-6">
            {{ $compras->links() }}
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('ventasChart');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($ventasMensuales->pluck('mes')->reverse()) !!},
            datasets: [{
                label: 'Total de Ventas ($)',
                data: {!! json_encode($ventasMensuales->pluck('total')->reverse()) !!},
                backgroundColor: 'rgba(37, 99, 235, 0.6)',
                borderColor: 'rgba(37, 99, 235, 1)',
                borderWidth: 1,
                borderRadius: 6,
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
@endsection

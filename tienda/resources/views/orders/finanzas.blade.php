<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Finanzas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">

<div class="container mt-5 mb-5">
    <h1 class="mb-4 text-center">Panel de Finanzas</h1>

    {{-- Estadísticas generales --}}
    <div class="row mb-4 text-center">
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Total de Ventas</h5>
                    <h3 class="text-success">${{ number_format($totalVentas, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Total de Compras</h5>
                    <h3 class="text-primary">{{ $totalCompras }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Promedio por Compra</h5>
                    <h3 class="text-warning">${{ number_format($promedioCompra, 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Gráfico de ventas mensuales --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header">
            <h5>Ventas de los últimos 6 meses</h5>
        </div>
        <div class="card-body">
            <canvas id="ventasChart"></canvas>
        </div>
    </div>

    {{-- Productos más vendidos --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header">
            <h5>Top 10 Productos Más Vendidos</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Producto</th>
                        <th>Cantidad Vendida</th>
                        <th>Ingresos Generados</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productosMasVendidos as $index => $producto)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $producto->producto->nombre ?? 'N/A' }}</td>
                            <td>{{ $producto->total_vendido }}</td>
                            <td>${{ number_format($producto->ingresos, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tabla de compras recientes --}}
    <div class="card shadow-sm">
        <div class="card-header">
            <h5>Compras Recientes</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Fecha</th>
                        <th>Usuario</th>
                        <th>Total</th>
                        <th>Productos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($compras as $compra)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($compra->fecha_compra)->format('d/m/Y H:i') }}</td>
                            <td>{{ $compra->user->name ?? 'Sin usuario' }}</td>
                            <td>${{ number_format($compra->total, 2) }}</td>
                            <td>
                                <ul class="mb-0">
                                    @foreach($compra->detalles as $detalle)
                                        <li>{{ $detalle->producto->nombre ?? 'N/A' }} (x{{ $detalle->cantidad }})</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Paginación --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $compras->links() }}
            </div>
        </div>
    </div>

</div>

<script>
    const ctx = document.getElementById('ventasChart');
    const ventasChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($ventasMensuales->pluck('mes')->reverse()) !!},
            datasets: [{
                label: 'Total de Ventas ($)',
                data: {!! json_encode($ventasMensuales->pluck('total')->reverse()) !!},
                borderWidth: 1,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket de compra #{{ $compra->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        .header { text-align: center; margin-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; }
        .meta { margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background: #f6f6f6; text-align: left; }
        .text-right { text-align: right; }
        .totals td { border: none; padding: 6px; }
        .footer { margin-top: 12px; text-align: center; color: #666; font-size: 11px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Tienda - Ticket de compra</h1>
        <div>#{{ $compra->id }}</div>
    </div>

    <div class="meta">
        <div><strong>Fecha:</strong> {{ optional($compra->fecha_compra)->format('Y-m-d H:i') }}</div>
        <div><strong>Cliente:</strong> {{ $compra->user?->name }} {{ $compra->user?->apellido }}</div>
        <div><strong>Email:</strong> {{ $compra->user?->email }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th class="text-right">Precio</th>
                <th class="text-right">Cantidad</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($compra->detalles as $detalle)
                @php
                    $nombre = $detalle->producto?->nombre ?? 'Producto';
                    $precio = $detalle->producto?->precio_final ?? $detalle->producto?->precio ?? 0;
                    $subtotal = $detalle->subtotal ?? ($detalle->cantidad * $precio);
                    $total += $subtotal;
                @endphp
                <tr>
                    <td>{{ $nombre }}</td>
                    <td class="text-right">{{ number_format($precio, 2) }}</td>
                    <td class="text-right">{{ $detalle->cantidad }}</td>
                    <td class="text-right">{{ number_format($subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td style="width:80%; text-align:right"><strong>Total</strong></td>
            <td style="width:20%; text-align:right"><strong>{{ number_format($compra->total ?? $total, 2) }}</strong></td>
        </tr>
    </table>

    <div class="footer">Gracias por su compra.</div>
</body>
</html>

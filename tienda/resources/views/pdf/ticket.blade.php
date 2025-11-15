<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Compra #{{ $compra->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #e74c3c;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #e74c3c;
            font-size: 28px;
            margin-bottom: 5px;
        }
        
        .header p {
            color: #666;
            font-size: 14px;
        }
        
        .info-section {
            margin-bottom: 25px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 5px 0;
        }
        
        .info-label {
            font-weight: bold;
            color: #555;
        }
        
        .info-value {
            color: #333;
        }
        
        .ticket-number {
            background: #e74c3c;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            display: inline-block;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        thead {
            background: #f8f9fa;
        }
        
        th {
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
            color: #333;
            border-bottom: 2px solid #e74c3c;
        }
        
        td {
            padding: 10px 8px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        tbody tr:last-child td {
            border-bottom: none;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .totals {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid #e0e0e0;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 14px;
        }
        
        .total-final {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
        }
        
        .total-final .total-row {
            font-size: 18px;
            font-weight: bold;
            color: #e74c3c;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            color: #666;
            font-size: 11px;
        }
        
        .product-name {
            font-weight: 600;
            color: #333;
        }
        
        .discount-badge {
            background: #e74c3c;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            margin-left: 5px;
        }
    </style>
</head>
<body>
    
    {{-- Header --}}
    <div class="header">
        <h1>TICKET DE COMPRA</h1>
    </div>

    {{-- Número de ticket y fecha --}}
    <div class="info-section">
        <div class="ticket-number">
            TICKET #{{ str_pad($compra->id, 6, '0', STR_PAD_LEFT) }}
        </div>
        
        <div class="info-row">
            <span class="info-label">Fecha:</span>
            <span class="info-value">{{ $fecha }}</span>
        </div>
        
        <div class="info-row">
            <span class="info-label">Cliente:</span>
            <span class="info-value">{{ $compra->user->name }} {{ $compra->user->apellido ?? '' }}</span>
        </div>
        
        <div class="info-row">
            <span class="info-label">Email:</span>
            <span class="info-value">{{ $compra->user->email }}</span>
        </div>
    </div>

    {{-- Tabla de productos --}}
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th class="text-center">Cantidad</th>
                <th class="text-right">Precio Unit.</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($compra->detalles as $detalle)
                <tr>
                    <td>
                        <span class="product-name">{{ $detalle->producto->nombre }}</span>
                        @if($detalle->producto->descuento && $detalle->producto->descuento->estaActivo())
                            <span class="discount-badge">-{{ $detalle->producto->descuento->porcentaje }}%</span>
                        @endif
                    </td>
                    <td class="text-center">{{ $detalle->cantidad }}</td>
                    <td class="text-right">${{ number_format($detalle->subtotal / $detalle->cantidad, 2) }}</td>
                    <td class="text-right">${{ number_format($detalle->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totales --}}
    <div class="totals">
        <div class="total-row">
            <span>Subtotal:</span>
            <span>${{ number_format($compra->total, 2) }}</span>
        </div>
        
        <div class="total-row">
            <span>Envío:</span>
            <span>Gratis</span>
        </div>
        
        <div class="total-final">
            <div class="total-row">
                <span>TOTAL:</span>
                <span>${{ number_format($compra->total, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p>Gracias por tu compra</p>
        <p>Este es un comprobante de compra electrónico</p>
        <p>Para cualquier duda o aclaración contacta a soporte@tienda.com</p>
    </div>

</body>
</html>
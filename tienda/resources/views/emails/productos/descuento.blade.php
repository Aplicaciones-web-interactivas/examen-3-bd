<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Producto con descuento</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; color:#222; }
        .container { max-width:600px; margin:0 auto; padding:16px; }
        h1 { font-size:20px; margin-bottom:12px; }
        .footer { margin-top:24px; font-size:12px; color:#666; }
        .card { border:1px solid #e5e7eb; border-radius:8px; padding:16px; background:#f9fafb; }
        .price-original { text-decoration: line-through; color:#b91c1c; }
        .price-final { font-weight:bold; color:#065f46; }
    </style>
</head>
<body>
<div class="container">
    <h1>¡Nuevo producto con descuento!</h1>
    <p>Se ha publicado un producto con un descuento activo. Aprovecha la oferta:</p>
    <div class="card">
        <p><strong>Producto:</strong> {{ $producto->nombre }}</p>
        <p><strong>Descripción:</strong> {{ $producto->descripcion ?? 'Sin descripción' }}</p>
        <p><strong>Descuento:</strong> {{ $descuento->porcentaje }}%</p>
        <p><strong>Precio original:</strong> <span class="price-original">${{ number_format($producto->precio,2) }}</span></p>
        <p><strong>Precio con descuento:</strong> <span class="price-final">${{ number_format($precio_final,2) }}</span></p>
        <p><strong>Vigencia:</strong> {{ $descuento->fecha_inicio->format('d/m/Y') }} - {{ $descuento->fecha_fin->format('d/m/Y') }}</p>
    </div>
    <p style="margin-top:16px;">Visita la tienda para comprar antes de que termine la promoción.</p>
    <div class="footer">Este mensaje se generó automáticamente. No respondas a este correo.</div>
</div>
</body>
</html>
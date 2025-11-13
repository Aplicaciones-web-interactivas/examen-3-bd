@component('mail::message')
# Hola {{ $usuario->name }}

Hay un nuevo descuento disponible:

- **Porcentaje:** {{ $descuento->porcentaje }}%
- **Vigencia:** {{ $descuento->fecha_inicio }} a {{ $descuento->fecha_fin }}

@if ($descuento->productos->count())
Estos productos tienen este descuento:
@foreach ($descuento->productos as $producto)
- {{ $producto->nombre }} (precio: ${{ number_format($producto->precio, 2) }})
@endforeach
@endif

@component('mail::button', ['url' => url('/')])
Ir a la tienda
@endcomponent

Gracias por tu preferencia.
@endcomponent

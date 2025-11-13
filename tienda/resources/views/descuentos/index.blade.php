<h1>Listado de Descuentos</h1>

@foreach ($descuentos as $d)
    <p>ID: {{ $d->id }} — {{ $d->porcentaje }}%</p>
@endforeach

<a href="/testing">Volver a Testing</a>

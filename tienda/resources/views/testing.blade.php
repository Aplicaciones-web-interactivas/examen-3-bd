<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Testing CRUD</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        h2 { margin-top: 40px; }
        form { margin-bottom: 20px; padding: 15px; border: 1px solid #ccc; }
        input, select { padding: 7px; width: 100%; margin: 5px 0; }
        button { padding: 10px 15px; cursor: pointer; }
        .item { border: 1px solid #eee; padding: 10px; margin-bottom: 10px; }
    </style>
</head>
<body>

<h1>Vista de Testing – CRUD Imágenes y Descuentos</h1>

{{-- Mensajes --}}
@if(session('status'))
    <p style="color: green;"><strong>{{ session('status') }}</strong></p>
@endif

<hr>

{{-- ===================================================== --}}
{{-- FORMULARIO CREAR IMAGEN --}}
{{-- ===================================================== --}}
<h2>Crear Imagen</h2>

<form action="{{ url('/imagenes') }}" method="POST">
    @csrf
    <label>Nombre:</label>
    <input type="text" name="nombre" required>

    <label>Imagen URL:</label>
    <input type="text" name="imagen_url" required>

    <button type="submit">Guardar Imagen</button>
</form>


{{-- ===================================================== --}}
{{-- LISTA DE IMÁGENES --}}
{{-- ===================================================== --}}
<h2>Lista de Imágenes</h2>

@foreach($imagenes as $img)
    <div class="item">
        <strong>ID:</strong> {{ $img->id }} <br>
        <strong>Nombre:</strong> {{ $img->nombre }} <br>
        <strong>URL:</strong> <a href="{{ $img->imagen_url }}" target="_blank">{{ $img->imagen_url }}</a>
        <br><br>

        {{-- EDITAR IMAGEN --}}
        <form action="{{ url('/imagenes/'.$img->id) }}" method="POST">
            @csrf
            @method('PUT')

            <label>Editar Nombre:</label>
            <input type="text" name="nombre" value="{{ $img->nombre }}">

            <label>Editar Imagen URL:</label>
            <input type="text" name="imagen_url" value="{{ $img->imagen_url }}">

            <button type="submit">Actualizar</button>
        </form>

        {{-- ELIMINAR IMAGEN --}}
        <form action="{{ url('/imagenes/'.$img->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" style="background:red;color:white;">Eliminar</button>
        </form>
    </div>
@endforeach


{{-- ===================================================== --}}
{{-- FORMULARIO CREAR DESCUENTO --}}
{{-- ===================================================== --}}
<h2>Crear Descuento</h2>

<form action="{{ url('/descuentos') }}" method="POST">
    @csrf

    <label>Porcentaje de descuento:</label>
    <input type="number" name="porcentaje" min="1" max="100" required>

    <label>Fecha inicio (opcional):</label>
    <input type="date" name="fecha_inicio">

    <label>Fecha fin (opcional):</label>
    <input type="date" name="fecha_fin">

    <button type="submit">Crear Descuento</button>
</form>


</body>
</html>

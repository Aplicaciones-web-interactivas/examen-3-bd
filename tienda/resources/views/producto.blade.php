<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Productos (pruebas)</title>
</head>
<body>

<h1>Productos</h1>

@if (session('status'))
  <div>{{ session('status') }}</div>
@endif

@if ($errors->any())
  <div>
    <strong>Errores:</strong>
    <ul>
      @foreach ($errors->all() as $e)
        <li>{{ $e }}</li>
      @endforeach
    </ul>
  </div>
@endif

<form method="GET" action="{{ route('productos.index') }}">
  <label>Buscar por nombre:
    <input type="text" name="nombre" value="{{ request('nombre') }}">
  </label>
  <button type="submit">Buscar</button>
  <a href="{{ route('productos.index') }}">Limpiar</a>
</form>

<hr>

<form method="POST" action="{{ route('productos.store') }}">
  @csrf
  <h3>Crear producto</h3>
  <label>Nombre <input type="text" name="nombre" value="{{ old('nombre') }}"></label>
  <label>Descripción <input type="text" name="descripcion" value="{{ old('descripcion') }}"></label>
  <label>Precio <input type="number" step="0.01" name="precio" value="{{ old('precio') }}"></label>
  <label>imagen_id <input type="number" name="imagen_id" value="{{ old('imagen_id') }}"></label>
  <label>descuento_id <input type="number" name="descuento_id" value="{{ old('descuento_id') }}"></label>
  <label>Stock <input type="number" name="stock" value="{{ old('stock') }}"></label>
  <button type="submit">Guardar</button>
</form>

<hr>



<h3>Listado</h3>

@if($productos->isEmpty())
  <p>No se encontraron productos.</p>
@else
  <table border="1" cellpadding="6" cellspacing="0">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Precio</th>
        <th>imagen_id</th>
        <th>descuento_id</th>
        <th>Stock</th>
        <th>Actualizar</th>
        <th>Eliminar</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($productos as $p)
        <tr>
          <td>{{ $p->id }}</td>
          <td>{{ $p->nombre }}</td>
          <td>{{ $p->descripcion }}</td>
          <td>{{ $p->precio }}</td>
          <td>{{ $p->imagen_id }}</td>
          <td>{{ $p->descuento_id }}</td>
          <td>{{ $p->stock }}</td>
          <td>
            <form method="POST" action="{{ route('productos.update', $p->id) }}">
              @csrf
              @method('PUT')
              <input type="text" name="nombre" value="{{ $p->nombre }}">
              <input type="text" name="descripcion" value="{{ $p->descripcion }}">
              <input type="number" step="0.01" name="precio" value="{{ $p->precio }}">
              <input type="number" name="imagen_id" value="{{ $p->imagen_id }}">
              <input type="number" name="descuento_id" value="{{ $p->descuento_id }}">
              <input type="number" name="stock" value="{{ $p->stock }}">
              <button type="submit">Actualizar</button>
            </form>
          </td>
          <td>
            <form method="POST" action="{{ route('productos.destroy', $p->id) }}">
              @csrf
              @method('DELETE')
              <button type="submit" onclick="return confirm('¿Eliminar?')">Eliminar</button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endif

</body>
</html>

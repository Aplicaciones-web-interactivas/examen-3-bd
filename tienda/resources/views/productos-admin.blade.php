<x-layouts.app>
<div class="max-w-6xl mx-auto px-6 py-8">
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
        <h3 class="text-xl font-semibold text-gray-800 mb-4 text-center">Crear Producto</h3>
        <form method="POST" action="{{ route('productos.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-gray-700">Nombre
                    <input type="text" name="nombre" value="{{ old('nombre') }}" class="border border-gray-300 rounded px-2 py-1 w-full">
                </label>
            </div>
            <div>
                <label class="block text-gray-700">Descripción
                    <textarea name="descripcion" class="border border-gray-300 rounded px-2 py-1 w-full">{{ old('descripcion') }}</textarea>
                </label>
            </div>
            <div>
                <label class="block text-gray-700">Precio
                    <input type="number" step="0.01" name="precio" value="{{ old('precio') }}" class="border border-gray-300 rounded px-2 py-1 w-full">
                </label>
            </div>
            <div>
                <label class="block text-gray-700">Stock
                    <input type="number" name="stock" value="{{ old('stock') }}" class="border border-gray-300 rounded px-2 py-1 w-full">
                </label>
            </div>
            <div>
                <label class="block text-gray-700">Imagen
                    <select name="imagen_id" class="border border-gray-300 rounded px-2 py-1 w-full">
                        <option value="" disabled selected>Selecciona imagen…</option>
                        @forelse($imagenes as $img)
                            <option value="{{ $img->id }}" @selected(old('imagen_id') == $img->id)>
                                {{ $img->nombre ?? 'Imagen #'.$img->id }}
                            </option>
                        @empty
                            <option value="" disabled>No hay imágenes registradas</option>
                        @endforelse
                    </select>
                </label>
            </div>
            <div>
                <label class="block text-gray-700">Descuento
                    <select name="descuento_id" class="border border-gray-300 rounded px-2 py-1 w-full">
                        <option value="" disabled selected>Selecciona descuento…</option>
                        @forelse($descuentos as $desc)
                            <option value="{{ $desc->id }}" @selected(old('descuento_id') == $desc->id)>
                                @if(!is_null($desc->porcentaje)) {{ $desc->porcentaje }}% @else Descuento #{{ $desc->id }} @endif
                            </option>
                        @empty
                            <option value="" disabled>No hay descuentos registrados</option>
                        @endforelse
                    </select>
                </label>
            </div>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                Guardar
            </button>
        </form>
    </div>

    <div class="max-w-md mx-auto bg-white rounded-xl shadow-lg border border-purple-200 p-6 mb-8">
        <h3 class="text-xl font-semibold text-purple-700 mb-4 text-center">Importar productos desde Excel</h3>
        <form  method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="file" name="archivo" accept=".xlsx,.xls,.csv" required class="border border-gray-300 rounded px-2 py-1 w-full" />
            <div class="flex justify-end">
                <button type="submit" class="bg-purple-600 hover:bg-purple-800 text-white rounded-lg px-4 py-2 shadow-md">
                    Importar Productos
                </button>
            </div>
        </form>
    </div>

    <div class="overflow-x-auto bg-white rounded-xl shadow-lg">
        <table class="w-full border-collapse text-sm">
            <thead>
                <tr class="bg-gray-100 text-gray-800 text-center">
                    <th class="p-4 font-semibold">ID</th>
                    <th class="p-4 font-semibold">Nombre</th>
                    <th class="p-4 font-semibold">Descripción</th>
                    <th class="p-4 font-semibold">Precio</th>
                    <th class="p-4 font-semibold">Stock</th>
                    <th class="p-4 font-semibold">Imagen</th>
                    <th class="p-4 font-semibold">Descuento</th>
                    <th class="p-4 font-semibold">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productos as $p)
                <tr class="hover:bg-gray-50 transition">
                    <td class="border-b p-4 text-center">{{ $p->id }}</td>
                    <td class="border-b p-4 text-center">{{ $p->nombre }}</td>
                    <td class="border-b p-4 text-center">{{ $p->descripcion }}</td>
                    <td class="border-b p-4 text-center">${{ $p->precio }}</td>
                    <td class="border-b p-4 text-center">{{ $p->stock }}</td>
                    <td class="border-b p-4 text-center">{{ optional($p->imagen)->nombre ?? '—' }}</td>
                    <td class="border-b p-4 text-center">{{ $p->descuento?->porcentaje ?? 'N/A' }}</td>
                    <td class="border-b p-4 flex justify-center items-center gap-2">
                        <button onclick="abrirModal({{ $p->id }}, '{{ $p->nombre }}', '{{ $p->descripcion }}', {{ $p->precio }}, {{ $p->stock }}, {{ $p->imagen_id ?? 'null' }}, {{ $p->descuento_id ?? 'null' }})" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">Editar</button>
                        <form method="POST" action="{{ route('productos.destroy', $p->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded" onclick="return confirm('¿Eliminar?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div id="modalEditar" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5);">
        <div style="background:white; padding:20px; margin:50px auto; width:500px; border-radius:10px; position:relative;">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 text-center">Editar Producto</h3>
            <form id="formEditar" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="producto_id" id="producto_id">
                <div>
                    <label class="block text-gray-700">Nombre
                        <input type="text" name="nombre" id="editNombre" class="border border-gray-300 rounded px-2 py-1 w-full">
                    </label>
                </div>
                <div>
                    <label class="block text-gray-700">Descripción
                        <textarea name="descripcion" id="editDescripcion" class="border border-gray-300 rounded px-2 py-1 w-full"></textarea>
                    </label>
                </div>
                <div>
                    <label class="block text-gray-700">Precio
                        <input type="number" step="0.01" name="precio" id="editPrecio" class="border border-gray-300 rounded px-2 py-1 w-full">
                    </label>
                </div>
                <div>
                    <label class="block text-gray-700">Stock
                        <input type="number" name="stock" id="editStock" class="border border-gray-300 rounded px-2 py-1 w-full">
                    </label>
                </div>
                <div>
                    <label class="block text-gray-700">Imagen
                        <select name="imagen_id" id="editImagen" class="border border-gray-300 rounded px-2 py-1 w-full">
                            <option value="" disabled selected>Selecciona imagen…</option>
                            @foreach($imagenes as $img)
                                <option value="{{ $img->id }}">{{ $img->nombre ?? 'Imagen #'.$img->id }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>
                <div>
                    <label class="block text-gray-700">Descuento
                        <select name="descuento_id" id="editDescuento" class="border border-gray-300 rounded px-2 py-1 w-full">
                            <option value="" disabled selected>Selecciona descuento…</option>
                            @foreach($descuentos as $desc)
                                <option value="{{ $desc->id }}">
                                    @if(!is_null($desc->porcentaje)) {{ $desc->porcentaje }}% @else Descuento #{{ $desc->id }} @endif
                                </option>
                            @endforeach
                        </select>
                    </label>
                </div>
                <div class="mt-4 flex justify-end gap-2">
                    <button type="button" onclick="cerrarModal()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancelar</button>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Guardar</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function abrirModal(id, nombre, descripcion, precio, stock, imagen_id, descuento_id) {
    document.getElementById('modalEditar').style.display = 'block';
    document.getElementById('producto_id').value = id;
    document.getElementById('editNombre').value = nombre;
    document.getElementById('editDescripcion').value = descripcion;
    document.getElementById('editPrecio').value = precio;
    document.getElementById('editStock').value = stock;
    document.getElementById('editImagen').value = imagen_id || "";
    document.getElementById('editDescuento').value = descuento_id || "";

    document.getElementById('formEditar').action = `/productos/${id}`;
}

function cerrarModal() {
    document.getElementById('modalEditar').style.display = 'none';
}
</script>
</x-layouts.app>

<x-layouts.app :title="__('Gestión de Productos')">
    <div class="space-y-6 max-w-6xl mx-auto px-6 py-8">
        <div class="border-b border-gray-200 pb-3 dark:border-gray-700 text-center">
        <h1 class="text-2xl font-semibold text-text">Gestión de Productos</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Administración de los productos registrados en el sistema.</p>
    </div>
    <div class="flex items-start justify-between">
        <div class="w-fit bg-surface rounded-md border border-gray-200 dark:border-gray-700 p-4">
            <form method="GET" action="{{ route('productos-admin.index') }}" class="flex flex-wrap items-end gap-3 md:gap-4">
                <div>
                    <label class="block text-xs font-medium text-black dark:text-gray-300 mb-1">Buscar por nombre</label>
                    <input type="text" name="nombre" value="{{ request('nombre') }}" placeholder="Ej: Lápiz" class="w-64 rounded-md border border-gray-300 px-2 py-1 text-sm focus:border-brand focus:ring-1 focus:ring-brand dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                </div>
                <div class="flex gap-2 pt-5">
                    <button type="submit" class="rounded-md border border-gray-300 px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 cursor-pointer">Buscar</button>
                    <a href="{{ route('productos-admin.index') }}" class="rounded-md border border-gray-300 px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">Limpiar</a>
                </div>
            </form>
        </div>
        <div class="flex gap-3 items-start">
            <button onclick="abrirModalCrear()" class="inline-flex items-center gap-1 rounded-md border border-brand px-3 py-1.5 text-sm font-medium text-brand hover:bg-brand hover:text-white transition-colors cursor-pointer">+ Agregar Producto</button>
            <button onclick="abrirModalImportar()" class="inline-flex items-center gap-1 rounded-md border border-brand px-3 py-1.5 text-sm font-medium text-brand hover:bg-brand hover:text-white transition cursor-pointer">Importar productos desde Excel</button>
        </div>
    </div>
    <div class="overflow-x-auto rounded-md border border-gray-200 bg-surface dark:border-gray-700">
        <table class="min-w-full text-sm">
            <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-800/50">
                <tr>
                    <th class="px-4 py-2 text-left font-medium text-text">ID</th>
                    <th class="px-4 py-2 text-left font-medium text-text">Nombre</th>
                    <th class="px-4 py-2 text-left font-medium text-text">Descripción</th>
                    <th class="px-4 py-2 text-left font-medium text-text">Precio</th>
                    <th class="px-4 py-2 text-left font-medium text-text">Stock</th>
                    <th class="px-4 py-2 text-left font-medium text-text">Imagen</th>
                    <th class="px-4 py-2 text-left font-medium text-text">Descuento</th>
                    <th class="px-4 py-2 text-center font-medium text-text">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach ($productos as $p)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition">
                        <td class="px-4 py-2 text-sm text-text">{{ $p->id }}</td>
                        <td class="px-4 py-2 text-sm font-medium text-text">{{ $p->nombre }}</td>
                        <td class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400">{{ $p->descripcion ?: '— Sin descripción —' }}</td>
                        <td class="px-4 py-2 text-sm text-text">${{ $p->precio }}</td>
                        <td class="px-4 py-2 text-sm text-text">{{ $p->stock }}</td>
                        <td class="px-4 py-2 text-sm text-text">{{ optional($p->imagen)->nombre ?? '—' }}</td>
                        <td class="px-4 py-2 text-sm text-text">{{ $p->descuento?->porcentaje ?? 'N/A' }}</td>
                        <td class="px-4 py-2 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="abrirModalEditar({{ $p->id }}, '{{ $p->nombre }}', '{{ $p->descripcion }}', {{ $p->precio }}, {{ $p->stock }}, {{ $p->imagen_id ?? 'null' }}, {{ $p->descuento_id ?? 'null' }})" class="text-xs font-medium text-brand hover:underline cursor-pointer">Editar</button>
                                    <span class="text-gray-400">|</span>
                                    <form method="POST" action="{{ route('productos.destroy', $p->id) }}" onsubmit="return confirm('¿Eliminar este producto?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-medium text-danger hover:underline cursor-pointer">Eliminar</button>
                                    </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div id="modalCrear" style="display:none" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-surface rounded-md border border-gray-200 dark:border-gray-700 w-full max-w-lg p-6">
            <h3 class="text-lg font-semibold text-text mb-4 text-center">Agregar Producto</h3>
            <form method="POST" action="{{ route('productos.store') }}" class="space-y-4" id="formCrearProducto">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Nombre</label>
                    <input type="text" name="nombre" class="w-full rounded-md border border-gray-300 px-2 py-1 text-sm focus:border-brand focus:ring-1 focus:ring-brand dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Descripción</label>
                    <textarea name="descripcion" class="w-full rounded-md border border-gray-300 px-2 py-1 text-sm focus:border-brand focus:ring-1 focus:ring-brand dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"></textarea>
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Precio</label>
                        <input type="number" step="0.01" name="precio" class="w-full rounded-md border border-gray-300 px-2 py-1 text-sm focus:border-brand focus:ring-1 focus:ring-brand dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Stock</label>
                        <input type="number" name="stock" class="w-full rounded-md border border-gray-300 px-2 py-1 text-sm focus:border-brand focus:ring-1 focus:ring-brand dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Imagen</label>
                    <select name="imagen_id" class="w-full rounded-md border border-gray-300 px-2 py-1 text-sm focus:border-brand focus:ring-1 focus:ring-brand dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                        <option value="" disabled selected>Selecciona imagen…</option>
                        @foreach ($imagenes as $img)
                            <option value="{{ $img->id }}">{{ $img->nombre ?? 'Imagen #'.$img->id }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Descuento</label>
                    <select name="descuento_id" class="w-full rounded-md border border-gray-300 px-2 py-1 text-sm focus:border-brand focus:ring-1 focus:ring-brand dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                        <option value="" disabled selected>Selecciona descuento…</option>
                        @foreach ($descuentos as $desc)
                            <option value="{{ $desc->id }}">
                                @if(!is_null($desc->porcentaje)) {{ $desc->porcentaje }}%
                                @else Descuento #{{ $desc->id }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="cerrarModalCrear()" class="rounded-md border border-gray-300 px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 cursor-pointer" id="btnCancelarCrear">Cancelar</button>
                    <button type="submit" class="rounded-md bg-brand px-3 py-1.5 text-sm font-medium text-white hover:brightness-110 cursor-pointer flex items-center gap-2" id="btnGuardarCrear">
                        <span id="textoGuardar">Guardar</span>
                        <span id="loaderGuardar" class="hidden animate-spin border-2 border-white border-t-transparent rounded-full w-4 h-4"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div id="modalImportar" style="display:none" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-surface rounded-md border border-gray-200 dark:border-gray-700 w-full max-w-md p-6">
            <h3 class="text-lg font-semibold text-brand mb-4 text-center">Importar productos desde Excel</h3>
            <form action="{{ route('productos.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="formImportarProductos">
                @csrf
                <input type="file" name="archivo" accept=".xlsx,.xls,.csv" required class="w-full text-sm file:mr-3 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-white file:bg-blue-800 hover:file:bg-blue-900 bg-blue-100 text-blue-900 rounded-md cursor-pointer focus:outline-none focus:ring-1 focus:ring-blue-800"/>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="cerrarModalImportar()" class="rounded-md border border-gray-300 px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 cursor-pointer" id="btnCancelarImportar">Cancelar</button>
                    <button type="submit" class="rounded-md bg-brand px-3 py-1.5 text-sm font-medium text-white hover:brightness-110 flex items-center gap-2 cursor-pointer" id="btnImportar">
                        <span id="textoImportar">Importar</span>
                        <span id="loaderImportar" class="hidden animate-spin border-2 border-white border-t-transparent rounded-full w-4 h-4"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div id="modalEditar" style="display:none" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-surface rounded-md border border-gray-200 dark:border-gray-700 w-full max-w-lg p-6">
            <h3 class="text-lg font-semibold text-text mb-4 text-center">Editar Producto</h3>
            <form id="formEditar" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="producto_id" id="producto_id">
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Nombre</label>
                    <input type="text" name="nombre" id="editNombre" class="w-full rounded-md border border-gray-300 px-2 py-1 text-sm focus:border-brand focus:ring-1 focus:ring-brand dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Descripción</label>
                    <textarea name="descripcion" id="editDescripcion" class="w-full rounded-md border border-gray-300 px-2 py-1 text-sm focus:border-brand focus:ring-1 focus:ring-brand dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"></textarea>
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Precio</label>
                        <input type="number" step="0.01" name="precio" id="editPrecio" class="w-full rounded-md border border-gray-300 px-2 py-1 text-sm focus:border-brand focus:ring-1 focus:ring-brand dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Stock</label>
                        <input type="number" name="stock" id="editStock" class="w-full rounded-md border border-gray-300 px-2 py-1 text-sm focus:border-brand focus:ring-1 focus:ring-brand dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Imagen</label>
                    <select name="imagen_id" id="editImagen" class="w-full rounded-md border border-gray-300 px-2 py-1 text-sm focus:border-brand focus:ring-1 focus:ring-brand dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                        <option value="" disabled selected>Selecciona imagen…</option>
                        @foreach($imagenes as $img)
                            <option value="{{ $img->id }}">{{ $img->nombre ?? 'Imagen #'.$img->id }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Descuento</label>
                    <select name="descuento_id" id="editDescuento" class="w-full rounded-md border border-gray-300 px-2 py-1 text-sm focus:border-brand focus:ring-1 focus:ring-brand dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                        <option value="" disabled selected>Selecciona descuento…</option>
                        @foreach($descuentos as $desc)
                            <option value="{{ $desc->id }}">
                                @if(!is_null($desc->porcentaje)) {{ $desc->porcentaje }}%
                                @else Descuento #{{ $desc->id }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="cerrarModalEditar()" id="btnCancelarEditar" class="cursor-pointer rounded-md border border-gray-300 px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">Cancelar</button>
                    <button type="submit" id="btnGuardarEditar" class="cursor-pointer rounded-md bg-brand px-3 py-1.5 text-sm font-medium text-white hover:brightness-110 flex items-center gap-2">
                        <span id="textoGuardarEditar">Guardar</span>
                        <span id="loaderEditar" class="hidden animate-spin border-2 border-white border-t-transparent rounded-full w-4 h-4"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function abrirModalCrear() { document.getElementById('modalCrear').style.display = 'flex'; }
        function cerrarModalCrear() { document.getElementById('modalCrear').style.display = 'none'; }
        function abrirModalImportar() { document.getElementById('modalImportar').style.display = 'flex'; }
        function cerrarModalImportar() { document.getElementById('modalImportar').style.display = 'none'; }
        function abrirModalEditar(id, nombre, descripcion, precio, stock, imagen_id, descuento_id) {
            document.getElementById('modalEditar').style.display = 'flex';
            document.getElementById('producto_id').value = id;
            document.getElementById('editNombre').value = nombre;
            document.getElementById('editDescripcion').value = descripcion;
            document.getElementById('editPrecio').value = precio;
            document.getElementById('editStock').value = stock;
            document.getElementById('editImagen').value = imagen_id || "";
            document.getElementById('editDescuento').value = descuento_id || "";

            document.getElementById('formEditar').action = `/productos/${id}`;
        }
        function cerrarModalEditar() {
            document.getElementById('modalEditar').style.display = 'none';
        }
        document.getElementById('formCrearProducto').addEventListener('submit', function(e) {
            const btnGuardar = document.getElementById('btnGuardarCrear');
            const btnCancelar = document.getElementById('btnCancelarCrear');
            const texto = document.getElementById('textoGuardar');
            const loader = document.getElementById('loaderGuardar');
            btnGuardar.disabled = true;
            btnCancelar.disabled = true;
            texto.textContent = "Guardando...";
            loader.classList.remove('hidden');
        });
        document.getElementById('formImportarProductos').addEventListener('submit', function(e) {
            const btnImportar = document.getElementById('btnImportar');
            const btnCancelar = document.getElementById('btnCancelarImportar');
            const texto = document.getElementById('textoImportar');
            const loader = document.getElementById('loaderImportar');
            btnImportar.disabled = true;
            btnCancelar.disabled = true;
            texto.textContent = "Importando...";
            loader.classList.remove('hidden');
        });
        document.getElementById('formEditar').addEventListener('submit', function() {
            const btnGuardar = document.getElementById('btnGuardarEditar');
            const btnCancelar = document.getElementById('btnCancelarEditar');
            const texto = document.getElementById('textoGuardarEditar');
            const loader = document.getElementById('loaderEditar');

            btnGuardar.disabled = true;
            btnCancelar.disabled = true;
            texto.textContent = "Guardando...";
            loader.classList.remove('hidden');
        });
        </script>
</x-layouts.app>
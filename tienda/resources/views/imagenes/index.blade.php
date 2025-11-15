<x-layouts.app :title="__('Gestión de Imágenes')">
    <div class="space-y-6 max-w-6xl mx-auto px-6 py-8">
        <div class="border-b border-gray-200 pb-3 dark:border-gray-700 text-center">
            <h1 class="text-2xl font-semibold text-text">Gestión de Imágenes</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Administración de las imágenes registradas en el sistema.</p>
        </div>
        
        @include('partials.flash')
        
        <div class="flex justify-end">
            <a href="{{ route('productos-admin.index') }}" class="inline-flex items-center gap-1 rounded-md border border-gray-300 px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                ← Volver a Productos
            </a>
        </div>
        
        <div class="overflow-x-auto rounded-md border border-gray-200 bg-surface dark:border-gray-700">
            <table class="min-w-full text-sm">
                <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-4 py-2 text-left font-medium text-text">ID</th>
                        <th class="px-4 py-2 text-left font-medium text-text">Nombre</th>
                        <th class="px-4 py-2 text-left font-medium text-text">URL</th>
                        <th class="px-4 py-2 text-left font-medium text-text">Vista Previa</th>
                        <th class="px-4 py-2 text-center font-medium text-text">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($imagenes as $imagen)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition">
                            <td class="px-4 py-2 text-sm text-text">{{ $imagen->id }}</td>
                            <td class="px-4 py-2 text-sm font-medium text-text">{{ $imagen->nombre }}</td>
                            <td class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400">
                                <a href="{{ $imagen->imagen_url }}" target="_blank" class="text-brand hover:underline">
                                    {{ Str::limit($imagen->imagen_url, 50) }}
                                </a>
                            </td>
                            <td class="px-4 py-2">
                                <img src="{{ $imagen->imagen_url }}" alt="{{ $imagen->nombre }}" class="h-12 w-12 object-cover rounded">
                            </td>
                            <td class="px-4 py-2 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="abrirModalEditar({{ $imagen->id }}, '{{ $imagen->nombre }}', '{{ $imagen->imagen_url }}')" class="text-xs font-medium text-brand hover:underline cursor-pointer">
                                        Editar
                                    </button>
                                    <span class="text-gray-400">|</span>
                                    <form method="POST" action="{{ route('imagenes.destroy', $imagen->id) }}" onsubmit="return confirm('¿Eliminar esta imagen?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-medium text-danger hover:underline cursor-pointer">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                No hay imágenes registradas
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Editar -->
    <div id="modalEditar" style="display:none" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-surface rounded-md border border-gray-200 dark:border-gray-700 w-full max-w-lg p-6">
            <h3 class="text-lg font-semibold text-text mb-4 text-center">Editar Imagen</h3>
            <form id="formEditar" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="imagen_id" id="imagen_id">
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Nombre</label>
                    <input type="text" name="nombre" id="editNombre" required class="w-full rounded-md border border-gray-300 px-2 py-1 text-sm focus:border-brand focus:ring-1 focus:ring-brand dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">URL de la Imagen</label>
                    <input type="url" name="imagen_url" id="editUrl" required class="w-full rounded-md border border-gray-300 px-2 py-1 text-sm focus:border-brand focus:ring-1 focus:ring-brand dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="cerrarModalEditar()" id="btnCancelar" class="cursor-pointer rounded-md border border-gray-300 px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                        Cancelar
                    </button>
                    <button type="submit" id="btnGuardar" class="cursor-pointer rounded-md bg-brand px-3 py-1.5 text-sm font-medium text-white hover:brightness-110 flex items-center gap-2">
                        <span id="textoGuardar">Guardar</span>
                        <span id="loader" class="hidden animate-spin border-2 border-white border-t-transparent rounded-full w-4 h-4"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function abrirModalEditar(id, nombre, url) {
            document.getElementById('modalEditar').style.display = 'flex';
            document.getElementById('imagen_id').value = id;
            document.getElementById('editNombre').value = nombre;
            document.getElementById('editUrl').value = url;
            document.getElementById('formEditar').action = `/imagenes/${id}`;
        }
        
        function cerrarModalEditar() {
            document.getElementById('modalEditar').style.display = 'none';
        }

        document.getElementById('formEditar').addEventListener('submit', function() {
            const btnGuardar = document.getElementById('btnGuardar');
            const btnCancelar = document.getElementById('btnCancelar');
            const texto = document.getElementById('textoGuardar');
            const loader = document.getElementById('loader');

            btnGuardar.disabled = true;
            btnCancelar.disabled = true;
            texto.textContent = "Guardando...";
            loader.classList.remove('hidden');
        });
    </script>
</x-layouts.app>

<div class="space-y-6 bg-white text-gray-900">

    <h1 class="text-2xl font-bold">CRUD de Descuentos (Admin)</h1>

    @if (session()->has('status'))
        <div class="p-3 rounded border border-green-400 bg-green-50 text-sm">
            {{ session('status') }}
        </div>
    @endif

    {{-- FORMULARIO --}}
    <div class="p-4 border rounded bg-white shadow-sm">
        <h2 class="text-xl font-semibold mb-4">
            {{ $modo === 'crear' ? 'Crear descuento' : 'Editar descuento' }}
        </h2>

        <form wire:submit.prevent="guardar" class="space-y-4">

            {{-- DATOS DEL DESCUENTO --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium">Porcentaje (%)</label>
                    <input
                        type="number"
                        step="0.01"
                        wire:model="porcentaje"
                        class="w-full border rounded px-2 py-1"
                    >
                    @error('porcentaje')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium">Fecha inicio</label>
                    <input
                        type="date"
                        wire:model="fecha_inicio"
                        class="w-full border rounded px-2 py-1"
                        min="{{ now()->toDateString() }}"
                    >
                    @error('fecha_inicio')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium">Fecha fin</label>
                    <input
                        type="date"
                        wire:model="fecha_fin"
                        class="w-full border rounded px-2 py-1"
                        min="{{ $fecha_inicio ?: now()->toDateString() }}"
                    >
                    @error('fecha_fin')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- PRODUCTOS --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Productos a los que aplica el descuento
                </label>

                <select
                    wire:model="productos_seleccionados"
                    multiple
                    class="w-full border rounded px-2 py-1 h-40"
                >
                    @foreach ($productos as $producto)
                        <option value="{{ $producto->id }}">
                            {{ $producto->nombre }}
                            @if ($producto->descuento)
                                (ya tiene {{ $producto->descuento->porcentaje }}%)
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- CORREOS --}}
            <div class="border-t pt-4 mt-4 space-y-3">

                {{-- Checkbox para activar envío --}}
                <div class="flex items-center space-x-2">
                    <input
                        type="checkbox"
                        id="enviar_correo"
                        wire:model="enviar_correo"
                    >
                    <label for="enviar_correo" class="text-sm font-semibold">
                        Enviar correo a clientes sobre este descuento
                    </label>
                </div>

                {{-- Selector de destino SIEMPRE visible --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Destino</label>
                        <select
                            wire:model="destino"
                            class="w-full border rounded px-2 py-1"
                        >
                            <option value="ninguno">No enviar</option>
                            <option value="uno">Un solo cliente</option>
                            <option value="todos">Todos los clientes</option>
                        </select>
                    </div>

                    {{-- Si es UNO, mostramos el select de usuarios --}}
                    @if ($destino === 'uno')
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium">
                                Selecciona cliente
                            </label>
                            <select
                                wire:model="usuario_unico_id"
                                class="w-full border rounded px-2 py-1"
                            >
                                <option value="">Seleccione...</option>
                                @foreach ($usuarios as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('usuario_unico_id')
                                <span class="text-red-600 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    {{-- Si es TODOS, solo mostramos un texto informativo --}}
                    @if ($destino === 'todos')
                        <div class="md:col-span-2 text-sm text-gray-600">
                            Se enviará este descuento a
                            <strong>todos</strong>
                            los clientes registrados ({{ $usuarios->count() }}).
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex space-x-2 mt-4">
                <button
                    type="submit"
                    class="px-4 py-2 rounded text-white bg-blue-600 hover:bg-blue-700 text-sm"
                >
                    {{ $modo === 'crear' ? 'Guardar descuento' : 'Actualizar descuento' }}
                </button>

                <button
                    type="button"
                    wire:click="limpiarFormulario"
                    class="px-4 py-2 rounded border text-sm"
                >
                    Cancelar
                </button>
            </div>

        </form>
    </div>

    {{-- TABLA DE DESCUENTOS --}}
    <div class="p-4 border rounded bg-white shadow-sm">
        <h2 class="text-xl font-semibold mb-4">Listado de descuentos</h2>

        <table class="w-full text-sm">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-2 px-2">ID</th>
                    <th class="text-left px-2">Porcentaje</th>
                    <th class="text-left px-2">Vigencia</th>
                    <th class="text-left px-2">Productos</th>
                    <th class="text-left px-2">Activo hoy</th>
                    <th class="text-left px-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($descuentos as $descuento)
                    <tr class="border-b">
                        <td class="py-2 px-2">{{ $descuento->id }}</td>
                        <td class="px-2">{{ $descuento->porcentaje }}%</td>
                        <td class="px-2">
                            {{ $descuento->fecha_inicio }} –
                            {{ $descuento->fecha_fin }}
                        </td>
                        <td class="px-2">
                            @if ($descuento->productos->count())
                                <ul class="list-disc list-inside text-xs">
                                    @foreach ($descuento->productos as $p)
                                        <li>{{ $p->nombre }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-gray-500 text-xs">
                                    Sin productos asignados
                                </span>
                            @endif
                        </td>
                        <td class="px-2">
                            @if ($descuento->estaActivo())
                                <span class="text-green-600 font-semibold text-xs">
                                    Activo
                                </span>
                            @else
                                <span class="text-red-600 font-semibold text-xs">
                                    Inactivo
                                </span>
                            @endif
                        </td>
                        <td class="px-2 space-x-2">
                            <button
                                wire:click="editar({{ $descuento->id }})"
                                class="text-blue-600 text-xs"
                            >
                                Editar
                            </button>
                            <button
                                wire:click="eliminar({{ $descuento->id }})"
                                onclick="return confirm('¿Seguro que deseas eliminar este descuento?')"
                                class="text-red-600 text-xs"
                            >
                                Eliminar
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td
                            colspan="6"
                            class="py-3 text-center text-gray-500 text-sm"
                        >
                            No hay descuentos registrados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $descuentos->links() }}
        </div>
    </div>

</div>

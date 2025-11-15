<div class="space-y-6 max-w-6xl mx-auto">

    <h1 class="text-2xl font-bold text-brand">
        CRUD de Descuentos (Admin)
    </h1>

    @if (session()->has('status'))
        <div class="p-3 rounded border border-success/40 bg-success/10 text-sm text-success">
            {{ session('status') }}
        </div>
    @endif

    {{-- FORMULARIO --}}
    <div class="p-4 border border-zinc-200 rounded-xl bg-surface shadow-sm">
        <h2 class="text-xl font-semibold mb-4 text-text">
            {{ $modo === 'crear' ? 'Crear descuento' : 'Editar descuento' }}
        </h2>

        <form wire:submit.prevent="guardar" class="space-y-6">

            {{-- DATOS DEL DESCUENTO --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-zinc-600">
                        Porcentaje (%)
                    </label>
                    <input
                        type="number"
                        step="0.01"
                        wire:model="porcentaje"
                        class="mt-1 w-full border border-zinc-300 rounded-md px-3 py-2 text-sm bg-surface focus:outline-none focus:ring-2 focus:ring-brand/70"
                    >
                    @error('porcentaje')
                        <span class="text-danger text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-zinc-600">
                        Fecha inicio
                    </label>
                    <input
                        type="date"
                        wire:model="fecha_inicio"
                        class="mt-1 w-full border border-zinc-300 rounded-md px-3 py-2 text-sm bg-surface focus:outline-none focus:ring-2 focus:ring-brand/70"
                        min="{{ now()->toDateString() }}"
                    >
                    @error('fecha_inicio')
                        <span class="text-danger text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-zinc-600">
                        Fecha fin
                    </label>
                    <input
                        type="date"
                        wire:model="fecha_fin"
                        class="mt-1 w-full border border-zinc-300 rounded-md px-3 py-2 text-sm bg-surface focus:outline-none focus:ring-2 focus:ring-brand/70"
                        min="{{ $fecha_inicio ?: now()->toDateString() }}"
                    >
                    @error('fecha_fin')
                        <span class="text-danger text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- PRODUCTOS --}}
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide text-zinc-600 mb-1">
                    Productos a los que aplica el descuento
                </label>

                <select
                    wire:model="productos_seleccionados"
                    multiple
                    class="w-full border border-zinc-300 rounded-md px-3 py-2 text-sm bg-surface h-40 focus:outline-none focus:ring-2 focus:ring-brand/70"
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
                <p class="text-xs text-zinc-500 mt-1">
                    Usa Ctrl+click (o Cmd+click) para seleccionar varios.
                </p>
            </div>

            {{-- CORREOS --}}
            <div class="border-t border-zinc-200 pt-4 mt-4 space-y-3">

                {{-- Checkbox para activar envío --}}
                <div class="flex items-center space-x-2">
                    <input
                        type="checkbox"
                        id="enviar_correo"
                        wire:model="enviar_correo"
                        class="rounded border-zinc-300 text-brand focus:ring-brand/80"
                    >
                    <label for="enviar_correo" class="text-sm font-semibold text-text">
                        Enviar correo a clientes sobre este descuento
                    </label>
                </div>

                {{-- Selector de destino SIEMPRE visible --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div @class([
                        'opacity-40 pointer-events-none' => !$enviar_correo,
                    ])>
                        <label class="block text-xs font-semibold uppercase tracking-wide text-zinc-600">
                            Destino
                        </label>
                        <select
                            wire:model="destino"
                            class="mt-1 w-full border border-zinc-300 rounded-md px-3 py-2 text-sm bg-surface focus:outline-none focus:ring-2 focus:ring-brand/70"
                            @disabled(!$enviar_correo)
                        >
                            <option value="ninguno">No enviar</option>
                            <option value="uno">Un solo cliente</option>
                            <option value="todos">Todos los clientes</option>
                        </select>
                    </div>

                    {{-- Si es UNO, mostramos el select de usuarios --}}
                    @if ($destino === 'uno')
                        <div class="md:col-span-2" @class([
                            'opacity-40 pointer-events-none' => !$enviar_correo,
                        ])>
                            <label class="block text-xs font-semibold uppercase tracking-wide text-zinc-600">
                                Selecciona cliente
                            </label>
                            <select
                                wire:model="usuario_unico_id"
                                class="mt-1 w-full border border-zinc-300 rounded-md px-3 py-2 text-sm bg-surface focus:outline-none focus:ring-2 focus:ring-brand/70"
                                @disabled(!$enviar_correo)
                            >
                                <option value="">Seleccione...</option>
                                @foreach ($usuarios as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('usuario_unico_id')
                                <span class="text-danger text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    {{-- Si es TODOS, solo mostramos un texto informativo --}}
                    @if ($destino === 'todos')
                        <div class="md:col-span-2 text-sm text-zinc-600" @class([
                            'opacity-40' => !$enviar_correo,
                        ])>
                            Se enviará este descuento a
                            <strong class="text-brand">todos</strong>
                            los clientes registrados ({{ $usuarios->count() }}).
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex space-x-2 mt-4">
                <button
                    type="submit"
                    class="px-4 py-2 rounded-md text-sm font-semibold text-white bg-brand hover:bg-brand/90 transition-colors"
                >
                    {{ $modo === 'crear' ? 'Guardar descuento' : 'Actualizar descuento' }}
                </button>

                <button
                    type="button"
                    wire:click="limpiarFormulario"
                    class="px-4 py-2 rounded-md border border-zinc-300 text-sm text-zinc-700 hover:bg-zinc-100 transition-colors"
                >
                    Cancelar
                </button>
            </div>

        </form>
    </div>

    {{-- TABLA DE DESCUENTOS --}}
    <div class="p-4 border border-zinc-200 rounded-xl bg-surface shadow-sm">
        <h2 class="text-xl font-semibold mb-4 text-text">Listado de descuentos</h2>

        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-zinc-200 bg-zinc-50">
                    <th class="text-left py-2 px-2 text-xs font-semibold text-zinc-600 uppercase">ID</th>
                    <th class="text-left px-2 text-xs font-semibold text-zinc-600 uppercase">Porcentaje</th>
                    <th class="text-left px-2 text-xs font-semibold text-zinc-600 uppercase">Vigencia</th>
                    <th class="text-left px-2 text-xs font-semibold text-zinc-600 uppercase">Productos</th>
                    <th class="text-left px-2 text-xs font-semibold text-zinc-600 uppercase">Activo hoy</th>
                    <th class="text-left px-2 text-xs font-semibold text-zinc-600 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($descuentos as $descuento)
                    <tr class="border-b border-zinc-100 hover:bg-zinc-50/70">
                        <td class="py-2 px-2 text-zinc-700">{{ $descuento->id }}</td>
                        <td class="px-2 text-zinc-700">{{ $descuento->porcentaje }}%</td>
                        <td class="px-2 text-zinc-700">
                            {{ $descuento->fecha_inicio }} –
                            {{ $descuento->fecha_fin }}
                        </td>
                        <td class="px-2">
                            @if ($descuento->productos->count())
                                <ul class="list-disc list-inside text-xs text-zinc-700">
                                    @foreach ($descuento->productos as $p)
                                        <li>{{ $p->nombre }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-zinc-500 text-xs">
                                    Sin productos asignados
                                </span>
                            @endif
                        </td>
                        <td class="px-2">
                            @if ($descuento->estaActivo())
                                <span class="text-success font-semibold text-xs">
                                    Activo
                                </span>
                            @else
                                <span class="text-danger font-semibold text-xs">
                                    Inactivo
                                </span>
                            @endif
                        </td>
                        <td class="px-2 space-x-2">
                            <button
                                wire:click="editar({{ $descuento->id }})"
                                class="text-brand text-xs font-semibold hover:underline"
                            >
                                Editar
                            </button>
                            <button
                                wire:click="eliminar({{ $descuento->id }})"
                                onclick="return confirm('¿Seguro que deseas eliminar este descuento?')"
                                class="text-danger text-xs font-semibold hover:underline"
                            >
                                Eliminar
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td
                            colspan="6"
                            class="py-3 text-center text-zinc-500 text-sm"
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

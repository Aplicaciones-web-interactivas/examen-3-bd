<x-layouts.app :title="__('Gestión de Usuarios')">
    <div class="space-y-4">
        {{--Encabezado simple--}}
        <div class="flex items-center justify-between border-b border-gray-200 pb-3 dark:border-gray-700">
            <div>
                <h1 class="text-2xl font-semibold text-text">
                    Gestión de Usuarios
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Lista de usuarios registrados en el sistema.
                </p>
            </div>

            {{--Botón crear--}}
            <a
                href="{{ route('usuarios.create') }}"
                class="inline-flex items-center gap-1 rounded-md border border-brand px-3 py-1.5 text-sm font-medium text-brand hover:bg-brand hover:text-white transition-colors"
            >
                + Nuevo
            </a>
        </div>

        {{--Mensaje de estado--}}
        @if (session('success'))
            <div class="rounded-md border-l-4 border-success bg-surface px-3 py-2 text-sm text-success">
                {{ session('success') }}
            </div>
        @endif

        {{--Contenedor tabla--}}
        <div class="overflow-x-auto">
            @if ($usuarios->count() > 0)
                <table class="min-w-full text-sm">
                    <thead class="border-b border-gray-200 bg-surface dark:border-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left font-medium text-text">Nombre</th>
                            <th class="px-4 py-2 text-left font-medium text-text">Apellido</th>
                            <th class="px-4 py-2 text-left font-medium text-text">Correo</th>
                            <th class="px-4 py-2 text-left font-medium text-text">Rol</th>
                            <th class="px-4 py-2 text-left font-medium text-text">Registro</th>
                            <th class="px-4 py-2 text-center font-medium text-text">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($usuarios as $usuario)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40">
                                <td class="px-4 py-2">
                                    <div class="flex items-center gap-2">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-brand text-xs font-semibold text-white">
                                            {{ $usuario->initials() }}
                                        </div>
                                        <span class="text-sm font-medium text-text">
                                            {{ $usuario->name }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-2 text-sm text-text">
                                    {{ $usuario->apellido ?? '—' }}
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $usuario->email }}
                                </td>
                                <td class="px-4 py-2 text-sm">
                                    @if ($usuario->rol === 'admin')
                                        <span class="rounded-full bg-accent/10 px-2.5 py-0.5 text-xs font-medium text-accent">
                                            Admin
                                        </span>
                                    @else
                                        <span class="rounded-full bg-info/10 px-2.5 py-0.5 text-xs font-medium text-info">
                                            Cliente
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-xs text-gray-600 dark:text-gray-400">
                                    {{ $usuario->created_at->format('d M Y') }}
                                </td>
                                <td class="px-4 py-2">
                                    <!--Boton de Editar-->
                                    <div class="flex items-center justify-center gap-2">
                                        <a
                                            href="{{ route('usuarios.edit', $usuario->id) }}"
                                            class="text-xs font-medium text-brand hover:underline"
                                        >
                                            Editar
                                        </a>

                                        {{--Separador simple--}}
                                        <span class="text-gray-400">|</span>

                                        <form
                                            method="POST"
                                            action="{{ route('usuarios.destroy', $usuario->id) }}"
                                            class="inline"
                                            onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?');"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <!--Boton de Editar-->
                                            <button
                                                type="submit"
                                                class="text-xs font-medium text-danger hover:underline"
                                            >
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                {{--Estado vacío simple--}}
                <div class="rounded-md border border-dashed border-gray-300 px-4 py-10 text-center dark:border-gray-700">
                    <p class="text-sm font-medium text-text">
                        No hay usuarios registrados.
                    </p>
                    <p class="mt-1 text-xs text-gray-600 dark:text-gray-400">
                        Crea el primer usuario para comenzar a gestionar el sistema.
                    </p>
                    <a
                        href="{{ route('usuarios.create') }}"
                        class="mt-4 inline-flex items-center justify-center rounded-md bg-brand px-3 py-1.5 text-sm font-medium text-white hover:brightness-110"
                    >
                        Crear usuario
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>

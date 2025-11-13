<x-layouts.app :title="__('Gestión de Usuarios')">
    <div class="space-y-6">
        
        <!-- Encabezado con título y botón de crear -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-text">Gestión de Usuarios</h1>
                <p class="mt-1 text-gray-600 dark:text-gray-400">Administra todos los usuarios del sistema</p>
            </div>
            <!-- Botón de crear nuevo usuario --> 
            <a href="{{ route('usuarios.create') }}" class="flex items-center gap-2 rounded-lg bg-brand px-4 py-2 font-semibold text-white transition-all duration-200 hover:shadow-lg hover:brightness-110">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nuevo Usuario
            </a>

        </div>

        <!-- Mensajes de estado -->
        @if (session('success'))
            <div class="rounded-lg border-l-4 border-success bg-green-50 p-4 dark:bg-green-900/20">
                <div class="flex items-center gap-3">
                    <svg class="size-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-success">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Tabla de usuarios -->
        <div class="overflow-hidden rounded-lg border border-gray-200 bg-surface shadow-sm dark:border-gray-700">
            @if ($usuarios->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <!-- Encabezado de tabla -->
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-text">
                                    Nombre
                                </th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-text">
                                    Apellido
                                </th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-text">
                                    Correo Electrónico
                                </th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-text">
                                    Rol
                                </th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-text">
                                    Registro
                                </th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-text">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <!-- Cuerpo de tabla -->
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($usuarios as $usuario)
                                <tr class="transition-colors duration-150 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-brand text-sm font-semibold text-white">
                                                {{ $usuario->initials() }}
                                            </div>
                                            <span class="font-medium text-text">{{ $usuario->name }}</span>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-text">
                                        {{ $usuario->apellido ?? '—' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $usuario->email }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        @if ($usuario->rol === 'admin')
                                            <span class="inline-flex items-center rounded-full bg-accent/10 px-3 py-1 text-sm font-semibold text-accent">
                                                Administrador
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-info/10 px-3 py-1 text-sm font-semibold text-info">
                                                Cliente
                                            </span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $usuario->created_at->format('d M Y') }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <!-- Botón Editar -->
                                            <a href="{{ route('usuarios.edit', $usuario->id) }}" class="inline-flex items-center justify-center rounded-md bg-brand/10 p-2 text-brand transition-all duration-200 hover:bg-brand hover:text-white">
                                                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            <!-- Botón Eliminar -->
                                            <form method="POST" action="{{ route('usuarios.destroy', $usuario->id) }}" class="inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center justify-center rounded-md bg-danger/10 p-2 text-danger transition-all duration-200 hover:bg-danger hover:text-white">
                                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <!-- Estado vacío -->
                <div class="flex flex-col items-center justify-center px-6 py-16">
                    <svg class="size-16 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.856-1.488M15 6a3 3 0 11-6 0 3 3 0 016 0zM6 20h12a6 6 0 00-6-6 6 6 0 00-6 6z"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-semibold text-text">No hay usuarios registrados</h3>
                    <p class="mt-1 text-gray-600 dark:text-gray-400">Comienza creando el primer usuario</p>
                    <a href="{{ route('usuarios.create') }}" class="mt-6 inline-flex items-center gap-2 rounded-lg bg-brand px-4 py-2 font-semibold text-white transition-all duration-200 hover:shadow-lg hover:brightness-110">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Crear Primer Usuario
                    </a>
                </div>
            @endif
        </div>

    </div>
</x-layouts.app>

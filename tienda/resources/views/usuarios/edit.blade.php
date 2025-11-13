<x-layouts.app :title="__('Editar Usuario')">
    <div class="space-y-6 max-w-2xl">
        <div class="flex items-center justify-between border-b border-gray-200 pb-3 dark:border-gray-700">
            <h1 class="text-2xl font-semibold text-text">Editar Usuario</h1>
            <a href="{{ route('usuarios.index') }}" class="text-sm text-brand hover:underline">Volver</a>
        </div>

        @include('partials.flash')

        <form method="POST" action="{{ route('usuarios.update', $usuario->id) }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="grid gap-4 md:grid-cols-2">
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-medium text-gray-600 dark:text-gray-300">Nombre *</label>
                    <input name="name" value="{{ old('name', $usuario->name) }}" required type="text" class="rounded-md border border-gray-300 px-2 py-1 text-sm focus:border-brand focus:ring-1 focus:ring-brand dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-medium text-gray-600 dark:text-gray-300">Apellido</label>
                    <input name="apellido" value="{{ old('apellido', $usuario->apellido) }}" type="text" class="rounded-md border border-gray-300 px-2 py-1 text-sm focus:border-brand focus:ring-1 focus:ring-brand dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
                </div>
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs font-medium text-gray-600 dark:text-gray-300">Correo *</label>
                <input name="email" value="{{ old('email', $usuario->email) }}" required type="email" class="rounded-md border border-gray-300 px-2 py-1 text-sm focus:border-brand focus:ring-1 focus:ring-brand dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs font-medium text-gray-600 dark:text-gray-300">Nueva Contraseña (opcional)</label>
                <input name="password" type="password" minlength="6" placeholder="Dejar vacío para mantener la actual" class="rounded-md border border-gray-300 px-2 py-1 text-sm focus:border-brand focus:ring-1 focus:ring-brand dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs font-medium text-gray-600 dark:text-gray-300">Rol *</label>
                <select name="rol" required class="rounded-md border border-gray-300 px-2 py-1 text-sm focus:border-brand focus:ring-1 focus:ring-brand dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                    <option value="admin" {{ old('rol', $usuario->rol) === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="cliente" {{ old('rol', $usuario->rol) === 'cliente' ? 'selected' : '' }}>Cliente</option>
                </select>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="rounded-md bg-brand px-4 py-2 text-sm font-medium text-white hover:brightness-110">Actualizar</button>
                <a href="{{ route('usuarios.index') }}" class="text-sm text-gray-600 hover:underline dark:text-gray-300">Cancelar</a>
            </div>
        </form>
    </div>
</x-layouts.app>
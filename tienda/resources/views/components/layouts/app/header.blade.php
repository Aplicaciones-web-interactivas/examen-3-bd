<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="flex min-h-screen flex-col bg-white dark:bg-white" style="--color-zinc-100: #f5f5f5;">
        @php
            $role = auth()->user()->rol ?? null;

            $commonNavigation = [
                [
                    'label' => 'Dashboard',
                    'route' => 'dashboard',
                    'active' => ['dashboard'],
                    'icon' => 'layout-grid',
                ],
            ];

            $adminNavigation = [
                [
                    'label' => 'CRUD de productos',
                    'route' => 'productos-admin.index',
                    'active' => ['productos-admin.*'],
                    'icon' => 'shopping-cart',
                ],
                [
                    'label' => 'CRUD de descuentos',
                    'route' => 'admin.descuentos',
                    'active' => ['admin.descuentos'],
                    'icon' => 'percent-badge',
                ],
                [
                    'label' => 'Finanzas',
                    'route' => 'admin.finanzas',
                    'active' => ['admin.finanzas'],
                    'icon' => 'banknotes',
                ],
                [
                    'label' => 'Inventario',
                    'route' => 'productos.index',
                    'active' => ['productos.index', 'productos.show'],
                    'icon' => 'archive-box',
                ],
                [
                    'label' => 'Clientes y usuarios',
                    'route' => 'usuarios.index',
                    'active' => ['usuarios.*'],
                    'icon' => 'users',
                ],
            ];

            $clientNavigation = [
                [
                    'label' => 'Carrito y tickets',
                    'route' => 'orders.index',
                    'active' => ['orders.*'],
                    'icon' => 'shopping-bag',
                ],
                [
                    'label' => 'Productos en descuento',
                    'route' => 'productos.descuento',
                    'active' => ['productos.descuento'],
                    'icon' => 'percent-badge',
                ],
                [
                    'label' => 'Todos los productos',
                    'route' => 'productos.catalogo',
                    'active' => ['productos.catalogo'],
                    'icon' => 'sparkles',
                ],
            ];

            $roleNavigation = [];

            if ($role === 'admin') {
                $roleNavigation = $adminNavigation;
            } elseif ($role === 'cliente') {
                $roleNavigation = $clientNavigation;
            }

            $navigation = array_merge($commonNavigation, $roleNavigation);
        @endphp
        <flux:header container class="border-b border-zinc-200 bg-red-600 dark:bg-red-600 py-8">
            <flux:sidebar.toggle class="lg:hidden size-12 text-[#f5f5f5]" icon="bars-2" inset="left" />

            <a href="{{ route('dashboard') }}" class="ms-2 me-5 flex items-center space-x-5 rtl:space-x-reverse lg:ms-0" wire:navigate>
                <img
                    src="{{ asset('images/buenfinsinfondo.png') }}"
                    alt="Logo"
                    class="h-16 w-auto"
                />
            </a>

            <flux:navbar class="-mb-px max-lg:hidden gap-8 text-white text-2xl font-semibold">
                @foreach ($navigation as $item)
                    @php
                        $patterns = (array) ($item['active'] ?? $item['route']);
                        $isCurrent = request()->routeIs(...$patterns);
                        $url = route($item['route']);
                    @endphp
                    <flux:navbar.item
                        class="!text-white text-2xl font-semibold flex items-center gap-3 [&_svg]:size-7"
                        icon="{{ $item['icon'] }}"
                        href="{{ $url }}"
                        :current="$isCurrent"
                        wire:navigate
                    >
                        {{ __($item['label']) }}
                    </flux:navbar.item>
                @endforeach
            </flux:navbar>

            <flux:spacer />

            <!-- Desktop User Menu -->
            <flux:dropdown
                position="top"
                align="end"
                class="text-red-600"
                style="--color-accent-content: var(--color-accent-light);"
            >
                <flux:profile
                    class="cursor-pointer text-2xl text-white"
                    :initials="auth()->user()->initials()"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold text-red-600">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs text-red-600">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item
                            :href="route('profile.edit')"
                            icon="cog"
                            wire:navigate
                            class="!text-red-600"
                        >
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full !text-red-600"
                        >
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>

        </flux:header>

        <!-- Mobile Menu -->
        <flux:sidebar stashable sticky class="lg:hidden border-e border-zinc-200 bg-white dark:border-zinc-200 dark:bg-white">
            <flux:sidebar.toggle class="lg:hidden size-12 text-white" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="ms-1 flex items-center space-x-3 rtl:space-x-reverse" wire:navigate>
                <img
                    src="{{ asset('images/buenfinsinfondo.png') }}"
                    alt="Logo"
                    class="h-16 w-auto"
                />
            </a>

            <flux:navlist variant="outline">
                @foreach ($navigation as $item)
                    @php
                        $patterns = (array) ($item['active'] ?? $item['route']);
                        $isCurrent = request()->routeIs(...$patterns);
                        $url = route($item['route']);
                    @endphp
                    <flux:navlist.item
                        class="!text-red-600 text-2xl font-semibold [&_svg]:size-7"
                        icon="{{ $item['icon'] }}"
                        href="{{ $url }}"
                        :current="$isCurrent"
                        wire:navigate
                    >
                        {{ __($item['label']) }}
                    </flux:navlist.item>
                @endforeach
            </flux:navlist>

            <flux:spacer />

            <flux:navlist variant="outline">
                <flux:navlist.item class="!text-red-600 text-2xl font-semibold [&_svg]:size-7" icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                    {{ __('Repository') }}
                </flux:navlist.item>

                <flux:navlist.item class="!text-red-600 text-2xl font-semibold [&_svg]:size-7" icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                    {{ __('Documentation') }}
                </flux:navlist.item>
            </flux:navlist>
        </flux:sidebar>

        <div class="flex-1 w-full">
            {{ $slot }}
        </div>

        <footer class="mt-12 w-full bg-zinc-900 text-white">
            <div class="mx-auto flex w-full max-w-6xl flex-col gap-4 px-6 py-8 text-sm md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-lg font-semibold">Tienda Buen Fin</p>
                    <p>Av. Insurgentes Sur 1234, Col. Roma Norte, CDMX</p>
                </div>
                <div class="space-y-1">
                    <p class="font-semibold uppercase tracking-wide text-zinc-300">Contacto</p>
                    <p>Teléfono: (55) 1234 5678</p>
                    <p>WhatsApp: +52 55 9876 5432</p>
                    <p>Correo: contacto@tiendabuenfin.mx</p>
                </div>
            </div>
        </footer>

        @fluxScripts
    </body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-white flex flex-col" style="--color-zinc-100: #f5f5f5;">
        <flux:header container class="border-b border-zinc-200 bg-red-600 dark:bg-red-600 py-8 sticky top-0 inset-x-0 z-50 w-full shadow-lg">
            <flux:sidebar.toggle class="lg:hidden size-12 text-[#f5f5f5]" icon="bars-2" inset="left" />

            <a href="{{ route('dashboard') }}" class="ms-2 me-5 flex items-center space-x-5 rtl:space-x-reverse lg:ms-0" wire:navigate>
                <img
                    src="{{ asset('images/buenfinsinfondo.png') }}"
                    alt="Logo"
                    class="h-16 w-auto"
                />
            </a>

            <flux:navbar class="-mb-px max-lg:hidden gap-8 text-white text-2xl font-semibold">
               
                @auth
                    @if(auth()->user()->rol === 'admin')
                        <flux:navbar.item
                            class="!text-white text-2xl font-semibold flex items-center gap-3 [&_svg]:size-7"
                            icon="shopping-cart"
                            :href="route('productos-admin.index')"
                            :current="request()->routeIs('productos-admin.*')"
                            wire:navigate
                        >
                            {{ __('Productos') }}
                        </flux:navbar.item>
                        <flux:navbar.item
                            class="!text-white text-2xl font-semibold flex items-center gap-3 [&_svg]:size-7"
                            icon="percent-badge"
                            :href="route('admin.descuentos')"
                            :current="request()->routeIs('admin.descuentos')"
                            wire:navigate
                        >
                            {{ __('Descuentos') }}
                        </flux:navbar.item>
                        <flux:navbar.item
                            class="!text-white text-2xl font-semibold flex items-center gap-3 [&_svg]:size-7"
                            icon="users"
                            :href="route('usuarios.index')"
                            :current="request()->routeIs('usuarios.*')"
                            wire:navigate
                        >
                            {{ __('Gestión de Usuarios') }}
                        </flux:navbar.item>
                        <flux:navbar.item
                            class="!text-white text-2xl font-semibold flex items-center gap-3 [&_svg]:size-7"
                            icon="banknotes"
                            :href="route('admin.finanzas')"
                            :current="request()->routeIs('admin.finanzas')"
                            wire:navigate
                        >
                            {{ __('Finanzas') }}
                        </flux:navbar.item>
                    @else
                        <flux:navbar.item
                            class="!text-white text-2xl font-semibold flex items-center gap-3 [&_svg]:size-7"
                            icon="shopping-cart"
                            :href="route('productos.catalogo')"
                            :current="request()->routeIs('productos.catalogo')"
                            wire:navigate
                        >
                            {{ __('Productos') }}
                        </flux:navbar.item>
                    @endif
                @endauth
            </flux:navbar>

            <flux:spacer />

            @auth
                @if(auth()->user()->rol === 'cliente')
                    <flux:navbar class="-mb-px max-lg:hidden gap-4 text-white text-2xl font-semibold">
                        <flux:navbar.item
                            class="!text-white text-2xl font-semibold flex items-center gap-3 [&_svg]:size-7"
                            icon="percent-badge" 
                            :href="route('productos.descuento')"
                            :current="request()->routeIs('productos.descuento')"
                            wire:navigate
                        >
                            {{ __('Descuentos') }}
                        </flux:navbar.item>
                        <flux:navbar.item
                            class="!text-white text-2xl font-semibold flex items-center gap-3 [&_svg]:size-7"
                            icon="shopping-cart"
                            :href="route('cart.index')"
                            :current="request()->routeIs('cart.index')"
                            wire:navigate
                        >
                            {{ __('Carrito') }}
                        </flux:navbar.item>
                    </flux:navbar>
                @endif
            @endauth

            <flux:spacer />

            <flux:dropdown
                position="top"
                align="end"
                class="text-red-600"
                style="--color-accent-content: var(--color-accent-light);"
            >
                <flux:profile
                    class="cursor-pointer text-2xl text-red-600"
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
                @auth
                    @if(auth()->user()->rol === 'admin')
                        <flux:navlist.item
                            class="!text-red-600 text-2xl font-semibold [&_svg]:size-7"
                            icon="shopping-cart"
                            :href="route('productos-admin.index')"
                            :current="request()->routeIs('productos-admin.*')"
                            wire:navigate
                        >
                            {{ __('Productos') }}
                        </flux:navlist.item>
                        <flux:navlist.item
                            class="!text-red-600 text-2xl font-semibold [&_svg]:size-7"
                            icon="percent-badge"
                            :href="route('admin.descuentos')"
                            :current="request()->routeIs('admin.descuentos')"
                            wire:navigate
                        >
                            {{ __('Descuentos') }}
                        </flux:navlist.item>
                        <flux:navlist.item
                            class="!text-red-600 text-2xl font-semibold [&_svg]:size-7"
                            icon="users"
                            :href="route('usuarios.index')"
                            :current="request()->routeIs('usuarios.*')"
                            wire:navigate
                        >
                            {{ __('Gestión de Usuarios') }}
                        </flux:navlist.item>
                        <flux:navlist.item
                            class="!text-red-600 text-2xl font-semibold [&_svg]:size-7"
                            icon="banknotes"
                            :href="route('admin.finanzas')"
                            :current="request()->routeIs('admin.finanzas')"
                            wire:navigate
                        >
                            {{ __('Finanzas') }}
                        </flux:navlist.item>
                    @else
                        <flux:navlist.item
                            class="!text-red-600 text-2xl font-semibold [&_svg]:size-7"
                            icon="shopping-cart"
                            :href="route('productos.catalogo')"
                            :current="request()->routeIs('productos.catalogo')"
                            wire:navigate
                        >
                            {{ __('Productos') }}
                        </flux:navlist.item>
                        <flux:navlist.item
                            class="!text-red-600 text-2xl font-semibold [&_svg]:size-7"
                            icon="percent-badge" 
                            :href="route('productos.descuento')"
                            :current="request()->routeIs('productos.descuento')"
                            wire:navigate
                        >
                            {{ __('Descuentos') }}
                        </flux:navlist.item>
                        <flux:navlist.item
                            class="!text-red-600 text-2xl font-semibold [&_svg]:size-7"
                            icon="shopping-cart"
                            :href="route('cart.index')"
                            :current="request()->routeIs('cart.index')"
                            wire:navigate
                        >
                            {{ __('Mi Carrito') }}
                        </flux:navlist.item>
                    @endif
                @endauth
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

        <main class="flex-1 w-full">
            {{ $slot }}
        </main>

        <footer class="bg-red-600 text-white py-8 w-full mt-12">
            <div class="max-w-6xl mx-auto px-6 text-center space-y-1">
                <p class="text-xl font-semibold">Tienda Buen Fin</p>
                <p class="text-sm">Direccion: Av. Comercio 123, Col. Centro, Ciudad de Mexico.</p>
                <p class="text-sm">Contactos: Tel. (55) 1234 5678 | contacto@tiendabuenfin.mx</p>
            </div>
        </footer>

        @fluxScripts
    </body>
</html>
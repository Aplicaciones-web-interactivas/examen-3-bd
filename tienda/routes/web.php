<?php

use App\Http\Controllers\OrderController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\DescuentoController;
use App\Http\Controllers\ImagenController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    // Orders routes - Cliente
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');
        Route::get('/{id}', [OrderController::class, 'show'])->name('show');
        Route::get('/{id}/ticket', [OrderController::class, 'ticket'])->name('ticket');
    });

    // Cart routes - Cliente
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'getCart'])->name('get');
        Route::post('/add', [CartController::class, 'addItem'])->name('add');
        Route::put('/item/{id}', [CartController::class, 'updateItem'])->name('update');
        Route::delete('/item/{id}', [CartController::class, 'removeItem'])->name('remove');
        Route::delete('/clear', [CartController::class, 'clearCart'])->name('clear');
        Route::get('/preview', [CartController::class, 'checkoutPreview'])->name('preview');
    });


    //Rutas para Producto
    Route::get('productos', [ProductoController::class, 'index'])->name('productos.index');
    Route::get('productos/{id}', [ProductoController::class, 'show'])->name('productos.show');
    Route::post('productos', [ProductoController::class, 'store'])->name('productos.store');
    Route::put('productos/{id}', [ProductoController::class, 'update'])->name('productos.update');
    Route::delete('productos/{id}', [ProductoController::class, 'destroy'])->name('productos.destroy');
    Route::post('productos/import', [ProductoController::class, 'import'])->name('productos.import');

        // Rutas para Descuentos
    Route::get('descuentos', [DescuentoController::class, 'index'])->name('descuentos.index');
    Route::get('descuentos/create', [DescuentoController::class, 'create'])->name('descuentos.create');
    Route::post('descuentos', [DescuentoController::class, 'store'])->name('descuentos.store');
    Route::get('descuentos/{id}/edit', [DescuentoController::class, 'edit'])->name('descuentos.edit');
    Route::put('descuentos/{id}', [DescuentoController::class, 'update'])->name('descuentos.update');
    Route::delete('descuentos/{id}', [DescuentoController::class, 'destroy'])->name('descuentos.destroy');

    // Rutas para Imágenes
    Route::get('imagenes', [ImagenController::class, 'index'])->name('imagenes.index');
    Route::get('imagenes/create', [ImagenController::class, 'create'])->name('imagenes.create');
    Route::post('imagenes', [ImagenController::class, 'store'])->name('imagenes.store');
    Route::get('imagenes/{id}/edit', [ImagenController::class, 'edit'])->name('imagenes.edit');
    Route::put('imagenes/{id}', [ImagenController::class, 'update'])->name('imagenes.update');
    Route::delete('imagenes/{id}', [ImagenController::class, 'destroy'])->name('imagenes.destroy');

    // Rutas para la gestión de usuarios (solo para administradores)
    // AGREGAR AQUI TODAS LAS RUTAS A LAS QUE SOLO PUEDAN ACCEDER LOS ADMINISTRADORES
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/crear', [UserController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
        Route::get('/usuarios/{id}/editar', [UserController::class, 'edit'])->name('usuarios.edit');
        Route::put('/usuarios/{id}', [UserController::class, 'update'])->name('usuarios.update');
        Route::delete('/usuarios/{id}', [UserController::class, 'destroy'])->name('usuarios.destroy');
    });

     // Orders routes - Administrador
    Route::prefix('admin')->middleware('admin')->name('admin.')->group(function () {
        Route::get('/finanzas', [OrderController::class, 'finanza'])->name('finanzas');
    });

});

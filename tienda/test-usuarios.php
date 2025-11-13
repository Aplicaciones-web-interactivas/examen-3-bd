<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST DE USUARIOS ===\n\n";

// Verificar usuarios admin
$admins = App\Models\User::where('rol', 'admin')->get(['id', 'name', 'email', 'rol']);
echo "Usuarios con rol admin:\n";
foreach ($admins as $admin) {
    echo "  - ID: {$admin->id}, Nombre: {$admin->name}, Email: {$admin->email}\n";
}

echo "\n";

// Verificar que la vista existe
$viewPath = resource_path('views/usuarios/index.blade.php');
if (file_exists($viewPath)) {
    echo "✓ La vista usuarios/index.blade.php EXISTE\n";
    echo "  Ruta: {$viewPath}\n";
} else {
    echo "✗ La vista usuarios/index.blade.php NO EXISTE\n";
}

echo "\n";

// Probar el controlador
try {
    $controller = new App\Http\Controllers\UserController();
    echo "✓ El UserController se puede instanciar\n";
} catch (Exception $e) {
    echo "✗ Error al instanciar UserController: " . $e->getMessage() . "\n";
}

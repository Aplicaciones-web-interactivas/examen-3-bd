<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Mostrar todos los usuarios
    public function index()
    {
        $usuarios = User::all();
        return view('usuarios.index', compact('usuarios'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        return view('usuarios.create');
    }

    // Guardar nuevo usuario
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'apellido' => 'nullable',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'rol' => 'required|in:admin,cliente',
        ]);

        User::create([
            'name' => $request->name,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente');
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        return view('usuarios.edit', compact('usuario'));
    }

    // Actualizar usuario existente
    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'apellido' => 'nullable',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'rol' => 'required|in:admin,cliente',
        ]);

        $usuario->update([
            'name' => $request->name,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'rol' => $request->rol,
        ]);

        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
            $usuario->save();
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente');
    }

    // Eliminar usuario
    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente');
    }

}

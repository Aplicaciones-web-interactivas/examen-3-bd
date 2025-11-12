<?php

namespace App\Http\Controllers;

use App\Models\Imagen;
use Illuminate\Http\Request;

class ImagenController extends Controller
{
    public function index()
    {
        $imagenes = Imagen::all();
        return view('imagenes.index', compact('imagenes'));
    }

    public function create()
    {
        return view('imagenes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'imagen_url' => 'required|url',
        ]);

        Imagen::create($validated);

        return redirect()
            ->route('imagenes.index')
            ->with('status', 'Imagen registrada correctamente');
    }

    public function edit($id)
    {
        $imagen = Imagen::findOrFail($id);
        return view('imagenes.edit', compact('imagen'));
    }

    public function update(Request $request, $id)
    {
        $imagen = Imagen::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'imagen_url' => 'sometimes|url',
        ]);

        $imagen->update($validated);

        return redirect()
            ->route('imagenes.index')
            ->with('status', 'Imagen actualizada correctamente');
    }

    public function destroy($id)
    {
        $imagen = Imagen::findOrFail($id);
        $imagen->delete();

        return redirect()
            ->route('imagenes.index')
            ->with('status', 'Imagen eliminada correctamente');
    }
}

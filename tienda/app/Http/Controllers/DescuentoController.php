<?php

namespace App\Http\Controllers;

use App\Models\Descuento;
use Illuminate\Http\Request;

class DescuentoController extends Controller
{
    public function index()
    {
        $descuentos = Descuento::all();
        return view('descuentos.index', compact('descuentos'));
    }

    public function create()
    {
        return view('descuentos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'porcentaje' => 'required|numeric|min:1|max:100',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
        ]);

        Descuento::create($validated);

        return redirect()
            ->route('descuentos.index')
            ->with('status', 'Descuento creado correctamente');
    }

    public function edit($id)
    {
        $descuento = Descuento::findOrFail($id);
        return view('descuentos.edit', compact('descuento'));
    }

    public function update(Request $request, $id)
    {
        $descuento = Descuento::findOrFail($id);

        $validated = $request->validate([
            'porcentaje' => 'sometimes|numeric|min:1|max:100',
            'fecha_inicio' => 'sometimes|date',
            'fecha_fin' => 'sometimes|date|after:fecha_inicio',
        ]);

        $descuento->update($validated);

        return redirect()
            ->route('descuentos.index')
            ->with('status', 'Descuento actualizado correctamente');
    }

    public function destroy($id)
    {
        $descuento = Descuento::findOrFail($id);
        $descuento->delete();

        return redirect()
            ->route('descuentos.index')
            ->with('status', 'Descuento eliminado correctamente');
    }
}

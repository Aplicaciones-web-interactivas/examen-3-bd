<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

use App\Models\Imagen;
use App\Models\Descuento;

use Maatwebsite\Excel\Facades\Excel;



class ProductoController extends Controller
{
    public function index(Request $request)
    {
        //este es in filtro para buscar por nombre
        if ($request->filled('nombre')) {
            $productos = \App\Models\Producto::where('nombre', 'like', '%'.$request->nombre.'%')->get();
        } else { //si no lo encuientra te muestra todos
            $productos = \App\Models\Producto::all();
        }
        $imagenes = Imagen::select('id', 'nombre')->orderBy('id')->get();
        $descuentos = Descuento::select('id','porcentaje')->orderBy('id')->get(); // porcentaje existe según tu modelo

        return view('producto', compact('productos','imagenes','descuentos'));
    }

    public function show($id)
    {
        $producto = Producto::findOrFail($id);
        return view('productos.show', compact('producto'));
    }

    //funcion para guardar solo pide los campos que estaban en bd y crea el producto
    //En la bd viene que tiene que estar registrada una imagen y un desuento para crear el producto
    //Yo lo cree a mano en bd pero para test se necesita los cruids de decuento e imagen
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'        => 'required|string|max:255',
            'descripcion'   => 'nullable|string',
            'precio'        => 'required|numeric', // tu BD usa double, respetado
            'imagen_id'     => 'required|integer|exists:imagenes,id',
            'descuento_id'  => 'required|integer|exists:descuentos,id',
            'stock'         => 'required|integer|min:0',
        ]);

        Producto::create($validated);

        return redirect()
            ->route('productos-admin.index')
            ->with('status', 'Producto creado correctamente');
    }

    //De igual ,manera para actualizar el producto
    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $validated = $request->validate([
            'nombre'        => 'sometimes|string|max:255',
            'descripcion'   => 'sometimes|nullable|string',
            'precio'        => 'sometimes|numeric',
            'imagen_id'     => 'sometimes|integer|exists:imagenes,id',
            'descuento_id'  => 'sometimes|integer|exists:descuentos,id',
            'stock'         => 'sometimes|integer|min:0',
        ]);

        $producto->update($validated);

        return redirect()
            ->route('productos-admin.index')
            ->with('status', 'Producto actualizado');
    }


    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return redirect()
            ->route('productos-admin.index')
            ->with('status', 'Producto eliminado');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        $file = $request->file('file');

        Excel::import(new \App\Imports\ProductoImport, $file);

        return redirect()
            ->route('productos-admin.index')
            ->with('status', 'Productos importados correctamente');
    }

    public function index2(Request $request)
    {
        //este es in filtro para buscar por nombre
        if ($request->filled('nombre')) {
            $productos = \App\Models\Producto::where('nombre', 'like', '%'.$request->nombre.'%')->get();
        } else { //si no lo encuientra te muestra todos
            $productos = \App\Models\Producto::all();
        }
        $imagenes = Imagen::select('id', 'nombre')->orderBy('id')->get();
        $descuentos = Descuento::select('id','porcentaje')->orderBy('id')->get(); // porcentaje existe según tu modelo

        return view('productos-admin', compact('productos','imagenes','descuentos'));
    }
}

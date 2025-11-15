<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Descuento;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\NuevoDescuentoMail;
use Illuminate\Support\Facades\Log;

class DescuentosCrud extends Component
{
    use WithPagination;

    public $modo = 'crear'; // se crea o edita
    public $descuento_id;

    // Descuento
    public $porcentaje;
    public $fecha_inicio;
    public $fecha_fin;

    // productos a los que se aplica el descuento
    public $productos_seleccionados = [];

    // envío de correos 
    public $enviar_correo = false;
    public $destino = 'ninguno'; // ninguno | uno | todos
    public $usuario_unico_id;   

    protected $rules = [
        'porcentaje' => 'required|numeric|min:1|max:100',
        'fecha_inicio' => 'required|date|after_or_equal:today',
        'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        'productos_seleccionados' => 'array',
    ];

    public function render()
    {
        return view('livewire.admin.descuentos-crud', [
            'descuentos' => Descuento::with('productos')->latest()->paginate(10),
            'productos'  => Producto::with('descuento')->get(),
            'usuarios'   => User::all(),
        ]);
    }

    public function limpiarFormulario()
    {
        $this->reset([
            'descuento_id',
            'porcentaje',
            'fecha_inicio',
            'fecha_fin',
            'productos_seleccionados',
            'enviar_correo',
            'destino',
            'usuario_unico_id',
        ]);

        $this->modo = 'crear';
    }

    public function editar($id)
    {
        $this->modo = 'editar';
        $descuento = Descuento::with('productos')->findOrFail($id);

        $this->descuento_id  = $descuento->id;
        $this->porcentaje    = $descuento->porcentaje;
        $this->fecha_inicio  = optional($descuento->fecha_inicio)->format('Y-m-d');
        $this->fecha_fin     = optional($descuento->fecha_fin)->format('Y-m-d');

        $this->productos_seleccionados = $descuento->productos->pluck('id')->toArray();

        // reset correo
        $this->enviar_correo = false;
        $this->destino = 'ninguno';
        $this->usuario_unico_id = null;
    }

    public function guardar()
    {
        $this->validate();


        if ($this->enviar_correo && $this->destino === 'uno' && empty($this->usuario_unico_id)) {
            $this->addError('usuario_unico_id', 'Debes seleccionar un cliente para enviar el correo.');
            return;
        }

        if ($this->modo === 'crear') {
            $descuento = Descuento::create([
                'porcentaje'   => $this->porcentaje,
                'fecha_inicio' => $this->fecha_inicio,
                'fecha_fin'    => $this->fecha_fin,
            ]);
        } else {
            $descuento = Descuento::findOrFail($this->descuento_id);
            $descuento->update([
                'porcentaje'   => $this->porcentaje,
                'fecha_inicio' => $this->fecha_inicio,
                'fecha_fin'    => $this->fecha_fin,
            ]);
        }

        Producto::where('descuento_id', $descuento->id)->update(['descuento_id' => null]);

        // Asignar a productos seleccionados
        if (!empty($this->productos_seleccionados)) {
            Producto::whereIn('id', $this->productos_seleccionados)
                ->update(['descuento_id' => $descuento->id]);
        }

        // Enviar correos 
        if ($this->enviar_correo) {
            $this->enviarCorreos($descuento);
        }

        session()->flash('status', 'Descuento guardado correctamente.');

        $this->limpiarFormulario();
    }

    public function eliminar($id)
    {
        $descuento = Descuento::findOrFail($id);

        // quitarlo de los productos antes de borrar
        Producto::where('descuento_id', $descuento->id)->update(['descuento_id' => null]);

        $descuento->delete();

        session()->flash('status', 'Descuento eliminado correctamente.');
    }

    private function enviarCorreos(Descuento $descuento)
    {
        $destinatarios = collect();

        switch ($this->destino) {
            case 'uno':
                if ($this->usuario_unico_id) {
                    $destinatarios = User::where('id', $this->usuario_unico_id)->get();
                }
                break;

            case 'todos':
                $destinatarios = User::all();
                break;

            case 'ninguno':
            default:
                return; 
        }

        Log::info('enviarCorreos: se van a mandar correos a '.$destinatarios->count().' usuarios.');

        foreach ($destinatarios as $usuario) {
            Mail::to($usuario->email)->send(new NuevoDescuentoMail($descuento, $usuario));
        }
    }
}

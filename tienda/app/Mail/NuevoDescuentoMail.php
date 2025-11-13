<?php

namespace App\Mail;

use App\Models\Descuento;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NuevoDescuentoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $descuento;
    public $usuario;

    public function __construct(Descuento $descuento, User $usuario)
    {
        $this->descuento = $descuento;
        $this->usuario   = $usuario;
    }

    public function build()
    {
        return $this->subject('Nuevo descuento disponible')
                    ->markdown('emails.nuevo-descuento');
    }
}

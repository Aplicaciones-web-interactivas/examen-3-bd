<?php

namespace App\Mail;

use App\Models\Descuento as DescuentoModel;
use App\Models\Producto;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Descuentos extends Mailable
{
    use Queueable, SerializesModels;

    public DescuentoModel $descuento;
    public Producto $producto;

    public function __construct(Producto $producto, DescuentoModel $descuento)
    {
        $this->producto = $producto;
        $this->descuento = $descuento;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Producto con descuento: ' . $this->producto->nombre . ' (' . $this->descuento->porcentaje . '%)',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.productos.descuento',
            with: [
                'descuento' => $this->descuento,
                'producto' => $this->producto,
                'precio_final' => $this->producto->precio_final,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

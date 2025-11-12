<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetalleCarrito extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'carrito_id',
        'producto_id',
        'cantidad',
        'subtotal',
    ];

    public function carrito()
    {
        return $this->belongsTo(Carrito::class);
    }


    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
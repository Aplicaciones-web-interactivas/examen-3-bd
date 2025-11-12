<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetalleCarrito extends Model
{
    use SoftDeletes;

    protected $table = 'detalle_carrito';

    protected $primaryKey = 'id_detalle_carrito';

    protected $fillable = [
        'id_carrito',
        'id_producto',
        'cantidad',
        'subtotal',
    ];
}
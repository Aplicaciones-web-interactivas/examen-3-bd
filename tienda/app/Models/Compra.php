<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetalleCarrito extends Model
{
    use SoftDeletes;

    protected $table = 'compras';

    protected $primaryKey = 'id_compra';

    protected $fillable = [
        'id_usuario',
        'id_carrito_origen',
        'total',
        'fecha_compra',
    ];
}
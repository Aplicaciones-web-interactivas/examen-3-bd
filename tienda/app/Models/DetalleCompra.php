<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetalleCompra extends Model
{
    use SoftDeletes;

    protected $table = 'detalle_compra';

    protected $primaryKey = 'id_detalle';

    protected $fillable = [
        'id_compra',
        'id_producto',
        'cantidad',
        'subtotal',
    ];
}
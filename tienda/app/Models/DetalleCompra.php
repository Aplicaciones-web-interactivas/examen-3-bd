<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetalleCompra extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'compra_id',
        'producto_id',
        'cantidad',
        'subtotal',
    ];


    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }


    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
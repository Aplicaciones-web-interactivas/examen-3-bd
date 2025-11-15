<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Compra extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'carrito_id',
        'total',
        'fecha_compra',
    ];

    protected $casts = [
        'fecha_compra' => 'datetime',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function carrito()
    {
        return $this->belongsTo(Carrito::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetalleCompra::class);
    }
}
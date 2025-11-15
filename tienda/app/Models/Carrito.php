<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Carrito extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'total',
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
    public function detalles()
    {
        return $this->hasMany(DetalleCarrito::class);
    }

    
    public function compras()
    {
        return $this->hasMany(Compra::class);
    }
}
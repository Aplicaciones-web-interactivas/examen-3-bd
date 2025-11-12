<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'imagen_id',
        'descuento_id',
        'stock',
    ];

    public function imagen()
    {
        return $this->belongsTo(Imagen::class);
    }

    public function descuento()
    {
        return $this->belongsTo(Descuento::class);
    }

    public function detallesCarrito()
    {
        return $this->hasMany(DetalleCarrito::class);
    }

    public function detallesCompra()
    {
        return $this->hasMany(DetalleCompra::class);
    }

    public function getPrecioFinalAttribute()
    {
        if ($this->descuento && $this->descuento->estaActivo()) {
            return $this->precio * (1 - $this->descuento->porcentaje / 100);
        }
        return $this->precio;
    }
}
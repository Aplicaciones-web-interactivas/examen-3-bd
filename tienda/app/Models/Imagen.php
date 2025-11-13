<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Imagen extends Model
{
    use SoftDeletes;
    
    protected $table = 'imagenes';

    protected $fillable = [
        'nombre',
        'imagen_url',
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}
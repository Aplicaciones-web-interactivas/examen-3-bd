<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Descuento extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'porcentaje',
        'fecha_inicio',
        'fecha_fin',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];


    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    public function estaActivo()
    {
        $hoy = now()->toDateString();
        return $hoy >= $this->fecha_inicio && $hoy <= $this->fecha_fin;
    }
}
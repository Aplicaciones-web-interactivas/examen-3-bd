<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Descuento extends Model
{
    use SoftDeletes;

    protected $table = 'descuentos';
    protected $primaryKey = 'id_descuento';

    protected $fillable = [
        'porcentaje',
        'fecha_inicio',
        'fecha_fin',
    ];
}
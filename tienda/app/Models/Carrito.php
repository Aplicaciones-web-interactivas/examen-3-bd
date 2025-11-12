<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Carrito extends Model
{
    use SoftDeletes;

    protected $table = 'carritos';

    protected $primaryKey = 'id_carrito';

    protected $fillable = [
        'id_usuario',
        'total',
    ];

}
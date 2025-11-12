<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Imagen extends Model
{
    use SoftDeletes;

    protected $table = 'imagenes';

    protected $primaryKey = 'id_imagen';

    protected $fillable = [
        'nombre',
        'imagen_url',
    ];
}
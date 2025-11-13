<?php

namespace App\Imports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductoImport implements ToModel
{
    /**
     * Saltar la fila de encabezado si existe.
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $nombre = isset($row[0]) ? trim($row[0]) : null;
        $descripcion = isset($row[1]) ? trim($row[1]) : null;
        $precio = isset($row[2]) ? floatval($row[2]) : null;
        $imagen_id = isset($row[3]) ? intval($row[3]) : null;
        $descuento_id = isset($row[4]) ? intval($row[4]) : null;
        $stock = isset($row[5]) ? intval($row[5]) : null;

        if (empty($nombre) && empty($descripcion) && is_null($precio) && is_null($imagen_id) && is_null($descuento_id) && is_null($stock)) {
            return null;
        }

        return new Producto([
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'precio' => $precio,
            'imagen_id' => $imagen_id,
            'descuento_id' => $descuento_id,
            'stock' => $stock,
        ]);
    }
}

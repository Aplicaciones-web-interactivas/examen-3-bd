<?php

namespace App\Imports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Log;

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
        $imagen_url = isset($row[3]) ? trim($row[3]) : null;
        $imagen_nombre = isset($row[4]) ? trim($row[4]) : null;
        $descuento = isset($row[5]) ? intval($row[5]) : null;
        $descuento_fecha_inicio = isset($row[6]) ? trim($row[6]) : null;
        $descuento_fecha_fin = isset($row[7]) ? trim($row[7]) : null;
        $stock = isset($row[8]) ? intval($row[8]) : null;

        // create imagen and descuento if necessary
        $imagen = null;

        print("Imagen URL: $imagen_url, Nombre: $imagen_nombre");
        print("");

        $imagen = \App\Models\Imagen::firstOrCreate(
            ['imagen_url' => $imagen_url, 'nombre' => $imagen_nombre]
        );

        $descuento = \App\Models\Descuento::firstOrCreate(
            ['porcentaje' => $descuento, 'fecha_inicio' => $descuento_fecha_inicio, 'fecha_fin' => $descuento_fecha_fin]
        );

        return new Producto([
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'precio' => $precio,
            'imagen_id' => $imagen->id,
            'descuento_id' => $descuento->id,
            'stock' => $stock,
        ]);
    }
}

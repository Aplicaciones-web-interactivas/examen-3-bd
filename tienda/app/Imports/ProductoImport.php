<?php

namespace App\Imports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Log;

class ProductoImport implements ToModel
{

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    * @throws \InvalidArgumentException
    */
    public function model(array $row)
    {
        try {
            // Validar y extraer campos con validación de tipo
            $nombre = $this->validarCampo($row[0] ?? null, 'nombre', 'string', true);
            $descripcion = $this->validarCampo($row[1] ?? null, 'descripcion', 'string', false);
            $precio = $this->validarCampo($row[2] ?? null, 'precio', 'float', true);
            $imagen_url = $this->validarCampo($row[3] ?? null, 'imagen_url', 'string', true);
            $imagen_nombre = $this->validarCampo($row[4] ?? null, 'imagen_nombre', 'string', true);
            $descuento = $this->validarCampo($row[5] ?? null, 'descuento', 'int', false);
            $descuento_fecha_inicio = $this->validarCampo($row[6] ?? null, 'descuento_fecha_inicio', 'date', false);
            $descuento_fecha_fin = $this->validarCampo($row[7] ?? null, 'descuento_fecha_fin', 'date', false);
            $stock = $this->validarCampo($row[8] ?? null, 'stock', 'int', true);

            // Validaciones adicionales
            if ($precio < 0) {
                throw new \InvalidArgumentException('El precio no puede ser negativo.');
            }

            if ($stock < 0) {
                throw new \InvalidArgumentException('El stock no puede ser negativo.');
            }

            if ($descuento !== null && ($descuento < 0 || $descuento > 100)) {
                throw new \InvalidArgumentException('El descuento debe estar entre 0 y 100.');
            }

            $imagen = \App\Models\Imagen::firstOrCreate(
                ['imagen_url' => $imagen_url, 'nombre' => $imagen_nombre]
            );

            $descuentoModel = null;
            if ($descuento !== null || $descuento_fecha_inicio !== null || $descuento_fecha_fin !== null) {
                $descuentoModel = \App\Models\Descuento::firstOrCreate(
                    ['porcentaje' => $descuento, 'fecha_inicio' => $descuento_fecha_inicio, 'fecha_fin' => $descuento_fecha_fin]
                );
            }

            return new Producto([
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'precio' => $precio,
                'imagen_id' => $imagen->id,
                'descuento_id' => $descuentoModel?->id,
                'stock' => $stock,
            ]);
        } catch (\Exception $e) {
            Log::error('Error importando producto: ' . $e->getMessage());
            throw $e;
        }
    }

    private function validarCampo($valor, $nombreCampo, $tipo, $requerido = false)
    {
        if (is_null($valor) || (is_string($valor) && trim($valor) === '')) {
            if ($requerido) {
                throw new \InvalidArgumentException("El campo '{$nombreCampo}' es requerido.");
            }
            return null;
        }

        if (is_string($valor)) {
            $valor = trim($valor);
        }

        switch ($tipo) {
            case 'string':
                if (!is_string($valor)) {
                    throw new \InvalidArgumentException("El campo '{$nombreCampo}' debe ser texto.");
                }
                return $valor;

            case 'int':
                if (!is_numeric($valor) || intval($valor) != $valor) {
                    throw new \InvalidArgumentException("El campo '{$nombreCampo}' debe ser un número entero.");
                }
                return intval($valor);

            case 'float':
                if (!is_numeric($valor)) {
                    throw new \InvalidArgumentException("El campo '{$nombreCampo}' debe ser un número.");
                }
                return floatval($valor);

            case 'date':
                if (!$this->esFormatoFecha($valor)) {
                    throw new \InvalidArgumentException("El campo '{$nombreCampo}' tiene formato de fecha inválido (use YYYY-MM-DD).");
                }
                return $valor;

            default:
                throw new \InvalidArgumentException("Tipo de validación desconocido: {$tipo}");
        }
    }

    private function esFormatoFecha($fecha)
    {
        $patrón = '/^\d{4}-\d{2}-\d{2}$/';
        if (!preg_match($patrón, $fecha)) {
            return false;
        }

        $partes = explode('-', $fecha);
        return checkdate((int)$partes[1], (int)$partes[2], (int)$partes[0]);
    }
}

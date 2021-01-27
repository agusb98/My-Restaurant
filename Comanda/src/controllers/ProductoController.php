<?php

namespace App\Controllers;

use App\Models\Producto;
use App\Models\Sector;
use App\Models\Puesto;
use Clases\iToken;
use Clases\date;

class ProductoController{
    public function add($nombre, $puesto_id, $precio){
        $producto =  new Producto;
        $producto->nombre = $nombre;
        $producto->puesto_id = $puesto_id;
        $producto->precio = $precio;
        $producto->save();
    }

    public function getAll(){
        return Producto::select('productos.id', 
                                'productos.nombre', 
                                'productos.precio', 
                                'puestos.nombre as puesto')
                        ->join('puestos', 'puestos.id', '=', 'productos.puesto_id')
                        ->get();
    }

    public function getOne($id){
        foreach (ProductoController::getAll() as $e) {
            if($e->id == $id){
                return $e;
            }
        }
        return null;
    }

    public function getPopulars($date){
        $promedio = ProductoController::getPromedioUso($date);
        $top_productos = array();

        foreach (ProductoController::getCountAll($date) as $producto) {
            if($producto->contador >= $promedio){
                array_push($top_productos, $producto);
            }
        }
        return $top_productos;
    }

    public function getUnpopulars($date){
        $promedio = ProductoController::getPromedioUso($date);
        $buttom_productos = array();

        foreach (ProductoController::getCountAll($date) as $producto) {
            if($producto->contador < $promedio){
                array_push($buttom_productos, $producto);
            }
        }
        return $buttom_productos;
    }

    public function getPromedioUso($date){
        $productos = ProductoController::getCountAll($date);
        $acum = 0;
        foreach ($productos as $producto) {
            $acum += $producto->contador;
        }
        return ($acum / count($productos));
    }

    public function getCountAll($date){
        $productos = array();
        foreach (ProductoController::getAll() as $producto) {
            $producto->contador = ProductoController::getCount($producto, $date);
            array_push($productos, $producto);
        }
        return $productos;
    }

    public function getCount($producto, $date){
        $count = 0;
        foreach (ItemController::getAll() as $item) {
            if($item->producto_id == $producto->id){
                if(Date::Compare($item->created_at, $date) >= 0){
                    $count++;
                }
            }
        }
        return $count;
    }

    public function checkAdd($nombre, $puesto, $price){
        if(strlen($nombre) <= 2){
            return false;
        }
        if($price < 0 || !is_numeric($price)){
            return array("status" => false, "message" => "Precio Incorrecto");
        }
        if(!PuestoController::getOne($puesto)){
            return array("status" => false, "message" => "Puesto Inexistente");
        }
        if(Producto::where("nombre", "=", $nombre)->First()){
            return array("status" => false, "message" => "Producto previamente Registrado");
        }
        return array("status" => true);
    }

    public function checkUpdate($id, $price){
        if($price < 0 || !is_numeric($price)){
            return array("status" => false, "message" => "Precio Incorrecto");
        }
        if(!Producto::find($id)){
            return array("status" => false, "message" => "Producto Inexistente");
        }
        return array("status" => true);
    }

    public function delete($id){
        if($producto = Producto::find($id)){
            return $producto->delete();
        }
        return false;
    }

    public function update($id, $precio){
        $producto = Producto::find($id);
        $producto->precio = $precio;
        return $producto->save();
    }
}

<?php

namespace App\Controllers;

use App\Models\Item;
use Clases\iToken;
use Clases\Date;

class ItemController{
    public function getAll(){
        $array_items = array();
        $items = Item::select('items.id as item_id', 'items.pedido_codigo', 
                            'items.tiempo', 'productos.id as producto_id', 
                            'productos.nombre', 'productos.precio', 
                            'puestos.nombre as puesto', 'items.empleado_id', 
                            'items.created_at')
                    ->join('productos', 'productos.id', '=', 'items.producto_id')
                    ->join('puestos', 'puestos.id', '=', 'productos.puesto_id')
                    ->get();

        foreach ($items as $item) {
            if($empleado = EmpleadoController::getOne($item->empleado_id)){
                $item->empleado_nombre = $empleado->nombre;
                $item->empleado_apellido = $empleado->apellido;
            }
            array_push($array_items, $item);
        }
        return $array_items;
    }

    public function getPendientes(){
        $item_pendientes = array();
        foreach (ItemController::getAll() as $item) {
            if($item->empleado_id == NULL){
                array_push($item_pendientes, $item);
            }
        }
        return $item_pendientes;
    }

    public function getGroup($code){
        $group_items = array();
        foreach (ItemController::getAll() as $item) {
            if($item->pedido_codigo == $code){
                array_push($group_items, $item);
            }
        }
        return $group_items;
    }

    public function getOne($identity){        
        foreach (ItemController::getAll() as $item) {
            if($item->item_id == $identity){
                return $item;
            }
        }
        return NULL;
    }

    public function add($productos_id, $pedido_codigo) : bool{
        $flag = true;

        for ($i = 0; $i < count($productos_id); $i++) {
            if($flag){
                $item = new Item();
                $item->pedido_codigo = $pedido_codigo;
                $producto = ProductoController::getOne($productos_id[$i]);
                $item->producto_id = $producto->id;
                $flag = $item->save();
            }
        }
        return $flag;
    }

    public function Preparar($item_id, $empleado_id, $tiempo) : bool{
        $item = Item::find($item_id);
        $item->empleado_id = $empleado_id;
        $item->tiempo = $tiempo;
        return $item->save();
    }

    public function isReady($code){
        foreach (ItemController::getGroup($code) as $item) {
            if($item->tiempo == NULL){
                return false;
            }
        }
        return true;
    }

    public function acumImport($code){
        $acum = 0;
        foreach(ItemController::getGroup($code) as $item) {
            $acum += $item->precio;
        }
        return $acum;
    }

    public function getPromedioByMonths(){
        $months = array();
        for ($i=1; $i < 13; $i++) { 
            $promedio = ItemController::getPromedioByMonth($i);
            array_push($months, array('mes' => $i, 'promedio' => $promedio));
        }
        return $months;
    }

    public function getPromedioByMonth($month){
        $count = $acum = 0;
        foreach (ItemController::getAll() as $item) {
            if($month == $item->created_at->format("m")){
                $acum += $item->precio;
                $count++;
            }
        }
        if($count == 0){ return 0; }
        return ($acum / $count);
    }
}

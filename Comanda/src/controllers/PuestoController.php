<?php

namespace App\Controllers;

use App\Models\Puesto;
use Clases\iToken;
use Clases\date;

class PuestoController {
    public function getOne($identity){
        foreach (Puesto::get() as $puesto) {
            if(strcmp($puesto->nombre, $identity) == 0 || $puesto->id == $identity){
                return $puesto;
            }
        }
        return null;
    }

    public function countAllPedidos($date){
        $puestos = array();
        foreach (Puesto::get() as $puesto) {
            PuestoController::countPedidos($puesto, $date);
            array_push($puestos, $puesto);
        }
        return $puestos;
    }

    public function countPedidos(&$puesto, $date){
        $empleados = array();
        $count = 0;

        foreach (EmpleadoController::countAllPedidos($date) as $empleado) {
            if($empleado->puesto == $puesto->nombre){
                $count += $empleado->operaciones;
                $puesto->operaciones = $count;
                array_push($empleados, $empleado);
            }
        }
        $puesto->empleados = $empleados;
    }

    public function deleteEmpleadoDetails(&$puestos){
        foreach ($puestos as $puesto) {
            unset($puesto->empleados);
        }
    }
/*
    public function getCountOperationsAll($fecha_inicial, $fecha_final) {
        foreach ($puestos = Puesto::get() as $puesto) {
            $puesto->operations = PuestoController::filterCountPedidosByDate($puesto, $fecha_inicial, $fecha_final);
        }
        return $puestos;
    }

    public function filterCountPedidosByDate($puesto, $fecha_inicial, $fecha_final){
        $pedidos = PedidoController::getAll();
        $count = 0;

        switch($puesto->nombre){
            case 'MOZO':
                foreach ($pedidos as $pedido) {
                    if(Date::Compare($pedido->created_at, $fecha_inicial) == 1 && Date::Compare($pedido->created_at, $fecha_final) == -1){
                        $count++;
                    }
                }
            break;
            default:
                foreach ($pedidos as $pedido) {
                    if(Date::Compare($pedido->created_at, $fecha_inicial) == 1 && Date::Compare($pedido->created_at, $fecha_final) == -1){
                        foreach ($pedido->productos as $producto) {
                            if(strcmp($producto->puesto, $puesto->nombre) == 0){
                                $count++;
                            }
                        }
                    }
                }
            break;
        }
        return $count;
    }*/
}

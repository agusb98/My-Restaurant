<?php

namespace App\Controllers;

use Clases\date;
use App\Models\Ingreso_Empleado;

class IngresoController{
    public function Add($empleado_id, $date) {
        $ingreso = new Ingreso_empleado;
        $ingreso->empleado_id = $empleado_id;
        $ingreso->ingreso = $date;
        return $ingreso->save();
    }

    public function getGroup($empleado_id, $fecha_inicial) : array {
        $ingresos = array();

        foreach (Ingreso_Empleado::get() as $log) {            
            if($log->empleado_id == $empleado_id){
                if(Date::Compare($log->ingreso, $fecha_inicial) >= 0){
                    array_push($ingresos, $log->ingreso);
                }
            }
        }
        return $ingresos;
    }
}

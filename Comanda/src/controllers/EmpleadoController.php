<?php

namespace App\Controllers;

use Clases\date;
use Clases\iToken;
use App\Models\Empleado;
use App\Models\Ingreso_Empleado;

class EmpleadoController{
    public function add($persona_id, $puesto_id){
        $empleado =  new Empleado;
        
        $empleado->persona_id = $persona_id;
        $empleado->puesto_id = $puesto_id;
        $empleado->estado_id = 1;
        $empleado->save();

        return IngresoController::Add($empleado->id, Date::get_now());
    }

    public function getAll(){
        return Empleado::select('empleados.id', 'personas.id as persona_id',
                                'personas.nombre','personas.apellido', 
                                'puestos.nombre as puesto', 'estado_empleados.nombre as estado',
                                'empleados.created_at', 'empleados.updated_at')
                            ->join('personas', 'personas.id', '=', 'empleados.persona_id')
                            ->join('puestos', 'puestos.id', '=', 'empleados.puesto_id')
                            ->join('estado_empleados', 'estado_empleados.id', '=', 'empleados.estado_id')
                            ->get();
    }

    public function find($id){
        return Empleado::find($id);
    }

    public function getOne($id){
        foreach (EmpleadoController::getAll() as $empleado) {
            if($empleado->id == $id){
                unset($empleado->persona_id);
                return $empleado;
            }
        }
        return null;
    }

    public function findByPersonaId($id){
        return Empleado::where('empleados.persona_id', '=', $id)->first();
    }

    public function checkAdd($email, $pass, $puesto){
        if(!PuestoController::getOne($puesto)){
            return array("status" => false, "message" => "Puesto Inexistente");
        }
        if(!$user = UserController::findByEmail($email)){
            return array("status" => false, "message" => "Email Inexistente");
        }
        if(EmpleadoController::findByPersonaId($user->persona_id)){
            return array("status" => false, "message" => "Empleado previamente registrado");
        }
        if(!iToken::ComparePasswordHash($pass, $user->password)){
            return array("status" => false, "message" => "Password Incorrecto");
        }
        return array('status' => true);
    }

    public function checkUpdate($id, $estado){
        if($estado != 'ACTIVO' && $estado != 'INACTIVO'){
            return array("status" => false, "message" => "Estado Incorrecto");
        }
        if(!$empleado = EmpleadoController::getOne($id)){
            return array("status" => false, "message" => "Empleado Inexistente");
        }
        return array('status' => true);
    }

    public function getIngresosAll($fecha_inicial){
        $empleados = array();
        foreach (EmpleadoController::GetAll() as $empleado) {
            $ingreso = IngresoController::getGroup($empleado->id, $fecha_inicial);
            $empleado->ingresos = $ingreso;
            array_push($empleados, $empleado);
        }
        return $empleados;
    }

    public function countAllPedidos($fecha_inicial){
        $empleados = array();
        foreach (EmpleadoController::getAll() as $empleado) {
            $empleado->operaciones = EmpleadoController::countPedidos($empleado, $fecha_inicial);
            array_push($empleados, $empleado);
        }
        return $empleados;
    }

    public function countPedidos($empleado, $fecha_inicial){
        $count = 0;
        if($empleado->puesto == 'MOZO' || $empleado->puesto == 'SOCIO'){
            $count = EmpleadoController::countPedidosMozo($empleado->id, $fecha_inicial);
        }
        else{
            $count = EmpleadoController::countPedidosCocinero($empleado, $fecha_inicial);
        }
        return $count;
    }

    public function countPedidosCocinero($empleado, $fecha_inicial){
        $count = 0;
        foreach (ItemController::getAll() as $item) {
            if($item->empleado_id == $empleado->id){
                if(Date::Compare($item->created_at, $fecha_inicial) >= 0){                 
                    $count++;
                }
            }
        }
        return $count;
    }

    public function countPedidosMozo($empleado, $fecha_inicial){
        $count = 0;
        foreach (PedidoController::getAll() as $pedido) {
            if($pedido->mozo->id == $empleado){
                if(Date::Compare($pedido->created_at, $fecha_inicial) >= 0){
                    $count++;
                }
            }
        }
        return $count;
    }

    public function getEstadisticas($date){
        $empleados = array();
        foreach (EmpleadoController::getAll() as $empleado) {
            if($empleado->puesto == 'MOZO'){
                $empleado->puntuacion = EncuestaController::getEstadisticaMozo($empleado, $date);
            }
            else{
                $empleado->puntuacion = EncuestaController::getEstadisticaCocinero($empleado, $date);
            }
            array_push($empleados, $empleado);
        }
        return $empleados;
    }

    public function getPromedioByMonths(){
        $empleados = array();
        $months = array();

        for ($i=1; $i < 13; $i++) { 
            foreach (EmpleadoController::getAll() as $empleado) {
                $empleado->promedio_mensual = EmpleadoController::getPromedioByMonth($empleado, $i);
                array_push($empleados, $empleado);
            }
            $month = array('mes' => $i, 'empleados' => $empleados);
            array_push($months, $month);
            $empleados = array();
        }
        return $months;
    }

    public function getPromedioByMonth($empleado, $month){
        if($empleado->puesto == 'MOZO'){
            return EmpleadoController::getPromedioByMonth_Mozo($empleado, $month);
        }
        return EmpleadoController::getPromedioByMonth_Cocinero($empleado, $month);
    }

    public function getPromedioByMonth_Mozo($empleado, $month){
        $count = $acum = 0;
        foreach (PedidoController::getAll() as $pedido) {
            if($month == $pedido->created_at->format("m") && $empleado->id == $pedido->mozo->id){
                if($encuesta = EncuestaController::getOne($pedido->codigo)){
                    $acum += $encuesta->puntuacion_mozo;
                    $count++;
                }
            }

        }
        if($count == 0){ $count++;}
        return ($acum / $count);
    }

    public function getPromedioByMonth_Cocinero($empleado, $month){
        $count = $acum = 0;
        foreach (ItemController::getAll() as $item) {
            if($month == $item->created_at->format("m") && $empleado->id == $item->empleado_id){
                $encuesta = EncuestaController::getOne($item->pedido_codigo);
                $acum += $encuesta->puntuacion_cocinero;
                $count++;
            }
        }
        if($count == 0){ $count++;}
        return ($acum / $count);
    }

    public function delete($id){
        $empleado = Empleado::find($id);
        return $empleado->delete();
    }

    public function update($id, $estado_nombre){
        $estado_id = 0;
        if(strcmp($estado_nombre, 'ACTIVO') == 0){ $estado_id = 1;}

        $empleado = Empleado::find($id);
        $empleado->estado_id = $estado_id;
        return $empleado->save();
    }
}

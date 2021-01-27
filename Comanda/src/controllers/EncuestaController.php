<?php

namespace App\Controllers;

use App\Models\Encuesta;
use Clases\date;

class EncuestaController{
    public function Add($pedido_codigo, $cliente_id, $puntuaciones, $descripcion) {
        $encuesta = new Encuesta;
        $encuesta->pedido_codigo = $pedido_codigo;
        $encuesta->cliente_id = $cliente_id;
        $encuesta->mesa = $puntuaciones['mesa'];
        $encuesta->restaurante = $puntuaciones['restaurante'];
        $encuesta->mozo = $puntuaciones['mozo'];
        $encuesta->cocinero = $puntuaciones['cocinero'];
        $encuesta->descripcion = $descripcion;
        return $encuesta->save();
    }
   
    public function getAll(){
        return Encuesta::select('encuestas.id', 
                                'encuestas.pedido_codigo as codigo', 
                                'clientes.id as cliente_id', 
                                'personas.nombre as cliente_nombre', 
                                'personas.apellido as cliente_apellido', 
                                'encuestas.mesa as puntuacion_mesa', 
                                'encuestas.restaurante as puntuacion_restaurante', 
                                'encuestas.mozo as puntuacion_mozo', 
                                'encuestas.cocinero as puntuacion_cocinero', 
                                'encuestas.descripcion',
                                'encuestas.created_at')
                        ->join('pedidos', 'pedidos.codigo', '=', 'encuestas.pedido_codigo')
                        ->join('clientes', 'clientes.id', '=', 'pedidos.cliente_id')
                        ->join('personas', 'personas.id', '=', 'clientes.persona_id')
                        ->get();
    }

    public function getOne($codigo){
        foreach (EncuestaController::getAll() as $e) {
            if($e->codigo == $codigo){
                return $e;
            }
        }
        return null;
    }

    public function getBetter($date){
        $better_encuestas = array();

        foreach (EncuestaController::getAll() as $e) {
            $date_flag = Date::Compare($e->created_at, $date) >= 0;
            $acum = $e->puntuacion_mesa + $e->puntuacion_restaurante + 
                    $e->puntuacion_mozo + $e->puntuacion_cocinero;

            if(($acum / 4) >= 7 && $date_flag){
                array_push($better_encuestas, $e);
            }
        }
        return $better_encuestas;
    }

    public function getWorse($date){
        $worse_encuestas = array();

        foreach (EncuestaController::getAll() as $e) {
            $date_flag = Date::Compare($e->created_at, $date) >= 0;
            $acum = $e->puntuacion_mesa + $e->puntuacion_restaurante + 
                    $e->puntuacion_mozo + $e->puntuacion_cocinero;

            if(($acum / 4) < 7 && $date_flag){
                array_push($worse_encuestas, $e);
            }
        }
        return $worse_encuestas;
    }

    public function checkAdd($pedido_codigo, $cliente_id, $puntuaciones, $descrip)
    {
        if(Encuesta::where('encuestas.pedido_codigo', '=', $pedido_codigo)->first()){
            return array("status" => false, "message" => "Pedido previamente encuestado");
        }
        if(!$pedido = PedidoController::getOne($pedido_codigo)){
            return array("status" => false, "message" => "Pedido inexistente con codigo $pedido_codigo");
        }
        if($pedido->cliente->id != $cliente_id){
            return array("status" => false, "message" => "El cliente no realizo este Pedido");
        }
        if(!EncuestaController::checkPuntuacion($puntuaciones)){
            return array("status" => false, "message" => 'Puntuacion numerica, del 1 al 10');
        }
        if(strlen($descrip) > 66){
            return array("status" => false, "message" => 'Descripcion mayor a 66 caracteres');
        }
        return array('status' => true);
    }

    public function checkPuntuacion($puntuaciones){
        foreach ($puntuaciones as $punt) {
            if(!is_numeric($punt) || $punt < 1 || $punt > 10){
                return false;
            }
        }
        return true;
    }

    public function getEstadisticaCocinero($empleado, $date){
        $puntuaciones = Encuesta::select('encuestas.cocinero')
                        ->join('items', 'items.pedido_codigo', '=', 'encuestas.pedido_codigo')
                        ->join('empleados', 'empleados.id', '=', 'items.empleado_id')
                        ->where('empleados.id', '=', $empleado->id)
                        ->whereDate('encuestas.created_at', '>=', $date)
                        ->get();

        $acum = 0;
        foreach ($puntuaciones as $punt) {
            $acum += $punt->cocinero;
        }
        if(count($puntuaciones) == 0){ return 0; }
        else{ return $acum / count($puntuaciones); }
    }

    public function getEstadisticaMozo($empleado, $date){
        $puntuaciones = Encuesta::select('encuestas.mozo')
                        ->join('pedidos', 'pedidos.codigo', '=', 'encuestas.pedido_codigo')
                        ->join('empleados', 'empleados.id', '=', 'pedidos.mozo_id')
                        ->where('empleados.id', '=', $empleado->id)
                        ->whereDate('encuestas.created_at', '>=', $date)
                        ->get();

        $acum = 0;
        foreach ($puntuaciones as $punt) {
            $acum += $punt->mozo;
        }
        if(count($puntuaciones) == 0){ return 0; }
        else{ return $acum / count($puntuaciones); }
    }
}

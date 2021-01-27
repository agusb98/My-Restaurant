<?php

namespace App\Controllers;

use App\Models\Mesa;
use Clases\date;

class MesaController{
    public function add($code){
        $mesa =  new Mesa;
        $mesa->estado_id = 4;
        $mesa->codigo = $code;
        $mesa->save();
    }

    public function checkAdd($code){
        if(strlen($code) != 5){
            return array("status" => false, "message" => "Codigo Invalido");
        }
        if(Mesa::where("codigo", "=", $code)->First()){
            return array("status" => false, "message" => "Mesa previamente Registrada");
        }
        return array("status" => true);
    }

    public function getAll(){
        return Mesa::select('mesas.id', 'mesas.codigo', 'estado_mesas.nombre as estado',
                            'mesas.created_at', 'mesas.updated_at')
                    ->join('estado_mesas', 'estado_mesas.id', '=', 'mesas.estado_id')
                    ->get();
    }

    public function getOne($id_or_code){
        foreach (MesaController::getAll() as $mesa) {
            if(strcmp($mesa->codigo, $id_or_code) == 0 || $mesa->id == $id_or_code){
                unset($mesa->id);
                return $mesa;
            }
        }
        return null;
    }

    public function getPopulars($date){
        $promedio = mesaController::getPromedioUso($date);
        $top_mesas = array();

        foreach (mesaController::getCountAll($date) as $mesa) {
            if($mesa->contador >= $promedio){
                array_push($top_mesas, $mesa);
            }
        }
        return $top_mesas;
    }

    public function getUnpopulars($date){
        $promedio = mesaController::getPromedioUso($date);
        $buttom_mesas = array();

        foreach (mesaController::getCountAll($date) as $mesa) {
            if($mesa->contador < $promedio){
                array_push($buttom_mesas, $mesa);
            }
        }
        return $buttom_mesas;
    }

    public function getPromedioUso($date){
        $mesas = mesaController::getCountAll($date);
        $acum = 0;
        foreach ($mesas as $mesa) {
            $acum += $mesa->contador;
        }
        return ($acum / count($mesas));
    }

    public function getCountAll($date){
        $mesas = array();
        foreach (MesaController::getAll() as $mesa) {
            $mesa->contador = MesaController::getCount($mesa, $date);
            array_push($mesas, $mesa);
        }
        return $mesas;
    }

    public function getCount($mesa, $date){
        $count = 0;
        foreach (PedidoController::getAll() as $pedido) {
            if($pedido->mesa == $mesa->codigo){
                if(Date::Compare($pedido->created_at, $date) >= 0){
                    $count++;
                }
            }
        }
        return $count;
    }

    public function getTopAcumImports($date){
        $promedio = mesaController::getPromedioAcum($date);
        $top_mesas = array();

        foreach (mesaController::getAcumImportAll($date) as $mesa) {
            if($mesa->importe_acumulado >= $promedio){
                array_push($top_mesas, $mesa);
            }
        }
        return $top_mesas;
    }

    public function getLessAcumImports($date){
        $promedio = mesaController::getPromedioAcum($date);
        $buttom_mesas = array();

        foreach (mesaController::getAcumImportAll($date) as $mesa) {
            if($mesa->importe_acumulado < $promedio){
                array_push($buttom_mesas, $mesa);
            }
        }
        return $buttom_mesas;
    }

    public function getPromedioAcum($date){
        $mesas = mesaController::getAcumImportAll($date);
        $acum = 0;
        foreach ($mesas as $mesa) {
            $acum += $mesa->importe_acumulado;
        }
        return ($acum / count($mesas));
    }

    public function getAcumImportAll($date){
        $mesas = array();
        foreach (MesaController::getAll() as $mesa) {
            $mesa->importe_acumulado = MesaController::getAcumImport($mesa, $date);
            array_push($mesas, $mesa);
        }
        return $mesas;
    }

    public function getAcumImport($mesa, $date){
        $acum = 0;
        foreach (PedidoController::getAll() as $pedido) {
            if($pedido->mesa == $mesa->codigo){
                if(Date::Compare($pedido->created_at, $date) >= 0){
                    $acum += ItemController::acumImport($pedido->codigo);
                }
            }
        }
        return $acum;
    }

    public function getTopFacturas($date){
        $mesas = array();
        $acum = 0;

        foreach (MesaController::getAll() as $mesa) {
            $mesa->top_factura = MesaController::getTopFactura($mesa, $date);
            array_push($mesas, $mesa);
            if($mesa->top_factura > $acum){
                $acum = $mesa->top_factura;
            }
        }

        $top_mesas = array();
        foreach ($mesas as $mesa) {
            if($mesa->top_factura == $acum){
                array_push($top_mesas, $mesa);
            }
        }
        return $top_mesas;
    }

    public function getTopFactura($mesa, $date){
        foreach (PedidoController::getTopFacturas($date) as $pedido) {
            if($mesa->codigo == $pedido->mesa){
                return $pedido->importe;
            }
        }
        return 0;
    }

    public function getLessFacturas($date){
        $mesas = array();
        $acum = PHP_INT_MAX;

        foreach (MesaController::getAll() as $mesa) {
            $mesa->less_factura = MesaController::getLessFactura($mesa, $date);
            array_push($mesas, $mesa);
            if($mesa->less_factura < $acum){
                $acum = $mesa->less_factura;
            }
        }

        $less_mesas = array();
        foreach ($mesas as $mesa) {
            if($mesa->less_factura == $acum){
                array_push($less_mesas, $mesa);
            }
        }
        return $less_mesas;
    }

    public function getLessFactura($mesa, $date){
        foreach (PedidoController::getLessFacturas($date) as $pedido) {
            if($mesa->codigo == $pedido->mesa){
                return $pedido->importe;
            }
        }
        return 0;
    }

    public function getPromedioByMonths(){
        $mesas = array();
        $months = array();

        for ($i=1; $i < 13; $i++) { 
            foreach (MesaController::getAll() as $mesa) {
                $mesa->promedio_mensual = MesaController::getPromedioByMonth($mesa, $i);
                array_push($mesas, $mesa);
            }
            $month = array('mes' => $i, 'mesas' => $mesas);
            array_push($months, $month);
            $mesas = array();
        }
        return $months;
    }

    public function getPromedioByMonth($mesa, $month){
        $count = $acum = 0;
        foreach (PedidoController::getAll() as $pedido) {
            if($month == $pedido->created_at->format("m") && $mesa->codigo == $pedido->mesa){
                $acum += ItemController::acumImport($pedido->codigo);
                $count++;
            }
        }
        if($count == 0){ $count++;}
        return ($acum / $count);
    }

    public function Update($mesa_codigo, $estado_id){
        $mesa = Mesa::where('mesas.codigo', '=', $mesa_codigo)->first();
        $mesa->estado_id = $estado_id;
        return $mesa->save();
    }

    public function delete($code){
        if($mesa = Mesa::where('mesas.codigo', '=', $code)->first()){
            return $mesa->delete();
        }
        return false;
    }
}

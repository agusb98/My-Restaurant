<?php
use Illuminate\Database\Capsule\Manager as DBM;

namespace App\Controllers;

use App\Models\Pedido;
use Clases\Date;
use Clases\imagen;

class PedidoController{
    public function getOne($code){
        foreach (PedidoController::getAll() as $p) {
            if(strcmp($p->codigo, $code) == 0){
                return $p;
            }
        }
        return null;
    }

    public function getAll(){
        $pedidos = Pedido::select('pedidos.codigo', 
                            'pedidos.tiempoestimado as tiempo', 
                            'estado_pedidos.nombre as estado',
                            'pedidos.foto',
                            'mesas.codigo as mesa', 
                            'pedidos.created_at',
                            'clientes.id as cliente', 
                            'pedidos.mozo_id as mozo')
                        
                        ->join('estado_pedidos', 'estado_pedidos.id', '=', 'pedidos.estado_id')
                        ->join('clientes', 'clientes.id', '=', 'pedidos.cliente_id')
                        ->join('mesas', 'mesas.codigo', '=', 'pedidos.mesa_codigo')
                        ->get();

        foreach ($pedidos as $pedido) {
            $pedido->mozo = EmpleadoController::getOne($pedido->mozo);
            $pedido->cliente = ClienteController::getOne($pedido->cliente);
            $pedido->productos = itemController::getGroup($pedido->codigo);
        }
        return $pedidos;
    }

    public function filterByStatus($status, $date){
        $pedidos = array();
        foreach (PedidoController::getAll() as $p) {
            if(strcmp($p->estado, $status) == 0){
                if(Date::Compare($p->created_at, $date) >= 0){
                    array_push($pedidos, $p);
                }
            }
        }
        return $pedidos;
    }

    public function getDemored($date){
        $pedidos = array();
        foreach (PedidoController::getAll() as $p) {
            if(strcmp($p->estado, 'DEMORADO') == 0){       ///////FALLA, DEBO CHECKEAR SI ESTA DEMORADO
                if(Date::Compare($p->created_at, $date) >= 0){
                    array_push($pedidos, $p);
                }
            }
        }
        return $pedidos;
    }

    public function checkAdd($mesa_codigo, $cliente_id, $productos_id){
        if(!$mesa = MesaController::getOne($mesa_codigo)){
            return array("status" => false, "message" => "Mesa inexistente con codigo $mesa_codigo");
        }

        if($mesa->estado != 'cerrada'){
            return array("status" => false, "message" => "Mesa Ocupada");
        }
        
        if(!ClienteController::getOne($cliente_id)){
            return array("status" => false, "message" => "Cliente inexistente con id $cliente_id");
        }

        if(!$productos_id = explode(',', $productos_id)){
            return array("status" => false, "message" => "Estilo de ingreso id producto: 1, 2, 3, 3");
        }
        
        foreach ($productos_id as $producto_id) {
            if(!ProductoController::getOne($producto_id)){ 
                return array("status" => false, "message" => "Producto no encontrado");
            }
        }
        return array('status' => true);
    }

    public function add($mozo_id, $mesa_codigo, $cliente_id, $productos_id, $foto){
        $pedido = new Pedido();
        $productos_id = explode(',', $productos_id);
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $pedido->codigo = substr(str_shuffle($permitted_chars), 0, 5);
        $pedido->mozo_id = $mozo_id;
        $pedido->mesa_codigo = $mesa_codigo;
        $pedido->cliente_id = $cliente_id;
        $pedido->foto = $foto;
        $pedido->save();

        MesaController::Update($mesa_codigo, 1);
        if(ItemController::add($productos_id, $pedido->codigo)){
            return $pedido;
        }
        return null;
    }

    public function checkPreparar($pedido_codigo, $item_id, $empleado_id, $tiempo){
        if(!PedidoController:: getOne($pedido_codigo)){
            return array("status" => false, "message" => "Pedido inexistente con codigo $pedido_codigo");
        }
        if(!$item = ItemController::getOne($item_id)){
            return array("status" => false, "message" => "Item inexistente con id $item_id");
        }

        $empleado = EmpleadoController::getOne($empleado_id);
        $puesto = $empleado['puesto'];
        
        if($puesto != $item->puesto){
            return array("status" => false, "message" => "Item solo para empleados en el sector $item->puesto");
        }
        if($item->empleado_id != NULL){
            return array("status" => false, "message" => "Item previamente en preparacion");
        }

        if(strlen($tiempo) != 8){
            return array("status" => false, "message" => "Ingrese tiempo aproximado");
        }
        return array('status' => true);
    }

    public function Preparar($item_id, $empleado_id, $tiempo) : bool{
        return ItemController::Preparar($item_id, $empleado_id, $tiempo);
    }

    public function AcumTime($pedido_codigo, $tiempo) : bool{
        $pedido = Pedido::where('pedidos.codigo', '=', $pedido_codigo)->first();
        $pedido->tiempoestimado = '00:' . (date('i', strtotime($pedido->tiempoestimado)) 
                                + date('i', strtotime($tiempo))) . ':00';
        return $pedido->save();
    }

    public function checkReady($pedido_codigo){
        if(!$pedido = Pedido::where('pedidos.codigo', '=', $pedido_codigo)->first()){
            return array("status" => false, "message" => "Pedido inexistente con codigo $pedido_codigo");
        }
        if($pedido->estado_id != 2){
            return array("status" => false, "message" => "Pedido no disponible para estar listo");
        }
        if(!ItemController::isReady($pedido_codigo)){
            return array("status" => false, "message" => 'Pedido en preparacion');
        }
        return array('status' => true);
    }

    public function checkServir($pedido_codigo){
        if(!$pedido = Pedido::where('pedidos.codigo', '=', $pedido_codigo)->first()){
            return array("status" => false, "message" => "Pedido inexistente con codigo $pedido_codigo");
        }
        if($pedido->estado_id < 3){
            return array("status" => false, "message" => 'Pedido en preparacion');
        }
        if($pedido->estado_id > 3){
            return array("status" => false, "message" => 'Pedido no disponible');
        }
        return array('status' => true);
    }

    public function Update($pedido_codigo, $status) : bool{
        $pedido = Pedido::where('pedidos.codigo', '=', $pedido_codigo)->first();
        $pedido->estado_id = $status;
        return $pedido->save();
    }

    public function Delete($pedido_codigo) : bool{
        $pedido = Pedido::where('pedidos.codigo', '=', $pedido_codigo)->first();
        return $pedido->delete();
    }

    public function getTopFacturas($date){
        $facturas = array();
        $acum = 0;

        foreach (PedidoController::getAll() as $pedido) {
            if(Date::Compare($pedido->created_at, $date) >= 0){
                $pedido->importe = ItemController::acumImport($pedido->codigo);
                array_push($facturas, $pedido);
                if($pedido->importe > $acum){ $acum = $pedido->importe; }
            }
        }

        $top_facturas = array();
        foreach ($facturas as $factura) {
            if($factura->importe == $acum){
                array_push($top_facturas, $factura);
            }
        }
        return $top_facturas;
    }

    public function getLessFacturas($date){
        $facturas = array();
        $acum = PHP_INT_MAX;

        foreach (PedidoController::getAll() as $pedido) {
            if(Date::Compare($pedido->created_at, $date) >= 0){
                $pedido->importe = ItemController::acumImport($pedido->codigo);
                array_push($facturas, $pedido);
                if($pedido->importe < $acum){ $acum = $pedido->importe; }
            }
        }

        $less_facturas = array();
        foreach ($facturas as $factura) {
            if($factura->importe == $acum){
                array_push($less_facturas, $factura);
            }
        }
        return $less_facturas;
    }

    public function checkPedidoForCliente($pedido_codigo, $cliente){
        if(!$pedido = PedidoController::getOne($pedido_codigo)){
            return array("status" => false, "message" => "pedido inexistente con codigo $pedido_codigo");
        }
        if($pedido->cliente->id != $cliente->id){
            return array("status" => false, "message" => "Pedido no Correspondiente con cliente");
        }
        return array("status" => true);
    }
}
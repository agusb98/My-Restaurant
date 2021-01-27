<?php

namespace App\Controllers;
use Clases\iToken;
use Clases\imagen;
use Clases\date;

class Controller{
    //USUARIOS
    public function addUser($request, $response, $args){
        $body = $request->getParsedBody();
        $nombre = strtoupper($body['nombre'] ?? '');
        $apellido = strtoupper($body['apellido'] ?? '');
        $email = strtoupper($body["email"] ?? '');
        $password = $body['password'] ?? "";
        
        $result = UserController::checkAdd($email, $nombre, $apellido);
        if($result['status']){
            UserController::add($email, $password, $nombre, $apellido);
            $rta = array("status" => "OK", "message" => "Usuario Agregado");
        }
        else{ $rta = array("status" => "ERROR", "message" => $result['message']); }
        
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getUsers($request, $response, $args){
        $usuarios = UserController::getAll();
        $rta = array("status" => "OK", "message" => "Datos Usuarios", "usuarios" => $usuarios);
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getToken($request, $response, $args){
        $body = $request->getParsedBody();
        $email = strtoupper($body["email"] ?? '');
        $password = $body['password'] ?? "";

        if($token = UserController::getToken($email, $password)){
            $rta = array("status" => "OK", "token" => $token);
        }
        else{ $rta = array("status" => "ERROR", "message" => "Ingreso de datos erroneos"); }

        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    //EMPLEADOS
    public function addEmpleado($request, $response, $args){
        $body = $request->getParsedBody();
        $email = strtoupper($body["email"] ?? '');
        $password = $body['password'] ?? "";
        $puesto = strtoupper($body['puesto'] ?? '');
        
        $result = EmpleadoController::checkAdd($email, $password, $puesto);
        if($result['status']){
            $user = UserController::findByEmail($email);
            $puesto = PuestoController::getOne($puesto);

            EmpleadoController::add($user->persona_id, $puesto->id);
            $rta = array("status" => "OK", "message" => "Empleado Agregado");
        }
        else{ $rta = array("status" => "ERROR", "message" => $result['message']); }
        
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getEmpleado($request, $response, $args){
        $body = $request->getParsedBody();
        $id_empleado = $args["id"];

        if($empleado = EmpleadoController::getOne($id_empleado)){
            $rta = array("status" => "OK", "message" => "Datos Empleado", "empleado" => $empleado);
        }
        else{ $rta = array("status" => "ERROR", "message" => "Empleado no existente"); }
        
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getEmpleados($request, $response, $args){
        $empleados = EmpleadoController::getAll();
        $rta = array("status" => "OK", "message" => "Datos Empleados", "empleados" => $empleados);
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function updateEmpleado($request, $response, $args){
        $empleado_id = $args['id'];
        $body = $request->getParsedBody();
        $estado = strtoupper($body['estado'] ?? '');
        $result = EmpleadoController::checkUpdate($empleado_id, $estado);

        if($result['status']){
            EmpleadoController::update($empleado_id, $estado);
            $rta = array("status" => "OK", "message" => "Empleado Actualizado");
        }
        else { $rta = array("status" => "ERROR", "message" => $result['message']); }
        
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function deleteEmpleado($request, $response, $args){
        if (EmpleadoController::getOne($args['id'])) {
            EmpleadoController::delete($args['id']);
            $rta = array("status" => "OK", "message" => "Empleado Eliminado");
        } 
        else { $rta = array("status" => "ERROR", "message" => "Empleado no existente"); }
        
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    //CLIENTES
    public function getPedidoForCliente($request, $response, $args){
        $body = $request->getParsedBody();
        $pedido_codigo = $args["code_pedido"];
        $payload = iToken::checkToken();

        $cliente = ClienteController::findByPersonaId($payload->id);
        $result = PedidoController::checkPedidoForCliente($pedido_codigo, $cliente);

        if($result['status']){
            $pedido = PedidoController::getOne($pedido_codigo);
            $rta = array("status" => "OK", "message" => "Datos del Pedido", "pedido" => $pedido);
        }
        else{ $rta = array("status" => "ERROR", "message" => $result['message']); }
        
        $response->getBody()->write(json_encode($rta));
        return $response;
    }


    public function addCliente($request, $response, $args){
        $body = $request->getParsedBody();
        $email = strtoupper($body["email"] ?? '');
        $password = $body['password'] ?? "";
        $result = ClienteController::checkAdd($email, $password);

        if($result['status']){
            $user = UserController::findByEmail($email);
            ClienteController::add($user->persona_id);
            $rta = array("status" => "OK", "message" => "Cliente Agregado");
        }
        else{ $rta = array("status" => "ERROR", "message" => $result['message']); }
        
        $response->getBody()->write(json_encode($rta));
        return $response;
    }
        
    public function getCliente($request, $response, $args){
        $body = $request->getParsedBody();
        $id_cliente = $args["id"];

        if($cliente = ClienteController::getOne($id_cliente)){
            $rta = array("status" => "OK", "message" => "Datos Cliente", "cliente" => $cliente);
        }
        else{ $rta = array("status" => "ERROR", "message" => "cliente no existente"); }
        
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getClientes($request, $response, $args){
        $clientes = ClienteController::getAll();
        $rta = array("status" => "OK", "message" => "Datos Clientes", "clientes" => $clientes);

        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    //PRODUCTOS
    public function addProducto($request, $response, $args){
        $body = $request->getParsedBody();
        $precio = $body['precio'] ?? "";
        $nombre = strtoupper($body["nombre"] ?? '');
        $puesto = strtoupper($body["puesto"] ?? '');
        
        $result = ProductoController::checkAdd($nombre, $puesto, $precio);
        if($result['status']){
            $puesto = PuestoController::getOne($puesto);
            ProductoController::add($nombre, $puesto->id, $precio);
            $rta = array("status" => "OK", "message" => "Producto Agregado");
        }
        else{ $rta = array("status" => "ERROR", "message" => $result['message']); }
        
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getProducto($request, $response, $args){
        $body = $request->getParsedBody();
        $id_producto = $args["id"];

        if($producto = ProductoController::getOne($id_producto)){
            $rta = array("status" => "OK", "message" => "Datos Producto", "producto" => $producto);
        }
        else{ $rta = array("status" => "ERROR", "message" => "Producto no existente"); }
        
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getProductos($request, $response, $args){
        $productos = ProductoController::getAll();
        $rta = array("status" => "OK", "message" => "Datos Productos", "productos" => $productos);
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function updateProducto($request, $response, $args){
        $body = $request->getParsedBody();
        $precio = $body['precio'] ?? '';
        $producto_id = $args['id'];

        $result = ProductoController::checkUpdate($producto_id, $precio);
        if($result['status']){
            ProductoController::update($producto_id, $precio);
            $rta = array("status" => "OK", "message" => "Producto Actualizado");
        }
        else { $rta = array("status" => "ERROR", "message" => $result['message']); }
        
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function deleteProducto($request, $response, $args){
        if (ProductoController::delete($args['id'])) {
            $rta = array("status" => "OK", "message" => "Producto Eliminado");
        } 
        else { $rta = array("status" => "ERROR", "message" => "Producto no existente"); }
        
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    //MESAS
    public function addMesa($request, $response, $args){
        $body = $request->getParsedBody();
        $mesa_codigo = strtoupper($body['code'] ?? '');
        
        $result = MesaController::checkAdd($mesa_codigo);
        if($result['status']){
            MesaController::add($mesa_codigo);
            $rta = array("status" => "OK", "message" => "Mesa Agregada");
        }
        else{ $rta = array("status" => "ERROR", "message" => $result['message']); }
        
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getMesa($request, $response, $args){
        $body = $request->getParsedBody();
        $codigo_mesa = strtoupper($args['code'] ?? '');

        if($mesa = MesaController::getOne($codigo_mesa)){
            $rta = array("status" => "OK", "message" => "Informacion Mesa", "mesa" => $mesa);
        }
        else{ $rta = array("status" => "ERROR", "message" => "Mesa no existente"); }
        
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getMesas($request, $response, $args){
        $mesas = MesaController::getAll();
        $rta = array("status" => "OK", "message" => "Informacion Mesas", "mesas" => $mesas);
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function closeMesa($request, $response, $args){
        $body = $request->getParsedBody();
        $codigo_mesa = strtoupper($args['code'] ?? '');

        if(MesaController::getOne($codigo_mesa)){
            MesaController::Update($codigo_mesa, 4);
            $rta = array("status" => "OK", "message" => "Mesa Cerrada");
        }
        else{ $rta = array("status" => "ERROR", "message" => "Mesa no existente"); }
        
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function deleteMesa($request, $response, $args){
        $body = $request->getParsedBody();
        $codigo_mesa = strtoupper($args['code'] ?? '');

        if(MesaController::Delete($codigo_mesa)){
            $rta = array("status" => "OK", "message" => "Mesa Eliminada");
        }
        else{ $rta = array("status" => "ERROR", "message" => "Mesa no existente"); }
        
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    //PEDIDOS
    public function addPedido($request, $response, $args){
        $body = $request->getParsedBody();
        $mesa_codigo = strtoupper($body['mesa_codigo'] ?? '');
        $productos_id = $body['productos_id'] ?? '';
        $cliente_id = $body['cliente_id'] ?? '';
        $foto_name = $_FILES["foto"]['name'] ?? "";
        $foto_path = $_FILES["foto"]["tmp_name"] ?? "";

        $payload = iToken::checkToken();
        $empleado = EmpleadoController::findByPersonaId($payload->persona_id);

        $result = PedidoController::checkAdd($mesa_codigo, $cliente_id, $productos_id);
        if($result['status']){
            Imagen::saveCliente($foto_name, $foto_path);
            $pedido = PedidoController::add($empleado->id, $mesa_codigo, $cliente_id, $productos_id, $foto_name);
            $rta = array("status" => "OK", "message" => "Pedido Agregado", 'codigo' => $pedido->codigo);
        }
        else{ $rta = array("status" => "ERROR", "message" => $result['message']); }
        
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getPedido($request, $response, $args){
        $body = $request->getParsedBody();
        $codigo_pedido = $args['code'] ?? '';

        if($pedido = PedidoController::getOne($codigo_pedido)){
            $rta = array("status" => "OK", "message" => "Dato Pedido", "pedido" => $pedido);
        }
        else{ $rta = array("status" => "ERROR", "message" => "Pedido no existente"); }
        
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getPedidosPendientesBySector($request, $response, $args){
        $payload = iToken::checkToken();
        $empleado = EmpleadoController::findByPersonaId($payload->id);
        $puesto = PuestoController::getOne($empleado->puesto_id);
        $filter_list = array();
        foreach (ItemController::getPendientes() as $item) {
            if($item->puesto == $puesto->nombre || $empleado->puesto_id == 5){
                array_push($filter_list, $item);
            }
        }

        $rta = array("status" => "OK", "message" => "Pendientes para puesto $puesto->nombre", "pedidos" => $filter_list);
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function prepararPedido($request, $response, $args){
        $body = $request->getParsedBody();
        $tiempo = $body['tiempo'] ?? '';

        $pedido_codigo = $args['code'];
        $item_id = $args['item_id'];

        $payload = iToken::checkToken();
        $empleado = EmpleadoController::findByPersonaId($payload->id);
        $result = PedidoController::checkPreparar($pedido_codigo, $item_id, $empleado->id, $tiempo);

        if($result['status']){
            PedidoController::Update($pedido_codigo, 2);
            PedidoController::AcumTime($pedido_codigo, $tiempo);
            PedidoController::Preparar($item_id, $empleado['id'], $tiempo);
            $rta = array("status" => "OK", "message" => "Item en preparacion");
        }
        else { $rta = array("status" => "ERROR", "message" => $result['message']); }
        
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function listoPedido($request, $response, $args){
        $pedido_codigo = $args['code'];
        $result = PedidoController::checkReady($pedido_codigo);

        if($result['status']){
            PedidoController::Update($pedido_codigo, 3);
            $rta = array("status" => "OK", "message" => "Pedido listo para ser servido");
        }
        else { $rta = array("status" => "ERROR", "message" => $result['message']); }
        
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function servirPedido($request, $response, $args){
        $pedido_codigo = $args['code'];
        $result = PedidoController::checkServir($pedido_codigo);

        if($result['status']){
            PedidoController::Update($pedido_codigo, 4);
            $rta = array("status" => "OK", "message" => "Pedido servido en mesa");
        }
        else { $rta = array("status" => "ERROR", "message" => $result['message']); }
        
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function cobrarPedido_Excel($request, $response, $args){
        $pedido_codigo = $args['code'];

        if($pedido = PedidoController::getOne($pedido_codigo)){
            PedidoController::Update($pedido_codigo, 4);
            FacturaController::getExcel($pedido_codigo);
            MesaController::Update($pedido->mesa, 4);
            $rta = array("status" => "OK", "message" => "Pedido cobrado");
        }
        else { $rta = array("status" => "ERROR", "message" => 'Pedido Inexistente'); }
        
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function cobrarPedido_PDF($request, $response, $args){
        $pedido_codigo = $args['code'];

        if(PedidoController::getOne($pedido_codigo)){
            PedidoController::Update($pedido_codigo, 4);
            FacturaController::getPDF($pedido_codigo);
            $rta = array("status" => "OK", "message" => "Pedido cobrado");
        }
        else { $rta = array("status" => "ERROR", "message" => 'Pedido Inexistente'); }
        
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function cancelarPedido($request, $response, $args){
        $pedido_codigo = $args['code'];

        if($pedido = PedidoController::getOne($pedido_codigo)){
            PedidoController::Update($pedido_codigo, 6);
            MesaController::Update($pedido->mesa, 4);
            $rta = array("status" => "OK", "message" => "Pedido Cancelado");
        }
        else { $rta = array("status" => "ERROR", "message" => "Pedido Inexistente"); }
        
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function deletePedido($request, $response, $args){
        $pedido_codigo = $args['code'];

        if($pedido = PedidoController::getOne($pedido_codigo)){
            MesaController::Update($pedido->mesa, 4);
            PedidoController::Delete($pedido_codigo);
            $rta = array("status" => "OK", "message" => "Pedido Borrado");
        }
        else { $rta = array("status" => "ERROR", "message" => "Pedido Inexistente"); }
        
        $response->getBody()->write(json_encode($rta));
        return $response;
    }
    
    //ENCUESTAS
    public function addEncuesta($request, $response, $args){
        $body = $request->getParsedBody();
        $pedido_codigo = $body['pedido_codigo'] ?? '';
        $descripcion = $body['descripcion'] ?? '';
        $puntuaciones = array('mesa' => $body['mesa'] ?? '', 'restaurante' => $body['restaurante'] ?? '', 
                              'mozo' => $body['mozo'] ?? '', 'cocinero' => $body['cocinero'] ?? '');

        $payload = iToken::checkToken();
        $cliente = ClienteController::getOneByPersonaId($payload->persona_id);
        $result = EncuestaController::checkAdd($pedido_codigo, $cliente->id, $puntuaciones, $descripcion);

        if($result['status']){
            EncuestaController::Add($pedido_codigo, $cliente->id, $puntuaciones, $descripcion);                  
            $rta = array("status" => "OK", "message" => "Encuesta Agregada");
        }
        else{ $rta = array("status" => "ERROR", "message" => $result['message']); }

        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getEncuesta($request, $response, $args){
        $body = $request->getParsedBody();
        $pedido_codigo = $args['code'] ?? '';

        if($encuesta = EncuestaController::getOne($pedido_codigo)){
            $rta = array("status" => "OK", "message" => "Informacion Encuesta", "encuesta" => $encuesta);
        }
        else{ $rta = array("status" => "ERROR", "message" => "Encuesta no existente"); }
        
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getEncuestas($request, $response, $args){
        $obj = EncuestaController::getAll();
        $rta = array("status" => "OK", "message" => "Informacion Encuestas", "encuestas" => $obj);
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getBestEncuestas($request, $response, $args){ 
        $body = $request->getParsedBody();
        $fecha_inicial = $body['fecha_inicial'] ?? '';

        if(Date::checkDate($fecha_inicial)){
            $encuestas = EncuestaController::getBetter($fecha_inicial);
            $rta = array("status" => "OK",
                         "message" => "Encuestas Positivas desde $fecha_inicial",
                         'encuestas' => $encuestas);
        }
        else{ $rta = array("status" => "ERROR", "message" => "Fecha Ingresada Incorrecta"); }

        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getWorstEncuestas($request, $response, $args){ 
        $body = $request->getParsedBody();
        $fecha_inicial = $body['fecha_inicial'] ?? '';

        if(Date::checkDate($fecha_inicial)){
            $encuestas = EncuestaController::getWorse($fecha_inicial);
            $rta = array("status" => "OK",
                         "message" => "Encuestas Negativas desde $fecha_inicial",
                         'encuestas' => $encuestas);
        }
        else{ $rta = array("status" => "ERROR", "message" => "Fecha Ingresada Incorrecta"); }

        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getStatisticEmpleados($request, $response, $args){         
        $empleado = EncuestaController::getStatisticEmpleados();
        $rta = array("status" => "OK",
                     "message" => "Promedio Puntuacion Empleados",
                     'empleado' => $empleado);

        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getLogsEmpleados($request, $response, $args){ 
        $body = $request->getParsedBody();
        $fecha_inicial = $body['fecha_inicial'] ?? '';

        if(Date::checkDate($fecha_inicial)) {
            $ingresos = EmpleadoController::getIngresosAll($fecha_inicial);
            $rta = array("status" => "OK",
                         "message" => "Ingresos al Sistema desde $fecha_inicial",
                         'empleados' => $ingresos);
        }
        else{ $rta = array("status" => "ERROR", "message" => "Fecha Ingresada Incorrecta"); }

        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getCountOperations_Empleado($request, $response, $args){ 
        $body = $request->getParsedBody();
        $fecha_inicial = $body['fecha_inicial'] ?? '';
        $empleado = EmpleadoController::getOne($args['id']);

        if(Date::checkDate($fecha_inicial)) {
            if($empleado) { 
                $empleado->operaciones = EmpleadoController::countPedidos($empleado, $fecha_inicial);
                $rta = array("status" => "OK", 
                             "message" => "Cantidad Operaciones desde $fecha_inicial",
                             'empleado' => $empleado);
            }
            else{ $rta = array("status" => "ERROR", "message" => "Empleado Inexistente"); }
        }
        else{ $rta = array("status" => "ERROR", "message" => "Fecha Ingresada Incorrecta"); }

        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getCountOperations_Puestos($request, $response, $args){ 
        $body = $request->getParsedBody();
        $fecha_inicial = $body['fecha_inicial'] ?? '';

        if(Date::checkDate($fecha_inicial)) {
            $puestos = PuestoController::countAllPedidos($fecha_inicial);
            PuestoController::deleteEmpleadoDetails($puestos);

            $rta = array("status" => "OK", 
                         "message" => "Cantidad Operaciones por Sector desde $fecha_inicial",
                         'puestos' => $puestos);
        }
        else{ $rta = array("status" => "ERROR", "message" => "Fecha Ingresada Incorrecta"); }

        $response->getBody()->write(json_encode($rta));
        return $response;
    }
    
    public function getCountOperations_PuestosAndEmpleados($request, $response, $args){ 
        $body = $request->getParsedBody();
        $fecha_inicial = $body['fecha_inicial'] ?? '';

        if(Date::checkDate($fecha_inicial)) {
            $puestos = PuestoController::countAllPedidos($fecha_inicial);
            $rta = array("status" => "OK", 
                         "message" => "Cantidad Operaciones por Sector y Empleados desde $fecha_inicial",
                         'puestos' => $puestos);
        }
        else{ $rta = array("status" => "ERROR", "message" => "Fecha Ingresada Incorrecta"); }

        $response->getBody()->write(json_encode($rta));
        return $response;
    }
    
    public function getPopularProductos($request, $response, $args){ 
        $body = $request->getParsedBody();
        $fecha_inicial = $body['fecha_inicial'] ?? '';

        if(Date::checkDate($fecha_inicial)) {
            $productos = ProductoController::getPopulars($fecha_inicial);
            $rta = array("status" => "OK", 
                         "message" => "Productos mas consumidos desde $fecha_inicial",
                         "productos" => $productos);
        }
        else{ $rta = array("status" => "ERROR", "message" => "Fecha Ingresada Incorrecta"); }

        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getUnpopularProductos($request, $response, $args){ 
        $body = $request->getParsedBody();
        $fecha_inicial = $body['fecha_inicial'] ?? '';

        if(Date::checkDate($fecha_inicial)) {
            $productos = ProductoController::getUnpopulars($fecha_inicial);
            $rta = array("status" => "OK", 
                         "message" => "Productos menos consumidos desde $fecha_inicial",
                         "productos" => $productos);
        }
        else{ $rta = array("status" => "ERROR", "message" => "Fecha Ingresada Incorrecta"); }

        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getDemoredPedidos($request, $response, $args){ 
        $body = $request->getParsedBody();
        $fecha_inicial = $body['fecha_inicial'] ?? '';
        $pedidos = array();

        if(Date::checkDate($fecha_inicial)) {
            $pedidos = PedidoController::getDemored($fecha_inicial);
            $rta = array("status" => "OK", 
                         "message" => "Pedidos Demorados desde $fecha_inicial",
                         "pedidos" => $pedidos);
        }
        else{ $rta = array("status" => "ERROR", "message" => "Fecha Ingresada Incorrecta"); }

        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getCanceledPedidos($request, $response, $args){ 
        $body = $request->getParsedBody();
        $fecha_inicial = $body['fecha_inicial'] ?? '';
        $pedidos = array();

        if(Date::checkDate($fecha_inicial)) {
            $pedidos = PedidoController::filterByStatus('CANCELADO', $fecha_inicial);
            $rta = array("status" => "OK", 
                         "message" => "Pedidos Cancelados desde $fecha_inicial",
                         "pedidos" => $pedidos);
        }
        else{ $rta = array("status" => "ERROR", "message" => "Fecha Ingresada Incorrecta"); }

        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getPopularMesas($request, $response, $args){ 
        $body = $request->getParsedBody();
        $fecha_inicial = $body['fecha_inicial'] ?? '';

        if(Date::checkDate($fecha_inicial)) {
            $Mesas = MesaController::getPopulars($fecha_inicial);
            $rta = array("status" => "OK", 
                         "message" => "Mesas mas populares desde $fecha_inicial",
                         "mesas" => $Mesas);
        }
        else{ $rta = array("status" => "ERROR", "message" => "Fecha Ingresada Incorrecta"); }

        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getUnpopularMesas($request, $response, $args){ 
        $body = $request->getParsedBody();
        $fecha_inicial = $body['fecha_inicial'] ?? '';

        if(Date::checkDate($fecha_inicial)) {
            $Mesas = MesaController::getUnpopulars($fecha_inicial);
            $rta = array("status" => "OK", 
                         "message" => "Mesas menos populares desde $fecha_inicial",
                         "mesas" => $Mesas);
        }
        else{ $rta = array("status" => "ERROR", "message" => "Fecha Ingresada Incorrecta"); }

        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getMesas_MostImport($request, $response, $args){ 
        $body = $request->getParsedBody();
        $fecha_inicial = $body['fecha_inicial'] ?? '';

        if(Date::checkDate($fecha_inicial)){
            $mesas = MesaController::getTopAcumImports($fecha_inicial);
            $rta = array("status" => "OK", 
                         "message" => "Mesas con mayor importe desde $fecha_inicial", 
                         'mesas' => $mesas);
        }
        else{ $rta = array("status" => "ERROR", "message" => "Fecha Ingresada Incorrecta"); }

        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getMesas_LessImport($request, $response, $args){ 
        $body = $request->getParsedBody();
        $fecha_inicial = $body['fecha_inicial'] ?? '';

        if(Date::checkDate($fecha_inicial)){
            $mesas = MesaController::getLessAcumImports($fecha_inicial);
            $rta = array("status" => "OK", 
                         "message" => "Mesas con menor importe desde $fecha_inicial", 
                         'mesas' => $mesas);
        }
        else{ $rta = array("status" => "ERROR", "message" => "Fecha Ingresada Incorrecta"); }

        $response->getBody()->write(json_encode($rta));
        return $response;
    }
    
    public function getMesas_MostFactura($request, $response, $args){ 
        $body = $request->getParsedBody();
        $fecha_inicial = $body['fecha_inicial'] ?? '';

        if(Date::checkDate($fecha_inicial)){
            $mesas = MesaController::getTopFacturas($fecha_inicial);
            $rta = array("status" => "OK", 
                         "message" => "Mesas con mayor Factura desde $fecha_inicial", 
                         'mesas' => $mesas);
        }
        else{ $rta = array("status" => "ERROR", "message" => "Fecha Ingresada Incorrecta"); }

        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getMesas_LessFactura($request, $response, $args){ 
        $body = $request->getParsedBody();
        $fecha_inicial = $body['fecha_inicial'] ?? '';

        if(Date::checkDate($fecha_inicial)){
            $mesas = MesaController::getLessFacturas($fecha_inicial);
            $rta = array("status" => "OK", 
                         "message" => "Mesas con menor Factura desde $fecha_inicial", 
                         'mesas' => $mesas);
        }
        else{ $rta = array("status" => "ERROR", "message" => "Fecha Ingresada Incorrecta"); }

        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function acumImportMesa($request, $response, $args){ 
        $body = $request->getParsedBody();
        $fecha_inicial = $body['fecha_inicial'] ?? '';
        $mesa_code = strtoupper($args['code'] ?? '');

        if(Date::checkDate($fecha_inicial)){
            if($mesa = MesaController::getOne($mesa_code)){
                $importe = MesaController::getAcumImport($mesa, $fecha_inicial);
                $rta = array("status" => "OK", 
                            "message" => "Facturacion desde $fecha_inicial", 
                            'importe' => $importe);
            }
            else{ $rta = array("status" => "ERROR", "message" => "Mesa no Encontrada"); }
        }
        else{ $rta = array("status" => "ERROR", "message" => "Fecha Ingresada Incorrecta"); }

        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getEstadistica_Empleados($request, $response, $args){ 
        $body = $request->getParsedBody();
        $fecha_inicial = $body['fecha_inicial'] ?? '';

        if(Date::checkDate($fecha_inicial)){
            $empleados = EmpleadoController::getEstadisticas($fecha_inicial);
            $rta = array("status" => "OK", 
                         "message" => "Estadistica Empleados desde $fecha_inicial", 
                         'empleados' => $empleados);
        }
        else{ $rta = array("status" => "ERROR", "message" => "Fecha Ingresada Incorrecta"); }

        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getPromedioImportesByMonth($request, $response, $args){ 
        $importes = ItemController::getPromedioByMonths();
        $rta = array("status" => "OK", 
                     "message" => "Promedio de Importes por Mes", 
                     'importes' => $importes);

        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getPromedioMesasByMonth($request, $response, $args){ 
        $mesas = MesaController::getPromedioByMonths();
        $rta = array("status" => "OK", 
                     "message" => "Promedio Uso de Mesas por Mes", 
                     'promedio' => $mesas);

        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getPromedioEmpleadosByMonth($request, $response, $args){ 
        $empleados = EmpleadoController::getPromedioByMonths();
        $rta = array("status" => "OK", 
                     "message" => "Promedio Puntuacion de Empleados por Mes", 
                     'promedio' => $empleados);

        $response->getBody()->write(json_encode($rta));
        return $response;
    }
}
?>
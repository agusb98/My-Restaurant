<?php
//COMPOSER - MANEJADOR DE ARCHIVOS

//En la terminal, "composer init" y crea archivo "composer.json"
//Con ese archivo, podre manejar las dependencias
//En la terminal, "composer require firebase/php-jwt" y creara "vendor" y "composer.lock"

//Nunca subire vendor en github, para lo cual creare el archivo ".gitignore"

//Link firebase/php-jwt: https://github.com/firebase/php-jwt

//En la terminal, " composer dump-autoload -o "
//En la terminal, " composer require slim/slim:"4.*" "
//En la terminal, " composer require slim/psr7 "
//En la terminal, " composer require illuminate/database "
//En la terminal, " composer require illuminate/events "

//Link: https://www.slimframework.com/docs/v4/

///////////////////////////////////////////////////////////////////////////

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

use App\Controllers\Controller;

use App\Middlewares\UserMiddleware;
use App\Middlewares\JsonMiddleware;
use App\Middlewares\SocioMiddleware;
use App\Middlewares\MozoMiddleware;
use App\Middlewares\EmpleadoMiddleware;
use App\Middlewares\ClienteMiddleware;
use App\Middlewares\FechaMiddleware;

use Config\Database;
use Illuminate\Container\Container;

require './vendor/autoload.php';

$container = new Container();
AppFactory::setContainer($container);
$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->setBasePath("/05-TPs/Comanda");
new Database();

//////////////////////////////////////////////////////////////////////////////////////////

//USUARIOS: socios, empleados y clientes
$app->group('/user', function (RouteCollectorProxy $group) {

    $group->post('/sign[/]', Controller::class . ':addUser')
        ->add(new UserMiddleware);

    $group->post('/login[/]', Controller::class . ':getToken')
        ->add(new UserMiddleware);

    $group->get('[/]', Controller::class . ':getUsers')     //Obtiene todos
        ->add(new SocioMiddleware);
});

//EMPLEADOS
$app->group('/empleado', function (RouteCollectorProxy $group) {

    $group->post('[/]', Controller::class . ":addEmpleado");    //7-E

    $group->get('/{id}', Controller::class . ":getEmpleado");    //Obtiene uno

    $group->get('[/]', Controller::class . ":getEmpleados");  //Obtiene todos

    $group->delete('/{id}', Controller::class . ":deleteEmpleado");    //7-E

    $group->post('/update/{id}', Controller::class . ":updateEmpleado");  //7-E

    $group->post('/logs[/]', Controller::class . ":getLogsEmpleados");  //7-A
    
    $group->post('/cantidad/{id}[/]', Controller::class . ":getCountOperations_Empleado");  //7-D

    $group->post('/estadistica[/]', Controller::class . ":getEstadistica_Empleados");  //7-D
            
})->add(new SocioMiddleware)
    ->add(new EmpleadoMiddleware);


//PUESTOS
$app->group('/puesto', function (RouteCollectorProxy $group) {

    $group->post('/cantidad[/]', Controller::class . ":getCountOperations_Puestos");  //7-B

    $group->post('-empleado/cantidad[/]', Controller::class . ":getCountOperations_PuestosAndEmpleados");  //7-C

})->add(new SocioMiddleware)
    ->add(new EmpleadoMiddleware);


//CLIENTES
$app->get('/cliente/pedido/{code_pedido}', Controller::class . ":getPedidoForCliente")
    ->add(new ClienteMiddleware);   //Muestra el pedido del cliente

$app->group('/cliente', function (RouteCollectorProxy $group) {
    
    $group->post('[/]', Controller::class . ":addCliente");    //Agrega

    $group->get('/{id}', Controller::class . ":getCliente");    //Obtiene uno

    $group->get('[/]', Controller::class . ":getClientes");    //Obtiene todos

})->add(new MozoMiddleware)
    ->add(new EmpleadoMiddleware);

//PRODUCTOS
$app->group('/producto', function (RouteCollectorProxy $group) {

    $group->get('/{id}', Controller::class . ":getProducto");    //Obtiene uno

    $group->get('[/]', Controller::class . ":getProductos");    //Obtiene todos

    $group->post('[/]', Controller::class . ":addProducto")    //Agrega
        ->add(new EmpleadoMiddleware);

    $group->post('/update/{id}', Controller::class . ":updateProducto")    //Modifica precio
        ->add(new SocioMiddleware);

    $group->delete('/{id}', Controller::class . ":deleteProducto")     //Borra uno
        ->add(new SocioMiddleware);

    $group->post('/popular[/]', Controller::class . ":getPopularProductos");  //8-A

    $group->post('/unpopular[/]', Controller::class . ":getUnpopularProductos");  //8-B
});

//MESAS
$app->group('/mesa', function (RouteCollectorProxy $group) {

    $group->get('/{code}', Controller::class . ":getMesa");    //Obtiene uno
    
    $group->get('[/]', Controller::class . ":getMesas");    //Obtiene todos

    $group->post('[/]', Controller::class . ":addMesa")    //Agrega
        ->add(new SocioMiddleware);

    $group->post('/close/{code}', Controller::class . ":closeMesa") //Cierra una mesa
        ->add(new SocioMiddleware);

    $group->delete('/{code}', Controller::class . ":deleteMesa")   //Borra uno
        ->add(new SocioMiddleware);

    $group->post('/popular[/]', Controller::class . ":getPopularMesas");  //9-A
    
    $group->post('/unpopular[/]', Controller::class . ":getUnpopularMesas");  //9-B

    $group->post('/import-most[/]', Controller::class . ":getMesas_mostImport");  //9-C
    
    $group->post('/import-less[/]', Controller::class . ":getMesas_lessImport");  //9-D
    
    $group->post('/factura-most[/]', Controller::class . ":getMesas_MostFactura");  //9-E

    $group->post('/factura-less[/]', Controller::class . ":getMesas_LessFactura");  //9-F

    $group->post('/{code}[/]', Controller::class . ":acumImportMesa");  //9-G
        
})->add(new MozoMiddleware)
    ->add(new EmpleadoMiddleware);

//PEDIDOS
$app->group('/pedido', function (RouteCollectorProxy $group) {

    $group->get('/{code}', Controller::class . ":getPedido")    //Obtiene uno
        ->add(new SocioMiddleware);
    
    $group->get('[/]', Controller::class . ":getPedidosPendientesBySector");    //Obtiene pedidos pendientes dependiendo sector empleado

    $group->post('[/]', Controller::class . ":addPedido")    //Agrega
        ->add(new MozoMiddleware);

    $group->post('/preparar/{code}-{item_id}', Controller::class . ":prepararPedido");    //Prepara uno

    $group->post('/listo/{code}', Controller::class . ":listoPedido");

    $group->post('/servir/{code}', Controller::class . ":servirPedido")
        ->add(new MozoMiddleware);

    $group->post('/cobrar-excel/{code}', Controller::class . ":cobrarPedido_Excel")
        ->add(new MozoMiddleware);

    $group->post('/cobrar-pdf/{code}', Controller::class . ":cobrarPedido_PDF")
        ->add(new MozoMiddleware);

    $group->post('/cancelar/{code}', Controller::class . ":cancelarPedido")
        ->add(new SocioMiddleware);

    $group->delete('/{code}', Controller::class . ":deletePedido")
        ->add(new SocioMiddleware);

    //FALLA
    $group->post('/demored[/]', Controller::class . ":getDemoredPedidos")  //8-C
        ->add(new SocioMiddleware);

    $group->post('/canceled[/]', Controller::class . ":getCanceledPedidos")  //8-D
        ->add(new SocioMiddleware);

})->add(new EmpleadoMiddleware);

//ENCUESTAS
$app->group('/encuesta', function (RouteCollectorProxy $group) {

    $group->post('[/]', Controller::class . ":addEncuesta")
        ->add(new ClienteMiddleware);

    $group->get('/{code}', Controller::class . ":getEncuesta");  //Obtiene una encuesta determinada

    $group->get('[/]', Controller::class . ":getEncuestas");  //Obtiene todas las encuestas

    $group->post('/best[/]', Controller::class . ":getBestEncuestas");  //9-H

    $group->post('/worst[/]', Controller::class . ":getWorstEncuestas");  //9-I
    
    $group->post('/statistics[/]', Controller::class . ":getStatisticEmpleados");  //Obtiene estadistica empleados
});

//PROMEDIOS MENSUALES
$app->group('/promedio-month', function (RouteCollectorProxy $group) {

    $group->get('/importes[/]', Controller::class . ":getPromedioImportesByMonth");  //Obtiene una encuesta determinada

    $group->get('/mesas[/]', Controller::class . ":getPromedioMesasByMonth");  //Obtiene una encuesta determinada

    $group->get('/empleados[/]', Controller::class . ":getPromedioEmpleadosByMonth");  //Obtiene una encuesta determinada

})->add(new SocioMiddleware);

$app->add(new JsonMiddleware);
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$app->run(); 

?>
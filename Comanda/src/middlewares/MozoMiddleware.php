<?php

namespace App\Middlewares;

use Clases\iToken;
use App\Models\Empleado;
use Slim\Psr7\Response;
use \DateTime;
use \DateTimeZone;
use App\Models\Ingreso_empleado;

class MozoMiddleware{
    public function __invoke($request, $handler){
        $response = new Response();
        $date = new DateTime();
        $date->setTimeZone(new DateTimeZone('America/Argentina/Buenos_Aires'));
        $flag = true;
        
        if(!$payload = iToken::checkToken()) {
            $rta = array("mstatus" => 'ERROR', "message" => "En credenciales");
            $flag = false;
        }

        if(!$empleado = Empleado::where("persona_id", "=", $payload->id)->First()) {
            $rta = array("status" => 'ERROR', "message" => "Permisos Insuficientes");
            $flag = false;
        }

        if($flag && $empleado->puesto_id == 4 || $empleado->puesto_id == 5) {
            $response = $handler->handle($request);
            return $response;
        }
        else{
            $rta = array("status" => 'ERROR', "message" => "Permisos Insuficientes");
        }
        
        $response->getBody()->write(json_encode($rta));
        return $response;
    }
}

<?php

namespace App\Middlewares;

use Clases\iToken;
use App\Models\Empleado;
use Slim\Psr7\Response;
use \DateTime;
use \DateTimeZone;
use App\Models\Ingreso_empleado;

class SocioMiddleware{
    public function __invoke($request, $handler){
        $response = new Response();
        $date = new DateTime();
        $date->setTimeZone(new DateTimeZone('America/Argentina/Buenos_Aires'));
        $flag = true;

        if(!$payload = iToken::checkToken()){
            $rta = array("message" => "[Error] en credenciales");
            $flag = false;
        }
        
        if ($flag && !$empleado = Empleado::where("persona_id", "=", $payload->id)->First()) {
            $rta = array("message" => "[Error] empleado no encontrado");
            $flag = false;
        } 

        if ($flag && $empleado->puesto_id != 5) {
            $rta = array("message" => "[Error] permisos insuficientes");
            $flag = false;
        } 
        
        if($flag){
            $response = $handler->handle($request);
            return $response;
        }

        $response->getBody()->write(json_encode($rta));
        return $response;
    }
}

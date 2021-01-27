<?php

namespace App\Middlewares;

use Clases\iToken;
use Clases\Date;
use App\Models\Empleado;
use App\Models\Ingreso_empleado;
use Slim\Psr7\Response;

class EmpleadoMiddleware{
    public function __invoke($request, $handler){
        $response = new Response();
        
        if ($payload = iToken::checkToken()) {
            $empleado = Empleado::where("persona_id", "=", $payload->id)->First();
            if ($empleado->puesto_id != null && $empleado->estado_id == 1) {
                $ingreso = new Ingreso_empleado;
                $ingreso->empleado_id = $empleado->id;
                $ingreso->ingreso = Date::get_now();
                $ingreso->save();
                
                $response = $handler->handle($request);
                return $response;
            } 
            else { $rta = array("status" => "ERROR", "message" => 'Empleado Inexistente'); }
        } 
        else { $rta = array("status" => 'ERROR', "message" => "En credenciales"); }

        $response->getBody()->write(json_encode($rta));
        return $response;
    }
}

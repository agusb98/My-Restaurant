<?php

namespace App\Middlewares;

use Clases\iToken;
use Slim\Psr7\Response;
use App\Controllers\ClienteController;

class ClienteMiddleware{
    public function __invoke($request, $handler){
        $response = new Response();
        $flag = true;

        if (!$payload = iToken::checkToken()) {
            $flag = false;
            $rta = array("status" => "ERROR", "message" => 'Permisos Insuficientes');
        }
        if ($flag && !ClienteController::getOneByPersonaId($payload->persona_id)) {
            $flag = false;
            $rta = array("status" => "ERROR", "message" => 'Cliente Inexistente');
        } 
        if($flag){
            $response = $handler->handle($request);
            return $response;
        }

        $response->getBody()->write(json_encode($rta));
        return $response;
    }
}

<?php

namespace App\Middlewares;

use Slim\Psr7\Response;

class FechaMiddleware{
    public function __invoke($request, $handler){
        $response = new Response();
        $body = $request->getParsedBody();
        $flag = true;

        if(!$body['fecha_inicial'] ?? ""){
            $flag = false;
            $rta = array("message" => "[ERROR] Debe ingresar fecha en particular");
        }

        if($flag){
            $response = $handler->handle($request);
            return $response;
        }

        $response->getBody()->write(json_encode($rta));
        return $response;
    }
}

<?php

namespace App\Middlewares;

use Clases\iToken;
use App\Models\User;
use Slim\Psr7\Response;

class UserMiddleware{
    public function __invoke($request, $handler){
        $flag = true;
        $response = new Response();
        $body = $request->getParsedBody();

        if(!$email = strtoupper($body["email"] ?? '')){
            $rta = array("status" => "ERROR", "message" => "Ingrese Email");
            $flag = false;
        }

        if($flag && !$password = $body["password"] ?? ''){
            $rta = array("status" => "ERROR", "message" => "Ingrese Password");
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

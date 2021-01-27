<?php
namespace App\Controllers;

use App\Models\Empleado;
use App\Models\User;
use App\Models\Sector;
use App\Models\Puesto;
use Clases\iToken;

class SocioController{/*
    public function add($request, $response, $args) {
        $dataInput = $request->getParsedBody();
        $email = $dataInput['email'] ?? "";
        $password = $dataInput['password'] ?? "";

        if($user = User::where("email", "=", $email)->First()){
            if(iToken::ComparePasswordHash($password, $user->password)){
                if(!$empleado = Empleado::where("persona_id", "=", $user->persona_id)->First()){
                    $empleado =  new Empleado;
                    $empleado->persona_id = $user->persona_id;
                    $empleado->sector_id = 5;
                    $empleado->puesto_id = 5;
                    $empleado->estado_id = 1;
                    
                    $empleado->save();
                    $rta = array("message" => "Agregado con Exito");
                }
                else{ $rta = array("message" => "[ERROR] Empleado ya existente"); }
            }
            else{ $rta = array("message" => "[ERROR] Contraseña invalida"); }
        }
        else{ $rta = array("message" => "[ERROR] Email inexistente"); }

        $response->getBody()->write(json_encode($rta));
        return $response;
    }*/
}

?>
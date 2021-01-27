<?php

namespace App\Controllers;

use App\Models\Cliente;
use Clases\iToken;

class ClienteController{
    public function add($persona_id){
        $obj =  new Cliente;
        $obj->persona_id = $persona_id;
        return $obj->save();
    }

    public function getAll(){
        return Cliente::select('clientes.id', 'personas.id as persona_id', 
                               'personas.nombre', 'personas.apellido',
                                'clientes.created_at', 'clientes.updated_at')
                        ->join('personas', 'personas.id', '=', 'clientes.persona_id')
                        ->get();
    }

    public function getOne($id){
        foreach (ClienteController::getAll() as $cliente) {
            if($cliente->id == $id){
                return $cliente;
            }
        }
        return null;
    }

    public function getOneByPersonaId($id){
        foreach (ClienteController::getAll() as $cliente) {
            if($cliente->persona_id == $id){
                unset($cliente->persona_id);
                return $cliente;
            }
        }
        return null;
    }

    public function findByPersonaId($id){
        if($cliente = Cliente::where('clientes.persona_id', '=', $id)->first()){
            return ClienteController::getOne($cliente->id);
        }
    }

    public function checkAdd($email, $password){
        if(!$user = UserController::findByEmail($email)){
            return array("status" => false, "message" => "Email Inexistente");
        }
        if(ClienteController::findByPersonaId($user->persona_id)){
            return array("status" => false, "message" => "Cliente previamente Agregado");
        }
        if(!iToken::ComparePasswordHash($password, $user->password)){
            return array("status" => false, "message" => "Password Incorrecto");
        }
        return array("status" => true);
    }
}

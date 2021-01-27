<?php

namespace App\Controllers;

use Clases\iToken;
use App\Models\User;

class UserController{
    public function findByEmail($email){
        return User::where("email", "=", $email)->first();
    }

    public function getAll(){
        return User::get();
    }

    public function add($email, $password, $nombre, $apellido){
        $persona = PersonaController::Add($nombre, $apellido);
        $user = new User;
        $user->email = $email;
        $user->persona_id = $persona->id;
        $user->password = password_hash($password, PASSWORD_BCRYPT);
        return $user->save();
    }

    public function checkAdd($email, $nombre, $apellido){
        if (!strlen($nombre) > 2) {
            return array("status" => false, "message" => "El nombre debe contener mas de 2 caracteres");
        }
        if (!strlen($apellido) > 2) {
            return array("status" => false, "message" => "El apellido debe contener mas de 2 caracteres");
        }
        if (UserController::findByEmail($email)) {
            return array("status" => false, "message" => "Email repetido");
        }
        return array('status' => true);
    }

    public function getToken($email, $pass){
        if (!$user = UserController::findByEmail($email)) {
            return null;
        }
        if (!iToken::ComparePasswordHash($pass, $user->password)) {
            return null;
        }
        return iToken::createToken($user);
    }
}
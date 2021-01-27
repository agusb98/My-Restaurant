<?php

namespace Clases;

require_once './vendor/autoload.php';

use Firebase\JWT\JWT;
use Throwable;

class iToken{    
    static function PasswordHash($password) {
        if (isset($password))
            return password_hash($password, PASSWORD_DEFAULT);
    }

    static function ComparePasswordHash($password, $hash) {
        if (isset($password) && isset($hash)) {
            return password_verify($password, $hash);
        } 
        else { return false; }
    }

    static function createToken($payload) {
        return JWT::encode($payload, "COMANDA");
    }

    static function checkToken($tokenKey = "COMANDA") {
        $token = $_SERVER['HTTP_TOKEN'] ?? null;
        
        try {
            if (isset($token) && isset($tokenKey)) {
                $payload = JWT::decode($token, $tokenKey, array("HS256"));
                
                if (isset($payload)) { return $payload; } 
                else { return null; }
            }
        } 
        catch (Throwable $a) { return null; }
        return null;
    }

    static function decodeToken($token, $tokenKey = "COMANDA") {
        try {
            if (isset($token) && isset($tokenKey)) {
                $payload = JWT::decode($token, $tokenKey, array("HS256"));
                if (isset($payload)) {
                    return $payload;
                } 
                else { return null; }
            }
        } 
        catch (Throwable $a) { return null; }
        return null;
    }
}

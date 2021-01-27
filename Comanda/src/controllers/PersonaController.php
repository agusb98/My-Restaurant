<?php

namespace App\Controllers;

use App\Models\Persona;

class PersonaController{
    public function add($nombre, $apellido){
        $persona = new Persona;
        $persona->nombre = $nombre;
        $persona->apellido = $apellido;
        $persona->save();
        return $persona;
    }
}
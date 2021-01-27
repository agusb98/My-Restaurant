<?php
namespace Clases;

class Imagen{   
    public function SaveCliente(&$name, &$path){
        Imagen::setName($name, date("Gis"));
        $flag = Imagen:: Move($path, "./img/cliente/" . $name);
        Imagen::setPath($path, "./img/cliente/" . $name);
        return $flag;
    }

    public function setName(&$name, $newName) {
        $name = $newName . ".jpeg";
    }

    public function setPath(&$path, $newPath) {
        $path = $newPath;
    }

    public function Open($path) {
        header('Content-type: image/jpeg');
        readFile($path);
    }

    public function Move($origen, $destino) {
        return move_uploaded_file($origen, $destino);
    }
}
?>
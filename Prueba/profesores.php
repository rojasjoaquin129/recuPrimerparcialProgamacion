<?php

use App\Models\Materia;

require_once './ManejoArchivos.php';
class Profesores extends ManejoArchivos
{
    public $_legajo;
    public $_nombre;

    public function __construct($legajo,$nombre) {
        $this->_legajo = $legajo;
        $this->_nombre = $nombre;
        
       
    }
    public static function legajoID(array $array = null){
        if($array !== null){
            $legajo = 1000;
            foreach ($array as $item) {
                if($item->_legajo > $legajo){
                    $legajo = $item->_legajo;
                }
            }
        }
        return $legajo + 1;
    }

    public function verificarProfesores(array $array = null){
        $loginUser = false;

        if($array !== null){
            foreach ($array as $item ) {

                if($item->_legajo === $this->_legajo){
                    $loginUser = true;
                }
            }
        }else{
            echo "profesor repetido";
            //throw new Exception('<br/>Array null.<br/>');
        }
        return $loginUser;
    }
    public static function TraerJsonProfesores($ruta)
    {
        try
        {
            $lista=parent::traerJson($ruta);
            $Array=[];
            foreach ($lista as $dato) 
            {
                $nuevo=new Profesores($dato->_legajo ,$dato->_nombre);
                array_push($Array,$nuevo);
            }
        }catch(Throwable $e)
        {
            echo "Error al agregar un vehiculo";
        }
        return $Array;


    }

    public static function guardarJsonProfesores($ruta, $array)
    {
        parent::guardarJson($ruta,$array);     
    }


}    
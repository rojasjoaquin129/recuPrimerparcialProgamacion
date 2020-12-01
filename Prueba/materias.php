<?php


require_once './ManejoArchivos.php';
class Materias extends ManejoArchivos
{
    public $_nombre;
    public $_id;
    public $_cuatrimestre;
    public function __construct($id,$nombre,$cuatrimestre) {
        $this->_id = $id;
        $this->_nombre = $nombre;
        $this->_cuatrimestre = $cuatrimestre;
       
    }

    public function __get($name){ return $this->$name; }
    public function __set($name, $value){ $this->$name = $value; }
    public function __toString(){

        $datos = '';
        $datos .= 'DATOS DE LA MATERIA:</br>';
        $datos .= 'ID: ' . $this->_id  . '</br>';
        $datos .= 'NOMBRE: ' . $this->_nombre  . '</br>';
        $datos .= 'CUATRIMESTRE: ' . $this->_cuatrimestre  . '</br>';

        return $datos;
    }


    public static function MateriaID(array $array = null){
        if($array !== null){
            $id = 0;
            foreach ($array as $item) {
                if($item->_id > $id){
                    $id = $item->_id;
                }
            }
        }
        $id=$id+1;
        return $id;
    }
    public function verificarMateria(array $array = null){
        $loginUser = false;

        if($array !== null){
            foreach ($array as $patente ) {

                if($patente->_patente === $this->_patente){
                    $loginUser = true;
                }
            }
        }else{
            throw new Exception('<br/>Array null.<br/>');
        }
        return $loginUser;
    }
    public static function TraerJsonMaterias($ruta)
    {
        try
        {
            $lista=parent::traerJson($ruta);
            $Array=[];
            foreach ($lista as $dato) 
            {
                $nuevo=new Materias($dato->_id ,$dato->_nombre,$dato->_cuatrimestre);
                array_push($Array,$nuevo);
            }
        }catch(Throwable $e)
        {
            echo "Error al agregar un vehiculo";
        }
        return $Array;


    }

    public static function guardarJsonMaterias($ruta, $array)
    {
        parent::guardarJson($ruta,$array);     
    }

}
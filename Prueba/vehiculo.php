<?php

require_once './ManejoArchivos.php';

class Vehiculo extends ManejoArchivos
{
    public $_patente;
    public $_marca;
    public $_modelo;
    public $_precio;

    public function __construct($patente,$marca,$modelo,$precio) {
        $this->_patente = $patente;
        $this->_marca = $marca;
        $this->_modelo = $modelo;
        $this->_precio = $precio;
    }

    public function __get($name){ return $this->$name; }
    public function __set($name, $value){ $this->$name = $value; }
    public function __toString(){

        $datos = '';
        $datos .= 'DATOS DEL VEHICULO:</br>';
        $datos .= 'PATENTE: ' . $this->_patente  . '</br>';
        $datos .= 'MODELO: ' . $this->_modelo  . '</br>';
        $datos .= 'MARCA: ' . $this->_marca  . '</br>';
        $datos .= 'PRECIO: ' . $this->_precio  . '</br>';

        return $datos;
    }

    public function verificarPatente(array $array = null){
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
    public static function TraerJsonVehiculos($ruta)
    {
        try
        {
            $lista=parent::traerJson($ruta);
            $vehiculosArray=[];
            foreach ($lista as $dato) 
            {
                $nuevo=new Vehiculo($dato->_patente ,$dato->_marca,$dato->_modelo,$dato->_precio);
                array_push($vehiculosArray,$nuevo);
            }
        }catch(Throwable $e)
        {
            echo "Error al agregar un vehiculo";
        }
        return $vehiculosArray;


    }

    public static function guardarJsonVehiculos($ruta, $array)
    {
        parent::guardarJson($ruta,$array);     
    }


}

<?php

require_once './ManejoArchivos.php';

class Usuario extends ManejoArchivos{
    public $_email;
    public $_password;
    public $_namefoto;
    public static $pathJSON = './usuariosjson.txt';

    public function __construct($email, $password,$foto) {
        $this->_email = $email;
        $this->_password = $password;
        $this->_namefoto=$foto;
    }

    public function __get($name){ return $this->$name; }
    public function __set($name, $value){ $this->$name = $value; }
    public function __toString(){
        return $this->_email . '*' .$this->_tipoUsuario . '*' . $this->_password;
    }


    //  ----------------------------------------------------------------
    //----------------------------------------------------------------
    //!! VERIFICACION USUARIO
    public function verificarUsuario(array $array = null){ 
        $loginUser=false;

        if($array !== null){
            foreach ($array as $user ) {
                
                if($user->_password == $this->_password && $user->_email == $this->_email){
                    $loginUser = true;
                }
            }
            
        }else{
            echo "aca problema";
        }
        return $loginUser;
    }


    //----------------------------------------------------------------
    //----------------------------------------------------------------
    //JSON
    public static function SaveUsuarioJSON(array $arrayObj = null){
        try {
            echo parent::SaveJSON(Usuario::$pathJSON,$arrayObj);
        } catch (\Throwable $e) {
            throw new Exception($e->getMessage());
        }
    }

    public static function ReadUsuarioJSON(){
        try {
            //Pasamanos...
            $listaFromArchivoJSON = parent::ReadJSON(Usuario::$pathJSON);
            $arrayUsuario = [];

            foreach ($listaFromArchivoJSON as $dato) {
                $nuevoUsuario = new Usuario($dato->_email,$dato->_password,$dato->_namefoto);
                array_push($arrayUsuario,$nuevoUsuario);
            }

        } catch (\Throwable $e) {
            echo "Error al LEER LISTA DE USUARIOS";
        }
        
        return $arrayUsuario;
    }

    public function EmailUnico(array $array = null, $primerdato=false){
        $emailRepetido = false;
        if( $primerdato ===false || $array !== null){
            foreach ($array as $item) {
                if($item->_email === $this->_email){
                    $emailRepetido = true;
                break;
                }
            }
        }
    
        return $emailRepetido;
    }

    /**
     * POR SI LAS MOSCAS!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!1
     */
    public static function autoID(array $array = null){
        if($array !== null){
            $id = 0;
            foreach ($array as $item) {
                if($item->_id > $id){
                    $id = $item->_id;
                }
            }
        }
        return $id + 1;
    }




    public static function TraerJsonUsuario($ruta)
    {
        try
        {
            $lista=(array) parent::traerJson($ruta);
            $usuariosArray=[];
            foreach ($lista as $dato) 
            {
                $nuevo=new Usuario($dato->_email ,$dato->_password,$dato->_namefoto);
                array_push($usuariosArray,$nuevo);
            }
        }catch(Throwable $e)
        {
            echo "Error al LEER LISTA DE USUARIOS";
        }
        return $usuariosArray;


    }

    public static function guardarJsonUsuario($ruta, $array)
    {
        parent::guardarJson($ruta,$array);     
    }

    public static function generateUniqueCode() {
        $strToShuffle = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr( str_shuffle( $strToShuffle ), 0, 5 );
    }

    public function esUnico($array){
        $unico = true;
        foreach($array as $item){
            if($item->_email == $this->_email ){
                $unico = false;
            }
        }
        return $unico;
    }

   

}
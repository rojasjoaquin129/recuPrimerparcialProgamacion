<?php

require_once '../Prueba/usuario.php';
require_once '../Prueba/materias.php';
require_once '../Prueba/profesores.php';

$method = $_SERVER['REQUEST_METHOD']??'';
$path = $_SERVER['PATH_INFO'] ?? '';
$token = $_SERVER['HTTP_TOKEN'] ?? '';
require __DIR__.'/vendor/autoload.php';

use \Firebase\JWT\JWT;
$key = 'primerparcial';
$status = 'no logueado';
$logeado=false;
if($token!=='')
{
    try{
        $decoded = JWT::decode($token, $key, array('HS256'));
        //$tokenTipo = $decoded->data->tipo ?? '';
        $tokenEmail = $decoded->data->email ?? '';
        $logeado=true;
        echo "esta logeado".PHP_EOL;
    }catch(Throwable $th){
        //echo 'No logueado<br/>';
        
    }
}



$peticion=explode('/',$path);
switch($peticion[1])
{
    
    case 'usuario':
        registro($method);
    break;
    case 'login':
        login($method,$key);
    break;
    case 'materia':
        if($logeado)
        {
            Materia($method);
        }else
        {
            echo"operacion invalida no esta logiado".PHP_EOL;
           
        }
        break;

    case 'profesor':
        if($logeado)
        {
            Profesor($method);
        }else
        {
            echo"operacion invalida no esta logiado".PHP_EOL;
        }
        break;
    case 'value':
        # code...
        break;
    
        
    //break;
}


function registro($method)
{
    if($method==='POST')
    {
        $email = $_POST['email'] ?? '';
        $clave = $_POST['clave'] ?? '';
        $extArray=explode('.',$_FILES["imagen"]['name']??'');
        $foto=Usuario::generateUniqueCode().'.'.$extArray[1];
        $lista=array();
        $usuario = new Usuario($email,hash('sha256', $clave),$foto);
                //var_dump(hash('sha256', $clave));
                
        $lista = (array)Usuario::TraerJsonUsuario('user.txt');
        $usuariorepetido=$usuario->esUnico($lista);
        if($usuariorepetido)
        {  
            if(guardarImagen($extArray))
            {
                array_push($lista,$usuario);
                Usuario::guardarJsonUsuario("user.txt",$lista); 
                echo "El usuario fue registrado con exito "; 
            }
                
                 
                
        }else
        {
            echo "el usuario ya esta registrado";
        }
    }

}
function login($method,$key)
{
    if($method==='POST')
    {
        $email = $_POST['email'] ?? '';
        $clave = $_POST['clave'] ?? '';
        $usuario=new Usuario($email,hash('sha256',$clave),'');
        var_dump( hash('sha256',$clave));
        $lista = (array)Usuario::TraerJsonUsuario('user.txt');
        $usuariologeado=$usuario->verificarUsuario($lista);

        if($usuariologeado)
        {
            $payload = ['email' => $email ] ; 
            $jwt = JWT::encode($payload, $key);
            echo "Logueando...";
            print_r($jwt);
    
        }else{
            echo 'No se pudo logear';
        }
    }else
    {
        echo "no uso post";
    }
}

function Materia($method)
{
    if($method==='POST')
    {
        $listaIngresoJSON = (array)Materias::TraerJsonMaterias('materias.txt');
        $nombre = $_POST['nombre'] ?? '';
        $cuatrimestre=$_POST['cuatrimestre'] ?? '';
        $nuevoIngreso = new Materias(Materias::MateriaID($listaIngresoJSON), $nombre,$cuatrimestre);
        array_push( $listaIngresoJSON, $nuevoIngreso);
        Materias::guardarJsonMaterias( 'materias.txt',$listaIngresoJSON );
        echo "Materia agregada";
    }else if($method==='GET')
    {
        $listaIngresoJSON = (array)Materias::TraerJsonMaterias('materias.txt');
        
        echo json_encode($listaIngresoJSON);
    }
    
    
    
}


function Profesor($method)
{
    if($method==='POST')
    {
        $listaIngresoJSON = (array)Profesores::TraerJsonProfesores('profesores.txt');
        $legajo = $_POST['legajo'] ?? '';
        $nombre=$_POST['nombre'] ?? '';

        $nuevoIngreso = new Profesores($legajo, $nombre);
        $exist=$nuevoIngreso->verificarProfesores($listaIngresoJSON);
        if(!$exist)
        {
            array_push( $listaIngresoJSON, $nuevoIngreso);
            Profesores::guardarJsonProfesores( 'profesores.txt',$listaIngresoJSON );
            echo "profesor agregado";
        }else
        {
            echo "profesor ya ingresado";
        }
       
    }else if($method==='GET')
    {
        $listaIngresoJSON = (array)Profesores::TraerJsonProfesores('profesores.txt');
        
        echo json_encode($listaIngresoJSON);
    }
    
    
}

function busqueda($method,$peticion)
{
    if($method==='POST')
    {
        $listaIngresoJSON = (array)Vehiculo::TraerJsonVehiculos('vehiculos.txt');
        $sor=true;
        foreach ($listaIngresoJSON as $dato) 
        {
            if($dato->_marca===$peticion[2] || $dato->_patente===$peticion[2] || $dato->_modelo===$peticion[2]  )
            {
                echo $dato.PHP_EOL;
                $sor=false;
            }

        }
        if($sor)
        {
            echo "no existe ".$peticion[2].PHP_EOL;
        }
    }
}




function guardarImagen($extArray)
{
    //$aleatorio=rand(1000,10000);
    $flag=false;
    if($extArray!==null)
    {

        if($extArray[1]=="jpg"|| $extArray[1]=="png")
        {

            if($_FILES['imagen']['size']<= 3500000)
            {
        
            $origen=$_FILES['imagen']["tmp_name"]??''; // saco el origen del archivo 
            
            $destino="img/".$extArray[0].".".$extArray[1]; // cambio el nombre del archivo
            //$destino="MarcaDeAgua/".$extArray[0].".".$extArray[1]; 
            $subido=move_uploaded_file($origen,$destino);//lo mueve al archivo  ;
            //insertarMarcaDeAguaEnMedio($destino);
            $flag=true;
            //echo "Se subio la imagen ";
            }
            else
            {
            echo "La imagen pesa mas de 3,5 mb";
            }
        
        }
        else
        {
        echo "EL archivo no es una imagen";
        }
    }
    else{
        echo "no existe imagen";
    }
    
    return $flag;
}



 


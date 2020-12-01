<?php
class ManejoArchivos
{
    static function guardarJson($ruta ,$array)
    {
        //$array=array();
        //$array=Usuario::traerJson($ruta);
        if($array!=null)
        {
            $ar=fopen("./".$ruta,"w");
            //array_push($array,$objeto);
            fwrite($ar,json_encode($array,JSON_PRETTY_PRINT));
            fclose($ar);
        }
        else
        {
            echo "mal traer";
        }


    }
        //traer archivo con Json
    static function traerJson($ruta)
    {
        $lista=array();
        $final="./".$ruta;
        if(file_exists($final))
        {        
            $r=fopen($final,"r");
            $fSize = filesize($final);

                if ($fSize > 0) {
                    $fread = fread($r,$fSize);
                } else {
                    $fread = '{}';
                }
            $lista=json_decode($fread);
            fclose($r);
            
        }
        else 
        {
            echo "el archivo no existe";
        }

        return $lista;
    }   
}
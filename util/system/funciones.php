<?php


class Funciones{

    public static function Logs($nombre_archivo, $ubicacion, $descripcion) {
        $carpeta = $ubicacion.date('Y').'/'.date('m').'/'.date('d').'/';

        if(!file_exists(($ubicacion.date('Y').'/'.date('m').'/'.date('d')))){
            mkdir($carpeta, 0755, true);
        }
        
        $mi_archivo = fopen($carpeta.$nombre_archivo.'.txt', "a") or die("Archivo inaccesible!");
        fwrite($mi_archivo,"Fecha: ". date(DATE_RFC822).'>>>'.$descripcion."\r\n");
        fclose($mi_archivo);
      
    }

    public static function ObtenerIp() {
        $ipaddress = '';
    
        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ipaddress = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ipaddress = getenv('HTTP_FORWARDED');
        } elseif (getenv('REMOTE_ADDR')) {
            $ipaddress = getenv('REMOTE_ADDR');
        }else{
            $ipaddress = 'UNKNOWN';
            
        }

    
        return $ipaddress;
       
    }

    public static function ObtenerNavegador($useragent) {
        if (preg_match('/MSIE ([0-9]+\.[0-9]+)/', $useragent, $matches)) {
            $navegador = 'Internet Explorer';
            $version = $matches[1];
        }elseif (preg_match('/Firefox\/([0-9]+\.[0-9]+)/', $useragent, $matches)) {
                $navegador = 'Firefox';
                $version = $matches[1];
        }elseif (preg_match('/Opera\/([0-9]+\.[0-9]+)/', $useragent, $matches)) {
                $navegador = 'Opera';
                $version = $matches[1];
        }elseif (preg_match('/Chrome\/([0-9]+\.[0-9]+)/', $useragent, $matches)) {
                $navegador = 'Chrome';
                $version = $matches[1];
        }elseif (preg_match('/Safari\/([0-9]+\.[0-9]+)/', $useragent, $matches)) {
                $navegador = 'Safari';
                $version = $matches[1];
        }else {
            // Agregar más condicionales para detectar otros navegadores aquí
            // Ejemplo: elseif (preg_match('/Firefox\/([0-9]+\.[0-9]+)/', $useragent, $matches)) {
            //     $navegador = 'Firefox';
            //     $version = $matches[1];
            // }
            // Más condiciones según sea necesario
        }
    
        if (isset($navegador)) {
            return "$navegador $version";
        } else {
            return 'Navegador desconocido';
        }
    }
    
    
}

// Funciones::Logs("test", "../logs/", "Este es un mensaje de prueba");
//  echo Funciones::ObtenerIP();
//  echo Funciones::ObtenerNavegador($_SERVER['HTTP_USER_AGENT']);
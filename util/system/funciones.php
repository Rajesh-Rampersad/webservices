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
}

// Funciones::Logs("test", "../logs/", "Este es un mensaje de prueba");
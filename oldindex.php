<?php

include("util/system/funciones.php");
include("util/system/session.php");
include("util/system/conexion.php");

$conexion = new Conexion('util/logs/');
$conexion->conectar();

$session = new Session();

#Obterner los parametros del sistema

$resultado_parametros = $conexion->ejecutarConsulta("
    SELECT * FROM parametros

");


$parametro = array();

foreach($resultado_parametros as $fila){
    $parametro[trim($fila['parametro'])] = trim($fila['valor']);

}

##############################################################


#Si tenemos una sesion abierta, incluimos los modulos del sistema

if ($session->checkSession() ) {

    # Validacion del tiempo de session 

    if (isset($_SESSION['fechaSesion'])) {
        
        
        // $fechaGuardada = $_SESSION['fechaSesion'];
        
        // $ahora = date('Y-m-d H:i:s');
      
        // $tiempo_transcurrido = (strtotime($ahora)- strtotime($fechaGuardada));
      

        // if($tiempo_transcurrido >= ($parametro['timeout'] * 30)){

        //     $session->endSession();
        //     header("Refresh:0");
        //     exit();
        // }else {
        //     $_SESSION['fechaSesion'] = date('Y-m-d H:i:s');
        // }

     
    }else{
        $_SESSION['fechaSesion'] = date('Y-m-d H:i:s');
    }
    print_r($_SESSION['fechaSesion']);

    //URL POR DEFECTO
    $pagina = $parametro['paginadefecto'];

    if(isset($_GET['pagina']) && !empty($_GET['pagina']) ){
        $pagina = $_GET['pagina'];
        
       
    }
  
    // Traer los permisos de la pagina para el usuario

    $resultado_permisos = $conexion->ejecutarConsulta("
    SELECT a.idmenu, b.nombre, b.ventana, b.framework
    FROM usuarios_accesos AS a
    INNER JOIN menu AS b ON (a.idmenu = b.idmenu)
    WHERE a.usuario = '".$_SESSION['usuario']."'
    AND b.ventana = '".$pagina."'
    AND b.estado = 'ACTIVO'
");


$varAcceso = array();

foreach ($resultado_permisos as $fila) {

    
    $varAcceso['idmenu'] = $fila['idmenu'];
    $varAcceso['nombre'] = $fila['nombre'];
    $varAcceso['ventena'] = $fila['ventana'];
    $varAcceso['framework'] = explode(",",$fila['framework']);
}

// En caso del que usuario no tenga permiso, verificaremos en cual menu lo tiene

if (count($varAcceso) == 0 ){
    $flagAccPagina = false;
   

    $resultadoAccpagina = $conexion->ejecutarConsulta("
        SELECT a.ventana
        FROM menu AS a
        INNER JOIN usuarios_accesos AS b  ON (a.idmenu = b.idmenu)
        WHERE b.usuario = '".$_SESSION['usuario']."'
        AND a.estado = 'ACTIVO'
        AND a.es_menu = 'NO'
        ORDER BY a.idpadre, a.orden LIMIT 1
    ");
    print_r( $resultadoAccpagina);
    foreach ($resultadoAccpagina as $fila) {
        $pagina = $fila['ventana'];
        $flagAccPagina = true;
    }

    if ($flagAccPagina == false) {
        $session->endSession();
        echo"Estimado usuario, usted no tiene modulos asignados en el aplicativo, por favor conctatar con el administrador del sistema.";
            header("Refresh:10");
            exit();
    }else{
            $resultadoVeri = $conexion->ejecutarConsulta("
            SELECT a.idmenu, b.nombre, b.ventana, b.framework
            FROM usuarios_accesos AS a
            INNER JOIN menu AS b ON (a.idmenu = b.idmenu)
            WHERE a.usuario = '".$_SESSION['usuario']."'
            AND b.ventana = '".$pagina."'
            AND b.estado = 'ACTIVO'
        ");
        
            $varAcceso = array();
            
            foreach ($resultadoVeri as $fila) {
                $varAcceso['idmenu'] = $fila['idmenu'];
                $varAcceso['nombre'] = $fila['nombre'];
                $varAcceso['ventena'] = $fila['ventana'];
                $varAcceso['framework'] = explode(",",$fila['framework']);
            }
    }
}

if( count($varAcceso) > 0 ){
    $ip = Funciones::ObtenerIp();
    $navegador = Funciones::ObtenerNavegador($_SERVER['HTTP_USER_AGENT']);
    $conexion->ejecutarConsulta("
    INSERT INTO log_menu (ip, navegador, usuario, idmenu, nombre, ventana, fecha )
    VALUES('$ip','$navegador','$_SESSION[usuario]', '$varAcceso[idmenu]', '$varAcceso[nombre]', '$varAcceso[ventana]', NOW() 
    )
    ");
}



    #  se usa para hacer test  en pantalla print_r($_SESSION['fechaSesion']);
}else{
    #Si no hay una sesion iniciada, mostramos el login del sistema
    include('inc/login.php');
}
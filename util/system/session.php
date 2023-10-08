<?php

 class Session{


    private $codSession = "pedidos";

    public function __construct(){
       session_name($this->codSession);
       session_start();
    }

    public function checkSession(){
        $ckeck = false;

        if(
            isset($_SESSION['usuario']) && !empty($_SESSION['usuario'])
        ){
            $ckeck = true;
        }

        return $ckeck;
    }

    public function createSession(array $datos){
       $_SESSION = array();

       $_SESSION['usuario'] = $datos['usuario'];
       $_SESSION['nombre'] = $datos['nombre'];
    }

    public function endSession(){
        $_SESSION = array();

        if(ini_get("session.use_cookies")){
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"],$params["secure"],
            $params["httponly"]);
        }
        session_destroy();
    }
 }


//  $session = new Session();

//  if(!$session->checkSession()){
//     $session->createSession(
//         array(
//             'usuario' => 'admin',
//             'nombre'  => 'Administrador'
//         )
//     );
//  }
// $session->endSession();

//  print_r($_SESSION);
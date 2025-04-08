<?php

namespace Controllers;

use Model\Usuario;
use MVC\Router;
use Classes\Email; // Import the Email class

class LoginController
{
    public static function login(Router $router)
    {

        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin(); //Valida el login
            
            if(empty($alertas)){
                //Verifica si el usuario existe
                $usuario = Usuario::where('email', $auth->email);
                if(!$usuario || !$usuario->confirmado){
                    Usuario::setAlerta('error', 'El usuario no existe o no ha confirmado su cuenta');
                    $alertas = Usuario::getAlertas(); // Update the $alertas variable
            }else{
                //Verifica el password
                if(!password_verify($auth->password, $usuario->password)){
                    Usuario::setAlerta('error', 'El password es incorrecto');
                    $alertas = Usuario::getAlertas(); // Update the $alertas variable
                }else{
                    //Autenticar al usuario
                    session_start();
                    $_SESSION['id'] = $usuario->id;
                    $_SESSION['nombre'] = $usuario->nombre;
                    $_SESSION['email'] = $usuario->email;
                    $_SESSION['login'] = true;
                    header('Location: /proyectos');
                }
            }
        }
    }

    $aleras = Usuario::getAlertas(); //Obtiene las alertas

        //Render a la vista
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas
        ]);
    }

    public static function logout(Router $router)
    {
        echo 'desde el logout';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }
    }

    public static function crear(Router $router)
    {
        $alertas = [];
        $usuario = new Usuario;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST); //Sincroniza los datos del formulario
            $alertas = $usuario->validarNuevaCuenta(); //Valida los datos del formulario


            if (empty($aleras)) {
                $existeUsuario = Usuario::where('email', $usuario->email); //Verifica si el email ya existe
                if ($existeUsuario) {
                    Usuario::setAlerta('error', 'El usuario ya existe');
                    $alertas = Usuario::getAlertas();
                } else {
                    //Hashear el password
                    $usuario->hashPassword();
                    //Eliminar password2
                    unset($ususario->password2);
                    //Generar un token
                    $usuario->crearToken();
                    //Crear el usuario
                    $resultado = $usuario->guardar(); //Guarda el usuario en la base de datos
                    if ($resultado) {
                        //Enviar el email de confirmacion
                        $email  = new Email($usuario->email, $usuario->nombre, $usuario->token);
                        $email->enviarConfirmacion();
                        header('Location: /mensaje');
                    }
                }
            }
        }

        //Render a la vista
        $router->render('auth/crear', [
            'titulo' => 'Crear Cuenta',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function olvide(Router $router)
    {
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail(); //Valida el email
            if(empty($alertas)){
                $usuario = Usuario::where('email', $usuario->email); //Busca el usuario por email
                if($usuario && $usuario->confirmado === '1'){
                    //Generar un nuevo token
                    $usuario->crearToken();
                    unset($usuario->password2);
                    $usuario->guardar(); //Guarda el nuevo token en la base de datos

                    //Enviar el email de reestablecimiento de contraseña
                    $email  = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();
                    Usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu email');
                }else{
                    Usuario::setAlerta('error', 'El usuario no existe o no ha confirmado su cuenta');
                }
            }
        }

        $alertas = Usuario::getAlertas(); //Obtiene las alertas

        //Muestra la vista
        $router->render('auth/olvide', [
            'titulo' => 'Olvidé Contraseña',
            'alertas' => $alertas
        ]);
    }

    public static function reestablecer(Router $router)
    {
        $token = s($_GET['token']);
        $mostrar = true;
        $alertas = [];
        if (!$token) {
            header('Location:/');
        }
        $usuario = Usuario::where('token', $token);
        if (empty($usuario)) {
            Usuario::setAlerta('error', 'Token no válido');
            $mostrar = false;
        }

        $alertas = Usuario::getAlertas();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST); //Sincroniza los datos del formulario
            $usuario = $usuario->validarPassword(); //Valida el password
            if (empty($alertas)) {
                //Hashear el password
                $usuario->hashPassword();
                //Eliminar password2 y token
                unset($usuario->password2);
                $usuario->token = null;
                $usuario->guardar(); //Guarda el nuevo password en la base de datos
                header('Location: /');
            }
        }

        //Muestra la vista
        $router->render('auth/reestablecer', [
            'titulo' => 'Nueva Contraseña',
            'alertas' => $alertas,
            'mostrar' => $mostrar
        ]);
    }

    public static function mensaje(Router $router)
    {
        $router->render('auth/mensaje', [
            'titulo' => 'Cuenta Creada con Éxito'
        ]);
    }

    public static function confirmar(Router $router)
    {

        $token = s($_GET['token']);

        if (!$token) {
            header('Location:/');
        }

        //Encontrar al usuario por token
        $usuario = Usuario::where('token', $token);
        if (empty($usuario)) {
            Usuario::setAlerta('error', 'Token no válido');
        } else {
            // Ensure $usuario->confirmado and $usuario->token are properly set
            $usuario->confirmado = 1; // Set confirmed status
            $usuario->token = null;   // Clear the token
            unset($usuario->password2);
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta confirmada correctamente');
        }

        $alertas = Usuario::getAlertas();


        $router->render('auth/confirmar', [
            'titulo' => 'Confirmar cuenta',
            'alertas' => $alertas
        ]);
    }
}

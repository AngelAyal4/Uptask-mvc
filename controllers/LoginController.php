<?php

namespace Controllers;

use Model\Usuario;
use MVC\Router;
use Classes\Email; // Import the Email class

class LoginController
{
    public static function login(Router $router)
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }

        //Render a la vista
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión'
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }

        //Muestra la vista
        $router->render('auth/olvide', [
            'titulo' => 'Olvidé Contraseña'
        ]);
    }

    public static function reestablecer(Router $router)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }

        //Muestra la vista
        $router->render('auth/reestablecer', [
            'titulo' => 'Nueva Contraseña'
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

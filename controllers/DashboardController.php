<?php

namespace Controllers;
use MVC\Router;
use Model\Proyecto;
use Model\Usuario;

class DashboardController {
    public static function index(Router $router) {
        session_start();
        isAuth();

        $id = $_SESSION['id'];

        $proyectos = Proyecto::belongsTo('propietarioId', $id);

        //debuguear($proyectos);

        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos

        ]);
    }

    public static function crearProyecto(Router $router) {
        session_start();
        isAuth();
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //debuguear('Submi');
            $proyecto = new Proyecto($_POST);
            $alertas = $proyecto->validarProyecto();

            if (empty($alertas)) {
                //Generar una URL unica
                $hash = md5(uniqid());
                $proyecto->url = $hash;
                
                //Almacenar el creador del proyecto
                $proyecto->propietarioId = $_SESSION['id'];
                //debuguear($proyecto);
                // Guardar el proyecto en la base de datos
                $proyecto->guardar();
                // Redireccionar al usuario
                header('Location: /proyecto?id=' . $proyecto->url);
            }
        }

        $router->render('dashboard/crear-proyecto', [
            'titulo' => 'Crear Proyecto',
            'alertas'=> $alertas
        ]);
    }

    public static function perfil(Router $router) {
        session_start();
        isAuth();
        $alertas = [];
        $usuario = Usuario::find($_SESSION['id']);

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarPerfil();

            if(empty($alertas)) {
                // Verificar si el email ya existe
                $existeUsuario = Usuario::where('email', $usuario->email);
                if($existeUsuario && $existeUsuario->id !== $usuario->id) {
                    Usuario::setAlerta('error', 'El Email ya está en uso');
                    $alertas = Usuario::getAlertas();
                } else {
                    // Guardar los cambios
                    $resultado = $usuario->guardar();
                    if($resultado) {
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        Usuario::setAlerta('exito', 'Cambios guardados correctamente');
                        $alertas = Usuario::getAlertas();
                    }
                }
            }
            
        }

        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function proyecto(Router $router) {
        session_start();
        isAuth();

        $token = $_GET['id'];
        if (!$token) {
            header('Location: /dashboard');
        }
        //Revisar que la persona que visita el proyecto es el propietario
        $proyecto = Proyecto::where('url', $token);
        if (!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
            header('Location: /dashboard');
        }
        // Aquí puedes obtener el proyecto usando $_GET['id'] y pasarlo a la vista
        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto,

        ]);
    }

    public static function cambiar_password(Router $router) {
        session_start();
        isAuth();
        $alertas = [];
        $usuario = Usuario::find($_SESSION['id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->nuevo_password();

            if (empty($alertas)) {
                $resultado = $usuario->comprobarPassword();
                // Verificar si el password actual es correcto
                if ($resultado) {
                    // Actualizar el password
                    $usuario->password = $usuario->password_nuevo;

                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);

                    $usuario->hashPassword();

                    $resultado = $usuario->guardar();

                    if ($resultado) {
                        Usuario::setAlerta('exito', 'Password actualizado correctamente');
                        $alertas = Usuario::getAlertas();
                    }
                } else {
                    Usuario::setAlerta('error', 'El Password Actual es incorrecto');
                    $alertas = Usuario::getAlertas();
                }
            }
        }

        $router->render('dashboard/cambiar-password', [
            'titulo' => 'Cambiar Password',
            'alertas' => $alertas,
            'usuario' => $usuario
        ]);
    }
}
<?php

namespace Controllers;
use MVC\Router;
use Model\Proyecto;

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
        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil'
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
}
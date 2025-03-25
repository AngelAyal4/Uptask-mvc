<?php

namespace Controllers;

class LoginController
{
    public static function login()
    {
        echo 'desde el login';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }
    }

    public static function logout()
    {
        echo 'desde el logout';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }
    }

    public static function crear()
    {
        echo 'desde el crear';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }
    }

    public static function olvide()
    {
        echo 'desde el olvide';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }
    }

    public static function reestablecer()
    {
        echo 'desde el reestablecer';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }
    }

    public static function mensaje()
    {
        echo 'desde el mensaje';
    }

    public static function confirmar()
    {
        echo 'desde el confirmar';
    }
}

<?php
namespace zenden;

use zenden\app\classes\Router;

class App
{
    public static Router $router;

    public static function init()
    {
       // echo "App initialization <br>";
        self::$router = new Router();
        self::$router->start();
    }
}
<?php

namespace zenden\app\classes;

use zenden\app\controllers\MainController;

class Router
{
    private $controller;
    private $action;

    public function __construct()
    {
        $this->controller = "Main";
        $this->action = "Index";
    }

    public function start()
    {
        $uri = $this->getURI();

        switch ($uri)
        {
            case "/data":
            {
                $controller = 'zenden\\app\\controllers\\'.$this->controller.'Controller';

                $cntl = new MainController();
                $action = $this->action;
                $cntl->$action();
                break;
            }
            case "/show":
            {
                 $controller = 'zenden\\app\\controllers\\'.$this->controller.'Controller';

                 $cntl = new MainController();
                 $action = "GetData";
                // echo "other items";
                 $cntl->$action();
                // echo "other items";
                break;
            }
        }
    }

    private function getURI()
    {
        $query = rtrim($_SERVER['REQUEST_URI'], "/");
        return $query;
    }
}
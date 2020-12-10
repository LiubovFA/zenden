<?php

namespace zenden\app\controllers;

use zenden\app\classes\View;
use zenden\app\classes\Parsing;

class MainController
{
    private View $view;

    public function __construct()
    {
        $this->view = new View();
        // echo "это MainController! <br>";
    }

    public function Index()
    {
        $this->view->setView("Index");
        $this->view->render();
        // echo "Это метод Index <br>";
    }

    public function GetData()
    {
        if(isset($_POST['submit']))
        {
            if(isset($_POST['url']) != "")
            {
                $url = $_POST['url'];

                $curl = curl_init($url);

                //ответ в переменную, не на экран

                curl_setopt($curl, CURLOPT_HEADER, 0);
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                $html = curl_exec($curl);

                curl_close($curl);

                $parsing = new Parsing();

                $res = $parsing->start($html);

                if (!empty($res))
                {
                    $this->view->setView("Index");
                    $this->view->render($res);
                }
            }
        }

    }
}
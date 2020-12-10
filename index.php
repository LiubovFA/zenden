<?php

use zenden\App;

require_once __DIR__ .'/vendor/autoload.php';

try
{
   // echo 'hello, world! <br>';

    App::init();
}
catch (Exception $ex)
{
    echo $ex->getMessage();
}
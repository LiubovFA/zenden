<?php

namespace zenden\app\classes;

//use function Composer\Autoload\includeFile;

class View
{
    private $layout = 'app/views/layout.php';
    private string $viewName;
    private string $dataType;

    public function __construct()
    {

    }

    public function setView($name, $data = "")
    {
        $this->viewName = $name;
        $this->dataType = $data;
    }

    public function render($data = [])
    {
        $path = 'app/views/'.$this->viewName.'.php';
        $type = $this->dataType;

        if (!file_exists($path))
        {
            throw new \ErrorException ("View cannot be found");
        }
        else
        {
            // echo 'This is the view <br>';
            ob_start();
            include $path;
            $content = ob_get_clean();
            include $this->layout;
        }
    }
}
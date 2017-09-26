<?php

namespace src\libs;

abstract class Controller
{
    protected $view;
    protected $template;

    public function __construct()
    {
        $this->view = new View();
        $this->template = new Template();
    }
    
    public function loadModel($name, $model_path = 'src/models/')
    {
        $path = $model_path . ucfirst($name) . 'Model.php';
        if (file_exists($path)){
            //require $model_path . $name . '_model.php';
            $modelName = 'src\\models\\'.ucfirst($name) . 'Model';
            $this->model = new $modelName();
        }
    }
    
    protected function loadTemplate($title = '', $mode = 1, $footer = 1, $dodatek = [])
    {
        if ($mode == 1) {
            //header, menu and left table with info
            if(Session::_get('logged')){
                $this->template->menuHeaderTable($title, $dodatek);
            }
        }else{
            //footer
            $this->template->footer($footer);
        }
    }

}
?>
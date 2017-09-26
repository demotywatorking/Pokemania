<?php
namespace src\controllers;

use src\libs\Controller;
use src\libs\Session;

class Problem extends Controller {

    function __construct() {
        parent::__construct();
        if(Session::_isset('logged') && Session::_get('logged')){
            $this->loadTemplate('ERROR 404 -  '.NAME);
        }else{
            header('Location: '.URL);
            exit;
        }
        
    }
    
    function index(){
        
        $this->view->title = '404 Error';
        $this->view->msg = '<img src="'.URL.'public/img/error.png" />Podana strona nie została odnaleziona';
        $this->view->render('error/index');
    }

}


<?php
namespace src\controllers;

use src\libs\Controller;
use src\libs\Session;

class Lewo extends Controller
{

    function __construct() 
    {
        if (!Session::_isset('logged')) {
            header('Location: '.URL);
            exit;
        }
        parent::__construct();
    }
    
    function index()
    {
        $this->template->lewo(1);
    }
}
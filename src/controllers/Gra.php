<?php
namespace src\controllers;

use src\libs\Controller;
use src\libs\Session;

class Gra extends Controller
{

    function __construct()
    {
        parent::__construct();
        if(!isset($_GET['ajax'])){
            $this->loadTemplate('Statystyki dzisiejsze - '.NAME);
        }
    } 
    
    function index()
    {
        $rezultat = $this->model->statystyki();
        $w = $rezultat[0];
        $this->view->zlapane = $w['zlapanych'];
        $this->view->loteria = $w['loteria'];
        $this->view->kupony = $w['kupony'];
        $this->view->wyprawy = $w['wyprawy'];

        $this->view->render('gra/index');
        if(!isset($_GET['ajax'])){
            $this->loadTemplate('', 2);
        }
    }
    function desktop($desktop = '')
    {
        if($desktop == 'on'){
            Session::_set('desktop', 1);
        }else{
            Session::_unset('desktop');
        }
        if(!isset($_GET['ajax'])){
            $this->index();
        }
    }
}

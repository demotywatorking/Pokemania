<?php
namespace src\controllers;

use src\libs\Controller;
use src\libs\Session;

class Ogloszenia extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('Ogłoszenia - '.NAME);
        }
    }

    public function index()
    {
        $rezultat = $this->model->ogloszenia();
        $ile = $rezultat['rowCount'];
        for($i = 0 ; $i < $ile ; $i++) {
            $this->view->ogloszenie[$i] = $rezultat[$i];
            $this->view->ogloszenie[$i]['nowe'] = 0;
            if (Session::_isset('ogloszenia') && Session::_get('ogloszenia') > 0) {
                Session::_set('ogloszenia', (Session::_get('ogloszenia') - 1));
                $this->view->ogloszenie[$i]['nowe'] = 1;
            }
        }
        if (Session::_isset('ogloszenia')) {
            Session::_unset('ogloszenia');
            $this->model->przeczytane();
        }
        $this->view->render('ogloszenia/index');
    }
}
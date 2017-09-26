<?php

namespace src\controllers;

use src\libs\Controller;
use src\libs\Session;

class Kolekcja extends Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('Kolekcja - ' . NAME);
        }
    }

    public function index()
    {
        //KANTO
        $this->kanto = $this->model->kanto();
        $this->johto = $this->model->johto();

        $this->view->kanto = $this->kanto['kanto'];
        $this->view->spotkaneKanto = $this->kanto['spotkane'];
        $this->view->zlapaneKanto = $this->kanto['zlapane'];

        $this->view->johto = $this->johto['johto'];
        $this->view->spotkaneJohto = $this->johto['spotkane'];
        $this->view->zlapaneJohto = $this->johto['zlapane'];

        $this->view->render('kolekcja/index');
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('', 2);
        }
    }
}
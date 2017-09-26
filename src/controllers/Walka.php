<?php

namespace src\controllers;

use src\libs\Controller;
use src\libs\Session;

class Walka extends Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!Session::_isset('logged')) {
            header('Location: ' . URL);
            exit;
        }
    }

    public function index()
    {
        header('Location: ' . URL);
        exit;
    }

    public function trener()
    {
        $plik = fopen('./pliki/trener/' . Session::_get('id') . '.txt', 'r') or exit('Nie mogę otworzyć pliku');
        $this->view->walka = fread($plik, filesize('pliki/trener/' . Session::_get('id') . '.txt'));
        fclose($plik);
        $this->view->render('wyswietl_walke/index');
    }

    public function pokemon()
    {
        $plik = fopen('./pliki/pokemon/' . Session::_get('id') . '.txt', 'r') or exit('Nie mogę otworzyć pliku');
        $this->view->walka = fread($plik, filesize('pliki/pokemon/' . Session::_get('id') . '.txt'));
        fclose($plik);
        $this->view->render('wyswietl_walke/index');
    }
}

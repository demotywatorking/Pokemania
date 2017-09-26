<?php

namespace src\controllers;

use src\includes\functions\FunctionsDate;
use src\libs\Controller;
use src\libs\Session;

class Raporty extends Controller
{
    use FunctionsDate;

    public function __construct()
    {
        parent::__construct();
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('Raporty - '.NAME);
        }
    }

    public function index()
    {
        $this->wiadomosci();
        $this->view->render('poczta/index');
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('',2);
            $this->model->oznaczOdczytane();
            if(Session::_isset('nowe_p')) Session::_unset('nowe_p');
        }
    }

    private function wiadomosci()
    {
        $rezultat = $this->model->pobierzRaporty();
        $ile = $rezultat['rowCount'];
        $this->view->wiadomosci = $ile;
        for ($i = 0 ; $i < $ile ; $i++) {
            $wiersz = $rezultat[$i];
            $this->view->wiadomosc[$i]['ID'] = $wiersz['ID'];
            $this->view->wiadomosc[$i]['data'] = $this->pokazDate($wiersz['godzina'], 1);
            $this->view->wiadomosc[$i]['tytul'] = $wiersz['tytul'];
            $this->view->wiadomosc[$i]['odczytana'] = $wiersz['odczytana'];
        }
    }

    public function id(int $id = 0)
    {
        $rez = $this->model->raport($id);
        if($rez['rowCount']) {
            $rez = $rez[0];
            $this->view->show = '{ "title" : ' . json_encode($rez['tytul']) . ', "body": '. json_encode($rez['tresc']) . ' }';
        } else {
            $this->view->show = '{ "title" : "", "body": '. json_encode('<div class="alert alert-warning"><span>Nie znaleziono raportu</span></div>') .'}';
        }
        $this->view->render('poczta/modal');
    }

    public function usun(int $id = 0)
    {
         $this->model->raportUsun($id);
         $this->view->show = '<div class="alert alert-success"><span>RAPORT POPRAWNIE USUNIĘTY!</span></div>';
         $this->view->render('poczta/modal');
    }

    public function usunAll()
    {
        if (!(isset($_GET['potw']))) {
            $this->view->show =  '<div class="alert alert-warning"><span>Czy na pewno chcesz usunąć wszystkie raporty?</span></div>';
            $this->view->show .=  '<div class="row margin-bottom text-center"><button class="btn btn-primary nie" >NIE</button>';
            $this->view->show .=  '<button class="btn btn-primary tak">TAK</button></div>';
        } else {
            $this->model->usunWszystkie();
            $this->view->show =  '<div class="alert alert-success"><span>RAPORTY USUNIĘTE</span></div>';
        }
        $this->view->render('poczta/modal');
    }
}
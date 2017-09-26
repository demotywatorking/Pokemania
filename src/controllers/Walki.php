<?php

namespace src\controllers;

use src\libs\Controller;
use src\libs\Session;

class Walki extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('Walki - '.NAME);
        }
    }

    public function index()
    {
        $this->walki();
        $this->view->render('walki/index');
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('', 2);
        }
    }

    private function walki()
    {
        $rezultat = $this->model->walki();
        $ile = $rezultat['rowCount'];
        $this->view->ilosc = $ile;
        if ($ile) {
            for ($i = 0 ; $i < $ile ; $i++) {
                $this->view->raport[$i] = $rezultat[$i];
            }
        }
    }

    public function usun(int $ID)
    {
        $rezultat = $this->model->walkaId($ID);
        $ile = $rezultat['rowCount'];
        if (!$ile) {
            $this->view->blad = 'Raport nie istenieje, to nie Twój raport lub został już usunięty';
        } else {
            $this->model->usunWalka($ID);
            $this->view->komunikat = 'Pomyślnie usunięto raport.';
        }
        $this->index();
    }

    public function unlock(int $ID)
    {
        $rezultat = $this->model->walkaDoOdblokowania($ID);
        $ile = $rezultat['rowCount'];
        if (!$ile) {
            $this->view->blad = 'Raport nie istnieje lub został już udostępniony';
        } else {
            $this->model->odblokuj($ID);
            $this->view->komunikat = 'Pomyślnie udostępniono raport.';
        }
        $this->index();
    }

    public function zobacz(int $ID)
    {
        $rezultat = $this->model->walkaIdZobacz($ID);
        $ile = $rezultat['rowCount'];
        if($ile == 0) {
            $this->view->blad = 'Nie znaleziono raportu.';
        } else {
            $w = $rezultat[0];
            if ($w['id_gracza'] != Session::_get('id'))
                if (!$w['odblokowany']) {
                    $this->view->blad = 'Raport nie został udostępniony.';
                }
            if (($w['id_gracza'] != Session::_get('id') && $w['odblokowany']) || $w['id_gracza'] == Session::_get('id')) {
                $this->view->raport = html_zn($w['tresc']); //wyswietlenie treści raportu
            }
        }
        $this->view->render('walki/raport');
    }
}
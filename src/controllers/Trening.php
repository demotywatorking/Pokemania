<?php

namespace src\controllers;

use src\libs\Controller;
use src\libs\Session;
use src\libs\User;

class Trening extends Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('Trening - ' . NAME);
        }
    }

    public function index()
    {
        if (Session::_get('aktywnosc') == 'trening') {
            $this->trening();
        } else {
            $this->nieTrening();
        }
        $this->view->render('trening/index');
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('', 2);
        }
    }

    private function nieTrening()
    {
        $this->view->trening = 0;
        $sec = floor(7200 * (1 - Session::_get('poziom') / 110));
        $godziny = floor($sec / 3600);
        $sec -= ($godziny * 3600);
        $minuty = floor($sec / 60);
        if ($minuty < 10) $minuty = "0" . $minuty;
        $sec -= ($minuty * 60);
        if ($sec < 10) $sec = "0" . $sec;
        $this->view->godziny = $godziny;
        $this->view->minuty = $minuty;
        $this->view->sekundy = $sec;
    }

    private function trening()
    {
        $this->view->trening = 1;
        $rezultat = $this->model->aktywnosc();
        $rezultat = $rezultat[0];
        $sec = time() - $rezultat['czas'];
        $godziny = floor($sec / 3600);
        $sec -= ($godziny * 3600);
        $minuty = floor($sec / 60);
        $sec -= ($minuty * 60);
        $this->view->godziny = $godziny;
        $this->view->minuty = $minuty;
        $this->view->sekundy = $sec;

        $sec = floor(7200 * (1 - Session::_get('poziom') / 110));
        $godziny = floor($sec / 3600);
        $sec -= ($godziny * 3600);
        $minuty = floor($sec / 60);
        if ($minuty < 10) $minuty = "0" . $minuty;
        $sec -= ($minuty * 60);
        if ($sec < 10) $sec = "0" . $sec;
        $this->view->coGodziny = $godziny;
        $this->view->coMinuty = $minuty;
        $this->view->coSekundy = $sec;
    }

    public function trenuj()
    {
        if (Session::_get('aktywnosc') == 'trening') {
            $this->index();
            return;
        }
        $this->model->updateAktywnosc(time());
        Session::_set('aktywnosc', 'trening');
        $this->view->komunikatTrening = '<div class="alert alert-success text-center"><span>Rozpoczęto trening z pokemami.</span></div>';
        $this->index();
    }

    public function przerwij()
    {
        if (Session::_get('aktywnosc') == 'trening') {
            $this->view->przerwij = 1;
            $rezultat = $this->model->czas();
            $czas = $rezultat[0];
            $czas = time() - $czas['czas'];
            $this->view->exp = 3 * floor($czas / floor(7200 * (1 - Session::_get('poziom') / 110)));
            $this->model->aktywnoscBrak();
            Session::_set('aktywnosc', '');
            $sec = $czas;
            $godziny = floor($sec / 3600);
            if ($godziny < 10) $godziny = "0" . $godziny;
            $sec -= ($godziny * 3600);
            $minuty = floor($sec / 60);
            if ($minuty < 10) $minuty = "0" . $minuty;
            $sec -= ($minuty * 60);
            if ($sec < 10) $sec = "0" . $sec;
            $this->view->czas = $godziny . ':' . $minuty . ':' . $sec;
            if ($this->view->exp) {
                $this->view->tekst = '';
                for ($i = 1; $i < 7; $i++) {
                    if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') > 0) {
                        $this->view->tekst .= '<br />' . User::_get('pok', $i)->get('imie') . " +" . $this->view->exp . ' pkt. doświadczenia';
                        User::_get('pok', $i)->edit('dos', (User::_get('pok', $i)->get('dos') + $this->view->exp));
                    }
                }
            }
            $this->model->updatePokiDruzyna($this->view->exp);
        }
        $this->index();
    }

}

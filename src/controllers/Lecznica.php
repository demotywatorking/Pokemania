<?php

namespace src\controllers;

use src\libs\Controller;
use src\libs\Session;
use src\libs\User;

class Lecznica extends Controller
{

    function __construct()
    {
        parent::__construct();
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('Lecznica Pokemon - ' . NAME, 1);
        }
    }

    public function index()
    {
        if (User::$przedmioty->get('leczenia')) {
            $this->view->darmoweLeczenia = User::$przedmioty->get('leczenia');
        }
        $this->wyswietl();
        $this->view->render('lecznica/index');
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('', 2);
        }
    }

    public function wylecz($pok = '')
    {
        if ($pok == 'wszystkie') {
            $this->wszystkie();
        } elseif ($pok != '') {
            $this->jeden($pok);
        } else {//błąd
            $this->error();
        }
    }

    private function wszystkie()
    {
        $kwer1 = "UPDATE pokemony SET akt_HP = (round(jakosc * HP / 100) + Jag_HP + tr_6 * 5) WHERE (";
        $kwer2 = ") AND wlasciciel = '" . Session::_get('id') . "'";
        $p = 0;
        $koszt = 0;
        //$show .=  "Tu będzie leczenie wszystkich pokemonów!";
        for ($i = 1; $i < 7; $i++) {
            if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') > 0) {
                $pokemon = User::_get('pok', $i);
                if ($pokemon->get('akt_zycie') < $pokemon->get('zycie')) {
                    $k_p = ceil(((900 * $pokemon->get('lvl')) * 0.35) * (1 - ($pokemon->get('akt_zycie') / $pokemon->get('zycie'))) * ($pokemon->get('lvl') / 90));
                    $a = $pokemon->get('id');
                    if ($p == 0) $kwer1 = $kwer1 . "ID = '$a'";
                    else $kwer1 = $kwer1 . "OR ID = '$a'";
                    $p++;
                    $koszt += $k_p;
                }
            }
        }
        $kwerenda = $kwer1 . $kwer2;
        if (User::$przedmioty->get('apteczka') > 0) {
            $koszt *= (1 - (User::$przedmioty->get('apteczka') / 10));
            $koszt = floor($koszt);
        }
        if (User::$odznaki->kanto[5]) {
            $koszt *= 0.9;
            $koszt = floor($koszt);
        }
        if ($koszt > 0) {
            if (Session::_get('poziom') > 15) {
                if (Session::_get('poziom') > 15 && Session::_get('poziom') <= 20) {
                    $koszt = ceil($koszt / 2);
                }
                if (($p == 6 || $koszt > 10000) && User::$przedmioty->get('leczenia') > 0) {
                    User::$przedmioty->edit('leczenia', User::$przedmioty->get('leczenia') - 1);
                    $this->view->komunikat = '<div class="alert alert-success padding_2 text-medium text-center">
                              <span>Wyleczono Pokemony i użyto kuponu na darmowe leczenie. Pozostało ' . User::$przedmioty->get('leczenia') . '.</span></div>';
                    $this->model->db->update($kwerenda);
                    $this->model->darmoweLeczenie();
                    for ($i = 1; $i < 7; $i++) {
                        if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') > 0)
                            User::_get('pok', $i)->edit('akt_zycie', User::_get('pok', $i)->get('zycie'));
                    }
                } elseif (Session::_get('kasa') >= $koszt) {
                    $this->model->db->update($kwerenda, []);
                    for ($i = 1; $i < 7; $i++) {
                        if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') > 0)
                            User::_get('pok', $i)->edit('akt_zycie', User::_get('pok', $i)->get('zycie'));
                    }
                    $this->model->leczenieZaPieniadze($koszt);
                    Session::_set('kasa', (Session::_get('kasa') - $koszt));
                    $this->view->komunikat = '<div class="alert alert-success padding_2 text-medium text-center">
                              <span>Pokemony wyleczone. Koszt ' . $koszt . ' &yen;</span></div>';
                } else {
                    $this->view->error = '<div class="alert alert-danger padding_2 text-medium text-center">
                        <span>Nie stać Cię na leczenie Pokemonów!</span></div>';
                    $this->view->render('lecznica/blad');
                    return false;
                }
            } else {
                $this->model->db->update($kwerenda, []);
                $this->view->komunikat = '<div class="alert alert-success padding_2 text-medium text-center"><span>Pokemony uleczone.</span></div>';
                for ($i = 1; $i < 7; $i++) {
                    if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') > 0)
                        User::_get('pok', $i)->edit('akt_zycie', User::_get('pok', $i)->get('zycie'));
                }
            }
        } else {
            $this->view->error = '<div class="alert alert-success padding_2 text-medium text-center"><span>Pokemony nie wymagają leczenia.</span></div>';
            $this->view->render('lecznica/blad');
            return false;
        }
        $this->view->render('lecznica/komunikat');
    }

    private function jeden($pok)
    {
        if (is_numeric($pok) && $pok > 0 && $pok < 7) {
            if (User::_isset('pok', $pok)) {
                $pokemon = User::_get('pok', $pok);
                if ($pokemon->get('akt_zycie') == $pokemon->get('zycie')) {
                    $this->view->error = '<div class="alert alert-info text-medium text-center margin-top"><span>POKEMON NIE WYMAGA LECZENIA</span></div>';
                    $this->view->render('lecznica/blad');
                    if (!isset($_GET['ajax'])) {
                        $this->loadTemplate('', 2);
                    }
                    return false;
                } else {
                    if (Session::_get('poziom') > 10) {
                        $koszt = ceil(((900 * $pokemon->get('lvl')) * 0.35) * (1 - ($pokemon->get('akt_zycie') / $pokemon->get('zycie'))) * ($pokemon->get('lvl') / 90));
                        if (User::$przedmioty->get('apteczka') > 0) {
                            $koszt *= (1 - (User::$przedmioty->get('apteczka') / 10));
                            $koszt = floor($koszt);
                        }
                        if (User::$odznaki->kanto[5]) {
                            $koszt *= 0.9;
                            $koszt = floor($koszt);
                        }
                        if (Session::_get('poziom') > 10 && Session::_get('poziom') <= 20) {
                            $koszt = ceil($koszt / 2);
                        }
                        if (Session::_get('kasa') >= $koszt) {
                            $this->model->leczenieZaPieniadze($koszt);
                            $this->model->wyleczPokemon($pokemon->get('id'));
                            Session::_set('kasa', (Session::_get('kasa') - $koszt));
                            User::_get('pok', $pok)->edit('akt_zycie', $pokemon->get('zycie'));
                            $this->view->komunikat = '<div class="alert alert-success text-medium text-center margin-top"><span>Pokemon uleczony! Koszt ' . $koszt . ' &yen;</span></div>';
                        } else {
                            $this->view->error = '<div class="alert alert-danger fade in text-medium text-center margin-top"><span>Ne stać Cię na leczenie Pokemona!</span></div>';
                            $this->view->render('lecznica/blad');
                            $this->loadTemplate('', 2);
                            return false;
                        }
                    } else {
                        $this->model->wyleczPokemon($pokemon->get('id'));
                         User::_get('pok', $pok)->edit('akt_zycie', $pokemon->get('zycie'));
                        $this->view->komunikat = '<div class="alert alert-success fade in text-medium text-center margin-top"><span>Pokemon uleczony!</span></div>';
                    }
                    $this->view->render('lecznica/komunikat');
                }
            } else {
                $this->error();
            }

        } else {
            $this->error();
        }
    }

    private function error()
    {
        $this->view->error = 'Błędny numer Pokemona';
        $this->view->render('lecznica/blad');
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('', 2);
        }
    }

    private function wyswietl()
    {
        for ($i = 1; $i < 7; $i++) {
            if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') > 0) {
                $pokemon = User::_get('pok', $i);
                $this->view->pok[$i]['shiny'] = $pokemon->get('shiny');
                $this->view->pok[$i]['imie'] = $pokemon->get('imie');
                $this->view->pok[$i]['akt_zycie'] = $pokemon->get('akt_zycie');
                $this->view->pok[$i]['zycie'] = $pokemon->get('zycie');
                $this->view->pok[$i]['id_p'] = $pokemon->get('id_p');
                $this->view->pok[$i]['i'] = $i;
                if ($pokemon->get('akt_zycie') == $pokemon->get('zycie') || Session::_get('poziom') <= 10) {
                    $this->view->pok[$i]['cena'] = 0;
                } else {
                    $koszt = ceil(((900 * $pokemon->get('lvl')) * 0.35) * (1 - ($pokemon->get('akt_zycie') / $pokemon->get('zycie'))) * ($pokemon->get('lvl') / 90));
                    if (User::$przedmioty->get('apteczka') > 0) {
                        $koszt *= (1 - (User::$przedmioty->get('apteczka') / 10));
                        $koszt = floor($koszt);
                    }
                    if (User::$odznaki->kanto[5]) {
                        $koszt *= 0.9;
                        $koszt = floor($koszt);
                    }
                    if (Session::_get('poziom') > 10 && Session::_get('poziom') <= 20) {
                        $koszt = ceil($koszt / 2);
                    }
                    $this->view->pok[$i]['cena'] = $koszt;
                }
            }
        }
    }
}


<?php

namespace src\controllers;

use src\libs\Controller;
use src\libs\Session;
use src\libs\User;

class Sklep extends Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('Pokesklep -' . NAME, 1);
        }
        if (!isset($_GET['active']))
            $this->active = 1;
        else
            $this->active = $_GET['active'];
        $this->view->active = $this->active;
        require('./src/includes/pokeballe.php');
        $this->view->pokeballe = $pokeballe;
        $this->pokeballe = $pokeballe;
    }

    public function index()
    {
        $this->getPokeball();
        $this->getPrzedmioty();

        $this->view->render('sklep/index');
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('', 2);
        }
    }

    public function kup($co = '', $ilosc = 0)
    {
        if (!$ilosc) $ilosc = 1;
        if (in_array($co, ['mpa', 'safari', 'karma', 'loteria', 'batony', 'ciastka', 'box', 'pokedex',
            'apteczka', 'lopata', 'runa', 'bateria', 'latarka'])
        ) {
            $this->$co($ilosc);
        } else {
            $this->view->blad = '<div class="alert alert-danger"><span>Błędna nazwa przedmiotu.</span></div>';
        }
        $this->index();
    }

    private function latarka()
    {
        $rezultat = $this->model->przedmiot('latarka');
        $w = $rezultat[0];
        if ($w['latarka'] == 0) {
            if (Session::_get('kasa') >= 5000) {
                Session::_set('kasa', (Session::_get('kasa') - 5000));
                $this->model->zmienPieniadze(5000);
                $this->model->kupPrzedmiot('latarka');
                $this->view->kup = '<div class="alert alert-success"><span>Kupiono latarkę.</span></div>';
            } else $this->view->blad = '<div class="alert alert-danger"><span>Nie stać Cię na ten produkt.</span></div>';
        } else $this->view->blad = '<div class="alert alert-danger"><span>Posiadasz już latarkę.</span></div>';
    }

    private function bateria($ilosc)
    {
        if ($ilosc == '') $ilosc = 1;
        if (is_numeric($ilosc) && $ilosc > 0) {
            $wartosc = $ilosc * 55;
            if (Session::_get('kasa') >= $wartosc) {
                Session::_set('kasa', (Session::_get('kasa') - $wartosc));
                $this->model->zmienPieniadze($wartosc);
                $this->model->zmienPrzedmiot('baterie', $ilosc);
                $this->view->kup = '<div class="alert alert-success"><span>Kupiono ' . $ilosc . ' baterii za ' . $wartosc . ' &yen;.</span></div>';
            } else $this->view->blad = '<div class="alert alert-danger"><span>Nie stać Cię na zakup tylu baterii.</span></div>';
        } else $this->view->blad = '<div class="alert alert-danger"><span>Błędna ilość.</span></div>';
    }

    private function runa($ilosc)
    {
        if ($ilosc == '') $ilosc = 1;
        if (is_numeric($ilosc) && $ilosc > 0) {
            $wartosc = $ilosc * 100000;
            if ($wartosc <= Session::_get('kasa')) {
                Session::_set('kasa', (Session::_get('kasa') - $wartosc));
                $this->model->zmienPieniadze($wartosc);
                $this->model->zmienKamien('runa', $ilosc);
                $this->view->kup = '<div class="alert alert-success"><span>Kupiono ' . $ilosc . ' run ewolucyjnych za cenę ' . $wartosc . ' &yen;.</span></div>';
            } else $this->view->blad = '<div class="alert alert-danger"><span>Nie stać Cię na zakup tylu run.</span></div>';
        } else $this->view->blad = '<div class="alert alert-danger"><span>Błędna ilość.</span></div>';
    }

    private function lopata()
    {
        $rezultat = $this->model->przedmiot('lopata');
        $w = $rezultat[0];
        if ($w['lopata'] == 0) {
            if (Session::_get('kasa') >= 500000) {
                User::$przedmioty->edit('lopata', 1);
                Session::_set('kasa', (Session::_get('kasa') - 500000));
                $this->model->zmienPieniadze(500000);
                $this->model->kupPrzedmiot('lopata');
                $this->view->kup = '<div class="alert alert-success"><span>Kupiono złotą łopatę.</span></div>';
            } else $this->view->blad = '<div class="alert alert-danger"><span>Nie stać Cię na ten produkt.</span></div>';
        } else $this->view->blad = '<div class="alert alert-danger"><span>Posiadasz już złotą łopatę.</span></div>';
    }

    private function apteczka()
    {
        $p = [1 => 25000, 2 => 180000, 3 => 800000];
        $rezultat = $this->model->przedmiot('apteczka');
        $w = $rezultat[0];
        if ($w['apteczka'] < 3) {
            $cena = $p[$w['apteczka'] + 1];
            if (Session::_get('kasa') >= $cena) {
                $this->model->zmienPieniadze($cena);
                $this->model->zmienPrzedmiot('apteczka', 1);
                Session::_set('kasa', (Session::_get('kasa') - $cena));
                User::$przedmioty->edit('apteczka', (User::$przedmioty->get('apteczka') + 1));
                $this->view->kup = '<div class="alert alert-success"><span>Kupiono apteczkę poziomu ' . ($w['apteczka'] + 1) . ' za cenę ' . $cena . ' &yen;.</span></div>';
            } else  $this->view->blad = '<div class="alert alert-danger"><span>Nie stać Cię na zakup nowej apteczki.</span></div>';
        } else $this->view->blad = '<div class="alert alert-danger"><span>Posiadasz już maksymalny poziom apteczki.</span></div>';
    }

    private function pokedex()
    {
        $rezultat = $this->model->przedmiot('pokedex');
        $w = $rezultat[0];
        if ($w['pokedex'] < 3) {
            $cena = (5 ** ($w['pokedex'] + 1)) * 10000;
            if (Session::_get('kasa') >= $cena) {
                $this->model->zmienPieniadze($cena);
                $this->model->zmienPrzedmiot('pokedex', 1);
                Session::_set('kasa', (Session::_get('kasa') - $cena));
                User::$przedmioty->edit('pokedex', (User::$przedmioty->get('pokedex') + 1));
                $this->view->kup = '<div class="alert alert-success"><span>Kupiono pokedex poziomu ' . ($w['pokedex'] + 1) . ' za cenę ' . $cena . ' &yen;.</span></div>';
            } else   $this->view->blad = '<div class="alert alert-danger"><span>Nie stać Cię na zakup nowego pokedexu.</span></div>';
        } else  $this->view->blad = '<div class="alert alert-danger"><span>Posiadasz już maksymalny poziom pokedexu.</span></div>';
    }

    private function box()
    {
        $rezultat = $this->model->przedmiot('box');
        $w = $rezultat[0];
        if ($w['box'] < 5) {
            $cena = $w['box'] * 150000;
            if (Session::_get('kasa') >= $cena) {
                $this->model->kupMagazyn($cena);
                Session::_set('kasa', (Session::_get('kasa') - $cena));
                Session::_set('magazyn', (Session::_get('magazyn') * 2));
                $this->view->kup = '<div class="alert alert-success"><span>Kupiono ' . ($w['box'] + 1) . ' poziom magazynu za cenę ' . $cena . '&yen;.</span></div>';
            } else $this->view->blad = '<div class="alert alert-danger"><span>Nie stać Cię na zakup nowego magazynu.</span></div>';
        } else $this->view->blad = '<div class="alert alert-danger"><span>Posiadasz już maksymalny poziom magazynu.</span></div>';
    }

    private function ciastka($ilosc)
    {
        if ($ilosc == '') $ilosc = 1;
        if (is_numeric($ilosc) && $ilosc > 0) {
            $wartosc = $ilosc * 2100;
            if (Session::_get('kasa') >= $wartosc) {
                $this->model->zmienPieniadze($wartosc);
                $this->model->zmienPrzedmiot('ciastka', $ilosc);
                Session::_set('kasa', (Session::_get('kasa') - $wartosc));
                $this->view->kup = '<div class="alert alert-success"><span>Kupiono ' . $ilosc . ' ciastek za cenę ' . $wartosc . ' &yen;.</span></div>';
            } else $this->view->blad = '<div class="alert alert-danger"><span>Nie stać Cię na zakup tyle ciastek.</span></div>';
        } else $this->view->blad = '<div class="alert alert-danger"><span>Błędna ilość.</span></div>';
    }

    private function batony($ilosc)
    {
        if ($ilosc == '') $ilosc = 1;
        if (is_numeric($ilosc) && $ilosc > 0) {
            $wartosc = $ilosc * 400;
            if (Session::_get('kasa') >= $wartosc) {
                $this->model->zmienPieniadze($wartosc);

                $this->model->zmienPrzedmiot('batony', $ilosc);
                Session::_set('kasa', (Session::_get('kasa') - $wartosc));
                $this->view->kup = '<div class="alert alert-success"><span>Kupiono ' . $ilosc . ' batonów za cenę ' . $wartosc . ' &yen;.</span></div>';
            } else $this->view->blad = '<div class="alert alert-danger"><span>Nie stać Cię na zakup tylu batonów.</span></div>';
        } else $this->view->blad = '<div class="alert alert-danger"><span>Błędna ilość.</span></div>';
    }

    private function loteria($ilosc)
    {
        if ($ilosc == '') $ilosc = 1;
        if (is_numeric($ilosc) && $ilosc > 0) {
            $wartosc = $ilosc * 60000;
            if ($wartosc <= Session::_get('kasa')) {
                Session::_set('kasa', (Session::_get('kasa') - $wartosc));
                $this->model->zmienPieniadze($wartosc);
                $this->model->zmienStatystyki('loteria', $ilosc);
                $this->view->kup = '<div class="alert alert-success"><span>Kupiono ' . $ilosc . ' kuponów na  loterię za cenę ' . $wartosc . ' &yen;.</span></div>';
            } else $this->view->blad = '<div class="alert alert-danger"><span>Nie stać Cię na zakup tylu losów na loterię.</span></div>';
        } else $this->view->blad = '<div class="alert alert-danger"><span>Błędna ilość.</span></div>';
    }

    private function karma($ilosc)
    {
        if ($ilosc == '') $ilosc = 1;
        if (is_numeric($ilosc) && $ilosc > 0) {
            $wartosc = $ilosc * 1500;
            if (Session::_get('kasa') >= $wartosc) {
                Session::_set('kasa', (Session::_get('kasa') - $wartosc));
                $this->model->zmienPieniadze($wartosc);
                $this->model->zmienPrzedmiot('karma', $ilosc);
                $this->view->kup = '<div class="alert alert-success"><span>Kupiono ' . $ilosc . ' pudełek karmy za ' . $wartosc . ' &yen;.</span></div>';
            } else $this->view->blad = '<div class="alert alert-danger"><span>Nie stać Cię na zakup tylu pudełek karmy.</span></div>';
        } else $this->view->blad = '<div class="alert alert-danger"><span>Błędna ilość.</span></div>';
    }

    private function safari($ilosc)
    {
        if ($ilosc == '') $ilosc = 1;
        if (is_numeric($ilosc) && $ilosc > 0) {
            $wartosc = $ilosc * 15000;
            if ($wartosc <= Session::_get('kasa')) {
                Session::_set('kasa', (Session::_get('kasa') - $wartosc));
                $this->model->zmienPieniadze($wartosc);
                $this->model->zmienStatystyki('kupony', $ilosc);
                $this->view->kup = '<div class="alert alert-success"><span>Kupiono ' . $ilosc . ' kuponów na Safari za cenę ' . $wartosc . ' &yen;.</span></div>';
            } else $this->view->blad = '<div class="alert alert-danger"><span>Nie stać Cię na zakup tylu kuponów.</span></div>';
        } else $this->view->blad = '<div class="alert alert-danger"><span>Błędna ilość.</span></div>';
    }

    private function mpa()
    {
        $rezultat = $this->model->przedmiot('p_mpa');
        $w = $rezultat[0];
        $mpa = $w['p_mpa'];
        if ($mpa < 10) {
            $przedmiot = $mpa + 1;
            $s = 1;
            for ($i = 1; $i < $przedmiot; $i++) {
                $s *= 2;
            }
            $cena = $s * 25000;
            if (Session::_get('kasa') >= $cena) {
                Session::_set('kasa', (Session::_get('kasa') - $cena));
                Session::_set('mpa', (Session::_get('mpa') + 10));
                $this->model->kupMpa($cena);
                $this->model->zmienPrzedmiot('p_mpa', 1);
                $this->view->kup = '<div class="alert alert-success"><span>Kupiono przedmiot, Twoje MPA zwiększono o 10 za cenę ' . $cena . ' &yen;</span></div>';
            } else $this->view->blad = '<div class="alert alert-danger"><span>Nie stać Cię na ten produkt.</span></div>';
        } else $this->view->blad = '<div class="alert alert-danger"><span>Nie możesz kupić tego przedmiotu.</span></div>';
    }

    public function pokeball($pokeballn = '', int $ilosc = 0)
    {
        if (in_array($pokeballn, ['pokeball', 'nestball', 'greatball', 'ultraball', 'duskball', 'lureball', 'repeatball', 'safariball']) && $ilosc > 0) {
            $wartosc = 0;
            $pokeball = 0;
            $nestball = 0;
            $greatball = 0;
            $ultraball = 0;
            $duskball = 0;
            $lureball = 0;
            $repeatball = 0;
            $safariball = 0;
            for ($i = 1; $i <= count($this->pokeballe); $i++) {
                if ($this->pokeballe[$i]['nazwa'] == $pokeballn && $ilosc > 0) {
                    ${$this->pokeballe[$i]['nazwa']} = 1;
                    $wartosc += $ilosc * $this->pokeballe[$i]['cena'];
                }
            }
            $zmienna = Session::_get('kasa') - $wartosc;
            $i = 0;
            if ($zmienna >= 0) {
                $kwer = "UPDATE pokeballe SET ";
                $text = '';
                for ($j = 1; $j <= count($this->pokeballe); $j++) {
                    if (${$this->pokeballe[$j]['nazwa']}) {
                        if ($i == 0)
                            $kwer .= $this->pokeballe[$j]['nazwa'] . 'e=(' . $this->pokeballe[$j]['nazwa'] . 'e+' . $ilosc . ')';
                        else
                            $kwer .= ',' . $this->pokeballe[$j]['nazwa'] . 'e=(' . $this->pokeballe[$j]['nazwa'] . 'e+' . $ilosc . ')';
                        $i++;
                        $text .= 'Kupiono ' . $ilosc . ' ' . $this->pokeballe[$j]['nazwa'] . 'i <br />';
                    }
                }
                $kwer = $kwer . "WHERE id_gracza = '" . Session::_get('id') . "'";

                if ($i) {
                    $this->model->db->select($kwer, []);
                    $this->model->zmienPieniadze($wartosc);
                    Session::_set('kasa', (Session::_get('kasa') - $wartosc));
                    $this->view->kup = '<div class="alert alert-success"><span>' . $text . '<br />Za cenę: ' . $wartosc . ' &yen;.</span></div>';
                }
                unset($text);
            } else {
                $this->view->blad = '<div class="alert alert-danger"><span>Nie stać Cię na ten zakup.</span></div>';
            }
        } else {
            $this->view->blad = '<div class="alert alert-danger"><span>Błędna nazwa pokeballa</span></div>';
        }
        $this->index();
    }

    private function getPokeball()
    {
        $rez = $this->model->pokeballe();
        $this->view->pokeball = $rez[0];
    }

    private function getPrzedmioty()
    {
        $rezultat = $this->model->przedmioty();
        $this->view->przedmioty = $rezultat[0];
        $this->view->magazyn = Session::_get('magazyn');
    }

}

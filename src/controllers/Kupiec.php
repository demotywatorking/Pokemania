<?php

namespace src\controllers;

use src\libs\Controller;
use src\libs\Session;

class Kupiec extends Controller
{

    function __construct()
    {
        parent::__construct();
        if (!isset($_GET['ajax']) && !isset($_GET['komunikat'])) {
            $this->loadTemplate('Kupiec Pokemonów - ' . NAME, 1);
        }
    }

    public function index()
    {
        $this->pokiDoSprzedania();
        $this->view->render('kupiec/index');
        if (!isset($_GET['ajax']) && !isset($_GET['komunikat'])) {
            $this->loadTemplate('', 2);
        }
    }

    public function zaznaczone()
    {
        $rezultat = $this->model->pokiMozliweDoSprzedania();
        $kwer = "DELETE FROM pokemony WHERE ( ";
        $kwer2 = ") AND wlasciciel = '" . Session::_get('id') . "'";
        $ile = $rezultat['rowCount'];
        $kwer3 = "DELETE FROM pokemon_jagody WHERE (";
        $wartosc = 0;
        $ilee = 0;
        if ($ile > 0) {
            for ($i = 1; $i <= $ile; $i++) {
                $wiersz = $rezultat[$i - 1];
                if (isset($_GET[$wiersz['ID']])) {
                    $wartosc += $wiersz['wartosc'];
                    if ($ilee == 0) {
                        $kwer = $kwer . " ID = " . $wiersz['ID'];
                        $kwer3 = $kwer3 . " id_poka = " . $wiersz['ID'];
                    } else {
                        $kwer = $kwer . " OR ID = " . $wiersz['ID'];
                        $kwer3 = $kwer3 . " OR id_poka = " . $wiersz['ID'];
                    }
                    $ilee++;
                }
            }
            $kwerenda = $kwer . $kwer2;
            $kwerenda2 = $kwer3 . ")";
            //$show .=  $kwerenda;
            if ($ilee > 0) {
                $this->model->db->delete($kwerenda, [], $ilee);
                $this->model->db->delete($kwerenda2, [], $ilee);
                $this->model->dodajPieniadze($wartosc);
                Session::_set('kasa', (Session::_get('kasa') + $wartosc));
                Session::_set('poki_magazyn', (Session::_get('poki_magazyn') - $ilee));
                $this->view->komunikat = '<div class="alert alert-success text-medium"><span>Sprzedano ' . $ilee . ' pokemonów za cenę ' . number_format($wartosc, 0, '', '.') . ' &yen;!</span></div>';
            } else {
                $this->view->error = '<div class="alert alert-danger"><span>Nie zaznaczono żadnych pokemonów!</span></div>';
            }
        } else {
            $this->view->error = '<div class="alert alert-danger"><span>Nie masz pokemonów w rezerwie!</span></div>';
        }
        if (!isset($_GET['komunikat'])) {
            $this->index();
        } else {
            $this->view->render('kupiec/sprzedaz');
        }
    }

    public function wszystkie($potwierdzenie = 0)
    {
        if (!$potwierdzenie) {
            $this->potwierdz();
        } else {
            $this->sprzedajWszystkie();
        }
        if (!isset($_GET['komunikat'])) {
            $this->index();
        } else {
            $this->view->render('kupiec/sprzedaz');
        }
    }

    private function potwierdz()
    {
        $rezultat = $this->model->wszystkieBezShiny();
        $ile = $rezultat['rowCount'];
        $wart = 0;
        for ($i = 0; $i < $ile; $i++) {
            $wiersz = $rezultat[$i];
            $wart += $wiersz['wartosc'];
        }
        $this->view->komunikat = '<div class="alert alert-warning text-medium"><span>Czy na pewno chcesz sprzedać wszystkie pokemony 
                w rezerwie (poza shiny) za ' . number_format($wart, 0, '', '.') . ' &yen;?</span></div>
                <div class="btn-group"><button class="btn btn-primary btn-lg" id="tak">TAK</button>
                <button class="btn btn-primary btn-lg" id="nie">NIE</button></div>';
    }

    private function sprzedajWszystkie()
    {
        $rezultat = $this->model->wszystkieBezShiny();
        $ile = $rezultat['rowCount'];
        $wart = 0;
        $kwer = "DELETE FROM pokemon_jagody WHERE (";
        for ($i = 0; $i < $ile; $i++) {
            $wiersz = $rezultat[$i];
            $wart += $wiersz['wartosc'];
            if ($i == 1) $kwer .= "id_poka = '$wiersz[ID]'";
            else $kwer .= "OR id_poka = '$wiersz[ID]'";
        }
        $kwer .= ")";
        $this->model->db->delete($kwer, [], $ile);
        $this->model->usunWszystkie($ile);
        $this->model->dodajPieniadze($wart);
        Session::_set('kasa', (Session::_get('kasa') + $wart));
        Session::_set('poki_magazyn', (Session::_get('poki_magazyn') - $ile));
        $this->view->komunikat = '<div class="alert alert-success text-medium"><span>Pokemony sprzedane za ' . number_format($wart, 0, '', '.') . ' &yen;</span></div>';//col i row
    }

    private function pokiDoSprzedania()
    {
        $rezultat = $this->model->doSprzedania();
        $ile = $rezultat['rowCount'];
        $this->view->iloscPokemonow = $ile;

        if ($ile > 0) {
            for ($i = 0; $i < $ile; $i++) {
                $this->view->pokemon[$i] = $rezultat[$i];
            }
        }
    }
}

?>
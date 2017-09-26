<?php

namespace src\controllers;

use src\libs\Controller;
use src\libs\Session;
use src\libs\User;

class Panel extends Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!Session::_get('admin')) {
            echo 'Brak dostępu';
            exit;
        }
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('Panel Admina');
        }
    }

    public function index()
    {
        $this->view->render('panel/index');
    }

    public function wyloguj()
    {
        if (isset($_GET['wyloguj']) && $_GET['wyloguj']) {
            $this->model->db->update('UPDATE uzytkownicy SET id_sesji = \'\', ost_aktywnosc = 0 WHERE admin = 0', []);
            $this->view->wyloguj = 'Wylogowano graczy';
        } else {
            $this->view->wyloguj = 'Czy na pewno chcesz wylogować graczy?<br />';
            $this->view->wyloguj .= '<a href="'.URL.'panel">NIE</a><br />';
            $this->view->wyloguj .= '<a href="'.URL.'panel/wyloguj/?wyloguj=1">TAK</a><br />';
        }
        $this->view->render('panel/wyloguj');
    }

    public function online()
    {
        $rez = $this->model->db->select('SELECT * FROM uzytkownicy WHERE id_sesji != \'\'');
        $ile = $rez['rowCount'];
        $this->view->ile = $ile;
        for ($i = 0 ; $i < $ile ; $i++) {
            $wiersz = $rez[$i];
            $this->view->online[$i] = var_export($wiersz,1);
        }
        $this->view->render('panel/online');
    }

    public function shiny()
    {
        $rezultat = $this->model->db->select('SELECT * FROM shiny WHERE ID = 1');
        $rezultat = $rezultat[0];
        $rezultat1 = $this->model->db->select("SELECT nazwa FROM pokemon WHERE id_poka = '$rezultat[id_poka]'");
        $rezultat1 = $rezultat1[0];
        $this->view->nazwa = $rezultat1['nazwa'];
        $this->view->ilosc = $rezultat['ilosc_do_zlapania'];

        if($rezultat['ilosc_do_zlapania'] > 0) {
            $this->dzicz = '<br />Dzicz: ';
            switch ($rezultat['dzicz']) {
                case 1:
                    $this->dzicz .= 'Polana';
                    break;
                case 2:
                    $this->dzicz .= 'Wyspa';
                    break;
                case 3:
                    $this->dzicz .= 'Grota';
                    break;
                case 4:
                    $this->dzicz .= 'Dom Strachów';
                    break;
                case 5:
                    $this->dzicz .= 'Góry';
                    break;
                case 6:
                    $this->dzicz .= 'Wodospad';
                    break;
            }
        }
        $this->view->render('panel/shiny');
    }

    public function gracze()
    {
        $rezultat = $this->model->db->select('SELECT * FROM uzytkownicy');
        $ile = $rezultat['rowCount'];
        for ($i = 0 ; $i < $ile ; $i++) {
            $this->view->gracz[$i] = $rezultat[$i];
        }
        $this->view->graczy = $ile;

        $this->view->render('panel/gracze');
    }

    public function pokemony()
    {
        $rezultat = $this->model->db->select('SELECT * FROM pokemony, pokemon, uzytkownicy WHERE 
                                              pokemony.id_poka = pokemon.id_poka AND pokemony.wlasciciel = uzytkownicy.ID');
        $ile = $rezultat['rowCount'];
        $this->view->pokemony = $ile;
        for ($i = 0 ; $i < $ile ; $i++) {
            $this->view->pokemon[$i] = $rezultat[$i];
        }

        $this->view->render('panel/pokemony');
    }

    public function ogloszenie()
    {
        if(isset($_POST['tytul'])) {
            //tu się będą dodawać ogłoszenia
            if ($_POST['tytul'] != '' && $_POST['tresc'] != '') {
                $data = date('Y-m-d');
                $this->model->db->insert('INSERT INTO ogloszenia (tytul, tresc, data) VALUES (?, ?, ?)', [$_POST['tytul'], $_POST['tresc'], $data]);
                $this->model->db->update('UPDATE uzytkownicy SET ogloszenie = (ogloszenie + 1)', []);
                $this->view->komunikat = 'OK';
            }
            else $this->view->komunikat = 'BŁĄD Z TREŚCIĄ<br />TYTUŁ: '.$_POST['tytul'].'<br />TREŚĆ:'.$_POST['tresc'];
        }

        $this->view->render('panel/ogloszenie');
    }

    public function sesja2()
    {
        for ($i = 1 ; $i < 7 ; $i++) {
            if (User::_isset('pok', $i) && User::$pok[$i]->get('id') > 0) {
                $this->view->pokemon[$i] = User::$pok[$i];
            }
        }
        for ($i = 1 ; $i < 9 ; $i++) {
            $this->view->odznaka[$i] = User::$odznaki->kanto[$i];
        }

        $this->view->render('panel/sesja2');
    }

    public function logi()
    {
        if (isset($_POST['id'])) {
            $this->view->id = $_POST['id'];
            if ($_POST['id'] != '') {
                $login = $this->model->db->select('SELECT login FROM uzytkownicy WHERE ID = :id', ['id' => $_POST['id']]);
                if ($login['rowCount']) {
                    $login  = $login[0];
                    $this->view->komunikat = '<div class="alert alert-info"><span>LOGI GRACZA '.$login['login'].'</span></div>';
                    $kwer = 'SELECT * FROM logowanie WHERE id_gracza = '.$_POST['id'];
                } else {
                    $this->view->komunikat = '<div class="alert alert-danger"><span>BRAK GRACZA O DANYM ID</span></div>'; exit;
                }
            } else {
                $this->view->komunikat = '<div class="alert alert-info"><span>LOGI WSZYSTKICH GRACZY</span></div>';
                $kwer = 'SELECT * FROM logowanie ';
            }

            if($_POST['mod'] != '' && $_POST['id'] != '')
                $kwer .= ' AND co = \''.$_POST['mod'].'\'';
            else if($_POST['mod'] != '')
                $kwer .= ' WHERE co = \''.$_POST['mod'].'\'';
            $kwer .= ' ORDER BY data DESC';
            $rezultat = $this->model->db->select($kwer);
            if($rezultat['rowCount']) {
                for($i = 0 ; $i < $rezultat['rowCount'] ; $i++) {
                    $this->view->log[$i] = $rezultat[$i];
                }
                $this->view->ilosc = $rezultat['rowCount'];
            }
        } else {
            $this->view->id = 0;
        }

        $this->view->render('panel/logi');
    }

}
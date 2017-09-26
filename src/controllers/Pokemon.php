<?php

namespace src\controllers;

use src\includes\functions\FunctionsDate;
use src\libs\Controller;
use src\libs\Session;
use src\libs\User;
use src\libs\View;

class Pokemon extends Controller
{
    use FunctionsDate;

    var $druzyna = 0;
    var $swoj = 0;
    var $id = 0;

    public function __construct()
    {
        parent::__construct();
        if (!isset($_GET['ajax']) && !isset($_GET['modal'])) {
            $this->loadTemplate('Stan - ' . NAME, 1, 0, ['<script src="'.URL.'public/js/jquery.wysibb.min.js"></script>',
            '<link rel="stylesheet" href="'.URL.'public/css/default/wbbtheme.css" />', '<script src="'.URL.'public/js/lang/pl.js"]></script>']
            );
        }
        require('./src/includes/pokemony/pokemon.php');
        require('./src/includes/pokemony/rodzaj.php');
        require('./src/includes/odpornosci/odpornosci.php');
        require('./src/includes/ataki/ataki.php');
        $this->pokemonPlik = $pokemon_plik;
        $this->ataki = $ataki;
        $this->rodzaj = $rodzaj;
        $this->view->rodzaj = $rodzaj;
        $this->odpornosci = $odpornosci;
    }

    public function index(int $id = 0)
    {
        if (Session::_isset('podgladPoka')) {
            $this->podglad($id);
            return;
        }
        $this->view->druzyna = 0;
        $this->ilosc = 1;
        if (isset($_POST['id'])) $id = $_POST['id'];
        if ($id == 0) {
            $this->id = User::_get('pok',1 )->get('id');
            $this->druzyna = 1;
            $this->view->druzyna = 1;
            $this->swoj = 1;
        } else {
            $this->id = $id;
            $this->istnieje();
            $this->czyDruzyna();
        }
        if (!$this->swoj) {
            $this->czyZablokowany();
        }
        $this->wlasciciel();
        if ($this->druzyna) {
            $this->tabelkaDruzyna();
            $this->pokemon = $this->model->pobierz(Session::_get('id'));
        }
        $this->wyswietlPoki();

        $this->view->render('pokemon/index');
        if (!isset($_GET['ajax']) && !isset($_GET['modal'])) {
            $this->loadTemplate('', 2);
        }
    }

    private function wyswietlPoki()
    {
        if ($this->ilosc == 1 && !$this->druzyna) {
            $this->zrobPoka($this->pokemon, 1);
        } else {
            for ($i = 0 ; $i < $this->ilosc ; $i++) {
                $this->zrobPoka($this->pokemon[$i], $i);
            }
        }
    }

    private function wlasciciel()
    {
        $wl = $this->model->login($this->pokemon['wlasciciel']);
        $this->wlasciciel = $wl[0]['login'];
    }


    private function zrobPoka($pokemon, $i)
    {
        $this->view->pokemon[$i] = $pokemon;
        if ($this->druzyna)
            $this->view->pokemon[$i]['druzyna'] = 1;
        else
            $this->view->pokemon[$i]['druzyna'] = 0;

        if ($this->druzyna == $i+1) $this->view->pokemon[$i]['active'] = 1;
        else $this->view->pokemon[$i]['active'] = 0;

        $this->view->pokemon[$i]['swoj'] = $this->swoj;
        $this->view->pokemon[$i]['wlasciciel'] = $this->wlasciciel;

        $this->view->pokemon[$i]['nazwa'] = $this->pokemonPlik[$pokemon['id_poka']]['nazwa'];
        $this->view->pokemon[$i]['typ1'] = $this->pokemonPlik[$pokemon['id_poka']]['typ1'];
        $this->view->pokemon[$i]['typ2'] = $this->pokemonPlik[$pokemon['id_poka']]['typ2'];

        $this->view->pokemon[$i]['typ1_o'] = $this->rodzaj[$this->view->pokemon[$i]['typ1']];
        $this->view->pokemon[$i]['typ2_o'] = $this->rodzaj[$this->view->pokemon[$i]['typ2']];

        $this->view->pokemon[$i]['przywiazanie'] = przywiazanie($pokemon['przywiazanie']);
        if ($this->view->pokemon[$i]['przywiazanie'] > 100)
            $this->view->pokemon[$i]['przywiazanie'] = 100;

        $this->view->pokemon[$i]['data_zlapania'] = $this->pokazDate($this->view->pokemon[$i]['data_zlapania']);

        if($this->view->pokemon[$i]['glod'] > 100) {
            $this->view->pokemon[$i]['glod'] = 100;
            $this->model->glod100($pokemon['ID']);
        }
        $this->view->pokemon[$i]['Atak'] = round($pokemon['jakosc'] / 100 * $pokemon['Atak']);
        $this->view->pokemon[$i]['Sp_Atak'] = round($pokemon['jakosc'] / 100 * $pokemon['Sp_Atak']);
        $this->view->pokemon[$i]['Obrona'] = round($pokemon['jakosc'] / 100 * $pokemon['Obrona']);
        $this->view->pokemon[$i]['Sp_Obrona'] = round($pokemon['jakosc'] / 100 * $pokemon['Sp_Obrona']);
        $this->view->pokemon[$i]['Szybkosc'] = round($pokemon['jakosc'] / 100 * $pokemon['Szybkosc']);
        $this->view->pokemon[$i]['HP'] = round($pokemon['jakosc'] / 100 * $pokemon['HP']);

        $this->view->pokemon[$i]['glod'] = round($this->view->pokemon[$i]['glod'], 2);
        $this->view->pokemon[$i]['Jag_Atak'] = intval($this->view->pokemon[$i]['Jag_Atak']/5);
        $this->view->pokemon[$i]['Jag_Sp_Atak'] = intval($this->view->pokemon[$i]['Jag_Sp_Atak']/5);
        $this->view->pokemon[$i]['Jag_Obrona'] = intval($this->view->pokemon[$i]['Jag_Obrona']/5);
        $this->view->pokemon[$i]['Jag_Sp_Obrona'] = intval($this->view->pokemon[$i]['Jag_Sp_Obrona']/5);
        $this->view->pokemon[$i]['Jag_Szybkosc'] = intval($this->view->pokemon[$i]['Jag_Szybkosc']/5);

        $this->view->pokemon[$i]['tr_6'] = $this->view->pokemon[$i]['tr_6'] * 5;

        $this->view->pokemon[$i]['Atak_caly'] = $this->view->pokemon[$i]['tr_1'] + $this->view->pokemon[$i]['Atak'] + $this->view->pokemon[$i]['Jag_Atak'];
        $this->view->pokemon[$i]['Sp_Atak_caly'] = $this->view->pokemon[$i]['tr_2'] + $this->view->pokemon[$i]['Sp_Atak'] + $this->view->pokemon[$i]['Jag_Sp_Atak'];
        $this->view->pokemon[$i]['Obrona_caly'] = $this->view->pokemon[$i]['tr_3'] + $this->view->pokemon[$i]['Obrona'] + $this->view->pokemon[$i]['Jag_Obrona'];
        $this->view->pokemon[$i]['Sp_Obrona_caly'] = $this->view->pokemon[$i]['tr_4'] + $this->view->pokemon[$i]['Sp_Obrona'] + $this->view->pokemon[$i]['Jag_Sp_Obrona'];
        $this->view->pokemon[$i]['Szybkosc_caly'] = $this->view->pokemon[$i]['tr_5'] + $this->view->pokemon[$i]['Szybkosc'] + $this->view->pokemon[$i]['Jag_Szybkosc'];
        $this->view->pokemon[$i]['HP_caly'] = $this->view->pokemon[$i]['tr_6'] + $this->view->pokemon[$i]['HP'] + $this->view->pokemon[$i]['Jag_HP'];

        $this->view->pokemon[$i]['Jag_Limit'] = $this->view->pokemon[$i]['Jag_Limit'] / 5;

        //opis
        if($pokemon['opis'] == '<span></span><br>' || $pokemon['opis'] == '') $this->view->pokemon[$i]['opis'] = '<div class="alert alert-warning text-medium"><span>Ten Pokemon nie został opisany</span></div>';
        else $this->view->pokemon[$i]['opis'] = '<div class="well well-info c-black">'. html_zn($pokemon['opis']) . '</div>';

        //odpornosci
        if ($this->view->pokemon[$i]['typ2']) {
            for ($v = 1 ; $v < 19 ; $v++) {
                $this->view->pokemon[$i]['odp_'.$v] = $this->odpornosci[$this->view->pokemon[$i]['typ1']]['typ'.$v] * $this->odpornosci[$this->view->pokemon[$i]['typ2']]['typ'.$v];
            }
        } else {
            for ($v = 1 ; $v < 19 ; $v++) {
                $this->view->pokemon[$i]['odp_'.$v] = $this->odpornosci[$this->view->pokemon[$i]['typ1']]['typ'.$v];
            }
        }

        //ataki
        $this->view->pokemon[$i]['ataki'] = 0;
        for ($j = 1 ; $j < 5 ; $j++) {
            if ($pokemon['atak'.$j]) {
                $this->view->pokemon[$i]['ataki']++;
                $this->view->pokemon[$i]['atak'][$j] = $this->ataki[$pokemon['atak'.$j]];
            }
        }
    }


    private function tabelkaDruzyna()
    {
        for ($i = 1 ; $i <= 7 ; $i++) {
            if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id')) {
                $this->view->tabelka[$i]['shiny'] = User::_get('pok', $i)->get('shiny');
                $this->view->tabelka[$i]['active'] = $this->druzyna == $i ? 1 : 0;
                $this->view->tabelka[$i]['ID'] = User::_get('pok', $i)->get('id');
                $this->view->tabelka[$i]['id_p'] = User::_get('pok', $i)->get('id_p');
            } else {
                $this->ilosc = $i-1;
                return;
            }
        }
    }

    private function czyZablokowany()
    {
        if ($this->pokemon['blokada_podgladu']) {
            $this->error('Ten Pokemon ma zablokowany podgląd.');
        }
    }

    private function istnieje()
    {
        $this->pokemon = $this->model->pokemonInfo($this->id);
        if (!$this->pokemon['rowCount']) {
            $this->error('Pokemon o podanym ID nie istnieje.');
        }
        $this->pokemon = $this->pokemon[0];
    }

    private function czyDruzyna()
    {
        for ($i = 1 ; $i < 7 ; $i++) {
            if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') == $this->id) {
                $this->druzyna = $i;
                $this->swoj = 1;
                $this->view->druzyna = 1;
                return;
            }
        }
    }

    private function error($str)
    {
        $this->view->blad = $str;
        $this->view->render('pokemon/index');
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('', 2);
        }
        exit;
    }

    public function imie($imie, $pokemon)
    {
        $a = $this->model->czyIstnieje($pokemon);
        if (!$a['rowCount']) {
            $this->error('Błędny ID Pokemona.');
        }
        if ((mb_strlen($imie)<3) || (mb_strlen($imie)>15)) {
            $this->error('Imię Pokemona musi zawierać od 3 do 15 znaków.');
        }
        $sprawdz = '/^[0-9A-Za-z]*$/';
        if (!preg_match($sprawdz, $imie)) {
            $this->error('Imie Pokemona zawiera niedozwolone znaki.');
        }
        $this->model->zmienImie($imie, $pokemon);
        $this->view->komunikat = '<div class="alert alert-success"><span>Poprawnie zmieniono imię Pokemona.</span></div>';
        $wiersz['imie'] = $imie;
        for ($i = 1 ; $i < 7 ; $i++) {
            if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') == $pokemon) {
                User::_get('pok', $i)->edit('imie', $imie);
                break;
            }
        }
        $this->index($pokemon);
    }

    public function down(int $i, int $pokemon)
    {
        if ($i > 3 && $i < 1) {
            $this->error('Nie można zmienić kolejności tego ataku');
        }
        $rezultat = $this->model->pokemonInfo($pokemon);
        if (!$rezultat['rowCount']) {
            $this->error('Błędne ID Pokemona');
        }
        $wyzsza = $i + 1;
        $w = $rezultat[0];
        $at1 = $w['atak'.$i];
        $at2 = $w['atak'.$wyzsza];
        $this->model->atakWyzszy($wyzsza, $i, $at1, $at2, $pokemon);
        $this->view->komunikat = 'Poprawnie zmieniono kolejność ataku';
        $this->index($pokemon);
    }

    public function up(int $i, int $pokemon)
    {
        if ($i > 4 && $i < 2) {
            $this->error('Nie można zmienić kolejności tego ataku');
        }
        $rezultat = $this->model->pokemonInfo($pokemon);
        if (!$rezultat['rowCount']) {
            $this->error('Błędne ID Pokemona');
        }
        $liczba = $i;
        $wyzsza = $liczba - 1;
        $w = $rezultat[0];
        $at1 = $w['atak'.$i];
        $at2 = $w['atak'.$wyzsza];
        $this->model->atakNizszy($wyzsza, $i, $at1, $at2, $pokemon);
        $this->view->komunikat = 'Poprawnie zmieniono kolejność ataku';
        $this->index($pokemon);
    }

    public function nakarm(int $pokemon)
    {
        $rezultat = $this->model->pokemonInfo($pokemon);
        if (!$rezultat['rowCount']) {
            $this->view->error = 'Pokemon nie istnieje';
            $this->view->render('pokemon/nakarm');
            if (!isset($_GET['ajax'])) {
                $this->index($pokemon);
            }
            return;
        }
        $rezultat = $rezultat[0];
        $karmienie = $this->model->karmienie($rezultat['wlasciciel']);
        $karmienie = $karmienie[0];
        $karmienie = explode('|', $karmienie['karmienie_ip']);
        $ip = $_SERVER['REMOTE_ADDR'];
        $czy_karmil = 0;
        for ($i = 0 ; $i < count($karmienie) ; $i++) {
            if ($karmienie[$i] && $karmienie[$i] == $ip) {
                $czy_karmil = 1;
                break;
            }
        }
        //sprawdzenie czy dany gracz karmił danego pokemona
        if ($czy_karmil) {
            $this->view->error = 'Karmiłeś dzisiaj Pokemony tego gracza';
            $this->view->render('pokemon/nakarm');
            if (!isset($_GET['ajax'])) {
                $this->index($pokemon);
            }
            return;
        }
        //sprawdzenie czy Pokemon jadł nie więcej niż 50 razy dzisiaj
        if(count($karmienie) > 50) {
            $this->view->error = 'Pokemon jest już najedzony i nie może przyjąć kolejnego posiłku.';
            $this->view->render('pokemon/nakarm');
            if (!isset($_GET['ajax'])) {
                $this->index($pokemon);
            }
            return;
        }
        if (!isset($this->view->error)) {
            $this->model->nakarm($ip, $rezultat['wlasciciel']);
            $this->view->komunikat =  'Pokemon z chęcią zjada zaoferowany posiłek.';
            $this->view->render('pokemon/nakarm');
        }


        if (!isset($_GET['ajax'])) {
            $this->index($pokemon);
        }
    }

    public function ewo(int $i, int $pokemon)
    {
        $rezultat = $this->model->ewolucja($pokemon);
        $ile = $rezultat['rowCount'];
        if( $ile <= 0) {
            $this->view->error = 'Pokemon o podanym ID nie istnieje';
            $this->index($pokemon);
            return;
        }
        if($i != 0 && $i != 1) $i = 0;
        $this->model->zmienEwolucja($i, $pokemon);
        $i ? $this->view->komunikat = 'Zabroniono ewolucji' : $this->view->komunikat = 'Zezwolono na ewolucję';
        for ($j = 1 ; $j < 7 ; $j++) {
            if (User::_isset('pok', $j) && $pokemon == User::_get('pok', $j)->get('id')) {
                User::_get('pok', $j)->edit('ewo', $i);
                break;
            }
        }
        $this->index($pokemon);
    }

    public function zablokuj(int $i, int $pokemon)
    {
        $rezultat = $this->model->podglad($pokemon);
        if (!$rezultat['rowCount']) {
            $this->view->error = 'Pokemon o podanym ID nie istnieje';
            $this->index($pokemon);
            return;
        }
        if($i != 0 && $i != 1) $i = 0;
        $this->model->zmienBlokada($i, $pokemon);
        $i ? $this->view->komunikat = 'Pokemon został ukryty' : $this->view->komunikat = 'Pokemon nie jest już ukryty';
        $this->index($pokemon);
    }

    private function podglad($id)
    {
        $this->header = new View();
        $this->header->language = 'pl';
        $this->header->styl = '<link rel="stylesheet" href="'.URL.'public/css/style_black.css" type="text/css" id="black" >';
        $this->header->dodatek = '';

        $this->header->render('template/header');
        Session::_unset('podgladPoka');
        $this->id = $id;
        $this->istnieje();
        $this->czyZablokowany();
        $this->ilosc = 1;
        $this->wlasciciel();
        $this->wyswietlPoki();
        $this->view->render('pokemon/notlogged');
    }
}

?>
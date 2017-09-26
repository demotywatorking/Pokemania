<?php

namespace src\controllers;

use src\libs\Controller;
use src\libs\Session;

class Wymien extends Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('Wymiana - ' . NAME);
        }
        if (!isset($_GET['active']))
            $this->view->active = 1;
        else
            $this->view->active = $_GET['active'];
    }

    public function index()
    {
        $this->tabelki();
        $this->pokiWymiana();
        $this->view->render('wymien/index');
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('', 2);
        }
    }

    public function oddaj($pok)
    {
        $pok_wymien = $this->model->pokDoWymiany($pok);
        if ($pok_wymien['rowCount']) {
            $this->view->komunikat = 'Odebrano ' . $this->zrobPoka($pok_wymien[0]['id_poka']) . '. Pokemon znajduje się w Twojej rezerwie.';
            $this->model->usunZWymiany($pok);
        } else {
            $this->view->blad = 'Nieprawidłowe ID lub Pokemon jeszcze nie może być odebrany.';
        }
        $this->index();
    }

    private function pokiWymiana()
    {
        $poki_wymien = $this->model->wszystkiePokiWWymianie();
        if ($poki_wymien['rowCount']) {
            $this->view->wymiana = 1;
            require('./src/includes/pokemony/pokemon.php');
            for ($i = 0; $i < $poki_wymien['rowCount']; $i++) {
                $pokemon_w = $poki_wymien[$i];
                $this->view->pokWymiana[$i] = $pokemon_w;
                $this->view->pokWymiana[$i]['nazwa'] = $pokemon_plik[$pokemon_w['id_poka']]['nazwa'];
                if (time() >= $pokemon_w['czas']) {
                    $this->view->pokWymiana[$i]['czas'] = 1;
                } else {
                    $this->view->pokWymiana[$i]['czas'] = 0;
                    $czas = $pokemon_w['czas'] - time();
                    $this->view->pokWymiana[$i]['czasPoka'] = 'Pozostały czas: ';
                    $godz = intval($czas / 3600);
                    $czas -= $godz * 3600;
                    $min = intval($czas / 60);
                    $czas -= $min * 60;
                    if ($godz) $this->view->pokWymiana[$i]['czasPoka'] .= $godz . ' godzin ' . $min . ' minut ' . $czas . ' sekund.';
                    else if ($min) $this->view->pokWymiana[$i]['czasPoka'] .= $min . ' minut ' . $czas . ' sekund.';
                    else $this->view->pokWymiana[$i]['czasPoka'] .= $czas . ' sekund.';
                }
            }
        } else {
            $this->view->wymiana = 0;
        }
    }

    private function tabelki()
    {
        if (!isset($this->przedmioty)) {
            $this->przedmioty = $this->model->przedmiotyDoWymiany();
            $this->przedmioty = $this->przedmioty[0];
            $this->view->skamieliny = $this->przedmioty['czesci'];
            $this->view->monety = $this->przedmioty['monety'];
        }
    }

    public function wymien($wymien)
    {
        $this->tabelki();
        $poke = 0;
        $co = 0;
        $minus = 0;
        switch ($wymien) {
            case 133://eevee
                if ($this->przedmioty['monety'] >= 150) {
                    $poke = 133;
                    $minus = 150;
                }
                break;
            case 132: //ditto
                if ($this->przedmioty['monety'] >= 50) {
                    $poke = 132;
                    $minus = 50;
                }
                break;
            case 'masterball':
                if ($this->przedmioty['monety'] >= 120) {
                    $minus = 120;
                    $this->model->masterball();
                    $this->view->komunikat = 'Wymieniono 120 dukatów na Masterballa.';
                }
                break;
            case 'candy':
                if ($this->przedmioty['monety'] >= 100) {
                    $minus = 100;
                    $co = 'candy';
                    $this->view->komunikat = 'Wymieniono 100 dukatów na Rare Candy.';
                }
                break;
            case 'czesc':
                if ($this->przedmioty['monety'] >= 80) {
                    $minus = 80;
                    $co = 'czesci';
                    $this->view->komunikat = 'Wymieniono 80 dukatów na cześć skamieliny.';
                }
                break;
        }
        if ($minus) {
            $this->przedmioty['monety'] -= $minus;
            if (!$co) {
                $this->model->monetyWymien($minus);
            } else {
                $this->model->monetyPlusPrzedmiot($co, $minus);
            }
        } else {
            $this->view->blad = 'Posiadasz za mało dukatów.';
            return;
        }
        if ($poke) {
            $this->view->komunikat = 'Odebrano ' . $this->zrobPoka($poke) . '. za ' . $minus . ' dukatów. Pokemon znajduje się w Twojej rezerwie.';
        }
        $this->index();
    }

    private function zrobPoka($poke)
    {
        require('./src/includes/pokemony/pokemon.php');
        require('./src/includes/functionsPolowanie.php');
        $pokemon_gotowy = generuj_poka($poke, 1, $pokemon_plik[$poke]['ataki']);
        for ($i = 0; $i < 4; $i++) {
            if (isset($_SESSION['atak' . $i]))
                $pokemon_gotowy['atak' . ($i + 1)] = $_SESSION['atak' . $i]['id'];
            unset($_SESSION['atak' . $i]);
        }
        if ($pokemon_plik[$poke]['plec_k'] == '0' && $pokemon_plik[$poke]['plec_m'] == '0')
            $plec = 2;
        elseif ($pokemon_plik[$poke]['plec_k'] == '0')
            $plec = 0;
        elseif ($pokemon_plik[$poke]['plec_m'] == '0')
            $plec = 1;
        else {
            $_0 = $pokemon_plik[$poke]['plec_m'];
            $p = mt_rand() % 1000;
            if ($p < $_0)
                $plec = 0;
            else
                $plec = 1;
        }
        unset($pokemon_gotowy['pok_id']);
        $pokemon_gotowy['Atak'] = $pokemon_gotowy['pok_atak'];
        $pokemon_gotowy['Sp_Atak'] = $pokemon_gotowy['pok_sp_atak'];
        $pokemon_gotowy['Obrona'] = $pokemon_gotowy['pok_obrona'];
        $pokemon_gotowy['Sp_obrona'] = $pokemon_gotowy['pok_sp_obrona'];
        $pokemon_gotowy['Szybkosc'] = $pokemon_gotowy['pok_szybkosc'];
        $pokemon_gotowy['HP'] = $pokemon_gotowy['pok_hp'];
        $pokemon_gotowy['akt_HP'] = 0.75 * $pokemon_gotowy['pok_hp'];
        $pokemon_gotowy['poziom'] = $pokemon_gotowy['pok_poziom'];
        $pokemon_gotowy['jakosc'] = 75;
        unset($pokemon_gotowy['pok_szybkosc']);
        unset($pokemon_gotowy['pok_atak']);
        unset($pokemon_gotowy['pok_sp_atak']);
        unset($pokemon_gotowy['pok_sp_obrona']);
        unset($pokemon_gotowy['pok_obrona']);
        unset($pokemon_gotowy['pok_hp']);
        unset($pokemon_gotowy['pok_poziom']);

        $pokemon_gotowy['id_poka'] = $poke;
        $pokemon_gotowy['plec'] = $plec;
        $pokemon_gotowy['blokada'] = 1;
        $pokemon_gotowy['imie'] = $pokemon_plik[$poke]['nazwa'];
        $pokemon_gotowy['wartosc'] = 1000000;
        $pokemon_gotowy['przywiazanie'] = 500;
        $pokemon_gotowy['wlasciciel'] = Session::_get('id');
        $pokemon_gotowy['pierwszy_wlasciciel'] = Session::_get('id');
        $pokemon_gotowy['data_zlapania'] = date('Y-m-d H:i:s');
        $pokemon_gotowy['zlapany'] = 'wymiana';
        $kwer = 'INSERT INTO pokemony ( ';
        $kwer2 = ') VALUES ( ';
        foreach ($pokemon_gotowy as $key => $value) {
            $kwer .= $key . ', ';
            $kwer2 .= '\'' . $value . '\'' . ', ';
        }
        $kwer = rtrim($kwer, ', ');
        $kwer2 = rtrim($kwer2, ', ');
        $kwerenda = $kwer . $kwer2 . ' )';
        $this->model->db->insert($kwerenda, []);
        $id = $this->model->db->lastInsertId();
        $limit = mt_rand(50, 75) * 5;
        $this->model->dodajPokaKolekcja($id, $limit, $poke);
        return $pokemon_gotowy['imie'];
    }

    public function skamielina($wymien)
    {
        if (!in_array($wymien, [142, 138, 140])) {
            $this->view->blad = 'Nieprawidłowy ID Pokemona';
            $this->index();
            return;
        }
        $this->tabelki();
        require('./src/includes/pokemony/pokemon.php');
        if (isset($_GET['tak'])) {
            $wymien_m = 0;
            $czesci = 0;
            switch ($wymien) {
                case 142://aerodactyl
                    if ($this->przedmioty['czesci'] >= 65) {
                        $wymien_m = 1;
                        $czesci = 65;
                        $nazwa = 'Aerodactyla';
                    }
                    break;
                case 140://kabuto
                    if ($this->przedmioty['czesci'] >= 40) {
                        $wymien_m = 1;
                        $czesci = 40;
                        $nazwa = 'Kabuto';
                    }
                    break;
                case 138://omanyte
                    if ($this->przedmioty['czesci'] >= 40) {
                        $wymien_m = 1;
                        $czesci = 40;
                        $nazwa = 'Omanyte';
                    }
                    break;
            }
            if ($wymien_m && $czesci) {
                $this->view->komunikat = 'Oddajesz skamieliny. Za 24 godziny możesz odebrać ' . $nazwa . '.';
                $this->model->wymienSkamielina($wymien, $czesci);
                $this->przedmioty['czesci'] -= $czesci;
            } else {
                $this->view->blad = 'Masz za mało części skamielin.';
            }
        } else {
            $this->view->komunikat = 'Czy na pewno chcesz wymienić ';
            if ($wymien == 142) $this->view->komunikat .= '65';
            else if (in_array($wymien, [138, 140])) $this->view->komunikat .= '40';
            $this->view->komunikat .= ' części na ' . $pokemon_plik[$wymien]['nazwa'] . '?';
            $this->view->komunikat .= '<div class="row row-centered margin-bottom"><button class="btn btn-primary tak" id="' . $wymien . '">TAK</button><button class="btn btn-primary nie">NIE</button></div>';
        }
        $this->index();
    }

}

?>
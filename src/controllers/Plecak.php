<?php

namespace src\controllers;

use src\libs\Controller;
use src\libs\Session;
use src\libs\User;

class Plecak extends Controller
{

    private $przedmioty;

    public function __construct()
    {
        parent::__construct();
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('Plecak -' . NAME, 1, 1, ['<link rel="stylesheet" href="' . URL . 'public/css/select.css">',
                '<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">']);
        }
        require('./src/includes/plecak.php');
        $this->pokeballe = $pokeballe;
        $this->jagody = $jagody;
        $this->kamienie = $kamienie;
        $this->karta = $karta;
        if (!isset($_GET['active']))
            $this->view->active = 1;
        else
            $this->view->active = $_GET['active'];
    }

    public function index()
    {
        if (!isset($this->przedmioty)) {
            $this->getPrzedmioty();
        }
        $this->przedmiotyZakladka();
        $this->pokeballeZakladka();
        $this->jagodyZakladka();
        $this->kamienieZakladka();
        $this->inneZakladka();
        $this->kartyZakladka();
        $this->generujZawartosc();
    }

    public function rodzaj($co = '', $ilosc = 1, $pok = 0)
    {
        if (in_array($co, ['lemoniada', 'woda', 'soda', 'karma', 'baton', 'ciastko', 'candy'])) {
            $this->getPrzedmioty();
            $this->$co($ilosc, $pok);
        } else {
            $this->view->blad = 'Błędna nazwa przedmiotu.';
        }
        if (!isset($_GET['komunikat'])) {
            $this->index();
        } else {
            $this->view->render('plecak/komunikat');
        }
    }

    public function jagoda($co = '', $ilosc = 1, $pok = 0)
    {
        if (in_array($co, ['Cheri_Berry', 'Wiki_Berry', 'Chesto_Berry', 'Mago_Berry', 'Pecha_Berry', 'Aguav_Berry', 'Rawst_Berry', 'Lapapa_Berry', 'Aspear_Berry',
            'Razz_Berry', 'Leppa_Berry', 'Oran_Berry', 'Persim_Berry', 'Lum_Berry', 'Sitrus_Berry', 'Figy_Berry'])) {
            $this->getPrzedmioty();
            $co = lcfirst(str_replace('_', '', $co));
            $this->$co($ilosc, $pok);
        } else {
            $this->view->blad = 'Błędna nazwa jagody.';
        }
        if (!isset($_GET['komunikat'])) {
            $this->index();
        } else {
            $this->view->render('plecak/komunikat');
        }
    }

    public function kamien($co = '', $pok = 0)
    {
        if (in_array($co, ['ogniste', 'wodne', 'gromu', 'roslinne', 'ksiezycowe'])) {
            $this->getPrzedmioty();
            require('./src/includes/pokemony/przyrosty.php');
            $this->przyrost = $przyrost;
            $this->uzyjKamien($co, $pok);
        } else {
            $this->view->blad = 'Błędna nazwa kamienia.';
        }
        if (!isset($_GET['komunikat'])) {
            $this->index();
        } else {
            $this->view->render('plecak/komunikat');
        }
    }

    private function uzyjKamien($co, $pok)
    {
        $kamien = $this->przedmioty[$co];
        if ($kamien) {
            $rezultat = $this->model->getPokInfo($pok);
            if ($rezultat['rowCount']) {
                $id = 0;
                $w = $rezultat[0];
                switch ($co) {
                    case 'ogniste' :
                        if ($w['wymagania'] == 1) {
                            $id = $w['ewolucja_p'];
                        } elseif ($w['wymagania'] == 123) {
                            $id = 136; //eevee
                        }
                        break;
                    case 'wodne' :
                        if ($w['wymagania'] == 2) {
                            $id = $w['ewolucja_p'];
                            if ($id == 62000186) $id = 62;
                        } elseif ($w['wymagania'] == 123) {
                            $id = 134;
                        }
                        break;
                    case 'gromu' :
                        if ($w['wymagania'] == 3) {
                            $id = $w['ewolucja_p'];
                        } elseif ($w['wymagania'] == 123) {
                            $id = 135;
                        }
                        break;
                    case 'roslinne' :
                        if ($w['wymagania'] == 4) {
                            $id = $w['ewolucja_p'];
                            if ($id == 45000182) $id = 45;
                        }
                        break;
                    case 'ksiezycowe' :
                        if ($w['wymagania'] == 5) {
                            $id = $w['ewolucja_p'];
                        }
                        break;
                }
                if ($id) {
                    $rezultat = $this->model->getPokemonNazwa($id);
                    $ww = $rezultat[0];
                    $this->model->updateKamienie($co);
                    $this->przedmioty[$co]--;
                    if ($w['nazwa'] == $w['imie']) {
                        $imie = $ww['nazwa'];
                    } else {
                        $imie = $w['nazwa'];
                    }
                    $this->view->komunikat = 'Twój Pokemon ewoluuje.';
                    $wa = $this->przyrost[$id];
                    $atak = $wa['atak'] * 3;
                    $sp_atak = $wa['sp_atak'] * 3;
                    $obrona = $wa['obrona'] * 3;
                    $sp_obrona = $wa['sp_obrona'] * 3;
                    $szybkosc = $wa['szybkosc'] * 3;
                    $hp = $wa['hp'] * 3;
                    $i = $id . "s";
                    $ii = $id . "z";
                    $this->model->updateKolekcja($i, $ii);
                    $this->model->updatePokemon($atak, $sp_atak, $obrona, $sp_obrona, $szybkosc, $hp, $id, $imie, $pok);
                    $tytul = 'Twój Pokemon ' . $w['imie'] . ' ewoluował w ' . $ww['nazwa'] . '.';
                    $raport = '<div class="row nomargin text-center"><div class="col-xs-12">Twój Pokemon <span class="pogrubienie">' . $w['imie'] . '</span> ewoluował w <span class="pogrubienie">' . $ww['nazwa'] . '</span>.</div>'
                        . '<div class="col-xs-12 pogrubienie">Jego statystyki rosną:</div><div class="col-xs-12"><div class="row nomargin">'
                        . '<div class="col-xs-4">Atak +' . $atak . '</div><div class="col-xs-4">Sp. Atak +' . $sp_atak . '</div><div class="col-xs-4">Obrona +' . $obrona . '</div></div></div> '
                        . '<div class="col-xs-12"><div class="row nomargin">'
                        . '<div class="col-xs-4">Sp.Obrona +' . $sp_obrona . '</div><div class="col-xs-4">Szybkość +' . $szybkosc . '</div><div class="col-xs-4">HP +' . $hp . '</div></div></div></div>';
                    $godzina = date('Y-m-d-H-i-s');
                    $this->model->insertPoczta($raport, $godzina, $tytul);
                    for ($i = 1; $i < 7; $i++) {
                        if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') == $pok) {
                            User::_get('pok', $i)->edit('id_p', $id);
                            if (isset($imie))
                                User::_get('pok', $i)->edit('imie', $imie);
                            User::_get('pok', $i)->edit('zycie', (User::_get('pok', $i)->get('zycie') + round($hp * $w['jakosc'] / 100)));
                            User::_get('pok', $i)->edit('akt_zycie', User::_get('pok', $i)->get('zycie'));
                            break;
                        }
                    }
                } else {
                    $this->view->blad = 'Dałeś kamień Pokemonowi, ale nic ciekawego się nie wydarzyło.';
                }
            } else {
                $this->view->blad = 'Błędny ID Pokemona.';
            }
        } else {
            $this->view->blad = 'Nie posiadasz kamienia ';
            switch ($co) {
                case 'ogniste':
                    $this->view->blad .= 'ognistego.';
                    break;
            }
        }
    }

    private function aspearBerry($ilosc)
    {
        $w = $this->przedmioty['Aspear_Berry'];
        if (!$w) {
            $this->view->blad = 'Nie posiadasz Aspear Berry.';
            return;
        }
        if ($ilosc == 'all') $ilosc = $w;
        if (!is_numeric($ilosc) || $ilosc < 0) {
            $this->view->blad = 'Błędna ilość.';
            return;
        }
        if ($w < $ilosc) $ilosc = $w;
        $rez = $this->model->jagodyPa();
        $rez = $rez[0];
        if ($rez['jagody_pa'] < 3000) {
            $ile_jest = floor($rez['jagody_pa'] / 15);
            $ile_po = floor(($rez['jagody_pa'] + $ilosc) / 15);
            if ($ile_po > 200) {
                $ile_po = 200;
                $ile = 3000 - $rez['jagody_pa'];
            }
            $this->model->updateJagody('Aspear_Berry', $ilosc);
            $this->przedmioty['Aspear_Berry'] -= $ilosc;
            if ($ile_po > $ile_jest) {
                $dod = $ile_po - $ile_jest;
                $kwery = "UPDATE uzytkownicy SET mpa = (mpa + $dod), jagody_pa = (jagody_pa + $ilosc) WHERE ID = " . Session::_get('id');
                $this->view->komunikat = 'Zjedzono ' . $ilosc . ' Aspear Berry! Twoje MPA wzrosło o ' . $dod . '.';
                Session::_set('mpa', (Session::_get('mpa') + $dod));
            } else {
                $kwery = "UPDATE uzytkownicy SET jagody_pa = (jagody_pa + $ilosc) WHERE ID = " . Session::_get('id');
                $this->view->komunikat = 'Zjedzono ' . $ilosc . ' Aspear Berry!';
            }
            $this->model->db->update($kwery, []);
        } else {
            $this->view->blad = 'Nie możesz zjeść więcej Aspear Berry.';
        }
    }

    private function figyBerry($ilosc, $pok)
    {
        $w = $this->przedmioty['Figy_Berry'];
        if (!$w) {
            $this->view->blad = 'Nie posiadasz Figy Berry.';
            return;
        }
        $pokDobry = 0;
        for ($i = 1; $i < 7; $i++) {
            if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') == $pok) {
                $pokDobry = $i;
                break;
            }
        }
        if (!$pokDobry) {
            $this->view->blad = 'Błędny ID Pokemona.';
            return;
        }
        if ($ilosc == 'max') {
            $il = 'do maksimum ilosci';
        } else {
            if (!is_numeric($ilosc) || $ilosc < 0) {
                $this->view->blad = 'Błędna ilość.';
                return;
            }
            $il = $ilosc;
        }

        if ($il != "do maksimum ilosci") {
            if ($il >= $w) {
                $il = $w;
            }
        } else {
            $il = $w;
        }
        $rez = $this->model->jagPokemon($pok);
        $ww = $rez[0];
        $rez2 = $this->model->jagHP($pok);
        $rez2 = $rez2[0];
        $ww['Jag_Limit'] -= $rez2['Jag_HP'];
        if (!$ww['Jag_Limit']) {
            $this->view->blad = 'Pokemon nie może zjeść więcej Figy Berry.';
        } else {
            if ($il > $ww['Jag_Limit']) {
                $il = $ww['Jag_Limit'];
            }
            $jag_przed = $rez2['Jag_HP'];
            $jag_po = $rez2['Jag_HP'] + $il;
            $roz = $jag_po - $jag_przed;
            $this->model->updateJagody('Figy_Berry', $il);
            $this->model->updateJagHP($il, $pok);
            $this->przedmioty['Figy_Berry'] -= $il;
            $this->view->komunikat = 'HP Pokemona rośnie o ' . $roz . '.';
            User::_get('pok', $pokDobry)->edit('zycie', (User::_get('pok', $pokDobry)->get('zycie') + $roz));
        }
    }

    private function sitrusBerry($ilosc, $pok)
    {
        $w = $this->przedmioty['Sitrus_Berry'];
        if (!$w) {
            $this->view->blad = 'Nie posiadasz Sitrus Berry.';
            return;
        }
        $pokDobry = 0;
        for ($i = 1; $i < 7; $i++) {
            if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') == $pok) {
                $pokDobry = 1;
                break;
            }
        }
        if (!$pokDobry) {
            $this->view->blad = 'Błędny ID Pokemona.';
            return;
        }
        if ($ilosc == 'max') {
            $il = 'do maksimum ilosci';
        } else {
            if (!is_numeric($ilosc) || $ilosc < 0) {
                $this->view->blad = 'Błędna ilość.';
                return;
            }
            $il = $ilosc;
        }

        if ($il != "do maksimum ilosci") {
            if ($il >= $w) {
                $il = $w;
            }
        } else {
            $il = $w;
        }
        $rez = $this->model->jagPokemon($pok);
        $ww = $rez[0];
        $ww['Jag_Limit'] -= $ww['Jag_Szybkosc'];
        if (!$ww['Jag_Limit']) {
            $this->view->blad = 'Pokemon nie może zjeść więcej Sitrus Berry.';
        } else {
            if ($il > $ww['Jag_Limit']) {
                $il = $ww['Jag_Limit'];
            }
            $jag_przed = floor($ww['Jag_Szybkosc'] / 5);
            $jag_po = floor(($ww['Jag_Szybkosc'] + $il) / 5);
            $roz = $jag_po - $jag_przed;
            $this->model->updateJagody('Sitrus_Berry', $il);
            $this->model->updateSzybkosc($il, $pok);
            $this->przedmioty['Sitrus_Berry'] -= $il;
            if ($roz) {
                $this->view->komunikat = 'Zjedzono ' . $il . ' Sitrus Berry. Szybkość Pokemona rośnie o ' . $roz . '.';
            } else {
                $this->view->komunikat = 'Zjedzono ' . $il . ' Sitrus Berry.';
            }
        }
    }

    private function lumBerry($ilosc, $pok)
    {
        $w = $this->przedmioty['Lum_Berry'];
        if (!$w) {
            $this->view->blad = 'Nie posiadasz Lum Berry.';
            return;
        }
        $pokDobry = 0;
        for ($i = 1; $i < 7; $i++) {
            if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') == $pok) {
                $pokDobry = 1;
                break;
            }
        }
        if (!$pokDobry) {
            $this->view->blad = 'Błędny ID Pokemona.';
            return;
        }
        if ($ilosc == 'max') {
            $il = 'do maksimum ilosci';
        } else {
            if (!is_numeric($ilosc) || $ilosc < 0) {
                $this->view->blad = 'Błędna ilość.';
                return;
            }
            $il = $ilosc;
        }

        if ($il != "do maksimum ilosci") {
            if ($il >= $w) {
                $il = $w;
            }
        } else {
            $il = $w;
        }
        $rez = $this->model->jagPokemon($pok);
        $ww = $rez[0];
        $ww['Jag_Limit'] -= $ww['Jag_Sp_Obrona'];
        if (!$ww['Jag_Limit']) {
            $this->view->blad = 'Pokemon nie może zjeść więcej Lum Berry.';
        } else {
            if ($il > $ww['Jag_Limit']) {
                $il = $ww['Jag_Limit'];
            }
            $jag_przed = floor($ww['Jag_Sp_Obrona'] / 5);
            $jag_po = floor(($ww['Jag_Sp_Obrona'] + $il) / 5);
            $roz = $jag_po - $jag_przed;
            $this->model->updateJagody('Lum_Berry', $il);
            $this->model->updateSpObrona($il, $pok);
            $this->przedmioty['Lum_Berry'] -= $il;
            if ($roz) {
                $this->view->komunikat = 'Zjedzono ' . $il . ' Lum Berry. Specjalna obrona Pokemona rośnie o ' . $roz . '.';
            } else {
                $this->view->komunikat = 'Zjedzono ' . $il . ' Lum Berry.';
            }
        }
    }

    private function persimBerry($ilosc, $pok)
    {
        $w = $this->przedmioty['Persim_Berry'];
        if (!$w) {
            $this->view->blad = 'Nie posiadasz Persim Berry.';
            return;
        }
        $pokDobry = 0;
        for ($i = 1; $i < 7; $i++) {
            if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') == $pok) {
                $pokDobry = 1;
                break;
            }
        }
        if (!$pokDobry) {
            $this->view->blad = 'Błędny ID Pokemona.';
            return;
        }
        if ($ilosc == 'max') {
            $il = 'do maksimum ilosci';
        } else {
            if (!is_numeric($ilosc) || $ilosc < 0) {
                $this->view->blad = 'Błędna ilość.';
                return;
            }
            $il = $ilosc;
        }

        if ($il != "do maksimum ilosci") {
            if ($il >= $w) {
                $il = $w;
            }
        } else {
            $il = $w;
        }
        $rez = $this->model->jagPokemon($pok);
        $ww = $rez[0];
        $ww['Jag_Limit'] -= $ww['Jag_Obrona'];
        if (!$ww['Jag_Limit']) {
            $this->view->blad = 'Pokemon nie może zjeść więcej Persim Berry.';
        } else {
            if ($il > $ww['Jag_Limit']) {
                $il = $ww['Jag_Limit'];
            }
            $jag_przed = floor($ww['Jag_Obrona'] / 5);
            $jag_po = floor(($ww['Jag_Obrona'] + $il) / 5);
            $roz = $jag_po - $jag_przed;
            $this->model->updateJagody('Persim_Berry', $il);
            $this->model->updateObrona($il, $pok);
            $this->przedmioty['Persim_Berry'] -= $il;
            if ($roz) {
                $this->view->komunikat = 'Zjedzono ' . $il . ' Persim Berry. Obrona Pokemona rośnie o ' . $roz . '.';
            } else {
                $this->view->komunikat = 'Zjedzono ' . $il . ' Persim Berry.';
            }
        }
    }

    private function oranBerry($ilosc, $pok)
    {
        $w = $this->przedmioty['Oran_Berry'];
        if (!$w) {
            $this->view->blad = 'Nie posiadasz Oran Berry.';
            return;
        }
        $pokDobry = 0;
        for ($i = 1; $i < 7; $i++) {
            if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') == $pok) {
                $pokDobry = 1;
                break;
            }
        }
        if (!$pokDobry) {
            $this->view->blad = 'Błędny ID Pokemona.';
            return;
        }
        if ($ilosc == 'max') {
            $il = 'do maksimum ilosci';
        } else {
            if (!is_numeric($ilosc) || $ilosc < 0) {
                $this->view->blad = 'Błędna ilość.';
                return;
            }
            $il = $ilosc;
        }

        if ($il != "do maksimum ilosci") {
            if ($il >= $w) {
                $il = $w;
            }
        } else {
            $il = $w;
        }
        $rez = $this->model->jagPokemon($pok);
        $ww = $rez[0];
        $ww['Jag_Limit'] -= $ww['Jag_Sp_Atak'];
        if (!$ww['Jag_Limit']) {
            $this->view->blad = 'Pokemon nie może zjeść więcej Oran Berry.';
        } else {
            if ($il > $ww['Jag_Limit']) {
                $il = $ww['Jag_Limit'];
            }
            $jag_przed = floor($ww['Jag_Sp_Atak'] / 5);
            $jag_po = floor(($ww['Jag_Sp_Atak'] + $il) / 5);
            $roz = $jag_po - $jag_przed;
            $this->model->updateJagody('Oran_Berry', $il);
            $this->model->updateSpAtak($il, $pok);
            $this->przedmioty['Oran_Berry'] -= $il;
            if ($roz) {
                $this->view->komunikat = 'Zjedzono ' . $il . ' Oran Berry. Specjalny atak Pokemona rośnie o ' . $roz . '.';
            } else {
                $this->view->komunikat = 'Zjedzono ' . $il . ' Oran Berry.';
            }
        }
    }

    private function leppaBerry($ilosc, $pok)
    {
        $w = $this->przedmioty['Leppa_Berry'];
        if (!$w) {
            $this->view->blad = 'Nie posiadasz Leppa Berry.';
            return;
        }
        $pokDobry = 0;
        for ($i = 1; $i < 7; $i++) {
            if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') == $pok) {
                $pokDobry = 1;
                break;
            }
        }
        if (!$pokDobry) {
            $this->view->blad = 'Błędny ID Pokemona.';
            return;
        }
        if ($ilosc == 'max') {
            $il = 'do maksimum ilosci';
        } else {
            if (!is_numeric($ilosc) || $ilosc < 0) {
                $this->view->blad = 'Błędna ilość.';
                return;
            }
            $il = $ilosc;
        }

        if ($il != "do maksimum ilosci") {
            if ($il >= $w) {
                $il = $w;
            }
        } else {
            $il = $w;
        }
        $rez = $this->model->jagPokemon($pok);
        $ww = $rez[0];
        $ww['Jag_Limit'] -= $ww['Jag_Atak'];
        if (!$ww['Jag_Limit']) {
            $this->view->blad = 'Pokemon nie może zjeść więcej Leppa Berry.';
        } else {
            if ($il > $ww['Jag_Limit']) {
                $il = $ww['Jag_Limit'];
            }
            $jag_przed = floor($ww['Jag_Atak'] / 5);
            $jag_po = floor(($ww['Jag_Atak'] + $il) / 5);
            $roz = $jag_po - $jag_przed;
            $this->model->updateJagody('Leppa_Berry', $il);
            $this->model->updateAtak($il, $pok);
            $this->przedmioty['Leppa_Berry'] -= $il;
            if ($roz) {
                $this->view->komunikat = 'Zjedzono ' . $il . ' Leppa Berry. Atak Pokemona rośnie o ' . $roz . '.';
            } else {
                $this->view->komunikat = 'Zjedzono ' . $il . ' Leppa Berry.';
            }
        }
    }

    private function razzBerry($ilosc)
    {
        $w = $this->przedmioty['Razz_Berry'];
        if (!$w) {
            $this->view->blad = 'Nie posiadasz Razz Berry.';
            return;
        }
        if ($ilosc == 'all') $ilosc = $w;
        if (!is_numeric($ilosc) || $ilosc < 0) {
            $this->view->blad = 'Błędna ilość.';
            return;
        }
        if ($w < $ilosc) $ilosc = $w;
        $rez = $this->model->jagodyPa();
        $rez = $rez[0];
        if ($rez['jagody_pa'] < 3000) {
            $ile_jest = floor($rez['jagody_pa'] / 15);
            $ile_po = floor(($rez['jagody_pa'] + 2 * $ilosc) / 15);
            if ($ile_po > 200) {
                $ile_po = 200;
                $ile = 3000 - $rez['jagody_pa'];
            }
            $this->model->updateJagody('Razz_Berry', $ilosc);
            $this->przedmioty['Razz_Berry'] -= $ilosc;
            if ($ile_po > $ile_jest) {
                $dod = $ile_po - $ile_jest;
                $kwery = "UPDATE uzytkownicy SET mpa = (mpa + $dod), jagody_pa = (jagody_pa + 2 * $ilosc) WHERE ID = " . Session::_get('id');
                $this->view->komunikat = 'Zjedzono ' . $ilosc . ' Razz Berry! Twoje MPA wzrosło o ' . $dod . '.';
                Session::_set('mpa', (Session::_get('mpa') + $dod));
            } else {
                $kwery = "UPDATE uzytkownicy SET jagody_pa = (jagody_pa + 2 * $ilosc) WHERE ID = " . Session::_get('id');
                $this->view->komunikat = 'Zjedzono ' . $ilosc . ' Razz Berry!';
            }
            $this->model->db->update($kwery, []);
        } else {
            $this->view->blad = 'Nie możesz zjeść więcej Razz Berry.';
        }
    }

    private function lapapaBerry($ilosc)
    {
        $w = $this->przedmioty['Lapapa_Berry'];
        if (!$w) {
            $this->view->blad = 'Nie posiadasz Lapapa Berry.';
            return;
        }
        if ($ilosc == 'all') {
            $il = 'do maksimum ilosci';
        } else {
            if (!is_numeric($ilosc) || $ilosc < 0) {
                $this->view->blad = 'Błędna ilość.';
                return;
            }
            $il = $ilosc;
        }

        if ($il != "do maksimum ilosci") {
            if ($il >= $w) {
                $il = $w;
            }
        } else {
            $il = $w;
        }
        $exp = 2 * $il;
        $this->model->updateJagody('Lapapa_Berry', $il);
        $this->model->userAddExp($exp);
        $this->view->komunikat = 'Zjedzono ' . $il . ' Lapapa Berry.<br />Otrzymujesz ' . $exp . ' PD.';
        $this->przedmioty['Lapapa_Berry'] -= $il;
        Session::_set('tr_exp', (Session::_get('tr_exp') + $exp));
    }

    private function rawstBerry($ilosc)
    {
        $w = $this->przedmioty['Rawst_Berry'];
        if (!$w) {
            $this->view->blad = 'Nie posiadasz Rawst Berry.';
            return;
        }
        if ($ilosc == 'all') {
            $il = 'do maksimum ilosci';
        } else {
            if (!is_numeric($ilosc) || $ilosc < 0) {
                $this->view->blad = 'Błędna ilość.';
                return;
            }
            $il = $ilosc;
        }

        if ($il != "do maksimum ilosci") {
            if ($il >= $w) {
                $il = $w;
            }
        } else {
            $il = $w;
        }
        $this->model->updateJagody('Rawst_Berry', $il);
        $this->model->userAddExp($il);
        $this->view->komunikat = 'Zjedzono ' . $il . ' Rawst Berry.<br />Otrzymujesz ' . $il . ' PD.';
        $this->przedmioty['Rawst_Berry'] -= $il;
        Session::_set('tr_exp', (Session::_get('tr_exp') + $il));
    }

    private function aguavBerry($ilosc, $pok)
    {
        $w = $this->przedmioty['Aguav_Berry'];
        $pokDobry = 0;
        for ($i = 1; $i < 7; $i++) {
            if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') == $pok) {
                $pokDobry = 1;
                break;
            }
        }
        if (!$pokDobry) {
            $this->view->blad = 'Błędny ID Pokemona.';
            return;
        }
        if (!$w) {
            $this->view->blad = 'Nie posiadasz Aguav Berry.';
            return;
        }

        if ($ilosc == 'max') {
            $il = 'do maksimum ilosci';
        } else {
            if (!is_numeric($ilosc) || $ilosc < 0) {
                $this->view->blad = 'Błędna ilość.';
                return;
            }
            $il = $ilosc;
        }
        if ($il != "do maksimum ilosci") {
            if ($il >= $w) {
                $il = $w;
            }
        } else {
            $il = $w;
        }
        $exp = $il * 5;
        $this->model->updateJagody('Aguav_Berry', $il);
        $this->model->pokemonAddExp($exp, $pok);
        $this->view->komunikat = 'Zjedzono ' . $il . ' Pecha Berry.<br />Pokemon otrzymuje ' . $exp . ' PD.';
        $this->przedmioty['Aguav_Berry'] -= $il;
        for ($i = 1; $i < 7; $i++) {
            if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') == $pok) {
                User::_get('pok', $i)->edit('dos', (User::_get('pok', $i)->get('dos') + $exp));
                break;
            }
        }
    }

    private function pechaBerry($ilosc, $pok)
    {
        $w = $this->przedmioty['Pecha_Berry'];
        $pokDobry = 0;
        for ($i = 1; $i < 7; $i++) {
            if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') == $pok) {
                $pokDobry = 1;
                break;
            }
        }
        if (!$pokDobry) {
            $this->view->blad = 'Błędny ID Pokemona.';
            return;
        }
        if (!$w) {
            $this->view->blad = 'Nie posiadasz Pecha Berry.';
            return;
        }

        if ($ilosc == 'max') {
            $il = 'do maksimum ilosci';
        } else {
            if (!is_numeric($ilosc) || $ilosc < 0) {
                $this->view->blad = 'Błędna ilość.';
                return;
            }
            $il = $ilosc;
        }
        if ($il != "do maksimum ilosci") {
            if ($il >= $w) {
                $il = $w;
            }
        } else {
            $il = $w;
        }
        $exp = $il * 3;
        $this->model->updateJagody('Pecha_Berry', $il);
        $this->model->pokemonAddExp($exp, $pok);
        $this->view->komunikat = 'Zjedzono ' . $il . ' Pecha Berry.<br />Pokemon otrzymuje ' . $exp . ' PD.';
        $this->przedmioty['Pecha_Berry'] -= $il;
        for ($i = 1; $i < 7; $i++) {
            if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') == $pok) {
                User::_get('pok', $i)->edit('dos', (User::_get('pok', $i)->get('dos') + $exp));
                break;
            }
        }
    }

    private function magoBerry($ilosc)
    {
        $w = $this->przedmioty['Mago_Berry'];
        if ($w) {
            $ile = floor((Session::_get('mpa') - Session::_get('pa')) / 40);
            if ($ile) {
                if ($ilosc == 'all') {
                    $il = 'do maksimum ilosci';
                } else {
                    if (!is_numeric($ilosc) || $ilosc < 0) {
                        $this->view->blad = 'Błędna ilość.';
                        return;
                    }
                    $il = $ilosc;
                }

                if ($il == 'do maksimum ilosci') {
                    if ($w < $ile)
                        $ile = $w;
                    $pa = Session::_get('pa') + $ile * 40;
                } else {
                    if ($ile > $il)
                        $ile = $il;
                    if ($ile > $w)
                        $ile = $w;
                    $pa = Session::_get('pa') + $ile * 40;
                }
                $this->view->komunikat = 'Zjedzono ' . $ile . ' Mago Berry.<br />Przywrócono ' . ($ile * 20) . ' PA.';
                $this->model->updateJagody('Mago_Berry', $ile);
                $this->model->userSetPa($pa);
                Session::_set('pa', $pa);
            } else {
                $this->view->komunikat = 'Masz pełne PA, nie potrzebujesz Mago Berry.';
            }
        } else {
            $this->view->blad = 'Nie posiadasz Mago Berry.';
        }
    }

    private function chestoBerry($ilosc)
    {
        $w = $this->przedmioty['Chesto_Berry'];
        if ($w) {
            $ile = floor((Session::_get('mpa') - Session::_get('pa')) / 20);
            if ($ile) {
                if ($ilosc == 'all') {
                    $il = 'do maksimum ilosci';
                } else {
                    if (!is_numeric($ilosc) || $ilosc < 0) {
                        $this->view->blad = 'Błędna ilość.';
                        return;
                    }
                    $il = $ilosc;
                }

                if ($il == 'do maksimum ilosci') {
                    if ($w < $ile)
                        $ile = $w;
                    $pa = Session::_get('pa') + $ile * 20;
                } else {
                    if ($ile > $il)
                        $ile = $il;
                    if ($ile > $w)
                        $ile = $w;
                    $pa = Session::_get('pa') + $ile * 20;
                }
                $this->model->updateJagody('Chesto_Berry', $ile);
                $this->model->userSetPa($pa);
                Session::_set('pa', $pa);
                $this->view->komunikat = 'Zjedzono ' . $ile . ' Chesto Berry.<br />Przywrócono ' . ($ile * 20) . ' PA.';
            } else {
                $this->view->komunikat = 'Masz pełne PA, nie potrzebujesz Chesto Berry.';
            }
        } else {
            $this->view->blad = 'Nie posiadasz Chesto Berry.';
        }
    }

    private function wikiBerry($ilosc, $pok)
    {
        if ($ilosc == 'all') {
            $il = 'uleczono wszystkie';
        } else {
            $il = $ilosc;
        }
        if ($il != "uleczono wszystkie") {
            $rezultat = $this->model->selectPokemon($pok);
            $berry = $this->przedmioty['Wiki_Berry'];
            $ile = $rezultat['rowCount'];
            if ($ile) {
                $wiersz = $rezultat[0];
                if ($berry) {
                    if ($wiersz['akt_HP'] < (round($wiersz['jakosc'] * $wiersz['HP'] / 100) + $wiersz['Jag_HP'] + $wiersz['tr_6'] * 5)) {
                        $ile = ceil((round($wiersz['jakosc'] * $wiersz['HP'] / 100) + $wiersz['Jag_HP'] + $wiersz['tr_6'] * 5 - $wiersz['akt_HP']) / 30);
                        if ($il == 'max')
                            $il = $ile;
                        if ($ile > $il) {
                            ///uzyj tylko il
                            if ($berry >= $il) {
                                $this->model->updateJagody('Wiki_Berry', $il);
                                $zycie = $wiersz['akt_HP'] + $il * 30;
                                $this->przedmioty['Wiki_Berry'] -= $il;
                                $this->view->komunikat = 'Pokemon uleczony! Użyto ' . $il . ' Wiki Berry.';
                            } else {
                                $this->model->updateJagody('Wiki_Berry', $berry);
                                $zycie = $wiersz['akt_HP'] + $berry * 30;
                                $this->przedmioty['Wiki_Berry'] = 0;
                                $this->view->komunikat = 'Pokemon uleczony! Użyto ' . $berry . ' Wiki Berry.';
                            }
                        } else {
                            ///uzyj ile
                            if ($berry >= $ile) {
                                $this->model->updateJagody('Wiki_Berry', $ile);
                                $zycie = (round($wiersz['jakosc'] * $wiersz['HP'] / 100) + $wiersz['Jag_HP'] + $wiersz['tr_6'] * 5);
                                $this->przedmioty['Wiki_Berry'] -= $ile;
                                $this->view->komunikat = 'Pokemon uleczony! Użyto ' . $ile . ' Wiki Berry.';
                            } else {
                                $this->model->updateJagody('Wiki_Berry', $berry);
                                $zycie = $wiersz['akt_HP'] + $berry * 30;
                                $this->przedmioty['Wiki_Berry'] = 0;
                                $this->view->komunikat = 'Pokemon uleczony! Użyto ' . $berry . ' Wiki Berry.';
                            }
                        }
                        $this->model->updatePokHP($zycie, $pok);
                        for ($i = 1; $i < 7; $i++) {
                            if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') == $pok) {
                                User::_get('pok', $i)->edit('akt_zycie', $zycie);
                                break;
                            }
                        }
                    } else {
                        $this->view->blad = 'Pokemon nie potrzebuje leczenia.';
                    }
                } else {
                    $this->view->blad = 'Nie posiadasz Wiki Berry.';
                }
            } else {
                $this->view->blad = 'Błędny ID Pokemona.';
            }
        } else { /////////////uleczenie wszystkich pokemów
            $jagody = $this->przedmioty['Wiki_Berry'];
            if ($jagody) {
                $kwer = "SELECT * FROM pokemony WHERE ID IN(";
                $kwer2 = "order by case ID";
                for ($i = 1; $i < 7; $i++) {
                    if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') > 0) {
                        $a = User::_get('pok', $i)->get('id');
                        if ($i == 1)
                            $kwer = $kwer . "'$a'";
                        else
                            $kwer = $kwer . ", '$a'";
                        $kwer2 = $kwer2 . " WHEN '$a' THEN " . $i;
                        $a++;
                    }
                }
                $kwer = $kwer . ")" . $kwer2 . " END";
                $rezultat1 = $this->model->db->select($kwer);
                $ile = $rezultat1['rowCount'];
                $exit = 0;
                $jagody_u = 0;
                for ($i = 1; $i <= $ile; $i++) {
                    $wiersz = $rezultat1[$i - 1];
                    $ile_p = ceil((round($wiersz['jakosc'] * $wiersz['HP'] / 100) + $wiersz['Jag_HP'] + $wiersz['tr_6'] * 5 - $wiersz['akt_HP']) / 30);
                    if ($ile_p > 0) {
                        if ($ile_p > $jagody) {
                            $ile_p = $jagody;
                            $hp = $wiersz['akt_HP'] + $ile_p * 15;
                            $exit = 1;
                        } else
                            $hp = round($wiersz['jakosc'] * $wiersz['HP'] / 100) + $wiersz['Jag_HP'] + $wiersz['tr_6'] * 5;
                        $jagody_u += $ile_p;
                        $this->model->db->update('UPDATE pokemony SET akt_HP = ? WHERE ID = ?', [$hp, $wiersz['ID']]);
                        User::_get('pok', $i)->edit('akt_zycie', $hp);
                        $jagody -= $ile_p;
                        if ($exit == 1)
                            break;
                    }
                }
                if ($jagody_u == 0) {
                    $this->view->komunikat = 'Pokemony nie wymagają leczenia.';
                } else {

                    $this->model->updateJagody('Wiki_Berry', $jagody);
                    if ($exit == 1)
                        $this->view->komunikat = 'Użyto ' . $jagody_u . ' Wiki Berry, ale nie uleczono wszystkich Pokemonów.';
                    else
                        $this->view->komunikat = 'Użyto ' . $jagody_u . ' Wiki Berry, uleczono wszystkie Pokemony.';
                }
            } else {
                $this->view->blad = 'Nie posiadasz Wiki Berry.';
            }
        }
    }

    private function cheriBerry($ilosc, $pok)
    {
        if ($ilosc == 'all') {
            $il = 'uleczono wszystkie';
        } else {
            $il = $ilosc;
        }
        if ($il != "uleczono wszystkie") {
            $rezultat = $this->model->selectPokemon($pok);
            $berry = $this->przedmioty['Cheri_Berry'];
            $ile = $rezultat['rowCount'];
            if ($ile) {
                $wiersz = $rezultat[0];
                if ($berry) {
                    if ($wiersz['akt_HP'] < (round($wiersz['jakosc'] * $wiersz['HP'] / 100) + $wiersz['Jag_HP'] + $wiersz['tr_6'] * 5)) {
                        $ile = ceil((round($wiersz['jakosc'] * $wiersz['HP'] / 100) + $wiersz['Jag_HP'] + $wiersz['tr_6'] * 5 - $wiersz['akt_HP']) / 15);
                        if ($il == 'max')
                            $il = $ile;
                        if ($ile > $il) {
                            ///uzyj tylko il
                            if ($berry >= $il) {
                                $this->model->updateJagody('Cheri_Berry', $il);
                                $zycie = $wiersz['akt_HP'] + $il * 15;
                                $this->przedmioty['Cheri_Berry'] -= $il;
                                $this->view->komunikat = 'Pokemon uleczony! Użyto ' . $il . ' Cheri Berry.';
                            } else {
                                $this->model->updateJagody('Cheri_Berry', $berry);
                                $zycie = $wiersz['akt_HP'] + $berry * 15;
                                $this->przedmioty['Cheri_Berry'] = 0;
                                $this->view->komunikat = 'Pokemon uleczony! Użyto ' . $berry . ' Cheri Berry.';
                            }
                        } else {
                            ///uzyj ile
                            if ($berry >= $ile) {
                                $this->model->updateJagody('Cheri_Berry', $ile);
                                $zycie = round($wiersz['jakosc'] * $wiersz['HP'] / 100) + $wiersz['Jag_HP'] + $wiersz['tr_6'] * 5;
                                $this->przedmioty['Cheri_Berry'] -= $ile;
                                $this->view->komunikat = 'Pokemon uleczony! Użyto ' . $ile . ' Cheri Berry.';
                            } else {
                                $this->model->updateJagody('Cheri_Berry', $berry);
                                $zycie = $wiersz['akt_HP'] + $berry * 15;
                                $this->przedmioty['Cheri_Berry'] = 0;
                                $this->view->komunikat = 'Pokemon uleczony! Użyto ' . $berry . ' Cheri Berry.';
                            }
                        }
                        $this->model->updatePokHP($zycie, $pok);
                        for ($i = 1; $i < 7; $i++) {
                            if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') == $pok) {
                                User::_get('pok', $i)->edit('akt_zycie', $zycie);
                                break;
                            }
                        }
                    } else {
                        $this->view->blad = 'Pokemon nie potrzebuje leczenia.';
                    }
                } else {
                    $this->view->blad = 'Nie posiadasz Cheri Berry.';
                }
            } else {
                $this->view->blad = 'Błędny ID Pokemona.';
            }
        } else { /////////////uleczenie wszystkich pokemów
            $jagody = $this->przedmioty['Cheri_Berry'];
            if ($jagody) {
                $kwer = "SELECT * FROM pokemony WHERE ID IN(";
                $kwer2 = "order by case ID";
                for ($i = 1; $i < 7; $i++) {
                    if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') > 0) {
                        $a = User::_get('pok', $i)->get('id');
                        if ($i == 1)
                            $kwer = $kwer . "'$a'";
                        else
                            $kwer = $kwer . ", '$a'";
                        $kwer2 = $kwer2 . " WHEN '$a' THEN " . $i;
                        $a++;
                    }
                }
                $kwer = $kwer . ")" . $kwer2 . " END";
                $rezultat1 = $this->model->db->select($kwer);
                $ile = $rezultat1['rowCount'];
                $exit = 0;
                $jagody_u = 0;
                for ($i = 1; $i <= $ile; $i++) {
                    $wiersz = $rezultat1[$i - 1];
                    $ile_p = ceil((round($wiersz['jakosc'] * $wiersz['HP'] / 100) + $wiersz['Jag_HP'] + $wiersz['tr_6'] * 5 - $wiersz['akt_HP']) / 15);
                    if ($ile_p > 0) {
                        if ($ile_p > $jagody) {
                            $ile_p = $jagody;
                            $hp = $wiersz['akt_HP'] + $ile_p * 15;
                            $exit = 1;
                        } else
                            $hp = round($wiersz['jakosc'] * $wiersz['HP'] / 100) + $wiersz['Jag_HP'] + $wiersz['tr_6'] * 5;
                        $jagody_u += $ile_p;
                        $this->model->updatePokHP($hp, $wiersz['ID']);
                        User::_get('pok', $i)->edit('akt_zycie', $hp);
                        $jagody -= $ile_p;
                        if ($exit == 1)
                            break;
                    }
                }
                if ($jagody_u == 0) {
                    $this->view->komunikat = 'Pokemony nie wymagają leczenia.';
                } else {
                    $this->model->updateJagody('Cheri_Berry', $jagody);
                    if ($exit == 1)
                        $this->view->komunikat = 'Użyto ' . $jagody_u . ' Cheri Berry, ale nie uleczono wszystkich Pokemonów.';
                    else
                        $this->view->komunikat = 'Użyto ' . $jagody_u . ' Cheri Berry, uleczono wszystkie Pokemony.';
                }
            } else {
                $this->view->blad = 'Nie posiadasz Cheri Berry.';
            }
        }
    }

    private function candy($ilosc, $pok)
    {
        $rezultat = $this->przedmioty['candy'];
        if (!$rezultat) {
            $this->view->blad = 'Nie posiadasz Rare Candy.';
        } else {
            for ($i = 1; $i < 7; $i++)
                if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') == $pok) {
                    $pokemon_i = $i;
                    break;
                }

            if (!isset($pokemon_i)) {
                $this->view->blad = 'Błędny ID Pokemona.';
            } else {
                if (User::_get('pok', $pokemon_i)->get('lvl') <= 99) {
                    User::_get('pok', $pokemon_i)->edit('dos', User::_get('pok', $pokemon_i)->get('dos_p'));
                    $this->przedmioty['candy']--;
                    $this->model->updatePrzedmiot('candy', 1);
                    $this->view->komunikat = 'Pokemon awansuje na ' . (User::_get('pok', $pokemon_i)->get('lvl') + 1) . ' poziom.';
                } else {
                    $this->view->blad = 'Pokemon osiągnął już maksymalony poziom.';
                }
            }
        }
    }

    private function batonCiastko($ilosc, $pok, $co)
    {
        if ($ilosc == '')
            $ilosc = 1;
        if (is_numeric($ilosc) && $ilosc > 0) {
            $rezultat = $this->model->getPrzysmaki($pok);
            $i = $rezultat['rowCount'];
            if ($i) {
                $w = $rezultat[0];
                $il = $ilosc;
                if ($il > 21)
                    $il = 21;
                if (($w['przysmaki'] + $il) > 21)
                    $il -= $w['przysmaki'];
                if ($il > 0) {
                    if ($co == 'baton')
                        $przyw = $il * 2;
                    elseif ($co == 'ciastko')
                        $przyw = $il * 5;

                    if ($co == 'baton') {
                        $rez = $this->przedmioty['batony'];
                        if ($rez >= $il) {
                            $this->model->updatePrzedmiot('batony', $il);
                            $this->przedmioty['batony'] -= $il;
                            $this->view->komunikat = 'Zjedzono ' . $il . ' batonów.';
                            $this->model->pokDodajPrzywiazanie($przyw, $il, $pok);
                            $this->model->dodajOsiagniecie($il);
                        } else
                            $this->view->blad = 'Nie posiadasz takiej ilości batonów.';
                    } else if ($co == "ciastko") {
                        $rez = $this->przedmioty['ciastka'];
                        if ($rez >= $il) {
                            $this->model->updatePrzedmiot('ciastka', $il);
                            $this->view->komunikat = 'Zjedzono ' . $il . ' ciastek.';
                            $this->przedmioty['ciastka'] -= $il;
                            $this->model->pokDodajPrzywiazanie($przyw, $il, $pok);
                            $this->model->dodajOsiagniecie($il);
                        } else
                            $this->view->blad = 'Nie posiadasz takiej ilości ciastek.';
                    }
                } else
                    $this->view->blad = 'Pokemon nie może już zjeść dzisiaj przysmaków';
            }
        } else
            $this->view->blad = 'Błędna ilość.';
    }

    private function baton($ilosc, $pok)
    {
        $this->batonCiastko($ilosc, $pok, 'baton');
    }

    private function ciastko($ilosc, $pok)
    {
        $this->batonCiastko($ilosc, $pok, 'ciastko');
    }

    private function karma($ilosc, $pok)
    {
        ///////DOROBIĆ PRZY 1 POKU!
        if ($ilosc != 'max') {
            if ($ilosc == '')
                $ilosc = 1;
            elseif ($ilosc > 4)
                $ilosc = 4;
        }
        if ($this->przedmioty['karma'] == 0) {
            $this->view->blad = 'Nie posiadasz karmy dla Pokemonów';
        } else {
            if ($ilosc == 'max') {
                $this->view->komunikat = '';
                $uzytej_karmy = 0;
                $wsz = 1;
                $kwer = 'UPDATE pokemony SET glod = (CASE ID ';
                $kwer2 = '';
                $j = 0;
                $glodne = 0;
                for ($i = 1; $i < 7; $i++) {
                    if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') > 0) {
                        if (User::_get('pok', $i)->get('glod') > 100) {
                            User::_get('pok', $i)->edit('glod', 100);
                        }
                        $ile = ceil(User::_get('pok', $i)->get('glod') / 25);
                        if ($ile > 0) {
                            $glodne = 1;
                        }
                        if ($ile > $this->przedmioty['karma']) {
                            $ile = $this->przedmioty['karma'];
                        }
                        if ($ile <= $this->przedmioty['karma'] && $ile != 0) {
                            $glod = User::_get('pok', $i)->get('glod') - $ile * 25;
                            if ($glod < 0)
                                $glod = 0;
                            User::_get('pok', $i)->edit('glod', $glod);
                            $uzytej_karmy += $ile;
                            $this->przedmioty['karma'] -= $ile;
                            $this->view->komunikat .= User::_get('pok', $i)->get('imie') . ' zjada ' . $ile;
                            if ($ile == 1)
                                $this->view->komunikat .= ' pudełko.';
                            else
                                $this->view->komunikat .= ' pudełka.';
                            $id = User::_get('pok', $i)->get('id');
                            $kwer .= " WHEN $id THEN " . User::_get('pok', $i)->get('glod');
                            if ($j != 0)
                                $kwer2 .= ',' . $id;
                            else
                                $kwer2 .= $id;
                            $j++;
                            if (!isset($_GET['komunikat'])) {
                                $this->view->komunikat .= '<br />';
                            }
                        }
                    }
                }
                $kwery = $kwer . ' END ) WHERE ID IN ( ' . $kwer2 . ' ); ';
                if ($j) {
                    $this->view->komunikat .= '<br />Użyto ' . $uzytej_karmy . ' pudełek karmy.';
                    if (!$wsz)
                        $this->view->komunikat .= 'Nie wszystkie Pokemony się najadły.';
                    $this->model->updatePrzedmiot('karma', $uzytej_karmy);
                    $this->model->db->update($kwery, []);
                } elseif ($glodne) {
                    $this->view->blad = 'Nie posiadasz wystarczającej ilości karmy, a Pokemony są głodne';
                } else {
                    $this->view->komunikat = 'Żaden Pokemon nie jest głodny.';
                }
            } else {
                if ($this->przedmioty['karma'] < $ilosc) $ilosc = $this->przedmioty['karma'];
                $j = 0;
                for ($i = 1; $i < 7; $i++) {
                    if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') == $pok) {
                        $j = $i;
                        break;
                    }
                }
                if (!$j) {
                    $this->view->blad = 'Błędny ID Pokemona';
                } else {
                    $minus = $ilosc * 25;
                    $glod = User::_get('pok', $j)->get('glod');
                    if ($glod == 0) {
                        $this->view->komunikat = 'Pokemon nie jest głodny.';
                    } else {
                        if ($glod > 100) $glod = 100;
                        if ($glod < $minus) {
                            $ilosc = ceil($glod / 25);
                            $minus = 0;
                        } else $minus = $glod - $minus;
                        $this->model->zmienGlod($minus, $pok);
                        User::_get('pok', $j)->edit('glod', $minus);
                        $this->model->updatePrzedmiot('karma', $ilosc);
                        $this->view->komunikat = User::_get('pok', $j)->get('imie') . ' zajada się ' . $ilosc . ' pudełkami karmy.';
                    }
                }
            }
        }
    }

    private function soda($ilosc)
    {
        if (is_numeric($ilosc) && $ilosc > 0) {
            switch ($ilosc) {
                case 1:
                    $ilosc = 0.5;
                    $ile = 1;
                    break;
                case 2:
                    $ilosc = 1;
                    $ile = 2;
                    break;
                default:
                    $ilosc = 0.5;
                    $ile = 1;
                    break;
            }
            if ($this->przedmioty['soda'] >= $ile) {
                $przywracanie = round($ilosc * Session::_get('mpa'));
                if ((Session::_get('pa') + $przywracanie) > (Session::_get('mpa') + 9) && !isset($_GET['potwierdzenie'])) {
                    $this->view->komunikat = 'Czy napewno chcesz wypić sodę? Po jej spożyciu ilość PA przekroczy Twoją maksymalną ilość, więc część zostanie zmarnowana!
                    <br /><button class="btn btn-primary potwieredzeniewypicia" id="plecak/rodzaj/soda/' . $ile . '/?potwierdzenie&ajax">Wypij mimo to</button>';
                } else {
                    $this->przedmioty['soda'] -= $ile;
                    Session::_set('pa', (Session::_get('pa') + $przywracanie));
                    $this->model->userUpdatePa($przywracanie);
                    $this->model->updatePrzedmiot('soda', $ile);
                    $this->view->komunikat = 'Wypito ' . $ile . ' sod';
                    if ($ile == 1)
                        $this->view->komunikat .= 'ę';
                    else
                        $this->view->komunikat .= 'y';
                    $this->view->komunikat .= '. Przywrócono ' . $przywracanie . ' PA.';
                }
            } else {
                $this->view->blad = 'Nie posiadasz ' . $ile . ' sody.';
            }
        } else {
            $this->view->blad = 'Błędna ilość.';
        }
    }

    private function woda($ilosc)
    {
        if (is_numeric($ilosc) && $ilosc > 0) {
            switch ($ilosc) {
                case 1:
                    $ilosc = 0.25;
                    $ile = 1;
                    break;
                case 2:
                    $ilosc = 0.5;
                    $ile = 2;
                    break;
                case 3:
                    $ilosc = 0.75;
                    $ile = 3;
                    break;
                case 4:
                    $ilosc = 1;
                    $ile = 4;
                    break;
                default:
                    $ilosc = 0.25;
                    $ile = 1;
                    break;
            }
            if ($this->przedmioty['woda'] >= $ile) {
                $przywracanie = round($ilosc * Session::_get('mpa'));
                if ((Session::_get('pa') + $przywracanie) > (Session::_get('mpa') + 9) && !isset($_GET['potwierdzenie'])) {
                    $this->view->komunikat = 'Czy napewno chcesz wypić wodę? Po jej spożyciu ilość PA przekroczy Twoją maksymalną ilość, więc część zostanie zmarnowana!
                    <br /><button class="btn btn-primary potwieredzeniewypicia" id="plecak/rodzaj/woda/' . $ile . '/?potwierdzenie&ajax">Wypij mimo to</button>';
                } else {
                    $this->przedmioty['woda'] -= $ile;
                    Session::_set('pa', (Session::_get('pa') + $przywracanie));
                    $this->model->userUpdatePa($przywracanie);
                    $this->model->updatePrzedmiot('woda', $ile);
                    $this->view->komunikat = 'Wypito ' . $ile . ' wod';
                    if ($ile == 1)
                        $this->view->komunikat .= 'ę';
                    else
                        $this->view->komunikat .= 'y';
                    $this->view->komunikat .= '. Przywrócono ' . $przywracanie . ' PA.';
                }
            } else {
                $this->view->blad = 'Nie posiadasz ' . $ile . ' wody.';
            }
        } else {
            $this->view->blad = 'Błędna ilość.';
        }
    }

    private function lemoniada()
    {
        if (Session::_get('pa') > 10 && !isset($_GET['potwierdzenie'])) {
            $this->view->komunikat = 'Czy napewno chcesz wypić lemoniadę? Po jej spożyciu ilość PA przekroczy Twoją maksymalną ilość, więc część zostanie zmarnowana!
                    <br /><button class="btn btn-primary potwieredzeniewypicia" id="plecak/rodzaj/lemoniada/?potwierdzenie&ajax">Wypij mimo to</button>';
        } else {
            if ($this->przedmioty['lemoniada']) {
                $this->przedmioty['lemoniada']--;
                $this->model->userSetPa(Session::_get('mpa'));
                $this->model->updatePrzedmiot('lemoniada', 1);
                Session::_set('pa', Session::_get('mpa'));
                $this->view->komunikat = 'Przywrócono ' . Session::_get('mpa') . ' PA';
            } else {
                $this->view->blad = 'Nie posiadasz lemoniady';
            }
        }
    }

    private function kartyZakladka()
    {
        for ($i = 0; $i < count($this->karta); $i++) {
            //brazowa
            $this->view->karta[$i]['brazowa']['ilosc'] = $this->przedmioty['brazowa_' . ($i + 1)];
            $this->view->karta[$i]['opis'] = $this->karta[$i];
            $this->view->karta[$i]['srebrna']['ilosc'] = $this->przedmioty['srebrna_' . ($i + 1)];
            $this->view->karta[$i]['zlota']['ilosc'] = $this->przedmioty['zlota_' . ($i + 1)];
        }
    }

    private function inneZakladka()
    {
        $this->view->latarka = $this->przedmioty['latarka'];
        $this->view->baterie = $this->przedmioty['baterie'];
        $this->view->box = $this->przedmioty['box'];
        $this->view->pokedex = $this->przedmioty['pokedex'];
        $this->view->apteczka = $this->przedmioty['apteczka'];
        $this->view->lopata = $this->przedmioty['lopata'];
        $this->view->czesci = $this->przedmioty['czesci'];
        $this->view->monety = $this->przedmioty['monety'];
        $this->view->boxPoki = Session::_get('magazyn');
    }

    private function kamienieZakladka()
    {
        for ($i = 0; $i < count($this->kamienie); $i++) {
            $this->view->kamien[$i]['nazwa'] = $this->kamienie[$i]['nazwa'];
            $this->view->kamien[$i]['nazwa2'] = $this->kamienie[$i]['nazwa2'];
            $this->view->kamien[$i]['opis'] = $this->kamienie[$i]['opis'];
            $this->view->kamien[$i]['ilosc'] = $this->przedmioty[$this->kamienie[$i]['nazwa']];
        }
    }

    private function jagodyZakladka()
    {
        for ($i = 0; $i < count($this->jagody); $i++) {
            $this->view->jagoda[$i]['ilosc'] = $this->przedmioty[$this->jagody[$i]['nazwa']];
            $this->view->jagoda[$i]['nazwa'] = $this->jagody[$i]['nazwa'];
            $this->view->jagoda[$i]['nazwa2'] = $this->jagody[$i]['nazwa2'];
            $this->view->jagoda[$i]['opis'] = $this->jagody[$i]['opis'];
            $this->view->jagoda[$i]['rodzaj'] = $this->jagody[$i]['rodzaj'];
        }
    }

    private function pokeballeZakladka()
    {
        for ($i = 0; $i < count($this->pokeballe); $i++) {
            $this->view->pokeball[$i]['nazwa'] = $this->pokeballe[$i]['nazwa'];
            $this->view->pokeball[$i]['ilosc'] = $this->przedmioty[$this->pokeballe[$i]['nazwa'] . 'e'];
            $this->view->pokeball[$i]['opis'] = $this->pokeballe[$i]['opis'];
        }
    }

    private function przedmiotyZakladka()
    {
        $this->view->lemoniada = $this->przedmioty['lemoniada'];
        $this->view->soda = $this->przedmioty['soda'];
        $this->view->woda = $this->przedmioty['woda'];
        $this->view->karma = $this->przedmioty['karma'];
        $this->view->batony = $this->przedmioty['batony'];
        $this->view->ciastka = $this->przedmioty['ciastka'];
        $this->view->candy = $this->przedmioty['candy'];
        for ($vv = 1; $vv < 7; $vv++) {
            if (User::_isset('pok', $vv) && User::_get('pok', $vv)->get('id') > 0) {
                if (User::_get('pok', $vv)->get('shiny') == 1)
                    $this->view->pokemonSelect[$vv]['shiny'] = 1;
                else
                    $this->view->pokemonSelect[$vv]['shiny'] = 0;
                $this->view->pokemonSelect[$vv]['id_p'] = User::_get('pok', $vv)->get('id_p');
                $this->view->pokemonSelect[$vv]['id'] = User::_get('pok', $vv)->get('id');
                $this->view->pokemonSelect[$vv]['imie'] = User::_get('pok', $vv)->get('imie');
            }
        }
        $this->view->lemoniadaPA = Session::_get('mpa');
    }

    private function getPrzedmioty()
    {
        $this->przedmioty = $this->model->przedmioty(Session::_get('id'));
    }

    private function generujZawartosc()
    {
        $this->view->render('plecak/index');
        $this->loadTemplate('', 2);
    }

}

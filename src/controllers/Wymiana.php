<?php

namespace src\controllers;

use src\libs\Controller;
use src\libs\Session;
use src\libs\User;

class Wymiana extends Controller
{
    var $pokiDoWymiany = [1 => 64, 2 => 75, 3 => 67, 4 => 93];
    var $ilosc = 0;

    public function __construct()
    {
        parent::__construct();
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('WYMIANA - ' . NAME);
        }
    }

    public function index()
    {
        $this->pokemonyEwolucja();
        $this->pokiDoWymiany();
        $this->view->render('wymiana/index');
        $this->loadTemplate('', 2);
    }

    private function pokemonyEwolucja()
    {
        $poki_w_ewo = $this->model->wymiana();
        if (!$poki_w_ewo['rowCount']) return;

        $id = 'SELECT * FROM pokemony WHERE ID in ( ';
        for ($i = 1; $i <= $poki_w_ewo['rowCount']; $i++) {
            $pok_ewol[$i] = $poki_w_ewo[$i - 1];
            if ($i == 1) $id .= $pok_ewol[$i]['id_poka'];
            else $id .= ' , ' . $pok_ewol[$i]['id_poka'];
        }
        $id .= ' )';
        $poki = $this->model->db->select($id, []);
        if (!$poki['rowCount']) return;
        for ($i = 1; $i <= $poki['rowCount']; $i++) {
            $this->view->pokemonyEwolucja[$i] = $poki[$i - 1];
            if (time() >= $pok_ewol[$i]['czas']) {
                $this->view->pokemonyEwolucja[$i]['odbierz'] = 1;
            } else {
                $this->view->pokemonyEwolucja[$i]['odbierz'] = 0;
                $czas = $pok_ewol[$i]['czas'] - time();
                $this->view->pokemonyEwolucja[$i]['czas'] = 'Pozostały czas: ';
                if ($czas > 60) {
                    $min = intval($czas / 60);
                    $czas -= $min * 60;
                    $this->view->pokemonyEwolucja[$i]['czas'] .= $min . ' minut ' . $czas . ' sekund.';
                } else {
                    $this->view->pokemonyEwolucja[$i]['czas'] .= $czas . ' sekund.';
                }
            }
        }
    }

    private function pokiDoWymiany()
    {
        for ($i = 1; $i < 7; $i++) {
            if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') > 0)
                if (in_array(User::_get('pok', $i)->get('id_p'), $this->pokiDoWymiany))
                    $this->ilosc++;
        }
        if ($this->ilosc) {
            $przedmiotyEwo = $this->model->kamienie();
            $this->view->przedmiotyEwo = $przedmiotyEwo[0];
            for ($i = 1, $j = 0; $i < 7; $i++) {
                if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') > 0) {
                    if (in_array(User::_get('pok', $i)->get('id_p'), $this->pokiDoWymiany)) {
                        switch (User::_get('pok', $i)->get('id_p')) {
                            case 64:
                                $this->view->pokemonWymiana[$j]['ewolucjaLvl'] = 17;
                                $this->view->pokemonWymiana[$j]['przedmiot'] = 'kamien';
                                break;
                            case 67:
                                $this->view->pokemonWymiana[$j]['ewolucjaLvl'] = 29;
                                $this->view->pokemonWymiana[$j]['przedmiot'] = 'pas';
                                break;
                            case 75:
                                $this->view->pokemonWymiana[$j]['ewolucjaLvl'] = 26;
                                $this->view->pokemonWymiana[$j]['przedmiot'] = 'obsydian';
                                break;
                            case 93:
                                $this->view->pokemonWymiana[$j]['ewolucjaLvl'] = 26;
                                $this->view->pokemonWymiana[$j]['przedmiot'] = 'ektoplazma';
                                break;
                        }
                        $this->view->pokemonWymiana[$j]['imie'] = User::_get('pok', $i)->get('imie');
                        $this->view->pokemonWymiana[$j]['id_p'] = User::_get('pok', $i)->get('id_p');
                        $this->view->pokemonWymiana[$j]['lvl'] = User::_get('pok', $i)->get('lvl');
                        $this->view->pokemonWymiana[$j]['id'] = User::_get('pok', $i)->get('id');
                        $j++;
                    }
                }
            }
        } else {
            $this->view->pokiWymianaBlad = 'Nie posiadasz w drużynie Pokemona, którego można ewoluować przez wymianę.';
        }
    }

    public function oddaj(int $id = 0)
    {
        $pok_ewo = $this->model->pokemonWymiana($id);
        if ($pok_ewo['rowCount']) {
            $pok_ewo = $pok_ewo[0];
            $przedmiotyEwo = $this->model->kamienie();
            $przedmiotyEwo = $przedmiotyEwo[0];
            $ewo = 0;
            for ($i = 1; $i < 7; $i++) {
                if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') == $pok_ewo['ID'])
                    $j = $i;
            }
            if (!isset($j)) {
                $this->view->blad = 'Błedne ID Pokemona';
                $this->view->render('wymiana/oddaj');
                $this->index();
                return;
            }
            switch ($pok_ewo['id_poka']) {
                case 64://Kadabra
                    if ($pok_ewo['poziom'] > 16) {
                        if ($przedmiotyEwo['runa'] && $przedmiotyEwo['kamien']) {
                            $ewo = 1;
                            $kamien = 'kamien';
                        } else {
                            $this->view->blad = 'Nie posiadasz przedmiotów do ewolucji Pokemona.';
                        }
                    } else {
                        $this->view->blad = 'Pokemon ma zbyt niski poziom do ewolucji. Potrzebny 17 poziom.';
                    }
                    break;
                case 75://Graveler
                    if ($pok_ewo['poziom'] > 25) {
                        if ($przedmiotyEwo['runa'] && $przedmiotyEwo['obsydian']) {
                            $ewo = 1;
                            $kamien = 'obsydian';
                        } else {
                            $this->view->blad = 'Nie posiadasz przedmiotów do ewolucji Pokemona.';
                        }
                    } else {
                        $this->view->blad = 'Pokemon ma zbyt niski poziom do ewolucji. Potrzebny 26 poziom.';
                    }
                    break;
                case 67://Machoke
                    if ($pok_ewo['poziom'] > 28) {
                        if ($przedmiotyEwo['runa'] && $przedmiotyEwo['pas']) {
                            $ewo = 1;
                            $kamien = 'pas';
                        } else {
                            $this->view->blad = 'Nie posiadasz przedmiotów do ewolucji Pokemona.';
                        }
                    } else {
                        $this->view->blad = 'Pokemon ma zbyt niski poziom do ewolucji. Potrzebny 29 poziom.';
                    }
                    break;
                case 93://Haunter
                    if ($pok_ewo['poziom'] > 25) {
                        if ($przedmiotyEwo['runa'] && $przedmiotyEwo['ektoplazma']) {
                            $ewo = 1;
                            $kamien = 'ektoplazma';
                        } else {
                            $this->view->blad = 'Nie posiadasz przedmiotów do ewolucji Pokemona.';
                        }
                    } else {
                        $this->view->blad = 'Pokemon ma zbyt niski poziom do ewolucji. Potrzebny 26 poziom.';
                    }
                    break;
                default:
                    $this->view->blad = 'Ten Pokemon nie może być ewoluowany przez wymianę!';
            }
            if ($ewo) {
                $czas = time() + 3600;
                $this->model->dodajPoka($kamien, $id, $czas);
                ////////////////////////////////////////////////////////////////////////////////////////////////////
                $rezultat = $this->model->druzyna();
                $wiersz = $rezultat[0];
                $kwer = 'UPDATE druzyna SET ile = (ile-1)';
                $kwer2 = ', pok6 = 0 WHERE id_gracza = ' . Session::_get('id');
                for ($i = ($j + 1); $i < 7; $i++) {
                    $a = $i - 1;
                    if ($wiersz['pok' . $i] > 0) {
                        $poke[$a] = $wiersz['pok' . $i];
                        $kwer = $kwer . ", pok$a = '$poke[$a]'";
                        $pokee = User::_get('pok', $i)->get_all();
                        Session::_unset('pok' . $i);
                        Session::_unset('pok' . $a);
                        Session::_set('pok' . $a, $pokee);
                    } else {
                        Session::_unset('pok' . $a);
                        $kwer = $kwer . ", pok$a = 0";
                    }
                }
                //for($k = 1 ; $k < 7 ; $k++)
                //    if(Session::_isset('pok'.$k)) Session::_unset('pok'.$k);
                //save($pok, $user, $ustawienia, $przedmioty, $umiejetnosci, $odznaki);
                for ($i = 1; $i < 7; $i++) {
                    User::_unset('pok', $i);
                }
                User::getInstance();
                $kwer = $kwer . $kwer2;
                $this->model->zmienPokemon($id);
                $this->model->db->update($kwer, []);
                Session::_set('poki_magazyn', (Session::_get('poki_magazyn') + 1));
                ////////////////////////////////////////////////////////////////////////////////////////////////////
                $this->view->komunikat = 'Pokemon oddany do ewolucji.';
            }
        }
        $this->view->render('wymiana/oddaj');
        $this->index();
    }

    public function ewo(int $id = 0)
    {
        $pok_ewo = $this->model->wymianaCzas($id);
        if ($pok_ewo['rowCount']) {
            $pok_ewo = $this->model->pokemon($id);
            if ($pok_ewo['rowCount']) {
                $pok_ewo = $pok_ewo[0];
                require('./src/includes/pokemony/przyrosty.php');
                require('./src/includes/pokemony/pokemon.php');
                $kwer = "UPDATE pokemony SET ";
                switch ($pok_ewo['id_poka']) {
                    case 64:
                        $idp = 65;
                        break;
                    case 75:
                        $idp = 76;
                        break;
                    case 67:
                        $idp = 68;
                        break;
                    case 93:
                        $idp = 94;
                        break;
                }
                $wiersz = $przyrost[$idp];
                $w = $pokemon_plik[$idp];
                $wsp = 3;
                $atak = $wsp * $wiersz['atak'];
                $sp_atak = $wsp * $wiersz['sp_atak'];
                $obrona = $wsp * $wiersz['obrona'];
                $sp_obrona = $wsp * $wiersz['sp_obrona'];
                $szyb = $wsp * $wiersz['szybkosc'];
                $hp = $wsp * $wiersz['hp'];
                $kwer = $kwer . "id_poka = $idp, blokada = 1, wymiana = 0, Atak = (Atak + $atak), Sp_Atak = (Sp_Atak + $sp_atak), Obrona = (Obrona + $obrona), Sp_Obrona = (Sp_Obrona + $sp_obrona), Szybkosc = (Szybkosc + $szyb), HP = (HP + $hp), akt_HP = (HP + Jag_HP)";
                if ($pok_ewo['imie'] == $pokemon_plik[$pok_ewo['id_poka']]['nazwa']) {
                    $imie = $w['nazwa'];
                    $kwer = $kwer . ", imie = '$imie'";
                }
                $kwer = $kwer . ' WHERE ID = ' . $pok_ewo['ID'] . ' AND wlasciciel = ' . Session::_get('id');
                $this->model->db->update($kwer, []);
                $s = $idp . 's';
                $z = $idp . 'z';
                $this->model->usunWymiana($id);
                $this->model->kolekcja($s, $z);
                $this->view->komunikat = 'Ewolucja Pokemona przebiegła pomyślnie, znajduje się on w Twojej rezerwie!';
            } else {
                $this->view->blad = 'Pokemon nie znaleziony.';
            }
        } else {
            $this->view->blad = 'Nie minęła godzina od oddania Pokemona lub Pokemon nie znaleziony.';
        }
        $this->view->render('wymiana/oddaj');
        $this->index();
    }
}

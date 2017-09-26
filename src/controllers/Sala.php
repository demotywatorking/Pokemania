<?php

namespace src\controllers;

use src\libs\Controller;
use src\libs\Session;
use src\libs\User;

class Sala extends Controller
{
    private $id;

    public function __construct()
    {
        parent::__construct();
        require('./src/includes/ataki/ataki.php');
        require('./src/includes/pokemony/pokemon.php');
        $this->ataki = $ataki;
        $this->pokemon_plik = $pokemon_plik;
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('Sala Pokemon - ' . NAME, 1);
        }
    }

    public function index($id = 0)
    {
        $this->id($id);
    }

    private function id(int $id = 0)
    {
        $this->id = $id;
        $this->poke = 0;
        $this->sprawdzId();
        if ($this->poke) {
            $this->view->mozliwosc = 1;
            $this->zrobTabelka();
            $this->pokiInfo();
            $this->pokiWyswietl();
            $this->atakiPokemona();

        } else {
            $this->view->mozliwosc = 0;
            $this->view->blad = '<div class="alert alert-warning"><span>Ten Pokemon nie może być w tym momencie trenowany!</span></div>';
        }
        $this->view->render('sala/index');
        $this->loadTemplate('', 2);
    }

    public function trening(int $id = 0, int $co = 0, int $ile = 1)
    {
        $this->id = $id;
        $this->co = $co;
        $this->ile = $ile;
        if ($this->sprawdzTrening()) {
            $rezultat2 = $this->model->pokemonTrening($this->id);
            $pokemon = $rezultat2[0];
            $pokemon = $this->liczTrening($pokemon);
        }
        if (!isset($_GET['ajax'])) {
            $this->view->render('sala/trening');
        } else {
            $this->index($this->id);
        }
        $this->loadTemplate('', 2);
    }

    public function atak(int $id = 0, int $atak = 0, int $nr = 1)
    {
        $this->id = $id;
        $this->atak = $atak;
        $this->nr = $nr;
        if ($this->sprawdzAtak()) {
            $this->nauczAtak();
        } else {
            $this->view->blad = '<div class="alert alert-danger"><span>Pokemon nie może się nauczyć tego ataku.</span></div>';
        }
        if (!isset($_GET['ajax'])) {
            $this->view->render('sala/trening');
        } else {
            $this->index($this->id);
        }
    }

    private function nauczAtak()
    {
        $this->view->info = '<div class="alert alert-success"><span>Poprawnie zmieniono atak.</span></div>';
        $this->model->pokemonNauczAtak($this->nr, $this->atak, $this->id);
    }

    private function sprawdzAtak()
    {
        if ($this->id && $this->sprawdzId()) {
            $pok = $this->model->pokemonAtaki($this->id);
            if (in_array($this->atak, [$pok[0]['atak1'], $pok[0]['atak2'], $pok[0]['atak3'], $pok[0]['atak4']])) {
                $this->view->info = '<div class="alert alert-warning"><span>Ten Pokemon zna już ten atak!</span></div>';
                return false;
            }
            $id_p = $pok[0]['id_poka'];
            $str = $this->pokemon_plik[$id_p]['ataki'];
            $a = explode(';', $str);
            unset($atak);
            unset($pozz);
            foreach ($a as $key => $value) {
                if ($value) {
                    $b = explode(',', $value);
                    $atak[$key] = $b[0];
                    $pozz[$key] = $b[1];
                }
            }
            $moz = 0;
            for ($b = 0; $b < count($atak); $b++) {
                if ($atak[$b] == $this->atak) {
                    if ($pozz[$b] <= User::_get('pok', $this->poke)->get('lvl'))
                        $moz = 1;
                    break;
                }
            }
            return $moz;
        } else {
            $this->view->info = '<div class="alert alert-warning"><span>Ten Pokemon nie może być w tym momencie trenowany!</span></div>';
            return false;
        }
    }

    private function licztrening($pokemon)
    {
        $opis = ['o1' => 'Atak', 'o2' => 'Sp. Atak', 'o3' => 'Obrona', 'o4' => 'Sp. Obrona', 'o5' => 'Szybkość', 'o6' => 'HP'];
        $prz = $this->przywiazanie($pokemon['przywiazanie']);
        $plus = 1;
        if ($prz > 80) $plus = round((1 - (($prz - 80) / 100)), 2);
        if (User::$odznaki->kanto[8]) $plus -= 0.05;
        ////////przywiązanie koniec ///////////////////////////////////
        $koszt = $plus * 500;
        $suma = 0;
        $n_koszt = 0;
        for ($j = 1; $j < 7; $j++)
            if ($j != $this->co) $suma += $pokemon['tr_' . $j];

        for ($k = 0; $k < $pokemon['tr_' . $this->co]; $k++) {
            if (($suma + $k) <= 320) {
                $mnoznik = $plus * (0.05 * $k);
                $nowy_koszt = 40 * $mnoznik;
            } else {
                $mnoznik = $plus * ((0.06 * $k) + (0.02 * ($suma - 320)));
                $nowy_koszt = 40 * $mnoznik;
            }
            $nowy_koszt = ceil($nowy_koszt);
            $n_koszt += $nowy_koszt;
            $koszt += $n_koszt;
        }
        $zm = 0;
        $l_koszt = 0;
        $wyt = 0;
        while ($zm < $this->ile) {
            ////////////////////przywiązanie////////////////////////////////
            $prz = $this->przywiazanie($pokemon['przywiazanie']);
            $plus = 1;
            if ($prz > 80) $plus = round((1 - (($prz - 80) / 100)), 2);
            if (User::$odznaki->kanto[8]) $plus -= 0.05;
            ////////przywiązanie koniec ///////////////////////////////////
            $koszt = $plus * 500;
            if (($suma + $zm) <= 320) {
                $mnoznik = $plus * (0.05 * $pokemon['tr_' . $this->co]);
                $nowy_koszt = 40 * $mnoznik;
            } else {
                $mnoznik = $plus * ((0.06 * $pokemon['tr_' . $this->co]) + (0.02 * ($suma - 320)));
                $nowy_koszt = 40 * $mnoznik;
            }
            $nowy_koszt = ceil($nowy_koszt);
            $n_koszt += $nowy_koszt;
            $koszt += $n_koszt;
            if (Session::_get('kasa') >= ($koszt + $l_koszt)) {
                //DODAĆ TRENING
                $l_koszt += $koszt;
                $wyt++;
                $zm++;
                ++$pokemon['tr_' . $this->co];
                ++$pokemon['tr_' . $this->co];
            } else break;
        }
        if ($wyt > 0) {
            $this->model->treningKoszt($l_koszt);
            $this->model->osiagniecie($wyt);
            Session::_set('kasa', (Session::_get('kasa') - $l_koszt));
            $wartosc = floor(0.4 * $l_koszt);
            $exp = 5 * $wyt;
            for ($i = 1; $i < 7; $i++)
                if (User::_get('pok', $i)->get('id') == $this->id) {
                    User::_get('pok', $i)->edit('dos', (User::_get('pok', $i)->get('dos') + $exp));
                    if ($this->co == 6) User::_get('pok', $i)->edit('zycie', (User::_get('pok', $i)->get('zycie') + $wyt * 5));
                    break;
                }
            $przyw = ceil($wyt * 0.5);
            if ($this->co != 6) {
                $this->model->trening($this->co, $wyt, $this->id, $wartosc, $exp, $przyw);
            } else {
                $this->model->treningHP($this->co, $wyt, $wartosc, $exp, $przyw, $this->id);
                $wyt *= 5;
            }
            $this->view->info = '<div class="alert alert-success"><span>Wytrenowano ' . $wyt . ' ' . $opis['o' . $this->co] . ' Koszt: <span class="pogrubienie">' . number_format($l_koszt, 0, '', '.') . ' &yen;</span><br />Pokemon otrzymuje ' . $exp . ' pkt. doświadczenia.</span></div>';
        } else $this->view->blad = '<div class="alert alert-danger"><span>Nie stać Cię na trening!</span></div>';
        return $pokemon;
    }

    private function sprawdzTrening()
    {
        if ($this->id && $this->sprawdzId() && $this->co < 7 && $this->co > 0 && $this->ile > -1) {
            if (!$this->ile)
                $this->ile = 1;
            return true;
        } else {
            $this->view->blad = '<div class="alert alert-warning"><span>Ten Pokemon nie może być w tym momencie trenowany!</span></div>';
            return false;
        }
    }

    private function sprawdzId()
    {
        $this->poke = 0;
        if (!$this->id) {
            $this->poke = 1;
            $this->id = User::_get('pok', 1)->get('id');
        } else {
            for ($i = 1; $i < 7; $i++) {
                if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') > 0) {
                    if (User::_get('pok', $i)->get('id') == $this->id) {
                        $this->poke = $i;
                        break;
                    }
                } else break;
            }
        }
        return $this->poke;
    }

    private function zrobTabelka()
    {
        for ($i = 1; $i < 7; $i++) {
            if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') > 0) {
                $this->view->pokTabelka[$i] = '<li ';
                if (User::_get('pok', $i)->get('id') == $this->id) {
                    $this->ac = $i;
                    $this->view->pokTabelka[$i] .= 'class="active"';
                }
                $this->view->pokTabelka[$i] .= '><a data-toggle="tab" href="#' . User::_get('pok', $i)->get('id') . '" class="pok-tab-a">';
                if (User::_get('pok', $i)->get('shiny') == 1)
                    $this->view->pokTabelka[$i] .= '<img src="' . URL . 'public/img/poki/srednie/s' . User::_get('pok', $i)->get('id_p') . '.png" class="pok-tab center" />';
                else
                    $this->view->pokTabelka[$i] .= '<img src="' . URL . 'public/img/poki/srednie/' . User::_get('pok', $i)->get('id_p') . '.png" class="pok-tab center" />';
                $this->view->pokTabelka[$i] .= '</a></li>';

            }
        }
    }

    private function pokiInfo()
    {
        $klery = 'SELECT * FROM pokemony, pokemon, pokemon_jagody WHERE pokemony.wlasciciel = :id AND pokemony.id_poka = pokemon.id_poka AND pokemony.ID = pokemon_jagody.id_poka AND pokemony.ID in (';
        $klery2 = 'ORDER BY case pokemony.ID';
        for ($i = 1; $i < 7; $i++) {
            if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') > 0) {
                $aaa = User::_get('pok', $i)->get('id');
                if ($i == 1)
                    $klery .= " $aaa ";
                else
                    $klery .= ", $aaa ";
                $klery2 .= " WHEN $aaa THEN " . $i;
            }
        }
        $klery .= ')' . $klery2 . ' END';
        $this->pokiInfo = $this->model->db->select($klery, [':id' => Session::_get('id')]);
    }

    private function pokiWyswietl()
    {
        for ($i = 1; $i <= $this->pokiInfo['rowCount']; $i++) {
            $this->view->pokInformacja[$i] = $this->pokiInfo[$i - 1];
            if ($this->ac == $i)
                $this->view->pokInformacja[$i]['active'] = 1;
            else
                $this->view->pokInformacja[$i]['active'] = 0;
            for ($j = 1; $j <= 6; $j++) {
                $this->view->pokInformacja[$i]['koszt_' . $j] = $this->koszt($j, $i);
                if ($j == 6) $this->view->pokInformacja[$i]['tr_6'] *= 5;
            }
        }
    }

    private function koszt($i, $j)
    {
        $a = [
            1 => $this->view->pokInformacja[$j]['tr_1'],
            2 => $this->view->pokInformacja[$j]['tr_2'],
            3 => $this->view->pokInformacja[$j]['tr_3'],
            4 => $this->view->pokInformacja[$j]['tr_4'],
            5 => $this->view->pokInformacja[$j]['tr_5'],
            6 => $this->view->pokInformacja[$j]['tr_6'],
        ];
        $prz = $this->przywiazanie($this->view->pokInformacja[$j]['przywiazanie']);
        $plus = 1;
        if ($prz > 80) $plus = round((1 - (($prz - 80) / 100)), 2);
        if (User::$odznaki->kanto[8]) $plus -= 0.05;
        $koszt = $plus * 500;
        $suma = 0;
        for ($k = 1; $k < 7; $k++) {
            if ($k != $i)
                $suma += $a[$k];
        }
        for ($k = 0; $k <= $a[$i]; $k++) {
            if (($suma + $k) <= 320) {
                $mnoznik = $plus * (0.05 * $k);
                $nowy_koszt = 40 * $mnoznik;
            } else {
                $mnoznik = $plus * ((0.06 * $k) + (0.02 * ($suma - 320)));
                $nowy_koszt = 40 * $mnoznik;
            }
            $nowy_koszt = ceil($nowy_koszt);
            $koszt += $nowy_koszt;
        }
        return $koszt;
    }

    private function przywiazanie($x)
    {
        $przywiazanie = 0;
        if ($x < 6000)
            $przywiazanie += $x * 0.002843333;
        else {
            $przywiazanie = 17.06;
            $przywiazanie += ($x - 6000) * 0.00864818182;
        }
        $przywiazanie = -200 / ($przywiazanie + 1.98984) + 100.50054;
        if ($x == 0)
            $przywiazanie = 0;
        $przywiazanie = round($przywiazanie, 2);
        if ($przywiazanie > 100)
            return 100;
        return $przywiazanie;
    }

    private function atakiPokemona()
    {
        for ($i = 1; $i <= $this->pokiInfo['rowCount']; $i++) {
            $str = $this->view->pokInformacja[$i]['ataki'];
            unset($atak);
            unset($pozz);
            $a = explode(';', $str);
            foreach ($a as $key => $value) {
                if ($value) {
                    $b = explode(',', $value);
                    $atak[$key] = $b[0];
                    $pozz[$key] = $b[1];
                }
            }
            if (count($atak)) {
                $this->view->pokInformacja[$i]['atakLiczba'] = count($atak);
                for ($rr = 1; $rr <= 4; $rr++) {
                    $w1 = $this->ataki[$this->view->pokInformacja[$i]['atak' . $rr]];
                    if ($w1['nazwa'] == "") $this->view->pokInformacja[$i]['atak' . $rr] = "-brak-";
                    else $this->view->pokInformacja[$i]['atak' . $rr] = $w1['nazwa'];
                }
                for ($n = 0; $n < count($atak); $n++) {
                    $wierszyk = $this->ataki[$atak[$n]];
                    $this->view->pokInformacja[$i]['atak_' . $n]['ID'] = $atak[$n];
                    $this->view->pokInformacja[$i]['atak_' . $n]['znany'] = 0;
                    $this->view->pokInformacja[$i]['atak_' . $n]['nizszy'] = 0;
                    $this->view->pokInformacja[$i]['atak_' . $n]['rodzaj'] = $wierszyk['rodzaj'];
                    $this->view->pokInformacja[$i]['atak_' . $n]['typ'] = $wierszyk['typ'];
                    $this->view->pokInformacja[$i]['atak_' . $n]['nazwa'] = $wierszyk['nazwa'];
                    if (($this->view->pokInformacja[$i]['atak1'] == $wierszyk['nazwa'])
                        || ($this->view->pokInformacja[$i]['atak2'] == $wierszyk['nazwa'])
                        || ($this->view->pokInformacja[$i]['atak3'] == $wierszyk['nazwa'])
                        || ($this->view->pokInformacja[$i]['atak4'] == $wierszyk['nazwa'])
                    ) {
                        $this->view->pokInformacja[$i]['atak_' . $n]['znany'] = 1;
                    } elseif ($this->view->pokInformacja[$i]['poziom'] < $pozz[$n]) {
                        $this->view->pokInformacja[$i]['atak_' . $n]['dos'] = $pozz[$n];
                        $this->view->pokInformacja[$i]['atak_' . $n]['nizszy'] = 1;
                    }

                }
            } else {
                $this->view->pokInformacja[$i]['atakLiczba'] = 0;
            }
        }
    }
}

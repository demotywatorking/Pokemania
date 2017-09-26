<?php

namespace src\controllers;

use src\libs\Controller;
use src\libs\Session;
use src\libs\User;

class Profil extends Controller
{
    const DATA_ZEROWA = '0000-00-00';
    private $graczInfo;
    private $p = [
        '1' => ['nazwa' => 'Łapanie Pokemonów',
        'max' => 7,
        'opis' => 'Szansa na złapanie pokemona zwiększona o ',
        'wym' => 'osiagniecia;zlapane_poki',
        'wym_opis' => ' złapanych Pokemonów',
        1 => 3,
        '1_wym' => 20,
        2 => 5,
        '2_wym' => 100,
        3 => 8,
        '3_wym' => 450,
        4 => 12,
        '4_wym' => 1800,
        5 => 16,
        '5_wym' => 4000,
        6 => 20,
        '6_wym' => 12000,
        7 => 25,
        '7_wym' => 25000,
        ],
    ];

    public function __construct()
    {
        parent::__construct();
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('Profil gracza -' . NAME, 1);
        }
    }

    public function index(int $id = 0)
    {
        if (isset($_POST['gracz'])) {
            $id = $this->model->sprawdzNick($_POST['gracz']);
        }
        if (!$id) {
            $this->graczInfo = $this->model->infoDb(Session::_get('id'));
        } else {
            if (is_numeric($id) && $id > 0) {
                $this->graczInfo = $this->model->infoDb($id);
            } else {
                $this->view->blad = 'Błędny ID gracza';
                $this->generujBlad();
            }
        }
        if ($this->graczInfo['rowCount']) {
            $this->wyswietlGracza();
        } else {
            $this->view->blad = 'Gracz nie znaleziony!';
            $this->generujBlad();
        }
    }

    public function um($id = 0)
    {
        if ($id > 0 && $id <= count($this->p)) {
            $this->view->um = '';
            $um = $id;
            $rezultat = $this->model->punkty();
            $w = $rezultat[0];
            if ($w['p' . $um] < $this->p[$um]['max']) {
                $um1 = $w['p' . $um] + 1;
                if (Session::_get('punkty') >= $this->p[$um][$um1]) {
                    $osiagniecia = $this->model->osiagniecia();
                    $wym = explode(';', $this->p[$um]['wym']);
                    $wym_o = ${$wym[0]};
                    $wym_o = $wym_o[$wym[1]];
                    //sprawdzenie warunku!!
                    if ($wym_o >= $this->p[$um][$um1 . '_wym']) {
                        switch ($id) {
                            case 1:
                                User::$umiejetnosci->edit('lapanie', (User::$umiejetnosci->get('lapanie') + 1));
                                break;
                        }
                        Session::_set('punkty', (Session::_get('punkty') - $this->p[$um][$um1]));
                        $a = "p" . $um;
                        $pkt = $this->p[$um][$um1];
                        $this->model->updateUmiejetnosc($a, $pkt);
                        $this->view->um = '<div class="alert alert-success"><span>Umiejętność poprawnie podniesiona na następny poziom.</span></div>';
                    } else {
                        $this->view->um = '<div class="alert alert-warning"><span>Nie spełniono warunków, aby podnieść tę umiejętność.</span></div>';
                    }
                } else {
                    $this->view->um = '<div class="alert alert-warning"><span>Niestety nie masz wystarczającej ilości punktów, 
                            aby podnieść tę umiejętność na następny poziom.</span></div>';
                }
            } else {
                $this->view->um = '<div class="alert alert-danger"><span>Ta umiejętność ma już maksymalny poziom.</span></div>';
            }
        } else {
            $this->view->um = '<div class="alert alert-danger"><span>Błędny ID umiejętności</span></div>';
        }
        $this->index();
    }

    private function wyswietlGracza()
    {
        //$stow = $this->stow[0];
        $this->toView($this->graczInfo[0]);
        $this->wyswietl();
    }

    private function toView($gracz)
    {
        $this->view->login = $gracz['login'];
        $this->view->id = $gracz['ID'];
        $this->view->poziom = $gracz['poziom_trenera'];
        $this->view->opis = $gracz['opis'];
        if ($gracz['avatar'] != '') $this->view->avatar = '<img src="' . $gracz['avatar'] . '" class="img-responsive center"/>';
        else $this->view->avatar = "-Brak avataru-";
        if ($gracz['ID'] != Session::_get('id')) {
            $this->view->walka = '<a class="btn btn-primary" href="'.URL.'wiadomosci/nowa/?odbiorca=' . $gracz['login'] . '">Wyślij wiadomość</a>';
            $this->view->walka .= '<a class="btn btn-primary" href="'.URL.'pojedynek.php?id=' . $gracz['ID'] . '">Wyzwij gracza na pojedynek (5PA)</a>';
        }
        $this->view->znajomy = 0;
        if ($gracz['ID'] != Session::_get('id')) {
            $this->znajomy($gracz);
        }
        $this->czasOnline($gracz['online']);
        $this->odznaki($gracz['ID']);
        $this->druzyna($gracz);
        if ($gracz['ID'] == Session::_get('id')) {
            $this->graczUmiejetnosci($gracz);
        }

    }

    private function druzyna($gracz)
    {
        $this->view->druzyna = '';
        $u = explode("|", $gracz['ustawienia']);
        $podglad = $u[0];
        if (!$podglad) {
            $this->view->podglad = 0;
        } else {
            $this->view->podglad = 1;
            $rezultat = $this->model->druzyna($gracz['ID']);
            for ($i = 0; $i < $rezultat['rowCount']; $i++) {
                $rezultat1 = $rezultat[$i];
                $this->view->pokemonDruzyna[$i]['ID'] = $rezultat1['ID'];
                $this->view->pokemonDruzyna[$i]['shiny'] = $rezultat1['shiny'];
                $this->view->pokemonDruzyna[$i]['id_poka'] = $rezultat1['id_poka'];
            }
        }

    }

    private function odznaki($id)
    {
        $rezultat = $this->model->odznaki($id);
        $this->view->odznaki = $rezultat[0];
    }

    private function czasOnline($online)
    {
        $this->view->czasOnline = '';
        if (!$online) $this->view->czasOnline .= 'ani sekundy.';
        else {
            $ost = $online;
            if ($ost < 60) $this->view->czasOnline .= $ost . ' sekund.';
            else if ($ost < 3600) {
                $min = floor($ost / 60);
                $sek = $ost - 60 * $min;
                $this->view->czasOnline .= $min . ' minut ' . $sek . ' sekund.';
            } else {
                $dni = 0;
                if ($ost > 86400) {
                    $dni = floor($ost / 86400);
                    $ost -= $dni * 86400;
                }
                $godz = floor($ost / 3600);
                $ost -= $godz * 3600;
                $min = floor($ost / 60);
                $sek = $ost - 60 * $min;
                if ($dni > 0) $this->view->czasOnline .= $dni . ' dni ';
                $this->view->czasOnline .= $godz . ' godzin ' . $min . ' minut ' . $sek . ' sekund.';
            }
        }
    }

    private function znajomy($gracz)
    {
        $znajomy = 0;
        //sprawdzenie czy znajomy
        $znajomi = $this->model->znajomy($gracz['ID']);
        if ($znajomi['rowCount'])
            $znajomy = 1;

        if ($znajomy) {//|| Session::_get('admin') == 1) // $user->__get('admin') == 1 || {
            ///////TU DODAĆ COŚ W STYLU - JEŚLI W TYM SAMYM STOWARZYSZENIU
            $this->view->znajomy = 1;
            if ($gracz['id_sesji'] != '') $this->view->online = '<span class="zielony">ONLINE</span>';
            else {
                $this->view->online = '<span class="czerwony">OFFLINE</span><br />Ostatnia aktywność: ';
                if ($gracz['ost_aktywnosc1'] == 0) $this->view->online .= 'NIGDY';
                else {
                    $ost = time() - $gracz['ost_aktywnosc1'];
                    if ($ost < 60) $this->view->online .= $ost . ' sekund temu.';
                    elseif ($ost < 3600) {
                        $min = floor($ost / 60);
                        $sek = $ost - 60 * $min;
                        $this->view->online .= $min . ' minut ' . $sek . ' sekund temu.';
                    } else {
                        $dni = 0;
                        if ($ost > 86400) {
                            $dni = floor($ost / 86400);
                            $ost -= $dni * 86400;
                        }
                        $godz = floor($ost / 3600);
                        $ost -= $godz * 3600;
                        $min = floor($ost / 60);
                        $sek = $ost - 60 * $min;
                        if ($dni > 0) $this->view->online .= $dni . ' dni ';
                        $this->view->online .= $godz . ' godzin ' . $min . ' minut ' . $sek . ' sekund temu.';
                    }
                }
            }
        } else {
            $znajomi = $this->model->zaproszony($gracz['ID']);
            //sprawdzenie czy zaproszony
            if ($znajomi['rowCount'])
                $this->view->znajomy = 2;
            else $this->view->znajomy = 3;
        }
    }

    private function graczUmiejetnosci($gracz)
    {
        $this->view->umiejetnosci = 1;
        $rezultat = $this->model->punkty();
        $osiagniecia = $this->model->osiagniecia();
        $this->view->punktyUmiejetnosci = $gracz['punkty'];
        $w = $rezultat[0];
        for ($i = 1; $i <= count($this->p); $i++) {
            $this->view->umiejetnosc[$i]['nazwa'] = $this->p[$i]['nazwa'];
            $this->view->umiejetnosc[$i]['poziom'] = $w['p' . $i];
            $this->view->umiejetnosc[$i]['opis'] = $this->p[$i]['opis'];
            if ($i == 1) $this->view->umiejetnosc[$i]['opis'] .= ($w['p' . $i] * 10) . ' %';
            $wym = explode(';', $this->p[$i]['wym']);
            $wym_o = ${$wym[0]};
            $wym_o = $wym_o[$wym[1]];
            $n = $w['p' . $i] + 1;
            if ($n <= $this->p[$i]['max']) {
                $this->view->umiejetnosc[$i]['max'] = 0;
                $this->view->umiejetnosc[$i]['opis2'] = $this->p[$i]['opis'];
                if ($i == 1) $this->view->umiejetnosc[$i]['opis2'] .= ($n * 10) . ' %';
                $this->view->umiejetnosc[$i]['wymagania'] = '<br />Wymagania: ' . $this->p[$i][($w['p' . $i] + 1) . '_wym'] . ' ' . $this->p[$i]['wym_opis'] . '<br />';
                if ($wym_o >= $this->p[$i][($w['p' . $i] + 1) . '_wym']) {
                    $this->view->umiejetnosc[$i]['wymagania'] .= '<span class="pogrubienie zielony">Wymagania spełnione</span>';
                    $wym_s = 1;

                } else {
                    $this->view->umiejetnosc[$i]['wymagania'] .= '<div class="progress progress-gra prog_HP" data-original-title="Wymagania" data-toggle="tooltip" data-placement="top">';
                    $this->view->umiejetnosc[$i]['wymagania'] .= '<div class="progress-bar progress-bar-success progBarHP" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . (($wym_o / $this->p[$i][($w['p' . $i] + 1) . '_wym']) * 100) . '%;"><span>' . $wym_o . ' / ' . $this->p[$i][($w['p' . $i] + 1) . '_wym'] . '</span></div></div>';
                    $wym_s = 0;
                }
                if ($wym_s == 1) {
                    $this->view->umiejetnosc[$i]['kup'] = '<br />Wykup umiejętność za: ' . $this->p[$i][$n] . ' pkt umiejętności.<br />';
                    if ($gracz['punkty'] >= $this->p[$i][$n])
                        $this->view->umiejetnosc[$i]['kup'] .= '<button class="btn btn-primary um" id="' . $i . '">KUP</button>';
                    else $this->view->umiejetnosc[$i]['kup'] .= '<button class="btn btn-primary disabled" data-toggle="tooltip" data-title="Masz za mało punktów umiejętności" id="/' . $i . '">KUP</button>';
                } else {
                    $this->view->umiejetnosc[$i]['kup'] = '<span class="czerwony">Nie spełniono wymagań</span><br />';
                    $this->view->umiejetnosc[$i]['kup'] .= '<button class="btn btn-primary disabled" data-toggle="tooltip" data-title="Nie spełniono wymagań" id="&um=' . $i . '">KUP</button>';
                }
            } else $this->view->umiejetnosc[$i]['max'] = 1;
        }
    }

    private function generujBlad()
    {
        //$this->view->render('profil/blad');
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('', 2);
        }
    }

    private function wyswietl()
    {
        $this->view->render('profil/index');
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('', 2);
        }
    }

}
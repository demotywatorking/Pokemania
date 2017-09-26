<?php

namespace src\controllers;

use src\includes\functions\FunctionsPolowanie;
use src\libs\Controller;
use src\libs\Session;
use src\libs\User;

class Sale extends Controller
{
    use FunctionsPolowanie;

    const DATA_ZEROWA = '0000-00-00';

    public function __construct()
    {
        $this->lider();
        parent::__construct();
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('Sale Liderów - ' . NAME, 1);
        }
    }

    public function index()
    {
        $this->sprawdzLiderow();
        $this->sprawdzWymagania();
        $this->view->render('sale/index');
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('', 2);
        }
    }

    private function sprawdzLiderow()
    {
        $this->rezultat = $this->model->kolekcja();
        $this->rezultat = $this->rezultat[0];
        $this->rezultat2 = $this->model->sale();
        $this->rezultat2 = $this->rezultat2[0];
        $this->ac = 0;
        $this->view->ulTrenerzy = '';
        for ($i = 1; $i < 9; $i++) {
            $this->view->ulTrenerzy .= '<li ';
            if (!$this->ac && ($this->rezultat2['Kanto' . $i] == '0000-00-00' || $i == 8)) {
                $this->view->ulTrenerzy .= 'class="active"';
                $this->ac = $i;
            }
            $this->view->ulTrenerzy .= '><a data-toggle="tab" href="#' . $i . '">' . $this->lider[$i]['nazwa'] . '</a></li>';
        }
    }

    private function sprawdzWymagania()
    {
        for ($i = 1; $i < 9; $i++) {
            $this->view->lider[$i] = '';
            $this->view->lider[$i] .= '<div id="' . $i . '" class="tab-pane fade ';
            if ($this->ac == $i)
                $this->view->lider[$i] .= 'active in';
            $this->view->lider[$i] .= '">';
            $this->view->lider[$i] .= '<div class="alert alert-info"><span>Lider ' . $this->lider[$i]['nazwa'] . '<span></div>';
            $wym_s = 0;
            $this->view->lider[$i] .= '<div class="well well-primary jeden_ttlo lider">';
            if ($this->rezultat2['Kanto' . $i] == self::DATA_ZEROWA) {
                $this->view->lider[$i] .= '<span>Wymagania:';
                switch ($i) {
                    case 1:
                        $this->view->lider[$i] .= '<span class="margin-top">Złap 1x ONIX</span>';
                        if ($this->rezultat['95z'] > 0) {
                            $dl = 100;
                            $zl = 'Złapany';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = 0;
                            $zl = "Nie złapany";
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Złap 15x GEODUDE</span>';
                        if ($this->rezultat['74z'] > 14) {
                            $dl = 100;
                            $zl = 'Złapano 15 lub więcej';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            if ($this->rezultat['74z'] == 0)
                                $dl = 0;
                            else
                                $dl = (100 * ($this->rezultat['74z'] / 15));
                            $zl = 'Złapano ' . $this->rezultat['74z'] . '/15';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';
                        break;
                    case 2:
                        $this->view->lider[$i] .= '<span class="margin-top">Pokonaj Brocka</span>';
                        if ($this->rezultat2['Kanto1'] > self::DATA_ZEROWA) {
                            $dl = 100;
                            $zl = 'Pokonany';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = 0;
                            $zl = 'Nie pokonany';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Złap 20x GOLDEEN</span>';
                        if ($this->rezultat['118z'] > 19) {
                            $dl = 100;
                            $zl = 'Złapano 20 lub więcej';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = (100 * ($this->rezultat['118z'] / 20));
                            $zl = 'Złapano ' . $this->rezultat['118z'] . '/20';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Złap 10x STARYU</span>';
                        if ($this->rezultat['120z'] > 9) {
                            $dl = 100;
                            $zl = 'Złapano 10 lub więcej';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = (100 * ($this->rezultat['74z'] / 10));
                            $zl = 'Złapano ' . $this->rezultat['120z'] . '/10';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';
                        break;
                    case 3:
                        $this->view->lider[$i] .= '<span class="margin-top">Pokonaj Misty</span>';
                        if ($this->rezultat2['Kanto2'] > 0) {
                            $dl = 100;
                            $zl = 'Pokonana';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = 0;
                            $zl = 'Nie pokonana';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Złap 25x PIKACHU</span>';
                        if ($this->rezultat['25z'] > 24) {
                            $dl = 100;
                            $zl = 'Złapano 25 lub więcej';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = (100 * ($this->rezultat['25z'] / 25));
                            $zl = 'Złapano ' . $this->rezultat['25z'] . '/25';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Złap 20x VOLTORB</span>';
                        if ($this->rezultat['100z'] > 19) {
                            $dl = 100;
                            $zl = 'Złapano 20 lub więcej';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = (100 * ($this->rezultat['100z'] / 20));
                            $zl = 'Złapano ' . $this->rezultat['100z'] . '/20';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Złap ELECTRODE</span>';
                        if ($this->rezultat['101z']) {
                            $dl = 100;
                            $zl = 'Złapany';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = 0;
                            $zl = 'Nie złapany';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';
                        break;
                    case 4:
                        $this->view->lider[$i] .= '<span class="margin-top">Pokonaj Lt.Surge</span>';
                        if ($this->rezultat2['Kanto3'] > 0) {
                            $dl = 100;
                            $zl = 'Pokonany';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = 0;
                            $zl = 'Nie pokonany';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Złap 25x BELLSPROUT</span>';
                        if ($this->rezultat['69z'] > 24) {
                            $dl = 100;
                            $zl = 'Złapano 25 lub więcej';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = (100 * ($this->rezultat['69z'] / 25));
                            $zl = 'Złapano ' . $this->rezultat['69z'] . '/25';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Złap 20x WEEPINBELL</span>';
                        if ($this->rezultat['70z'] > 19) {
                            $dl = 100;
                            $zl = 'Złapano 20 lub więcej';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = (100 * ($this->rezultat['70z'] / 20));
                            $zl = 'Złapano ' . $this->rezultat['70z'] . '/20';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Złap 35x ODDISH</span>';
                        if ($this->rezultat['43z'] > 34) {
                            $dl = 100;
                            $zl = 'Złapano 35 lub więcej';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = (100 * ($this->rezultat['43z'] / 35));
                            $zl = 'Złapano ' . $this->rezultat['43z'] . '/35';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Złap 20x GLOOM</span>';
                        if ($this->rezultat['44z'] > 19) {
                            $dl = 100;
                            $zl = 'Złapano 20 lub więcej';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = (100 * ($this->rezultat['44z'] / 20));
                            $zl = 'Złapano ' . $this->rezultat['44z'] . '/20';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';
                        break;
                    case 5:
                        $this->view->lider[$i] .= '<span class="margin-top">Pokonaj Erikę</span>';
                        if ($this->rezultat2['Kanto4'] > 0) {
                            $dl = 100;
                            $zl = 'Pokonana';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = 0;
                            $zl = 'Nie pokonana';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Złap 35x GRIMER</span>';
                        if ($this->rezultat['88z'] > 34) {
                            $dl = 100;
                            $zl = 'Złapano 35 lub więcej';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = (100 * ($this->rezultat['88z'] / 35));
                            $zl = 'Złapano ' . $this->rezultat['88z'] . '/35';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Złap 30x MUK</span>';
                        if ($this->rezultat['89z'] > 29) {
                            $dl = 100;
                            $zl = 'Złapano 30 lub więcej';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = (100 * ($this->rezultat['89z'] / 30));
                            $zl = 'Złapano ' . $this->rezultat['89z'] . '/30';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Złap 35x KOFFING</span>';
                        if ($this->rezultat['109z'] > 34) {
                            $dl = 100;
                            $zl = 'Złapano 35 lub więcej';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = (100 * ($this->rezultat['109z'] / 35));
                            $zl = 'Złapano ' . $this->rezultat['109z'] . '/35';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Złap 30x WEEZEING</span>';
                        if ($this->rezultat['110z'] > 29) {
                            $dl = 100;
                            $zl = 'Złapano 30 lub więcej';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = (100 * ($this->rezultat['110z'] / 30));
                            $zl = 'Złapano ' . $this->rezultat['110z'] . '/30';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Złap 60x ZUBAT</span>';
                        if ($this->rezultat['41z'] > 59) {
                            $dl = 100;
                            $zl = 'Złapano 60 lub więcej';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = (100 * ($this->rezultat['41z'] / 60));
                            $zl = 'Złapano ' . $this->rezultat['41z'] . '/60';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Złap 30x GOLBAT</span>';
                        if ($this->rezultat['42z'] > 29) {
                            $dl = 100;
                            $zl = 'Złapano 30 lub więcej';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = (100 * ($this->rezultat['42z'] / 30));
                            $zl = 'Złapano ' . $this->rezultat['42z'] . '/30';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';
                        break;
                    case 6:
                        $this->view->lider[$i] .= '<span class="margin-top">Pokonaj Kogę</span>';
                        if ($this->rezultat2['Kanto5'] > 0) {
                            $dl = 100;
                            $zl = 'Pokonany';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = 0;
                            $zl = 'Nie pokonany';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Złap 30x ABRA</span>';
                        if ($this->rezultat['63z'] > 29) {
                            $dl = 100;
                            $zl = 'Złapano 30 lub więcej';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = (100 * ($this->rezultat['63z'] / 30));
                            $zl = 'Złapano ' . $this->rezultat['63z'] . '/30';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Złap 2x KADABRA</span>';
                        if ($this->rezultat['64z'] > 1) {
                            $dl = 100;
                            $zl = 'Złapano 2 lub więcej';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = (100 * ($this->rezultat['64z'] / 2));
                            $zl = 'Złapano ' . $this->rezultat['64z'] . '/2';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Złap 70x VENONAT</span>';
                        if ($this->rezultat['48z'] > 69) {
                            $dl = 100;
                            $zl = 'Złapano 70 lub więcej';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = (100 * ($this->rezultat['48z'] / 70));
                            $zl = 'Złapano ' . $this->rezultat['48z'] . '/70';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Złap 20x VENOMOTH</span>';
                        if ($this->rezultat['49z'] > 19) {
                            $dl = 100;
                            $zl = 'Złapano 20 lub więcej';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = (100 * ($this->rezultat['49z'] / 20));
                            $zl = 'Złapano ' . $this->rezultat['49z'] . '/20';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Złap 5x MR.MIME</span>';
                        if ($this->rezultat['122z'] > 4) {
                            $dl = 100;
                            $zl = 'Złapano 5 lub więcej';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = (100 * ($this->rezultat['122z'] / 5));
                            $zl = 'Złapano ' . $this->rezultat['122z'] . '/5';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';
                        break;
                    case 7:
                        $this->view->lider[$i] .= '<span class="margin-top">Pokonaj Sabrinę</span>';
                        if ($this->rezultat2['Kanto6'] > 0) {
                            $dl = 100;
                            $zl = 'Pokonana';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = 0;
                            $zl = 'Nie pokonana';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Złap 5x GROWLITHE</span>';
                        if ($this->rezultat['58z'] > 4) {
                            $dl = 100;
                            $zl = 'Złapano 5 lub więcej';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = (100 * ($this->rezultat['58z'] / 5));
                            $zl = 'Złapano ' . $this->rezultat['58z'] . '/5';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Posiadaj w kolekcji ARCANINE</span>';
                        if ($this->rezultat['59z']) {
                            $dl = 100;
                            $zl = 'Posiadasz';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = 0;
                            $zl = 'Nie posiadasz';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Złap 30x PONYTA</span>';
                        if ($this->rezultat['77z'] > 29) {
                            $dl = 100;
                            $zl = 'Złapano 30 lub więcej';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = (100 * ($this->rezultat['77z'] / 30));
                            $zl = 'Złapano ' . $this->rezultat['77z'] . '/30';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Złap RAPIDASHA</span>';
                        if ($this->rezultat['78z']) {
                            $dl = 100;
                            $zl = 'Złapany';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = 0;
                            $zl = 'Nie złapany';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Złap 8x CHARMANDER</span>';
                        if ($this->rezultat['4z'] > 7) {
                            $dl = 100;
                            $zl = 'Złapano 8 lub więcej';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = (100 * ($this->rezultat['4z'] / 8));
                            $zl = 'Złapano ' . $this->rezultat['4z'] . '/8';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Złap 5x CHARMELEON</span>';
                        if ($this->rezultat['5z'] > 4) {
                            $dl = 100;
                            $zl = 'Złapano 5 lub więcej';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = (100 * ($this->rezultat['5z'] / 5));
                            $zl = 'Złapano ' . $this->rezultat['5z'] . '/5';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Złap CHARIZARDA</span>';
                        if ($this->rezultat['6z']) {
                            $dl = 100;
                            $zl = 'Złapany';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = 0;
                            $zl = 'Nie złapany';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Złap 5x VULPIX</span>';
                        if ($this->rezultat['37z'] > 4) {
                            $dl = 100;
                            $zl = 'Złapano 5 lub więcej';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = (100 * ($this->rezultat['37z'] / 5));
                            $zl = 'Złapano ' . $this->rezultat['37z'] . '/5';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';
                        break;
                    case 8:
                        $this->view->lider[$i] .= '<span class="margin-top">Pokonaj Blaine\'a</span>';
                        if ($this->rezultat2['Kanto7'] > 0) {
                            $dl = 100;
                            $zl = 'Pokonany';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = 0;
                            $zl = 'Nie pokonany';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Złap 50x SANDSHREW</span>';
                        if ($this->rezultat['27z'] > 49) {
                            $dl = 100;
                            $zl = 'Złapano 50 lub więcej';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = (100 * ($this->rezultat['27z'] / 50));
                            $zl = 'Złapano ' . $this->rezultat['27z'] . '/50';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Złap 5x SANDSHLASH</span>';
                        if ($this->rezultat['28z'] > 4) {
                            $dl = 100;
                            $zl = 'Złapano 5 lub więcej';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = (100 * ($this->rezultat['28z'] / 5));
                            $zl = 'Złapano ' . $this->rezultat['28z'] . '/5';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Posiadaj w kolekcji NIDOQUEEN</span>';
                        if ($this->rezultat['31z']) {
                            $dl = 100;
                            $zl = 'Posiadasz';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = 0;
                            $zl = 'Nie posiadasz';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Posiadaj w kolekcji NIDOKINGA</span>';
                        if ($this->rezultat['34z']) {
                            $dl = 100;
                            $zl = 'Posiadasz';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = 0;
                            $zl = 'Nie posiadasz';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Złap 25x DUGTRIO</span>';
                        if ($this->rezultat['51z'] > 24) {
                            $dl = 100;
                            $zl = 'Złapano 25 lub więcej';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = (100 * ($this->rezultat['51z'] / 25));
                            $zl = 'Złapano ' . $this->rezultat['51z'] . '/25';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Złap 20x CUBONE</span>';
                        if ($this->rezultat['104z'] > 19) {
                            $dl = 100;
                            $zl = 'Złapano 20 lub więcej';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = (100 * ($this->rezultat['104z'] / 20));
                            $zl = 'Złapano ' . $this->rezultat['104z'] . '/20';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Złap 20x RHYHORN</span>';
                        if ($this->rezultat['111z'] > 19) {
                            $dl = 100;
                            $zl = 'Złapano 20 lub więcej';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = (100 * ($this->rezultat['111z'] / 20));
                            $zl = 'Złapano ' . $this->rezultat['111z'] . '/20';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Złap 10x RHYDON</span>';
                        if ($this->rezultat['112z'] > 9) {
                            $dl = 100;
                            $zl = 'Złapano 10 lub więcej';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = (100 * ($this->rezultat['112z'] / 10));
                            $zl = 'Złapano ' . $this->rezultat['112z'] . '/10';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';

                        $this->view->lider[$i] .= '<span class="margin-top">Złap 100x BEEDRILL</span>';
                        if ($this->rezultat['15z'] > 99) {
                            $dl = 100;
                            $zl = 'Złapano 10 lub więcej';
                            $wym_s++;
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_EXP progress2">';
                        } else {
                            $dl = (100 * ($this->rezultat['15z'] / 100));
                            $zl = 'Złapano ' . $this->rezultat['15z'] . '/100';
                            $this->view->lider[$i] .= '<div class="progress progress-gra prog_HP progress2">';
                        }
                        $this->view->lider[$i] .= '<div class="progress-bar progress-bar-success progBar';
                        $dl == 100 ? $this->view->lider[$i] .= 'EXP' : $this->view->lider[$i] .= 'HP';
                        $this->view->lider[$i] .= ' line30" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;"><span>' . $zl . '</span></div></div>';
                        break;
                }
                $this->view->lider[$i] .= '</span>';
            } else
                $this->view->lider[$i] .= '<div class="alert alert-success"><span>Ten lider został już pokonany.<br />Data zdobycia odznaki: ' . $this->rezultat2['Kanto' . $i] . '<br />Efekt odznaki: ' . $this->lider[$i]['efekt'] . '</span></div>';
            $this->view->lider[$i] .= '</div>'; //well
            if ($wym_s == $this->lider[$i]['wym'])
                $this->view->lider[$i] .= '<div class="row nomargin"><div class="col-xs-12 text-center"><div class="alert alert-success"><span>Wszystkie wymagania są spełnione, możesz wyzwać lidera na pojedynek</span></div><button class="btn btn-primary center" id="walka/' . $i . '">WYZWIJ LIDERA NA POJEDYNEK</button></div></div>';
            else if ($this->rezultat2['Kanto' . $i] == self::DATA_ZEROWA)
                $this->view->lider[$i] .= '<div class="row nomargin"><div class="col-xs-12 text-center"><div class="alert alert-danger"><span>Nie spełniono wszystkich wymagań, aby wyzwać lidera na pojedynek</span></div></div></div>';
            $this->view->lider[$i] .= '</div>'; //tab-pane
        }
    }

    private function lider()
    {
        $this->lider = [1 => [
            'nazwa' => 'Brock',
            'wym' => 2,
            'efekt' => '10% większa wartość Pokemonów w dziczy,'
        ], 2 => [
            'nazwa' => 'Misty',
            'wym' => 3,
            'efekt' => 'Codziennie dostajesz dodatkowy los na loterię.'
        ], 3 => [
            'nazwa' => 'Lt. Surge',
            'wym' => 4,
            'efekt' => '10% większa szansa na złapanie Pokemona.'
        ], 4 => [
            'nazwa' => 'Erika',
            'wym' => 5,
            'efekt' => ''
        ], 5 => [
            'nazwa' => 'Koga',
            'wym' => 7,
            'efekt' => '10% tańsze leczenie.'
        ], 6 => [
            'nazwa' => 'Sabrina',
            'wym' => 6,
            'efekt' => '+20 maksymalnych punktów akcji.'
        ], 7 => [
            'nazwa' => 'Blaine',
            'wym' => 9,
            'efekt' => 'Głód Pokemonów rośnie o 50% wolniej.'
        ], 8 => [
            'nazwa' => 'Giovani',
            'wym' => 10,
            'efekt' => '5% tańsze treningi.'
        ]];
    }

    public function walka($walka = 1)
    {
        if ($walka > 0 && $walka < 9) {
            $rezultat = $this->model->kolekcja();
            $rezultat = $rezultat[0];
            $rezultat2 = $this->model->sale();
            $rezultat2 = $rezultat2[0];
            if ($rezultat2['Kanto' . $walka] > self::DATA_ZEROWA) {
                $this->view->blad = "Już pokonałeś tego lidera.";
                return;
            } else {
                $wym_s = 0;
                if ($walka == 1) {//Brock
                    $wym = 2;
                    if ($rezultat['95z'] > 0)
                        $wym_s++;
                    if ($rezultat['74z'] > 14)
                        $wym_s++;
                } else if ($walka == 2) {//Misty
                    $wym = 3;
                    if ($rezultat2['Kanto1'] > 0)
                        $wym_s++;
                    if ($rezultat['118z'] > 19)
                        $wym_s++;
                    if ($rezultat['120z'] > 9)
                        $wym_s++;
                } else if ($walka == 3) {//Lt. Surge
                    $wym = 4;
                    if ($rezultat2['Kanto2'] > 0)
                        $wym_s++;
                    if ($rezultat['100z'] > 19)
                        $wym_s++;
                    if ($rezultat['101z'] > 0)
                        $wym_s++;
                    if ($rezultat['25z'] > 24)
                        $wym_s++;
                } else if ($walka == 4) {//Erica
                    $wym = 5;
                    if ($rezultat2['Kanto3'] > 0)
                        $wym_s++;
                    if ($rezultat['69z'] > 24)
                        $wym_s++;
                    if ($rezultat['70z'] > 19)
                        $wym_s++;
                    if ($rezultat['43z'] > 34)
                        $wym_s++;
                    if ($rezultat['44z'] > 19)
                        $wym_s++;
                } else if ($walka == 5) {//Koga
                    $wym = 7;
                    if ($rezultat2['Kanto4'] > 0)
                        $wym_s++;
                    if ($rezultat['88z'] > 34)
                        $wym_s++;
                    if ($rezultat['89z'] > 29)
                        $wym_s++;
                    if ($rezultat['109z'] > 34)
                        $wym_s++;
                    if ($rezultat['110z'] > 29)
                        $wym_s++;
                    if ($rezultat['41z'] > 59)
                        $wym_s++;
                    if ($rezultat['42z'] > 29)
                        $wym_s++;
                } else if ($walka == 6) {//Sabrina
                    $wym = 6;
                    if ($rezultat2['Kanto5'] > 0)
                        $wym_s++;
                    if ($rezultat['64z'] > 1)
                        $wym_s++;
                    if ($rezultat['48z'] > 69)
                        $wym_s++;
                    if ($rezultat['49z'] > 19)
                        $wym_s++;
                    if ($rezultat['122z'] > 4)
                        $wym_s++;
                    if ($rezultat['63z'] > 29)
                        $wym_s++;
                } else if ($walka == 7) {//Blaine
                    $wym = 9;
                    if ($rezultat2['Kanto6'] > 0)
                        $wym_s++;
                    if ($rezultat['58z'] > 4)
                        $wym_s++;
                    if ($rezultat['59z'] > 0)
                        $wym_s++;
                    if ($rezultat['77z'] > 29)
                        $wym_s++;
                    if ($rezultat['78z'] > 0)
                        $wym_s++;
                    if ($rezultat['4z'] > 7)
                        $wym_s++;
                    if ($rezultat['5z'] > 4)
                        $wym_s++;
                    if ($rezultat['6z'] > 0)
                        $wym_s++;
                    if ($rezultat['37z'] > 4)
                        $wym_s++;
                } else if ($walka == 8) {//Giovanni
                    $wym = 10;
                    if ($rezultat2['Kanto7'] > 0)
                        $wym_s++;
                    if ($rezultat['27z'] > 49)
                        $wym_s++;
                    if ($rezultat['28z'] > 4)
                        $wym_s++;
                    if ($rezultat['31z'] > 0)
                        $wym_s++;
                    if ($rezultat['34z'] > 0)
                        $wym_s++;
                    if ($rezultat['51z'] > 24)
                        $wym_s++;
                    if ($rezultat['104z'] > 19)
                        $wym_s++;
                    if ($rezultat['111z'] > 19)
                        $wym_s++;
                    if ($rezultat['112z'] > 9)
                        $wym_s++;
                    if ($rezultat['15z'] > 99)
                        $wym_s++;
                }
                if ($wym == $wym_s) {
                    if (Session::_get('pa') >= 50) {
                        Session::_set('pa', (Session::_get('pa') - 50));
                        $this->model->paZaWalke();
                        if ($walka == 1) {//Brock
                            $lider_nazwa = "Brock";
                            $ile_pokow = 2;
                            $poke[1]['id'] = 1; //Golem
                            $poke[1]['typ1'] = 13;
                            $poke[1]['typ2'] = 12;
                            $poke[2]['id'] = 2; //Onix
                            $poke[2]['typ1'] = 13;
                            $poke[2]['typ2'] = 12;
                        } else if ($walka == 2) {//Misty
                            $lider_nazwa = "Misty";
                            $ile_pokow = 2;
                            $poke[1]['id'] = 3; //Staryu
                            $poke[1]['typ1'] = 3;
                            $poke[1]['typ2'] = 0;
                            $poke[2]['id'] = 4; //Starmie
                            $poke[2]['typ1'] = 3;
                            $poke[2]['typ2'] = 7;
                        } else if ($walka == 3) {//Lt. Surge
                            $lider_nazwa = "Lt. Surge";
                            $ile_pokow = 3;
                            $poke[1]['id'] = 5; //Electrode
                            $poke[1]['typ1'] = 5;
                            $poke[1]['typ2'] = 0;
                            $poke[2]['id'] = 6; //Pikachu
                            $poke[2]['typ1'] = 5;
                            $poke[2]['typ2'] = 0;
                            $poke[3]['id'] = 7; //Raichu
                            $poke[3]['typ1'] = 5;
                            $poke[3]['typ2'] = 0;
                        } else if ($walka == 4) {//Erika
                            $lider_nazwa = "Erika";
                            $ile_pokow = 3;
                            $poke[1]['id'] = 8; //Victreebel
                            $poke[1]['typ1'] = 4;
                            $poke[1]['typ2'] = 8;
                            $poke[2]['id'] = 9; //Tangela
                            $poke[2]['typ1'] = 4;
                            $poke[2]['typ2'] = 0;
                            $poke[3]['id'] = 10; //Vileplume
                            $poke[3]['typ1'] = 4;
                            $poke[3]['typ2'] = 8;
                        } else if ($walka == 5) {//Koga
                            $lider_nazwa = "Koga";
                            $ile_pokow = 4;
                            $poke[1]['id'] = 11; //Victreebel
                            $poke[1]['typ1'] = 8;
                            $poke[1]['typ2'] = 0;
                            $poke[2]['id'] = 12; //Tangela
                            $poke[2]['typ1'] = 8;
                            $poke[2]['typ2'] = 0;
                            $poke[3]['id'] = 13; //Vileplume
                            $poke[3]['typ1'] = 8;
                            $poke[3]['typ2'] = 0;
                            $poke[4]['id'] = 14; //Vileplume
                            $poke[4]['typ1'] = 8;
                            $poke[4]['typ2'] = 0;
                        } else if ($walka == 6) {//Sabrina
                            $lider_nazwa = "Sabrina";
                            $ile_pokow = 4;
                            $poke[1]['id'] = 15; //Kadabra
                            $poke[1]['typ1'] = 7;
                            $poke[1]['typ2'] = 0;
                            $poke[2]['id'] = 16; //Mr.Mime
                            $poke[2]['typ1'] = 7;
                            $poke[2]['typ2'] = 0;
                            $poke[3]['id'] = 17; //Vileplume
                            $poke[3]['typ1'] = 16;
                            $poke[3]['typ2'] = 8;
                            $poke[4]['id'] = 18; //Vileplume
                            $poke[4]['typ1'] = 7;
                            $poke[4]['typ2'] = 0;
                        } else if ($walka == 7) {//Blaine
                            $lider_nazwa = "Blaine";
                            $ile_pokow = 4;
                            $poke[1]['id'] = 19; //Growlithe
                            $poke[1]['typ1'] = 2;
                            $poke[1]['typ2'] = 0;
                            $poke[2]['id'] = 20; //Ponyta
                            $poke[2]['typ1'] = 2;
                            $poke[2]['typ2'] = 0;
                            $poke[3]['id'] = 21; //Rapidash
                            $poke[3]['typ1'] = 2;
                            $poke[3]['typ2'] = 0;
                            $poke[4]['id'] = 22; //Arcanine
                            $poke[4]['typ1'] = 2;
                            $poke[4]['typ2'] = 0;
                        } else if ($walka == 8) {//Giovanni
                            $lider_nazwa = "Giovanni";
                            $ile_pokow = 5;
                            $poke[1]['id'] = 23; //Rhyhorn
                            $poke[1]['typ1'] = 13;
                            $poke[1]['typ2'] = 12;
                            $poke[2]['id'] = 24; //Dugtrio
                            $poke[2]['typ1'] = 12;
                            $poke[2]['typ2'] = 0;
                            $poke[3]['id'] = 25; //Nidoqueen
                            $poke[3]['typ1'] = 8;
                            $poke[3]['typ2'] = 12;
                            $poke[4]['id'] = 26; //Nidoking
                            $poke[4]['typ1'] = 8;
                            $poke[4]['typ2'] = 12;
                            $poke[5]['id'] = 27; //Rhydon
                            $poke[5]['typ1'] = 13;
                            $poke[5]['typ2'] = 12;
                        }

                        //wlasne poki, sprawdzenie ile się ma
                        $wl_pokow = 0;
                        $i1 = 1;
                        $i2 = 1;
                        $puste = 0;
                        for ($i = 1; $i <= 6; $i++) {
                            if (!User::_isset('pok', $i))
                                break;
                            if (User::_get('pok', $i)->get('akt_zycie') == User::_get('pok', $i)->get('zycie'))
                                $wl_pokow++;
                            else
                                $puste++;
                        }
                        if ($wl_pokow > $ile_pokow)
                            $wl_pokow = $ile_pokow;
                        if ($wl_pokow == 0) {
                            $this->view->blad = 'Nie możesz walczyć z liderem jeśli wszystkie Twoje Pokemony są ranne.';
                            return;
                        }

                        $stan1 = 0;
                        $runda1 = 0;
                        $pulapka1 = 0;
                        $at1 = 1;
                        $stan2 = 0;
                        $runda2 = 0;
                        $pulapka2 = 0;
                        $at2 = 1;
                        $pokemon_lidera = array();
                        $kwer = 'SELECT * FROM pokemony WHERE ID = ' . $poke[1]['id'];
                        for ($licznik = 2; $licznik <= $ile_pokow; $licznik++)
                            $kwer .= ' OR ID = ' . $poke[$licznik]['id'];
                        $rezultat = $this->model->db->select($kwer, []);

                        for ($licznik = 1; $licznik <= $ile_pokow; $licznik++) {
                            //$pokemon_lidera[$licznik] = $rezultat->fetch_assoc();
                            $pokemon = $rezultat[$licznik - 1];
                            $pokemon_lidera[$licznik] = $pokemon;
                            $pokemon_lidera[$licznik]['jakosc'] = 90;
                            $pokemon_lidera[$licznik]['pok_poziom'] = $pokemon['poziom'];
                            $pokemon_lidera[$licznik]['pok_nazwa'] = $pokemon['imie'];
                            $pokemon_lidera[$licznik]['pok_id'] = $pokemon['id_poka'];
                            $pokemon_lidera[$licznik]['typ1'] = $poke[$licznik]['typ1'];
                            $pokemon_lidera[$licznik]['typ2'] = $poke[$licznik]['typ2'];
                            $pokemon_lidera[$licznik]['shiny'] = 0;
                            $pokemon_lidera[$licznik]['pok_atak'] = $pokemon['Atak'];
                            $pokemon_lidera[$licznik]['pok_sp_atak'] = $pokemon['Sp_Atak'];
                            $pokemon_lidera[$licznik]['pok_obrona'] = $pokemon['Obrona'];
                            $pokemon_lidera[$licznik]['pok_sp_obrona'] = $pokemon['Sp_Obrona'];
                            $pokemon_lidera[$licznik]['pok_szybkosc'] = $pokemon['Szybkosc'];
                            $pokemon_lidera[$licznik]['pok_hp'] = $pokemon['HP'];
                            $pokemon_lidera[$licznik]['max_hp'] = $pokemon['HP'];
                            $pokemon_lidera[$licznik]['celnosc'] = 100;
                            for ($abcd = 0; $abcd < 4; $abcd++) {
                                $atak_p[$abcd] = $pokemon_lidera[$licznik]['atak' . ($abcd + 1)];
                                unset($pokemon_lidera[$licznik]['atak' . ($abcd + 1)]);
                            }
                            for ($abcd = 0; $abcd < 4; $abcd++)
                                $pokemon_lidera[$licznik]['atak' . $abcd]['id'] = $atak_p[$abcd];
                        }
                        for ($asd = 1; $asd < 7; $asd++)
                            $tabela[$asd] = 0;
                        for ($asd = 1; $asd <= $ile_pokow; $asd++)
                            $tabela2[$asd] = 0;
                        $runda = 1;
                        $_SESSION['walkat'] = '';
                        $_SESSION['walkat1'] = '';

                        for ($licznik = 1; $licznik < 7; $licznik++) {
                            if (User::_isset('pok', $licznik) && User::_get('pok', $licznik)->get('id') > 0) {
                                if (User::_get('pok', $licznik)->get('akt_zycie') == User::_get('pok', $licznik)->get('zycie') && User::_get('pok', $licznik)->get('zycie') > 0) {
                                    if (User::_get('pok', $licznik)->get('shiny') == 1)
                                        $_SESSION['walkat'] .= '<img src="' . URL . 'public/img/poki/srednie/s' . User::_get('pok', $licznik)->get('id_p') . '.png" class="trener_img img-responsive center" />';
                                    else
                                        $_SESSION['walkat'] .= '<img src="' . URL . 'public/img/poki/srednie/' . User::_get('pok', $licznik)->get('id_p') . '.png" class="trener_img img-responsive center" />';
                                } else
                                    $_SESSION['walkat'] .= '<img src="' . URL . 'public/img/poki/srednie/bw/' . User::_get('pok', $licznik)->get('id_p') . '.png" class="trener_img img-responsive center" />';
                            }
                        }
                        //avatar
                        $avatar = $this->model->avatarLogin();
                        $avatar = $avatar[0];
                        $_SESSION['walkat'] .= '</div><div class="col-xs-8">';
                        if ($avatar['avatar'] != '')
                            $_SESSION['walkat'] .= '<img src="' . $avatar['avatar'] . '" class="avatar img-responsive center" />';
                        else
                            $_SESSION['walkat'] .= '<img src="' . URL . 'public/img/no_avatar.png" class="avatar img-responsive center" />';


                        //avatar, może tu się kiedyś doda losowanie tła
                        $_SESSION['walkat'] .= '</div></div></div><div class="col-xs-12 col-sm-6"><div class="row nomargin"><div class="col-xs-8">'; //col row col
                        $_SESSION['walkat'] .= '<img src="' . URL . 'public/img/trener/1.png" class="img-responsive center avatar"/></div><div class="col-xs-4">';

                        for ($licznik = 1; $licznik <= $ile_pokow; $licznik++)
                            $_SESSION['walkat'] .= '<img src="' . URL . 'public/img/poki/srednie/' . $pokemon_lidera[$licznik]['pok_id'] . '.png" class="trener_img img-responsive center" />';

                        $_SESSION['walkat'] .= '</div></div></div></div></div></div>'; //col row col col col row
                        $_SESSION['walkat'] .= '<div class="col-xs-12 text-center margin-top"><a href="#wynik" class="btn btn-primary btn-lg walka_button" >PRZEJDŹ DO WYNIKU WALKI</a></div></div>';

                        while (1) {
                            if (!isset($pok11)) {
                                if ($i1 > $ile_pokow) {
                                    $wygrana = 1;
                                    break;
                                }
                                $at1 = 1;
                                $pok11 = 1;
                                $pok_runda1 = 0;
                                $pok1 = $pokemon_lidera[$i1];
                            }
                            if (!isset($pok22)) {
                                $pok22 = 1;
                                $pok_runda2 = 0;
                                if (($i2 - $puste) > $wl_pokow) {
                                    $porazka = 1;
                                    break;
                                }
                                while (1) {
                                    if (User::_get('pok', $i2)->get('akt_zycie') < User::_get('pok', $i2)->get('zycie') && $i2 < 7) {
                                        if ($tabela[$i2] != 1)
                                            $tabela[$i2] = 0;
                                        $i2++;
                                    } else
                                        break;
                                }
                                $tabela[$i2] = 1;
                                $rezultat = $this->model->pokemonDoWalki(User::_get('pok', $i2)->get('id'));
                                $wiersz = $rezultat[0];
                                $pok2['jakosc'] = $wiersz['jakosc'];
                                $pok2['poziom'] = $wiersz['poziom'];
                                $pok2['imie'] = $wiersz['imie'];
                                $pok2['idd'] = $wiersz['ID'];
                                $pok2['id_poka'] = User::_get('pok', $i2)->get('id_p');
                                $pok2['Atak'] = $wiersz['Atak'];
                                $pok2['Sp_Atak'] = $wiersz['Sp_Atak'];
                                $pok2['Obrona'] = $wiersz['Obrona'];
                                $pok2['Sp_Obrona'] = $wiersz['Sp_Obrona'];
                                $pok2['Szybkosc'] = $wiersz['Szybkosc'];
                                $pok2['akt_HP'] = $wiersz['akt_HP'];
                                $pok2['HP'] = $wiersz['HP'];
                                $pok2['typ1'] = $wiersz['typ1'];
                                $pok2['typ2'] = $wiersz['typ2'];
                                $pok2['shiny'] = $wiersz['shiny'];
                                $pok2['plec'] = $wiersz['plec'];
                                $pok2['i2'] = $i2;
                                $pok2['celnosc'] = $wiersz['celnosc'];
                                $pok2['przywiazanie'] = przywiazanie($wiersz['przywiazanie']);
                                $pok2['tr_1'] = $wiersz['tr_1'];
                                $pok2['tr_2'] = $wiersz['tr_2'];
                                $pok2['tr_3'] = $wiersz['tr_3'];
                                $pok2['tr_4'] = $wiersz['tr_4'];
                                $pok2['tr_5'] = $wiersz['tr_5'];
                                $pok2['tr_6'] = $wiersz['tr_6'];
                                $pok2['Jag_Atak'] = $wiersz['Jag_Atak'];
                                $pok2['Jag_Sp_Atak'] = $wiersz['Jag_Sp_Atak'];
                                $pok2['Jag_Obrona'] = $wiersz['Jag_Obrona'];
                                $pok2['Jag_Sp_Obrona'] = $wiersz['Jag_Sp_Obrona'];
                                $pok2['Jag_Szybkosc'] = $wiersz['Jag_Szybkosc'];
                                $pok2['Jag_HP'] = $wiersz['Jag_HP'];
                                $at2 = 1;
                                for ($ill = 1; $ill <= 4; $ill++)
                                    $pok2['atak' . $ill]['id'] = $wiersz['atak' . $ill];
                            }
                            if ($stan1 == 10)
                                $stan1 = 0;
                            if ($stan2 == 10)
                                $stan2 = 0;
                            $_SESSION['walkat'] .= '<div class="alert alert-warning text-center margin-top"><span>WALKA <span class="zloty pogrubienie">' . $runda . '</span></span></div>';
                            $tablica = $this->walkaPokemonow($pok2, $this->model->db, 1, $stan2, $stan1, $runda2, $runda1, $pulapka2, $pulapka1, $at2, $at1, $pok1, 0, $pok_runda1, $pok_runda2);
                            if ($tablica['kto'] == 3) {//oba przegrały
                                $stan1 = 0;
                                $stan2 = 0;
                                $runda1 = 0;
                                $runda2 = 0;
                                $pulapka1 = 0;
                                $pulapka2 = 0;
                                $pok_runda1 = 0;
                                $pok_runda2 = 0;
                                $i2++;
                                $i1++;
                                $at1 = 1;
                                $at2 = 1;
                                unset($pok11);
                                unset($pok22);
                                unset($pok1);
                                unset($pok2);
                            } else if ($tablica['kto'] == 2) {//wygrał pok 2
                                $i1++;
                                unset($pok11);
                                unset($pok1);
                                $stan1 = 0;
                                $runda1 = 0;
                                $pulapka1 = 0;
                                $pok_runda1 = 0;
                                $pok_runda2 = $tablica['atak_runda'];
                                $stan2 = $tablica['stan'];
                                $runda2 = $tablica['runda'];
                                $pulapka2 = $tablica['pulapka'];
                                $pok2['atak'] = $tablica['atak'];
                                $pok2['sp_atak'] = $tablica['sp_atak'];
                                $pok2['obrona'] = $tablica['obrona'];
                                $pok2['sp_obrona'] = $tablica['sp_obrona'];
                                $pok2['szybkosc'] = $tablica['szybkosc'];
                                $pok2['akt_HP'] = $tablica['hp'];
                                $pok2['HP'] = $tablica['max_hp'];
                                $pok2['id_poka'] = $tablica['id_poka'];
                                $pok2['typ1'] = $tablica['typ1'];
                                $pok2['typ2'] = $tablica['typ2'];
                                $pok2['tr_6'] = 0;
                                $pok2['Jag_HP'] = 0;
                                $at2 = $tablica['at'];
                                for ($i = 1; $i < 5; $i++)
                                    $pok2['atak' . $i]['id'] = $tablica['atak' . $i]['id'];
                            } else if ($tablica['kto'] == 1) {//wygrał pok 1
                                $i2++;
                                unset($pok22);
                                unset($pok2);
                                $stan2 = 0;
                                $runda2 = 0;
                                $pulapka2 = 0;
                                $pok_runda2 = 0;
                                $pok_runda1 = $tablica['atak_runda'];
                                $stan1 = $tablica['stan'];
                                $runda1 = $tablica['runda'];
                                $pulapka2 = $tablica['pulapka'];
                                $pok1['atak'] = $tablica['atak'];
                                $pok1['sp_atak'] = $tablica['sp_atak'];
                                $pok1['obrona'] = $tablica['obrona'];
                                $pok1['sp_obrona'] = $tablica['sp_obrona'];
                                $pok1['szybkosc'] = $tablica['szybkosc'];
                                $pok1['hp'] = $tablica['hp'];
                                $pok1['max_hp'] = $tablica['max_hp'];
                                $pok1['id_poka'] = $tablica['id_poka'];
                                $pok1['typ1'] = $tablica['typ1'];
                                $pok1['typ2'] = $tablica['typ2'];
                                $at1 = $tablica['at'];
                                for ($i = 0, $j = 1; $j < 5; $i++, $j++)
                                    $pok1['atak' . $i]['id'] = $tablica['atak' . $j]['id'];
                            }
                            $runda++;
                        }
                        $_SESSION['walkat'] .= '<a id="wynik"></a>';
                        if (isset($wygrana) && $wygrana == 1) {
                            $exp = 100;
                            $_SESSION['walkat'] .= '<div class="alert alert-success margin-top text-medium text-center pogrubienie"><span>Gratulacje, wygrałeś z ' . $lider_nazwa . '.</span></div>';
                            $_SESSION['walkat'] .= '<div class="alert alert-info"><span>W nagrodę dostajesz Odznakę';
                            if ($walka == 1)
                                $_SESSION['walkat'] .= ' Głazu';
                            if ($walka == 2)
                                $_SESSION['walkat'] .= ' Kaskady';
                            if ($walka == 3)
                                $_SESSION['walkat'] .= ' Pioruna';
                            if ($walka == 4)
                                $_SESSION['walkat'] .= ' Tęczy';
                            if ($walka == 5)
                                $_SESSION['walkat'] .= ' Duszy';
                            if ($walka == 6)
                                $_SESSION['walkat'] .= ' Bagna';
                            if ($walka == 7)
                                $_SESSION['walkat'] .= ' Wulkanu';
                            if ($walka == 8)
                                $_SESSION['walkat'] .= ' Ziemi';

                            $_SESSION['walkat'] .= ' <img src="' . URL . 'public/img/odznaki/Kanto' . $walka . '.png" height="40px" width="40px" /></span></div>';

                            $_SESSION['walkat'] .= '<div class="alert alert-info"><span>';
                            $kwer = "UPDATE pokemony SET exp = (exp + $exp) WHERE ";
                            for ($i = 1, $j = 0; $i <= $i2; $i++) {
                                if ($tabela[$i] == 1) {
                                    $_SESSION['walkat1'] .= User::_get('pok', $i)->get('imie') . ' otrzymuje ' . $exp . ' punkty doświadczenia.<br />';
                                    User::_get('pok', $licznik)->edit('dos', (User::_get('pok', $licznik)->get('dos') + $exp));
                                    $id = User::_get('pok', $licznik)->get('id');
                                    if ($j == 0)
                                        $kwer = $kwer . " ID = $id ";
                                    else
                                        $kwer = $kwer . "OR ID = $id ";
                                    $j++;
                                }
                            }
                            //unset($_SESSION['pok1']['id']);
                            $this->model->db->update($kwer, []);
                            $a = 'Kanto' . $walka;
                            $data_dzis = date('Y') . '-' . date('m') . '-' . date('d');
                            $this->model->dodajdoSali($a, $data_dzis);
                            User::$odznaki->kanto[$walka] = 1;
                            $_SESSION['walkat1'] .= '</span></div><div class="alert alert-info"><span>Dodatkowo zyskujesz 50 punktów doświadczenia trenera.</span></div>';
                            Session::_set('tr_exp', (Session::_get('tr_exp') + 50));
                            $kwer = 'UPDATE uzytkownicy SET doswiadczenie = (doswiadczenie + 50)';
                            if ($walka == 6) {
                                $kwer .= ', mpa = (mpa + 20)';
                                Session::_set('mpa', (Session::_get('mpa') + 20));
                            }
                            $this->model->db->select($kwer . ' WHERE ID = ' . Session::_get('id'), []);
                        }
                        if (isset($porazka) && $porazka == 1) {
                            $exp = 5;
                            $_SESSION['walkat'] .= '<div class="alert alert-danger"><span>' . $lider_nazwa . ' okazał się lepszy.</span></div>';
                            $kwer = "UPDATE pokemony SET exp = (exp + $exp) WHERE ";
                            $_SESSION['walkat'] .= '<div class="alert alert-info"><span>';
                            for ($i = 1, $j = 0; $i < $i2; $i++) {
                                if ($tabela[$i] == 1) {
                                    $_SESSION['walkat1'] .= User::_get('pok', $i)->get('imie') . ' otrzymuje ' . $exp . ' punkty doświadczenia.<br />';
                                    User::_get('pok', $i)->edit('dos', (User::_get('pok', $i)->get('dos') + $exp));
                                    $id = User::_get('pok', $i)->get('id');
                                    if ($j == 0)
                                        $kwer = $kwer . " ID = $id ";
                                    else
                                        $kwer = $kwer . " OR ID = $id ";
                                    $j++;
                                }
                            }
                            $this->model->db->update($kwer, []);
                            $_SESSION['walkat1'] .= 'Dodatkowo zyskujesz 3 punkty doświadczenia trenera.</span></div>';
                            Session::_set('tr_exp', (Session::_get('tr_exp') + 3));
                            $this->model->expZaPorazke();
                        }

                        $this->view->walka = $_SESSION['walkat'];
                        $this->view->walka .= $_SESSION['walkat1'];
                        unset($_SESSION['walkat']);
                        unset($_SESSION['walkat1']);
                    } else
                        $this->view->blad = '<div class="alert alert-danger"><span>Nie posiadasz 50PA, aby walczyć z liderem sali.</span></div>';
                } else
                    $this->view->blad = '<div class="alert alert-danger"><span>Nie spełniłeś wymagań, aby walczyć z liderem sali.</span></div>';
            }
        }
        $this->view->render('sale/walka');
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('', 2);
        }
    }

}

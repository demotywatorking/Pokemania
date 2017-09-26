<?php

namespace src\controllers;

use src\libs\Controller;

class Osiagniecia extends Controller
{

    public function __construct()
    {
        parent::__construct();
        require('./src/includes/osiagniecia.php');
        $this->osiagniecieGlowne = $osiagniecie_glowne;
        $this->osiagnieciePoboczne = $osiagniecie_poboczne;
        $this->osiagniecieKanto = $osiagniecie_kanto;
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('Osiągnięcia - ' . NAME, 1);
        }
    }

    public function index()
    {
        $this->getFromDb();
        $this->sprawdzOsiagniecia();
        $this->osiagnieciaGlowne();
        $this->osiagnieciaPoboczne();
        $this->osiagnieciaKanto();
        $this->view->render('osiagniecia/index');
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('', 2);
        }
    }

    private function getFromDb()
    {
        $achievementy = $this->model->achievementy();
        $this->achievementy = $achievementy[0];
        $osiagniecia = $this->model->osiagniecia();
        $this->osiagniecia = $osiagniecia[0];
        $uzytkownicy = $this->model->uzytkownicy();
        $this->uzytkownicy = $uzytkownicy[0];
    }

    private function sprawdzOsiagniecia()
    {
        $this->view->poziom = '';
        $wym_poziom = $this->achievementy['znawca_kanto'] + 1;
        if ($wym_poziom <= 5) {
            $ilosc = 0;
            for ($i = 1; $i <= 7; $i++)
                if ($this->achievementy['znawca_kanto_' . $i] >= $wym_poziom)
                    $ilosc++;
            if ($ilosc == 7) {
                //update bazy i dodanie poziomu achievementa
                $this->model->znawcaKanto();
                $this->achievementy['znawca_kanto']++;
                if ($wym_poziom < 5)
                    $dukaty = 1;
                else
                    $dukaty = 2;
                $this->model->monety($dukaty);
                $tytul = 'Nowe osiągnięcie';
                $tresc = '<div class="row nomargin text-center"><div class="col-xs-12">Zdobyłeś nowe osiągnięcie: <span class="pogrubienie">Znawca regionu Kanto</span>, poziom: ' . $wym_poziom . '</div></div>';
                $this->model->raport($tresc, $tytul);
                $this->view->poziom .= '<div class="alert alert-success text-center"><span>Nowy, ' . $wym_poziom . ' poziom: <span class="pogrubienie">
                        Znawca regionu Kanto</span><br />Otrzymujesz ' . $dukaty . 'x <img src="' . URL . 'public/img/przedmioty/dukat.png" 
                        class="pokeball_min" data-toggle="tooltip" data-title="Dukat" /></span></div>';
            }
        }
        for ($j = 1; $j < 4; $j++) {
            if ($j == 1)
                $osiagniecie = $this->osiagniecieGlowne;
            else if ($j == 2)
                $osiagniecie = $this->osiagnieciePoboczne;
            else if ($j == 3)
                $osiagniecie = $this->osiagniecieKanto;
            for ($i = 1; $i <= count($osiagniecie); $i++) {
                if ($osiagniecie[$i]['tabela'] == '') continue;
                $baza = $osiagniecie[$i]['baza']; //nazwa achievementa w bazie
                $poziom = $this->achievementy[$baza] + 1;//poziom achievementa w bazie
                if ($poziom <= (count($osiagniecie[$i]) - 4)) {//jeśli równe 4, to nie ma co sprawdzać
                    $tabela = explode(';', $osiagniecie[$i]['tabela']);
                    $kolumna = $this->{$tabela[0]}[$tabela[1]]; //nazwa kolumny w osiagnieciach
                    $wymagane = $osiagniecie[$i][$poziom]; //wymagania na nowy poziom osiągnięcia
                    if ($kolumna >= $wymagane) {//nowy poziom achievementa
                        //update bazy i dodanie poziomu achievementa
                        $this->model->achievement($baza);
                        $this->achievementy[$baza]++;
                        if (($poziom) < ((count($osiagniecie[$i]) - 4)))
                            $dukaty = 1;
                        else
                            $dukaty = 2;
                        $this->model->monety($dukaty);
                        $tytul = 'Nowe osiągnięcie';
                        $tresc = '<div class="row nomargin text-center"><div class="col-xs-12">Zdobyłeś nowe osiągnięcie: <span class="pogrubienie">' . $osiagniecie[$i]['nazwa'] . '</span>, poziom: ' . $poziom . '</div></div>';
                        $this->model->raport($tresc, $tytul);
                        $this->view->poziom .= '<div class="alert alert-success text-center"><span>Nowy, ' . $poziom . ' poziom: <span class="pogrubienie">' . $osiagniecie[$i]['nazwa'] . '</span><br />Otrzymujesz ' . $dukaty . 'x <img src="img/przedmioty/dukat.png" class="pokeball_min" data-toggle="tooltip" data-title="Dukat" /></span></div>';
                    }
                }
            }
        }
    }

    private function osiagnieciaGlowne()
    {
        for ($i = 1; $i <= count($this->osiagniecieGlowne); $i++) {
            $baza = $this->osiagniecieGlowne[$i]['baza']; //nazwa tabeli w bazie achievementów
            if ($this->achievementy[$baza] < (count($this->osiagniecieGlowne[$i]) - 4)) $this->view->osiagniecieGlowne[$i]['tlo'] = 'jeden_ttlo';
            else $this->view->osiagniecieGlowne[$i]['tlo'] = 'zielone_tlo_osiagniecie';
            $wymagania = $this->achievementy[$baza] + 1; //Następny poziom :)
            $this->view->osiagniecieGlowne[$i]['nazwa'] = $this->osiagniecieGlowne[$i]['nazwa'];
            $this->view->osiagniecieGlowne[$i]['baza'] = $this->achievementy[$baza];
            $tabela = explode(';', $this->osiagniecieGlowne[$i]['tabela']);
            $tabela_1 = $this->{$tabela[0]}[$tabela[1]];//tabela osiagniecia itp.
            $this->view->osiagniecieGlowne[$i]['max'] = 1;
            if (($this->achievementy[$baza] + 1) <= (count($this->osiagniecieGlowne[$i]) - 4)) {
                $this->view->osiagniecieGlowne[$i]['max'] = 0;
                $wy = $this->osiagniecieGlowne[$i][$wymagania];
                $this->view->osiagniecieGlowne[$i]['echo'] = $this->osiagniecieGlowne[$i]['echo'];
                $this->view->osiagniecieGlowne[$i]['tabela_1'] = $tabela_1;
                $this->view->osiagniecieGlowne[$i]['wy'] = $wy;
                $this->view->osiagniecieGlowne[$i]['dl'] = floor($tabela_1 / $wy * 10000) / 100;
                if ($this->view->osiagniecieGlowne[$i]['dl'] > 100) $this->view->osiagniecieGlowne[$i]['dl'] = 100;
            } else {
                $this->view->osiagniecieGlowne[$i]['echo'] = $this->osiagniecieGlowne[$i]['echo'];
                $this->view->osiagniecieGlowne[$i]['tabela_1'] = $tabela_1;
            }
        }
    }

    private function osiagnieciaPoboczne()
    {
        for ($i = 1; $i <= count($this->osiagnieciePoboczne); $i++) {
            $baza = $this->osiagnieciePoboczne[$i]['baza']; //nazwa tabeli w bazie achievementów
            if ($this->achievementy[$baza] < (count($this->osiagnieciePoboczne[$i]) - 4))
                $this->view->osiagnieciePoboczne[$i]['tlo'] = 'jeden_ttlo';
            else
                $this->view->osiagnieciePoboczne[$i]['tlo'] = 'zielone_tlo_osiagniecie';
            $wymagania = $this->achievementy[$baza] + 1; //Następny poziom :)
            $this->view->osiagnieciePoboczne[$i]['nazwa'] = $this->osiagnieciePoboczne[$i]['nazwa'];
            $this->view->osiagnieciePoboczne[$i]['baza'] = $this->achievementy[$baza];
            $tabela = explode(';', $this->osiagnieciePoboczne[$i]['tabela']);
            $tabela_1 = $this->{$tabela[0]}[$tabela[1]];//tabela osiagniecia itp.
            $this->view->osiagnieciePoboczne[$i]['max'] = 1;
            if (($this->achievementy[$baza] + 1) <= (count($this->osiagnieciePoboczne[$i]) - 4)) {
                $this->view->osiagnieciePoboczne[$i]['max'] = 0;
                $wy = $this->osiagnieciePoboczne[$i][$wymagania];
                $this->view->osiagnieciePoboczne[$i]['echo'] = $this->osiagnieciePoboczne[$i]['echo'];
                $this->view->osiagnieciePoboczne[$i]['tabela_1'] = $tabela_1;
                $this->view->osiagnieciePoboczne[$i]['wy'] = $wy;
                $this->view->osiagnieciePoboczne[$i]['dl'] = floor($tabela_1 / $wy * 10000) / 100;
                if ($this->view->osiagnieciePoboczne[$i]['dl'] > 100) $this->view->osiagnieciePoboczne[$i]['dl'] = 100;
            } else {
                $this->view->osiagnieciePoboczne[$i]['echo'] = $this->osiagnieciePoboczne[$i]['echo'];
                $this->view->osiagnieciePoboczne[$i]['tabela_1'] = $tabela_1;
            }
        }
    }

    private function osiagnieciaKanto()
    {
        $baza = $this->osiagniecieKanto[1]['baza']; //nazwa tabeli w bazie achievementów
        if ($this->achievementy[$baza] < 5) $this->view->kantoZnawca['tlo'] = 'jeden_ttlo';
        else $this->view->kantoZnawca['tlo'] = 'zielone_tlo_osiagniecie';
        $wymagania = $this->achievementy[$baza] + 1; //Następny poziom :)
        $this->view->kantoZnawca['nazwa'] = $this->osiagniecieKanto[1]['nazwa'];
        $this->view->kantoZnawca['baza'] = $this->achievementy[$baza];
        if (($this->achievementy[$baza] + 1) <= 5) {
            $wym_poziom = $this->achievementy[$baza] + 1;
            $ilosc = 0;
            for ($i = 1; $i <= 7; $i++)
                if ($this->achievementy['znawca_kanto_' . $i] >= $wym_poziom) $ilosc++;
            $this->view->kantoZnawca['echo'] = $wym_poziom . ' ' . $this->osiagniecieKanto[1]['echo'] . '(' . $ilosc . ' / 7)<br />';
        } else {
            $this->view->kantoZnawca['echo'] = '';
        }

        for ($i = 2; $i <= count($this->osiagniecieKanto); $i++) {
            $baza = $this->osiagniecieKanto[$i]['baza']; //nazwa tabeli w bazie achievementów
            if ($this->achievementy[$baza] < (count($this->osiagniecieKanto[$i]) - 4))
                $this->view->osiagniecieKanto[$i]['tlo'] = 'jeden_ttlo';
            else
                $this->view->osiagniecieKanto[$i]['tlo'] = 'zielone_tlo_osiagniecie';
            $wymagania = $this->achievementy[$baza] + 1; //Następny poziom :)
            $this->view->osiagniecieKanto[$i]['nazwa'] = $this->osiagniecieKanto[$i]['nazwa'];
            $this->view->osiagniecieKanto[$i]['baza'] = $this->achievementy[$baza];
            $tabela = explode(';', $this->osiagniecieKanto[$i]['tabela']);
            $tabela_1 = $this->{$tabela[0]}[$tabela[1]];//tabela osiagniecia itp.
            $this->view->osiagniecieKanto[$i]['max'] = 1;
            if (($this->achievementy[$baza] + 1) <= (count($this->osiagniecieKanto[$i]) - 4)) {
                $this->view->osiagniecieKanto[$i]['max'] = 0;
                $wy = $this->osiagniecieKanto[$i][$wymagania];
                $this->view->osiagniecieKanto[$i]['echo'] = $this->osiagniecieKanto[$i]['echo'];
                $this->view->osiagniecieKanto[$i]['tabela_1'] = $tabela_1;
                $this->view->osiagniecieKanto[$i]['wy'] = $wy;
                $this->view->osiagniecieKanto[$i]['dl'] = floor($tabela_1 / $wy * 10000) / 100;
                if ($this->view->osiagniecieKanto[$i]['dl'] > 100) $this->view->osiagniecieKanto[$i]['dl'] = 100;
            } else {
                $this->view->osiagniecieKanto[$i]['echo'] = $this->osiagniecieKanto[$i]['echo'];
                $this->view->osiagniecieKanto[$i]['tabela_1'] = $tabela_1;
            }
        }
    }
}


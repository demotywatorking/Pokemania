<?php

namespace src\controllers;

use src\libs\Controller;
use src\libs\Session;
use src\libs\User;

class Ustawienia extends Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('Ustawienia - ' . NAME, 1);
        }
        if (!isset($_GET['active'])) $this->active = 1;
        else $this->active = $_GET['active'];
        $this->view->active = $this->active;
    }

    public function index()
    {
        $this->ustawieniaView();
        $this->view->render('ustawienia/index');
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('', 2);
        }
    }

    public function zmien($co = '', $mode = 0)
    {
        if ($co == '' || !in_array($co,
                ['podpowiedz', 'druzyna', 'targ', 'zegar', 'tooltip', 'leczenie', 'soda', 'woda', 'lemoniada', 'cheri', 'wiki',
                    'nakarm', 'avatar', 'haslo', 'panel', 'tabelka', 'tlo', 'motyw'])
        ) {
            $this->index();
            exit;
        }
        $this->{'zmien' . ucfirst($co)}($mode);
        $this->zapiszUstawienia();
        $this->view->render('ustawienia/komunikat');
        $this->index();
    }

    private function zmienPanel($mode)
    {
        if ($mode < 0 || $mode > 6) $mode = '';
        $this->view->komunikat = 'Panele będą miały teraz ';
        User::$ustawienia->edit('panele', $mode);
        $this->view->komunikat .= $this->docss($mode, '', '');
        $this->view->komunikat .= ' kolor.';
    }

    private function zmienMotyw($mode)
    {
        if(!$mode) {
            $this->view->blad =  'Błąd, wybrano zły styl';
            return;
        }
        $baza = 0;
        switch ($mode) {
            case 1:
                $baza = 1;
                break;
            case 2:
                $baza = 2;
                break;
            default:
                $this->view->blad = 'Błąd, wybrano zły styl';
                return;
        }
        Session::_set('style', $baza);
        $this->model->zmienStyl($baza);
        $this->view->komunikat = 'Poprawnnie zmieniono styl.';
    }

    private function zmienTlo($mode)
    {
        if (!$mode == 'default') {
            $mode = urldecode($_POST['tlo']);
        }
        if ($mode != '' && $mode != 'default') {
            if (!ctype_xdigit(ltrim($mode, '#'))) {
                $this->view->komunikatBlad = 'Błędny kod koloru';
                return false;
            }
        }
        $this->view->komunikat = 'Zmieniono ustawienia tła';
        $this->docss('', $mode, '');
    }

    private function zmienTabelka($mode)
    {
        if ($mode != 1 && $mode != 0) $mode = '';
        $this->docss('', '', $mode);
        $this->view->komunikat = 'Zmieniono ustawienia tabelki.';
    }

    private function docss($panel, $tlo, $tabelka)
    {
        if ($panel == '')
            $panel = User::$ustawienia->get('panele');
        if ($tabelka == '')
            $tabelka = User::$ustawienia->get('tabelka');
        switch ($panel) {
            case 0://zielony
                $css = '';
                $show = 'zielony';
                break;
            case 1://niebieski
                $css = '.modal-header, .panel-success>.panel-heading {background-color:rgba(51, 122, 183, 0.45);border-color:#337ab7;}.panel.panel-success{border-color:#337ab7;}';
                $show = 'niebieski';
                break;
            case 2://pomarańczowy
                $css = '.modal-header, .panel-success>.panel-heading {background-color:rgba(240, 173, 78, 0.45);border-color:#f0ad4e;}.panel.panel-success{border-color:#f0ad4e;}';
                $show = 'pomarańczowy';
                break;
            case 3://czerwony
                $css = '.modal-header, .panel-success>.panel-heading {background-color:rgba(217, 83, 79, 0.45);border-color:#d9534f;}.panel.panel-success{border-color:#d9534f;}';
                $show = 'czerwony';
                break;
            case 4://błękitny
                $css = '.modal-header, .panel-success>.panel-heading {background-color:rgba(91, 192, 222, 0.45);border-color:#5bc0de;}.panel.panel-success{border-color:#5bc0de;}';
                $show = 'błękitny';
                break;
            case 5://ZÓŁTY
                $css = '.modal-header, .panel-success>.panel-heading {background-color:rgba(255, 235, 59, 0.45);border-color:#ffeb3b;}.panel.panel-success{border-color:#ffeb3b;}';
                $show = 'żółty';
                break;
            case 6://FIOLETOWY
                $css = '.modal-header, .panel-success>.panel-heading {background-color:rgba(140, 114, 203, 0.45);border-color:#8C72CB;}.panel.panel-success{border-color:#8C72CB;}';
                $show = 'fioletowy';
                break;
        }
        switch ($tlo) {
            case '':
                $css .= '.container-fluid{background-color:' . User::$ustawienia->get('tlo') . ';}';
                break;
            case 'domyslne':
                //no break;
            case '#1c5b4e':
                User::$ustawienia->edit('tlo', '');
                break;
            default:
                User::$ustawienia->edit('tlo', $tlo);
                $css .= '.container-fluid{background-color:' . $tlo . ';}';
                break;
        }

        switch ($tabelka) {
            case '1':
                User::$ustawienia->edit('tabelka', '1');
                $css .= '#lewo{float:right;}';
                break;
        }
        User::$ustawienia->edit('tabelka', $tabelka);
        $plik = fopen('pliki/css/' . Session::_get('id') . '.css', "w");
        fputs($plik, $css);//zapis do pliku
        fclose($plik);
        return $show;
    }

    private function zmienHaslo($mode)
    {
        if (!isset($_POST['stare']) || $_POST['stare'] == '' || !isset($_POST['haslo']) || $_POST['haslo'] == '' || !isset($_POST['haslo2']) || $_POST['haslo2'] == '') {
            $this->view->komunikatBlad = 'Podaj wszystkie dane';
        } else {
            $rezultat = $this->model->haslo();
            $w = $rezultat[0];
            $tajnykod = 'lLpK,>@d;@]O2eK_?:V7e]9:VPcCFsi?E82Rj2[z2PO[[oNM%y<h[jwf}9=52qmwYONI=7I9,muHbIjeHuV1dSG$?O7jFUfuz-C';
            require("./src/includes/funkcje/kod.php");
            $tajnasol = $sol;
            $solusera = $w['sol'];
            if ($tajnasol == "0") {
                $this->view->komunikatBlad = 'Błąd z bazą danych!';
            }
            $stare_haslo = $_POST['stare'];
            $stare = hash('sha512', $tajnasol . $stare_haslo . $solusera . $stare_haslo . $tajnasol . $solusera);
            if ($stare != $w['haslo']) $this->view->komunikatBlad = 'Podaj poprawnie swoje hasło.';
            else {
                if ($_POST['haslo'] != $_POST['haslo2']) {
                    $this->view->komunikatBlad = 'Hasła nie zgadzają się.';
                } elseif (strlen($_POST['haslo']) < 8) {
                    $this->view->komunikatBlad = 'Nowe hasło jest zbyt krótkie.';
                } else {
                    if ($_POST['haslo'] == $_POST['stare']) {
                        $this->view->komunikatBlad = 'Nie możesz ustawić takiego samego hasła jak poprzednio.';
                    } else {
                        $haslo = $_POST['haslo'];
                        $haslo = hash('sha512', $tajnasol . $haslo . $solusera . $haslo . $tajnasol . $solusera);
                        $this->model->zmienHaslo($haslo);
                        $this->view->komunikat = 'Poprawnie zmieniono hasło.<br />Za chwilę zostaniesz wylogowany.';
                        Session::_destroy();
                    }
                }
            }
        }
    }

    private function zmienAvatar($mode)
    {
        if (isset($_POST['link_a'])) {
            if ($_POST['link_a'] == 'usun') {
                $this->model->usunAvatar();
                $this->view->komunikat = 'Usunięto avatar.';
            } else {
                if ($_POST['link_a'] == '') {
                    $this->view->komunikatBlad = 'To pole nie może być puste!';
                } else {
                    $wym = getimagesize($link);
                    if ($wym[0] == 250 && $wym[1] == 300) {
                        $this->model->ustawAvatar();
                        $this->view->komunikat = 'Zmieniono avatar.';
                    } else {
                        $this->view->komunikatBlad = '<div class="alert alert-danger"><span>Avatar ma nieprawidłowe wymiary!</span></div>';
                    }
                }
            }
        } else {
            $this->index();
            exit;
        }
    }

    private function zmienSoda($mode)
    {
        if ($mode != 0 && $mode != 1) $mode = User::$ustawienia->get('soda');
        User::$ustawienia->edit('soda', $mode);
        $mode ? $this->view->komunikat = 'Przycisk wypicia sody będzie wyświetlany'
            : $this->view->komunikat = 'Przycisk wypicia sody nie będzie wyświetlany';
    }

    private function zmienWoda($mode)
    {
        if ($mode != 0 && $mode != 1) $mode = User::$ustawienia->get('woda');
        User::$ustawienia->edit('woda', $mode);
        $mode ? $this->view->komunikat = 'Przycisk wypicia wody będzie wyświetlany'
            : $this->view->komunikat = 'Przycisk wypicia wody nie będzie wyświetlany';
    }

    private function zmienLemoniada($mode)
    {
        if ($mode != 0 && $mode != 1) $mode = User::$ustawienia->get('lemoniada');
        User::$ustawienia->edit('lemoniada', $mode);
        $mode ? $this->view->komunikat = 'Przycisk wypicia lemoniady będzie wyświetlany'
            : $this->view->komunikat = 'Przycisk wypicia lemoniady nie będzie wyświetlany';
    }

    private function zmienCheri($mode)
    {
        if ($mode != 0 && $mode != 1) $mode = User::$ustawienia->get('cheri');
        User::$ustawienia->edit('cheri', $mode);
        $mode ? $this->view->komunikat = 'Przycisk uleczenia drużyny Cheri Berry będzie wyświetlany'
            : $this->view->komunikat = 'Przycisk uleczenia drużyny Cheri Berry nie będzie wyświetlany';
    }

    private function zmienWiki($mode)
    {
        if ($mode != 0 && $mode != 1) $mode = User::$ustawienia->get('wiki');
        User::$ustawienia->edit('wiki', $mode);
        $mode ? $this->view->komunikat = 'Przycisk uleczenia drużyny Wiki Berry będzie wyświetlany'
            : $this->view->komunikat = 'Przycisk uleczenia drużyny Wiki Berry nie będzie wyświetlany';
    }

    private function zmienNakarm($mode)
    {
        if ($mode != 0 && $mode != 1) $mode = User::$ustawienia->get('nakarm');
        User::$ustawienia->edit('nakarm', $mode);
        $mode ? $this->view->komunikat = 'Przycisk nakarm drużynę będzie wyświetlany'
            : $this->view->komunikat = 'Przycisk nakarm drużynę nie będzie wyświetlany';
    }

    private function zmienLeczenie($mode)
    {
        if ($mode != 0 && $mode != 1) $mode = User::$ustawienia->get('leczenie');
        User::$ustawienia->edit('leczenie', $mode);
        $mode ? $this->view->komunikat = 'Przycisk leczenia będzie wyświetlany'
            : $this->view->komunikat = 'Przycisk leczenia nie będzie wyświetlany';
    }

    private function zmienTooltip($mode)
    {
        if ($mode != 0 && $mode != 1) $mode = User::$ustawienia->get('tooltip');
        User::$ustawienia->edit('tooltip', $mode);
        $mode ? $this->view->komunikat = 'Tooltipy będą wyświetlane'
            : $this->view->komunikat = 'Tooltipy nie będą wyświetlane';
    }

    private function zmienZegar($mode)
    {
        if ($mode != 0 && $mode != 1) $mode = User::$ustawienia->get('zegar');
        User::$ustawienia->edit('zegar', $mode);
        $mode ? $this->view->komunikat = 'Zegar będzie wyświetlany'
            : $this->view->komunikat = 'Zegar nie będzie wyświetlany';
    }

    private function zmienTarg($mode)
    {
        if ($mode != 0 && $mode != 1) $mode = User::$ustawienia->get('targ');
        User::$ustawienia->edit('targ', $mode);
        $mode ? $this->view->komunikat = 'Własne oferty będą widoczne'
            : $this->view->komunikat = 'Własne oferty nie będą widoczne';
    }

    private function zmienDruzyna($mode)
    {
        if ($mode != 0 && $mode != 1) $mode = User::$ustawienia->get('druzyna');
        User::$ustawienia->edit('druzyna', $mode);
        $mode ? $this->view->komunikat = 'Odblokowano widok drużyny'
            : $this->view->komunikat = 'Zablokowano widok drużyny';
    }

    private function zmienPodpowiedz($mode)
    {
        if ($mode != 0 && $mode != 1) $mode = User::$ustawienia->get('podpowiedz');
        User::$ustawienia->edit('podpowiedz', $mode);
        $mode ? $this->view->komunikat = 'Podpowiedzi będą widoczne'
            : $this->view->komunikat = 'Podpowiedzi nie będą widoczne';
    }

    private function zapiszUstawienia()
    {
        $this->model->zapiszUstawienia();
    }

    private function ustawieniaView()
    {
        $avatar = $this->model->avatarPobierz();
        $this->view->avatar = $avatar['avatar'];
        $this->view->podp = User::$ustawienia->get('podpowiedz');
        $this->view->blokada = User::$ustawienia->get('druzyna');
        $this->view->targ = User::$ustawienia->get('targ');
        $this->view->zegar = User::$ustawienia->get('zegar');
        $this->view->tooltip = User::$ustawienia->get('tooltip');
        $this->view->css = User::$ustawienia->get('panele');
        $this->view->tlo = User::$ustawienia->get('tlo');
        $this->view->tabelka = User::$ustawienia->get('tabelka');
        $this->view->lemoniada = User::$ustawienia->get('lemoniada');
        $this->view->woda = User::$ustawienia->get('woda');
        $this->view->soda = User::$ustawienia->get('soda');
        $this->view->leczenie = User::$ustawienia->get('leczenie');
        $this->view->cheri = User::$ustawienia->get('cheri');
        $this->view->nakarm = User::$ustawienia->get('nakarm');
        $this->view->wiki = User::$ustawienia->get('wiki');
        $this->view->motyw = Session::_get('style');
    }
}
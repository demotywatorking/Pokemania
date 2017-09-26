<?php

namespace src\controllers;

use src\includes\functions\FunctionsDate;
use src\libs\Controller;
use src\libs\Session;

class Wiadomosci extends Controller
{
    use FunctionsDate;

    public function __construct()
    {
        parent::__construct();
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('Wiadomości - '.NAME, 1, 0, ['<script type="text/javascript" src="'.URL.'public/js/emoticons.js"></script>']);
        }
    }

    public function index()
    {
        $this->pobierzWiadomosci();
        $this->view->render('wiadomosci/index');
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('', 2);
            $this->model->oznaczPrzeczytane();
            if (Session::_isset('nowe_w')) {
                Session::_unset('nowe_w');
            }
        }
    }

    private function pobierzWiadomosci()
    {
        $rezultat = $this->model->pobierzWiadomosci();
        $ile = $rezultat['rowCount'];
        for ($i = 0 ; $i < $ile ; $i++) {
            $wiersz = $rezultat[$i];
            $rezultat2 = $this->model->login($wiersz['id_nadawca']);
            $wiersz2 = $rezultat2[0];
            $this->view->wiadomosc[$i]['twojaNazwa'] =  Session::_get('nick');
            $this->view->wiadomosc[$i]['login'] =  $wiersz2['login'];
            $this->view->wiadomosc[$i]['ID'] =  $wiersz['ID'];
            $this->view->wiadomosc[$i]['odczytana'] =  $wiersz['odczytana'];
            $this->view->wiadomosc[$i]['id_nadawca'] =  $wiersz['id_nadawca'];
            $this->view->wiadomosc[$i]['data_ost'] =  $this->pokazDate($wiersz['data_ost'], 1);
        }
    }

    public function nowa()
    {
        if (isset($_GET['odbiorca'])) {
            $this->view->odbiorca = $_GET['odbiorca'];
        } else {
            $this->view->odbiorca = '';
        }
        if (isset($_GET['tresc'])) {
            $this->view->tresc = $_GET['tresc'];
        } else {
            $this->view->tresc = '';
        }
        $this->view->render('wiadomosci/nowa');
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('', 2);
        }
    }

    public function wyslij()
    {
        if (!isset($_POST['tresc']) || !isset($_POST['odbiorca'])) {
            $this->nowa();
            return;
        }
        $tresc = $_POST['tresc'];
        $odbiorca = $_POST['odbiorca'];
        if($tresc == '') {
            $this->view->blad = 'Nie możesz wysłać pustej wiadomości.';
            $_GET['odbiorca'] = $odbiorca;
            $this->nowa();
            return;
        }
        if($odbiorca == '') {
            $this->view->blad = 'Pole odbiorcy nie może być puste.';
            $_GET['tresc'] = $tresc;
            $this->nowa();
            return;
        }
        if($odbiorca == Session::_get('nick')) {
            $this->view->blad = 'Nie możesz wysłać wiadomości do siebie.';
            $_GET['tresc'] = $tresc;
            $this->nowa();
            return;
        }
        $godzina = date('Y-m-d H:i:s');
        $rezultat = $this->model->idUzytkownika($odbiorca);
        if (!$rezultat['rowCount']) {
            $this->view->blad = 'Użytkownik o podanym loginie nie istnieje.';
            $_GET['tresc'] = $tresc;
            $this->nowa();
            return;
        }
        $rezultat = $rezultat[0];
        $czy_istnieje = $this->model->czyWiadomoscIstnieje($rezultat['ID']);
        if ($czy_istnieje['rowCount']) { //odpisujemy
            $tresc1 = '||W:'.$tresc.'|'.$godzina;
            $tresc2 = '||O:'.$tresc.'|'.$godzina;
            $this->model->dodajDoWiadomosci($tresc1, $tresc2, $godzina, $rezultat['ID']);
            $this->view->info = 'Wiadomość wysłana';
        } else {
            $tresc1 = 'W:'.$tresc.'|'.$godzina;
            $tresc2 = 'O:'.$tresc.'|'.$godzina;
            $this->model->dodajWiadomosc($rezultat['ID'], $tresc1, $godzina, $tresc2);
            $this->view->info = 'Wiadomość poprawnie wysłana.';
        }
        $this->index();
    }

    public function id(int $id, int $n = 0, $nowe = 0, $odpisz = 0)
    {
        $this->rez = $this->model->pobierzWiadomosc($id);
        if (!$this->rez['rowCount']) {
            $this->view->modal = '{ "title" : "", "body": ' . json_encode('<div class="alert alert-warning"><span>Nie znaleziono wiadomości</span></div>') . '}';
            $this->view->render('wiadomosci/modal');
            return;
        }
        if ($odpisz) {
            $this->odpisz($id);
            $this->view->render('wiadomosci/modal');
            return;
        }
        if (!$nowe) {
            $this->wiadomosc($id, $n);
        } else {
            $this->wiadomoscNowe($id, $n);
        }
        $this->view->render('wiadomosci/modal');
    }

    private function wiadomosc($id, $n)
    {
        if (!$n) $tresc = '<div class="scroll">';
        else $tresc = '';
        $this->rez = $this->rez[0];
        $nad = $this->model->login($this->rez['id_nadawca']);
        $nad = $nad[0];
        $nad = $nad['login'];
        if ($this->rez['tresc'] == '') $tresc .= '-Brak treści-';
        $wiad = explode('||', $this->rez['tresc']);
        if (!$n) $s = count($wiad) - 1;
        //sprawdzić czy wiad jest liczbą dodatnią!
        else $s = count($wiad) - 2 - $n;
        $z = $s - 30;
        if ($z < 0) $z = 0;
        for ($j = $z; $j <= $s; $j++) {
            if ($j == $z) $tresc .= '<div id="' . $s . '" class="ost d_none"></div>';
            if ($wiad[$j][0] == 'W') $tresc .= '<div class="wiad"><div class="pull-right twoja-wiadomosc width-75">';
            else $tresc .= '<div class="wiad"><div class="pull-left nadawca-wiadomosc width-75">';
            //$tresc .=  '<div class="wiad-div text-left">';
            //if($wiad[$j][0] == 'W') $tresc .=  $user->__get('nick');
            //else $tresc .=  $nad;

            $tresc .= '<div class="wiadomosc_tresc" data-toggle="tooltip" ';
            $wiad[$j][0] == 'W' ? $tresc .= 'data-placement="left"' : $tresc .= 'data-placement="right"';
            $wiad[$j] = explode('|', $wiad[$j]);
            $wiad[$j][0] = substr($wiad[$j][0], 2);
            $tresc .= ' data-title="' . $wiad[$j][1] . '">' . html_zn($wiad[$j][0]) . '</div></div></div><div class="clear"></div>';
        }
        if (!$n) {
            $tresc .= '</div>';
            $tresc .= '<div><textarea rows="1" class="form_input odpisz" placeholder="Wpisz tekst odpowiedzi"></textarea></div>';
        }
        $this->view->modal = '{ "nadawca" : ' . json_encode($nad) . ' , "ost" : ' . json_encode($this->rez['data_ost']) . ' , "body": ' . json_encode($tresc) . ' }';
    }

    private function wiadomoscNowe($id, $n)
    {
        $this->rez = $this->rez[0];
        $wiad = explode('||', $this->rez['tresc']);
        $ilosc = 0;
        $tresc = '';
        if($n < count($wiad) - 1) {
            $ilosc = count($wiad) - 1 - $n;
            $s = count($wiad) - 1;
            $z = $n + 1 ;
            for($j = $z ; $j <= $s ; $j++) {
                if($j == $z) $tresc .= '<div id="'.$s.'" class="ost d_none"></div>';
                if($wiad[$j][0] == 'W') $tresc .=  '<div class="wiad"><div class="pull-right twoja-wiadomosc width-75">';
                else $tresc .=  '<div class="wiad"><div class="pull-left nadawca-wiadomosc width-75">';
                //$tresc .=  '<div class="wiad-div text-left">';
                //if($wiad[$j][0] == 'W') $tresc .=  $user->__get('nick');
                //else $tresc .=  $nad;

                $tresc .=  '<div class="wiadomosc_tresc" data-toggle="tooltip" ';
                $wiad[$j][0] == 'W' ? $tresc .= 'data-placement="left"' : $tresc .= 'data-placement="right"';
                $wiad[$j] = explode('|', $wiad[$j]);
                $wiad[$j][0] = substr($wiad[$j][0], 2);
                $tresc .= ' data-title="'.$wiad[$j][1].'">'.html_zn($wiad[$j][0]).'</div></div></div><div class="clear"></div>';
            }
        }
        $this->view->modal = '{ "ilosc" : "'.$ilosc.'", "body" : '.  json_encode($tresc).' }';
    }

    private function odpisz($id)
    {
        $text = nl2br_str($_GET['text']);
        if ($text != '') {
            $rez = $this->rez[0];
            print_r($rez);
            $godzina = date('Y-m-d H:i:s');
            $rezultat1['ID'] = $rez['id_nadawca'];
            $tresc1 = '||W:'.$text.'|'.$godzina;
            $tresc2 = '||O:'.$text.'|'.$godzina;
            $this->model->dodajDoWiadomosci($tresc1, $tresc2, $godzina, $rezultat1['ID']);
            $body = '<div class="wiad"><div class="pull-right twoja-wiadomosc width-75"><div class="wiadomosc_tresc" data-toggle="tooltip" data-placement="left" data-title="'.$godzina.'">'.html_zn($text).'</div></div></div><div class="clear"></div>';
            $this->view->modal = '{ "status" : "OK", "body" : '.  json_encode($body).' }';
        }
        else $this->view->modal = '{  "status" : "pusta" }';
    }

}

?>

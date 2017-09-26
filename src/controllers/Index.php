<?php

namespace src\controllers;

use src\libs\Controller;
use src\libs\Session;

class Index extends Controller {

    public function __construct() {
        parent::__construct();
    }
    
    public function index()
    {
        if (Session::_get('logged')) {
            if (Session::_get('aktywnosc') == '') {
                    header('Location: '.URL.'gra');
            } else {
                header('Location: '.URL.Session::_get('aktywnosc'));
            }
            exit;
        } else {
            if(isset($_COOKIE['al']) && $_COOKIE['al'] != ''){
                header('Location: '.URL.'zaloguj/al');
		        exit;
            }
        }
        $this->view->render('index/head');
        $this->view->online = $this->graczyOnline();
        if(Session::_isset('lastpage')){
            $this->view->lastpage = Session::_get('lastpage');
        }
        if(Session::_isset('bladZaloguj')){
            $this->view->blad = '<div class="zal_blad">'.Session::_get('bladZaloguj').'</div>';
            Session::_unset('bladZaloguj');
        }
        if (Session::_isset('get')) {
            switch (Session::_get('get')) {
                case 1:
                    $this->view->wylogowano = 'Poprawnie wylogowałeś się z gry';
                    break;
                case 2:
                    $this->view->wylogowano = 'Twoja sesja wygasła';
                    break;
                default:
                    $this->view->wylogowano = 'Nie jesteś zalogowany';
                    break;
            }
            Session::_unset('get');
        }
        if(isset($_GET['rej_login'])){
            $this->view->rej_login = '<div class="info" id="zarejestrowano"><div id="zarejestrowano_zamknij">'
                    . '<img src="img/x.png" id="zamknij_zarejestrowano" class="zamknij_obrazek kursor" />'
                    . '</div>Poprawnie zarejestrowano użytkownika o loginie: '.$_GET['rej_login'].'</div>';
        }
        $this->view->render('index/index');
    }

    public function rejestracja()
    {
        if (!isset($_POST['login'])) {
            $this->index();
            return;
        }
        $wszystko_OK = true;
        $login = $_POST['login'];
        if ((strlen($login) < 5) || (strlen($login) > 20)) {
            $wszystko_OK = false;
            $this->view->e_login = 'Login musi posiadać od 5 do 20 znaków';
        }
        if (ctype_alnum($login) == false) {
            $wszystko_OK = false;
            $this->view->e_login = 'Nick może składać się tylko z liter i cyfr (bez polskich znaków)';
        }
        $rezultat = $this->model->idGraczaPoLoginie($login);
        if ($rezultat['rowCount']) {
            $wszystko_OK = false;
            $this->view->e_login = 'Istnieje już konto przypisane do tego loginu';
        }
        $email = $_POST['email'];
        $sprawdz = '/^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9\-.]+\.[a-zA-Z]{2,4}$/';
        if (!preg_match($sprawdz, $email)) {
            $wszystko_OK = false;
            $this->view->e_email = 'Email nie jest poprawny';
        }
        $rezultat = $this->model->idGraczaPoMailu($email);
        if ($rezultat['rowCount']) {
            $wszystko_OK = false;
            $this->view->e_email = 'Istnieje już konto przypisane do tego emaila';
        }
        //sprawdzenie poprawności emaila KONIEC
        //sprawdzenie poprawności hasła
        $haslo1 = $_POST['haslo'];
        $haslo2 = $_POST['haslo2'];
        if ((strlen($haslo1) < 8)) {
            $wszystko_OK = false;
            $this->view->e_haslo = 'Hasło zbyt krótkie';
        }
        if ($haslo1 != $haslo2) {
            $wszystko_OK = false;
            $this->view->e_haslo = 'Hasła nie są identyczne';
        }
        if ($_POST['pokemon'] != '1' && $_POST['pokemon'] != '4' && $_POST['pokemon'] != '7') {
            $wszystko_OK = false;
            $this->view->e_pok = 'Wybierz swojego pokemona';
        }
        if (!isset($_POST['regulamin'])) {
            $wszystko_OK = false;
            $this->view->e_regulamin = 'Potwierdź akceptację regulaminu';
        }
        $secret = '6LcgbAsUAAAAALGj0v2orGqp4EVbnWN9ELYcR1cS';
        $sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_POST['g-recaptcha-response']);
        $odpowiedz = json_decode($sprawdz);
        if ($odpowiedz->success == false) {
            $wszystko_OK = false;
            $this->view->e_bot = 'Potwierdź, że nie jesteś botem';
        }
        //sprawdzenie captchy KONIEC

        $this->view->fr_login = $_POST['login'];
        $this->view->fr_email = $_POST['email'];
        if (isset($_POST['regulamin'])) $this->view->fr_regulamin = true;
        $this->view->fr_pok = $_POST['pokemon'];
        if ($wszystko_OK == true) {/////////////////////////////////////////////
            $tajnykod = "lLpK,>@d;@]O2eK_?:V7e]9:VPcCFsi?E82Rj2[z2PO[[oNM%y<h[jwf}9=52qmwYONI=7I9,muHbIjeHuV1dSG$?O7jFUfuz-C";
            require_once('./src/includes/kod.php');
            $tajnasol = $sol;
            if (!$tajnasol) {
                header('Location: '.URL);
                $this->view->rej_blad = 'Błąd z bazą danych!<br />Skontaktuj się z administratorem, jeśli błąd
                    będzie się powtarzać!';
                $this->index();
                return;
            }
            $tabelka = '1234567890qwertyuiopasdfghjklzxcvbnm`[{]}\|-_=+;:".>/?,<!@#$%^&*()' . "'";
            $solusera = '';
            for ($i = 0; $i < 150; $i++) {// 150 to długość ciągu
                $solusera .= $tabelka[mt_rand(0, strlen($tabelka) - 1)];
            }
            $solusera = hash('sha512', $solusera);
            $haslo_hash = hash('sha512', $tajnasol . $haslo1 . $solusera . $haslo1 . $tajnasol . $solusera);
            $godzina = date('Y-m-d-H-i-s');
            /*$tabelka2 = '1234567890qwertyuiopasdfghjklzxcvbnm_';
            $kodmaila = '';
                for ($i = 0; $i < 20; $i++)
                    $kodmaila .= $tabelka2[mt_rand(0, strlen($tabelka) - 1)];
            */
            $ip = $_SERVER['REMOTE_ADDR'];
            $id = $this->model->rejestracja($solusera, $login, $haslo_hash, $email, $godzina, $ip);

            /*$wiadomosc = 'Potwierdź swój adres email klikając na <a href="pokemania.cf/potwierdz.php?kod=' . $kodmaila . '">TEN LINK</a>';
            $wiadomosc .= "<br /> Jeśli nie rejestrowałeś się w grze pokemon pokemania.cf - zignoruj tego maila";
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
            $headers .= 'From: potwierdzenie@pokemania.cf' . "\r\n";
            mail($email, "Potwierdź swój adres email", $wiadomosc, $headers);*/
            //header('Location: potwierdzenie.php');
            $this->view->rej_login = $login;
            $this->zrobStarter($_POST['pokemon'], $id);
        } else {
            $this->view->bladRejestracja = 1;
        }
        $this->index();
    }

    private function zrobStarter($idPoka, $idGracza)
    {
        if ($idPoka != 1 && $idPoka != 4 && $idPoka != 7) $idPoka = 1;
        require('./src/includes/pokemony/pokemon.php');
        $wiersz = $pokemon_plik[$idPoka];
        //WARTOŚĆ/////////////////////////////
        $rand = rand(90, 110)/100;
        $wiersz['wartosc'] = floor(((2500 + (5 * 330) + (3 * 1350)) * (0.75*3))*$rand);
        ////WARTOŚĆ KONIEC//////////////////////
        //PŁEĆ
        $_0 = $wiersz['plec_m'];
        $_1 = $wiersz['plec_k'];
        $p = rand()%1000;
        ($p < $_0) ? $wiersz['plec'] = 0 : $wiersz['plec'] = 1;
        //PŁEĆ KONIEC
        if ($idPoka == 1) {
            $ID = $this->insertBulbasaur($idGracza, $wiersz);
        } elseif ($idPoka == 4) {
            $ID = $this->insertCharmander($idGracza, $wiersz);
        } elseif ($idPoka == 7){
            $ID = $this->insertSquirtle($idGracza, $wiersz);
        }

        //$rezultat = $db->sql_query("SELECT ID FROM pokemony WHERE wlasciciel = $id_gracza");
        $rezultat = $this->model->starter($ID, $idGracza);
        $this->model->pokemonJagody($ID, $idGracza);
    }

    private function insertBulbasaur($wlasciciel, $info)
    {
        return $this->model->bulbasaur($wlasciciel, $info['id_poka'], $info['nazwa'], $info['plec'], $info['wartosc']);
    }

    private function insertCharmander($wlasciciel, $info)
    {
        return $this->model->charmander($wlasciciel, $info['id_poka'], $info['nazwa'], $info['plec'], $info['wartosc']);
    }

    private function insertSquirtle($wlasciciel, $info)
    {
        return $this->model->squirtle($wlasciciel, $info['id_poka'], $info['nazwa'], $info['plec'], $info['wartosc']);
    }

    /**
     * @return int number of players online
     */
    private function graczyOnline() : int
    {
        $rez = $this->model->online();
        $rez = $rez[0];
        return $rez['gracze'];
    }

    public function ostatnie()
    {
        require('./src/includes/pokemony/pokemon.php');
        $poczki = $this->model->ostatnie();
        for ($i = 0 ; $i < $poczki['rowCount']; $i++) {
            $this->view->pok[$i] = $poczki[$i];
            $this->view->pok[$i]['nazwa'] = $pokemon_plik[$this->view->pok[$i]['id_poka']]['nazwa'];
        }
        $this->view->render('index/poki');
    }

    public function przypomnij($kod = '')
    {
        if($kod) {
            $this->przypomnijZKodu($kod);
            return;
        }
        if (!isset($_POST['login'])) {
            $this->index();
            return;
        }
        $zmienna = $_POST['login'];
        $mail = false;
        $login = true;
        for ($i = 0 ; $i < strlen($zmienna) ; $i++) {
            if ($zmienna[$i] == '@') {
                $mail = true;
                $login = false;
                break;
            }
        }
        if($mail) $rezultat = $this->model->szukajMail($zmienna);
        else $rezultat = $this->model->szukajLogin($zmienna);
        if($rezultat['rowCount']) $w = $rezultat[0];
        else {
            $this->view->blad = 'Nie znaleziono użytkownika o danym loginie lub emailu.';
            $this->index();
            return;
        }
        //możliwość przypomnienia hasła
        if(!$mail) $email = $w['email'];
        else $email = $zmienna;
        $tabelka2 = '1234567890qwertyuiopasdfghjklzxcvbnm_';
        $kodmaila = '';
        for ($i = 0; $i < 20; $i++) {
            $kodmaila .= $tabelka2[mt_rand(0, strlen($tabelka2) - 1)];
        }
        $wiadomosc = 'Aby zresetować hasło kliknij na link: '.URL.'index/przypomnij/'.$kodmaila;
        $wiadomosc .= '<br /> Jeśli nie chciałeś zresetować hasła w pseudogrze pokemon pokemania.cf - zignoruj tego maila';
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        $headers .= 'From: potwierdzenie@pokemania.cf' . "\r\n";
        mail($email, 'Prośba o zresetowanie hasła', $wiadomosc, $headers);
        $this->model->zmienKod($kodmaila, $w['ID']);
        $this->view->komunikat = 'Wysłano e-mail umożliwiający zresetowanie hasła.';
        $this->index();
    }

    private function przypomnijZKodu($kod)
    {
        $wynik = $this->model->szukajKod($kod);
        if (!$wynik['rowCount']) {
            $this->view->blad = 'Błędny kod lub hasło już zmienione';
            $this->index();
            return;
        }
    }

}
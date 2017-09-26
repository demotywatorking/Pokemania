<?php

namespace src\controllers;

use src\libs\Controller;
use src\libs\Session;
use src\libs\User;

class Targ extends Controller
{
    public function __construct()
    {
        parent::__construct();
        require('./src/includes/przedmioty_targ.php');
        $this->przedmiotJagody = $przedmiot_jagody;
        $this->przedmiotInne = $przedmiot_inne;
        $this->przedmiotPokeball = $przedmiot_pokeball;
        $this->przedmiotKamienie = $przedmiot_kamienie;
        if (!isset($_GET{'ajax'})) {
            $this->loadTemplate('Targ - '.NAME);
        }
    }

    public function index($mode = 0)
    {
        if(!isset($_GET['active'])) $this->view->active = 1;
        else $this->view->active = $_GET['active'];
        if ($mode) $this->view->mode = 1;
        if (!isset($_GET{'ajax'})) {
            $this->jagody();
            $this->pokeballe();
            $this->inne();
            $this->kamienie();
            $this->view->render('targ/przedmiot');
            if (!$mode) {
                $this->loadTemplate('', 2);
            }
        }
    }

    private function jagody()
    {
        for ($i = 1 ; $i <= count($this->przedmiotJagody); $i++) {
            $this->view->jagoda[$i]['nazwa'] = $this->przedmiotJagody[$i];
        }
    }

    private function pokeballe()
    {
        for ($i = 1 ; $i <= count($this->przedmiotPokeball); $i++) {
            $this->view->pokeball[$i]['nazwa'] = $this->przedmiotPokeball[$i];
        }
    }

    private function inne()
    {
        for ($i = 1 ; $i <= count($this->przedmiotInne); $i++) {
            $this->view->inne[$i]['nazwa'] = $this->przedmiotInne[$i];
        }
    }

    private function kamienie()
    {
        for ($i = 1 ; $i <= count($this->przedmiotKamienie); $i++) {
            $this->view->kamien[$i]['nazwa'] = $this->przedmiotKamienie[$i];
            switch ($this->przedmiotKamienie[$i]) {
                case 'ksiezycowe':
                    $this->view->kamien[$i]['nazwa1'] = 'księżycowy';
                    break;
                case 'roslinne':
                    $this->view->kamien[$i]['nazwa1'] = 'roślinny';
                    break;
                case 'ogniste':
                    $this->view->kamien[$i]['nazwa1'] = 'ognisty';
                    break;
                case 'wodne':
                    $this->view->kamien[$i]['nazwa1'] = 'wodny';
                    break;
                default:
                    $this->view->kamien[$i]['nazwa1'] = $this->przedmiotKamienie[$i];
            }
        }
    }

    public function szukaj($przedmiot = 'all')
    {
        if (!isset($_GET['ajax'])) {
            $this->index(1);
        }
        $this->przedmiot['nazwa'] = $przedmiot;
        if ($this->przedmiot['nazwa'] == 'all') {
            $this->view->przedmiot = '- WSZYSTKO';
        } else {
            $this->przedmiot['rodzaj'] = '';
            $this->przedmiot['numer'] = 0;
            if (!$this->czyIstnieje()) {
                $this->view->blad = 'Przedmiot nie istnieje';
                $this->view->render('targ/szukaj');
                if (!isset($_GET['ajax'])) {
                    $this->loadTemplate('',2);
                }
                return;
            }
            if ($this->przedmiot['rodzaj'] == 'kamienie') {
                $this->view->przedmiot = '- KAMIENIE ';
                switch ($przedmiot) {
                    case 'ksiezycowe':
                        $this->view->przedmiot .= 'księżycowe';
                        break;
                    case 'roslinne':
                        $this->view->przedmiot  .= 'roślinne';
                        break;
                    default:
                        $this->view->przedmiot  .= $przedmiot;
                }
            } else {
                $this->view->przedmiot = '- '.str_replace("_"," ", $przedmiot);
            }
        }
        $this->view->przedmiotDiv = $przedmiot;
        $a = User::$ustawienia->get('targ');
        define ('ILOSC_NA_STRONIE', 30);
        if(isset($_GET['p']))  $p = --$_GET['p'];
        else  $p = 0;

        $klery = 'SELECT SQL_CALC_FOUND_ROWS *
                   FROM targ WHERE ';

        if ($przedmiot != "all") {
            $klery .= 'co = \''.$przedmiot.'\'';
            $klery1 = ' AND ';
        }
        if (!$a) {
            $klery2 = '1';
            if(isset($klery1)) $klery .= $klery1;
            $klery .= ' id_gracza != '.Session::_get('id');
        }
        if (!isset($klery1) && !isset($klery2)) {
            $klery .= ' 1=1 ';
        }
        $klery .= ' ORDER BY ID ASC LIMIT '.($p*ILOSC_NA_STRONIE).','.ILOSC_NA_STRONIE;
        //if($_GET['przedmiot'] == "all") $klery = "SELECT * FROM targ WHERE id_gracza <> $_SESSION[id]";
        //else {$nazwa = $db->sql_filter($_GET['przedmiot']); $klery = "SELECT * FROM targ WHERE co = '$nazwa' AND id_gracza <> $_SESSION[id]";}


        //$show .=  '<div id="przedmiot" class="d_none">'.$przedmiot.'</div>';
        $rezultat = $this->model->db->select($klery, []);
        $this->view->ilosc = 0;
        if ($rezultat['rowCount']) {
            $RES = $this->model->db->select('SELECT FOUND_ROWS() as Ilosc', []);
            $ilosc = $RES[0];
            $this->view->ilosc = $ilosc['Ilosc'];
            $this->przedmiotySzukanie($rezultat);
        }

        $this->view->render('targ/szukaj');
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('',2);
        }
    }

    private function przedmiotySzukanie(array $rezultat)
    {
        for ($i = 0 ; $i < $rezultat['rowCount'] ; $i++) {
            $this->view->przedmioty[$i] = $rezultat[$i];
            if ($rezultat[$i]['id_gracza'] == Session::_get('id')) {
                $this->view->przedmioty[$i]['wlasna'] = 1;
            } else {
                $this->view->przedmioty[$i]['wlasna'] = 0;
            }
            if($this->przedmiot['nazwa'] == 'all') {
                $prz = $wiersz['co'];
                if(isset($przedmiot['rodzaj']))unset ($przedmiot['rodzaj']);
                for($j = 1 ; $j <= count($przedmiot_jagody) ; $j++)
                    if($przedmiot_jagody[$j] == $prz)
                    {
                        $przedmiot['rodzaj'] = 'jagoda';
                        $przedmiot['numer'] = $j;
                        break;
                    }
                if(!isset($przedmiot['rodzaj']))
                    for($j = 1 ; $j <= count($przedmiot_pokeball) ; $j++)
                        if($przedmiot_pokeball[$j] == $prz)
                        {
                            $przedmiot['rodzaj'] = 'pokeball';
                            $przedmiot['numer'] = $j;
                            break;
                        }
                if(!isset($przedmiot['rodzaj']))
                    for($j = 1 ; $j <= count($przedmiot_inne) ; $j++)
                        if($przedmiot_inne[$j] == $prz)
                        {
                            $przedmiot['rodzaj'] = 'inne';
                            $przedmiot['numer'] = $j;
                            break;
                        }
                if(!isset($przedmiot['rodzaj']))
                    for($j = 1 ; $j <= count($przedmiot_kamienie) ; $j++)
                        if($przedmiot_kamienie[$j] == $prz)
                        {
                            $przedmiot['rodzaj'] = 'kamienie';
                            $przedmiot['numer'] = $j;
                            break;
                        }
            } else {
                $this->view->przedmioty[$i]['rodzaj'] = $this->przedmiot['rodzaj'];
            }
        }
    }

    /**
     * @return bool if thing exists
     */
    private function czyIstnieje()
    {
        for ($i = 1 ; $i <= count($this->przedmiotJagody) ; $i++) {
            if ($this->przedmiotJagody[$i] == $this->przedmiot['nazwa']) {
                $this->przedmiot['rodzaj'] = 'jagoda';
                $this->przedmiot['numer'] = $i;
                return true;
            }
        }
        for ($i = 1 ; $i <= count($this->przedmiotPokeball) ; $i++) {
            if ($this->przedmiotPokeball[$i] == $this->przedmiot['nazwa']) {
                $this->przedmiot['rodzaj'] = 'pokeball';
                $this->przedmiot['numer'] = $i;
                return true;
            }
        }
        for ($i = 1 ; $i <= count($this->przedmiotInne) ; $i++) {
            if ($this->przedmiotInne[$i] == $this->przedmiot['nazwa']) {
                $this->przedmiot['rodzaj'] = 'inne';
                $this->przedmiot['numer'] = $i;
                return true;
            }
        }
        for ($i = 1 ; $i <= count($this->przedmiotKamienie) ; $i++) {
            if ($this->przedmiotKamienie[$i] == $this->przedmiot['nazwa']) {
                $this->przedmiot['rodzaj'] = 'kamienie';
                $this->przedmiot['numer'] = $i;
                return true;
            }
        }
        return false;
    }

    public function kup(int $ID, int $ilosc, string $przedmiot)
    {
        if (!$ilosc) {
            $this->view->blad = 'Błędna ilość.';
            $this->szukaj($przedmiot);
            return;
        }

        $rezultat = $this->model->ofertaId($ID);
        $ile = $rezultat['rowCount'];
        if (!$ile) {
            $this->view->blad = 'Błędny ID transakcji lub oferta już wykupiona.';
            $this->szukaj($przedmiot);
            return;
        }

        $wiersz = $rezultat[0];
        if ($wiersz['id_gracza'] == Session::_get('id')) {
            $this->view->blad = 'Nie możesz kupić swojej oferty.';
            $this->szukaj($przedmiot);
            return;
        }
        $this->przedmiot['nazwa'] = $wiersz['co'];
        if (!$this->czyIstnieje()) {
            $this->view->blad = 'Błędna nazwa przedmiotu';
            $this->szukaj($przedmiot);
            return;
        }

        if ($ilosc >= $wiersz['ilosc']) {
            $ilosc = $wiersz['ilosc'];
            $usun = 1;
        } else {
            $usun = 0;
        }
        $wartosc = $wiersz['cena'] * $ilosc;
        if(Session::_get('kasa') < $wartosc) {
            $this->view->blad = 'Nie stać Cię na ten zakup.';
            $this->szukaj($przedmiot);
            return;
        }

        $nick_s = $this->model->login($wiersz['id_gracza']);
        $nick_s = $nick_s[0];
        $co = $ilosc.' '.$wiersz['co'].' po '.$wiersz['cena'].' Y za sztuke od '.$nick_s['login'].' ('.$wiersz['id_gracza'].')';
        $godzina = date('Y-m-d-H-i-s');
        $this->model->kup($co, $godzina, $wartosc, $wiersz['id_gracza']);
        $co = $this->przedmiot['nazwa'];
        if ($this->przedmiot['rodzaj'] == 'jagoda') {
            $this->model->jagody($wiersz['co'], $ilosc);
            $co = str_replace("_", " ", $this->przedmiotJagody[$this->przedmiot['numer']]);
        } elseif ($this->przedmiot['rodzaj'] == 'pokeball') {
            $this->model->pokeballe($wiersz['co'], $ilosc);
        } elseif ($przedmiotk['rodzaj'] == 'inne') {
            $this->model->inne($wiersz['co'], $ilosc);
        } elseif ($przedmiotk['rodzaj'] == 'kamienie') {
            $this->model->kamienie($wiersz['co'], $ilosc);
        }
        $this->view->komunikat = 'Kupiono '.$ilosc.'x ';
        if($this->przedmiot['rodzaj'] == 'kamienie') {
            $this->view->komunikat .= 'Kamień ';
            switch ($co) {
                case 'ksiezycowe':
                    $this->view->komunikat .= 'księżycowy';
                    break;
                case 'roslinne':
                    $this->view->komunikat .= 'roślinny';
                    break;
                case 'ogniste':
                    $this->view->komunikat .= 'ognisty';
                    break;
                case 'wodne':
                    $this->view->komunikat .= 'wodny';
                    break;
                default:
                    $this->view->komunikat .= $co;
            }
        } else {
            $this->view->komunikat .= $co;
        }
        $this->view->komunikat .= '.';

        $tytul = 'Twoja oferta została kupiona.';
        $raport = '<div class="row nomargin text-center"><div class="col-xs-12">Otrzymujesz '.$wartosc.' &yen; za sprzedaż '.$ilosc.' sztuk ';
        if($this->przedmiot['rodzaj'] == 'kamienie') {
            $raport .= 'Kamień ';
            switch ($co) {
                case 'ksiezycowe':
                    $raport .= 'księżycowy';
                    break;
                case 'roslinne':
                    $raport .= 'roślinny';
                    break;
                case 'ogniste':
                    $raport .= 'ognisty';
                    break;
                case 'wodne':
                    $raport .= 'wodny';
                    break;
                default:
                    $raport .= $co;
            }
        } else {
            $raport .= $co;
        }
        $raport .= '</div></div>';
        $this->model->raport($wiersz['id_gracza'], $raport, $godzina, $tytul);
        if($usun == 1) {
            $this->model->usunOferte($ID);
        } else {
            $this->model->zmienOferte($ilosc, $ID);
        }
        Session::_set('kasa', (Session::_get('kasa') - $wartosc));
        $this->szukaj($przedmiot);
    }

    public function pokemon($szukaj = '', int $ID = 0, int $minPoziom = 0, int $maxPoziom = 0, int $minCena = 0, int $maxCena = 0)
    {
        $this->view->mode = 0;
        if ($szukaj == 'szukaj') {
            $this->pokemonSzukaj($ID, $minPoziom, $maxPoziom, $minCena, $maxCena);
        } elseif($szukaj == 'kup') {
            $this->pokemonKup($ID);
        }
        $this->view->render('targ/pokemon');
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('', 2);
        }
    }

    private function pokemonKup($ID)
    {
        $rezultat = $this->model->pokemony($ID);
        $ile = $rezultat['rowCount'];
        if (!$ile) {
            $this->view->blad = 'Ta oferta nie jest już aktualna.';
            return;
        }
        $rezultat = $rezultat[0];
        if (Session::_get('kasa') < $rezultat['cena']) {
            $this->view->blad = 'Nie stać Cię.';
            return;
        }
        $rezultat2 = $this->model->pokemonImie($rezultat['ID_pokemona']);
        $imie = $rezultat2[0];
        $imie = $imie['imie'];
        $this->model->kupPokemon($rezultat['ID_pokemona'], $ID, $rezultat['cena'], $rezultat['id_wlasciciela']);
        $godzina = date('Y-m-d-H-i-s');
        $id_w = $this->model->login($rezultat['id_wlasciciela']);
        $id_w = $id_w[0];
        $wartosc_p = $this->model->pokemonWartosc($rezultat['ID_pokemona']);
        $wartosc_p = $wartosc_p[0];
        $raport = Session::_get('id').' kupił pokemona o ID: <a href="http://pokemania.cf/pokemon/'.$rezultat['ID_pokemona'].'" target="_blank">'.$rezultat['ID_pokemona'].'</a> [W: '.$wartosc_p['wartosc'].' &yen;] [C: '.$rezultat['cena'].' &yen;] od: '.$id_w['login'].' ('.$rezultat['id_wlasciciela'].')';

        $raport = 'Dostałeś '.$rezultat['cena'].' &yen; za sprzedaż '.$imie;
        $tytul = 'Pokemon został kupiony!';
        $this->model->wiadomosc($rezultat['id_wlasciciela'], $raport, $godzina, $tytul);
        Session::_set('kasa', (Session::_get('kasa') - $rezultat['cena']));
        $this->view->komunikat = 'Dokonano transakcji. Pokemon znajduje się w Twojej rezerwie.';
    }

    private function pokemonSzukaj($ID, $minPoziom, $maxPoziom, $minCena, $maxCena)
    {
        if($minPoziom > $maxPoziom && $maxPoziom != 0) $maxPoziom = $minPoziom;
        if($minCena > $maxCena && $maxCena != 0) $maxCena = $minCena;
        if($ID) $this->view->ID = $ID;
        if($minPoziom) $this->view->minPoziom = $minPoziom;
        if($maxPoziom) $this->view->maxPoziom = $maxPoziom;
        if($minCena) $this->view->minCena = $minCena;
        if($maxCena) $this->view->maxCena = $maxCena;
        $this->view->mode = 1;
        define ('ILOSC_NA_STRONIE', 30);
        if(isset($_GET['p']))  $p = --$_GET['p'];
        else  $p = 0;
        $a = User::$ustawienia->get('targ');

        $klery = "SELECT SQL_CALC_FOUND_ROWS *
              FROM targ_pokemon WHERE ";
        if($ID > 0) $klery .= " id_poka=$ID";
        else $klery .= ' id_poka <> 0 ';
        if($minPoziom > 0) $klery .=" AND poziom >= $minPoziom ";
        if($maxPoziom > 0) $klery .=" AND poziom <= $maxPoziom ";
        if($minCena > 0) $klery .= " AND cena >= $minCena ";
        if($maxCena > 0) $klery .= " AND cena <= $maxCena ";
        if($a == 0) $klery .= " AND id_wlasciciela != ".Session::_get('id');
        $klery2 = ' ORDER BY ID ASC LIMIT '.$p*ILOSC_NA_STRONIE.','.ILOSC_NA_STRONIE;
        $klery = $klery . $klery2;
        Debug::addInfo('kwerenda pobrania poków z tabeli targ_pokemon', $klery);
        $rezultat = $this->model->db->select($klery, []);
        $ile = $rezultat['rowCount'];
        if(!$ile) {
            $this->view->ilosc = 0;
        } else {
            $this->view->ilosc = $ile;
            $kwer  = "SELECT * FROM pokemony WHERE ID in (";
            $kwer2 = "order by case ID";
            $aa = 0;
            for ($i = 0 ; $i < $ile ; $i++) {
                $rezultat1[$i] = $rezultat[$i];
                $a = $rezultat1[$i]['ID_pokemona'];
                if($i == 0)$kwer = $kwer . "'$a'";
                else $kwer = $kwer . ", '$a'";
                $kwer2 = $kwer2 . " WHEN '$a' THEN ".$i;
                $aa++;
            }
            $kwer = $kwer . ")" . $kwer2 . " END";
            Debug::addInfo('kwerenda pobrania poków z tabeli pokemony', $kwer);
            $rezultat = $this->model->db->select($kwer, []);
            for($i = 0 ; $i < $ile ; $i++) {
                $this->view->pokemonOferta[$i] = $rezultat[$i];
                $this->view->pokemonOferta[$i]['gracza'] = $rezultat1[$i]['id_wlasciciela'] == Session::_get('id') ? 1 : 0;
                $this->view->pokemonOferta[$i] = array_merge($this->view->pokemonOferta[$i], $rezultat1[$i]);
            }
        }


    }

    public function wystaw($co = '')
    {
        if ($co == 'pokemon') {
            $this->wystawPokemona();
        } else {
            $this->wystawPrzedmiot();
        }
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('', 2);
        }

    }

    public function wystawianie($co = '')
    {
        if (!$co) {
            $this->index();
            return;
        }
        if ($co == 'pokemon') {
            $this->wystawianiePokemon();
        } else {
            $this->wystawianiePrzedmiot();
        }
    }

    private function wystawianiePrzedmiot()
    {
        if (!isset($_POST['nazwa'])) {
            $this->view->blad = 'Błędna nazwa przedmiotu';
            $this->wystawPrzedmiot();
            return;
        }
        $nazwa = $_POST['nazwa'];
        $this->przedmiot['nazwa'] = $nazwa;
        if (!$this->czyIstnieje()){
            $this->view->blad = 'Błędna nazwa przedmiotu';
            $this->wystawPrzedmiot();
            return;
        }
        if (!isset($_POST['cena']) || (isset($_POST['cena']) && (!is_numeric($_POST['cena']) || $_POST['cena'] <= 0)) ) {
            $this->view->blad = 'Błędna cena';
            $this->wystawPrzedmiot();
            return;
        }
        $cena = $_POST['cena'];
        if (!isset($_POST['ilosc']) || (isset($_POST['ilosc']) && (!is_numeric($_POST['ilosc']) || $_POST['ilosc'] <= 0)) ) {
            $this->view->blad = 'Błędna ilość';
            $this->wystawPrzedmiot();
            return;
        }
        $ilosc = $_POST['ilosc'];
        if($this->przedmiot['rodzaj'] == 'jagoda')
            $rezultat = $this->model->jagodyBaza();
        else if($this->przedmiot['rodzaj'] == 'pokeball')
            $rezultat = $this->model->pokeballeBaza();
        else if($this->przedmiot['rodzaj'] == 'inne')
            $rezultat = $this->model->inneBaza();
        else if($this->przedmiot['rodzaj'] == 'kamienie')
            $rezultat = $this->model->kamienieBaza();

        $wiersz = $rezultat[0];
        if($this->przedmiot['rodzaj'] == 'pokeball' || $this->przedmiot['rodzaj'] == 'inne') $nazwa = strtolower($nazwa);
        if ($wiersz[$nazwa] >= $ilosc) {/////jesli sie ma tyleprzedmiotów do sprzedania co podano w formularzu
            if($this->przedmiot['rodzaj'] == 'jagoda')
                $this->model->zabierzJagody($nazwa, $ilosc);
            elseif($this->przedmiot['rodzaj'] == 'pokeball')
                $this->model->zabierzPokeballe($nazwa, $ilosc);
            elseif($this->przedmiot['rodzaj'] == 'inne')
                $this->model->zabierzInne($nazwa, $ilosc);
            elseif($this->przedmiot['rodzaj'] == 'kamienie')
                $this->model->zabierzKamienie($nazwa, $ilosc);

            $this->model->wystaw($nazwa, $ilosc, $cena);
            $a = str_replace("_", " ", $nazwa);///potwierdzenie wystawienia oferty
            $this->view->komunikat =  'Poprawnie wystawiono '.$ilosc.' sztuk '.$a.' za cenę '.$cena.' &yen; za sztukę.';
        } else {
            $a = str_replace("_", " ", $nazwa);
            $this->view->blad = 'Masz za mało '.$a;
        }
        $this->wystawPrzedmiot();
    }

    private function wystawianiePokemon()
    {
        if (!isset($_POST['id'])) {
            $this->view->blad = 'Błędne ID';
            $this->wystawPokemona();
            return;
        }
        $id = $_POST['id'];
        if (!isset($_POST['cena']) || (isset($_POST['cena']) && (!is_numeric($_POST['cena']) || $_POST['cena'] <= 0)) ) {
            $this->view->blad = 'Błędna cena';
            $this->wystawPokemona();
            return;
        }
        $cena = $_POST['cena'];
        if (!isset($_POST['wiadomosc'])) {
          $opis = '';
        }  else {
            $opis = $_POST['wiadomosc'];
        }
        $rezultat = $this->model->pokemonDoWystawienia($id);
        $ile = $rezultat['rowCount'];
        if (!$ile) {
            $this->view->blad = 'Błędny ID Pokemona';
            $this->wystawPokemona();
            return;
        }
        $rezultat = $rezultat[0];
        require('./src/includes/pokemony/pokemon.php');
        $rezultat2 = $pokemon_plik[$rezultat['id_poka']];

        $this->model->wystawPokemon($id, $rezultat['id_poka'], $rezultat['poziom'], $cena, $rezultat['shiny'],
            $rezultat2['typ1'], $rezultat2['typ2'], $opis, $rezultat2['nazwa'], $rezultat['plec']);
        $this->view->komunikat = 'Wystawiono Pokemona na targ';
        $this->wystawPokemona();
    }

    private function wystawPokemona()
    {
        if(!isset($_GET['active'])) $this->view->active = 1;
        else $this->view->active = $_GET['active'];
        if(isset($_GET['h'])) $this->h = $_GET['h'];
        else $this->h = 0;
        $this->pokiDoWystawienia();
        $this->pokiWystawione();

        $this->view->render('targ/pokemonWystaw');
    }

    private function pokiDoWystawienia()
    {
        $rezultat = $this->model->pokemonyDoWystawienia();
        $ile = $rezultat['rowCount'];
        $this->view->iloscDoWystawienia = $ile;
        if ($ile) {
            for ($i = 0 ; $i < $ile ; $i++) {
                $this->view->pokemonDoWystawienia[$i] = $rezultat[$i];
                if ($this->h && $this->h == $rezultat[$i]['ID'])
                    $this->view->pokemonDoWystawienia[$i]['h'] = 1;
                else $this->view->pokemonDoWystawienia[$i]['h'] = 0;
            }
        }
    }

    private function pokiWystawione()
    {
        $rezultat = $this->model->pokiWystawione();
        $ile = $rezultat['rowCount'];
        $this->view->iloscPokiWystawione = $ile;
        if ($ile) {
            $kwer  = "SELECT * FROM pokemony WHERE ID in (";
            $kwer2 = "order by case ID";
            $aa = 0;
            for ($i = 0 ; $i < $ile ; $i++) {
                $rezultat2[$i] = $rezultat[$i];
                $a = $rezultat2[$i]['ID_pokemona'];
                if($i == 0)$kwer = $kwer . "'$a'";
                else $kwer = $kwer . ", '$a'";
                $kwer2 = $kwer2 . " WHEN '$a' THEN ".$i;
            }
            $kwer = $kwer . ")" . $kwer2 . " END";
            $rezultat3 = $this->model->db->select($kwer, []);
            for ($i = 0 ; $i < $ile ; $i++) {
                $wiersz = $rezultat2[$i];
                $wiersz2 = $rezultat3[$i];
                $this->view->pokemonWystawiony[$i] = array_merge($wiersz, $wiersz2);
                $this->view->pokemonWystawiony[$i]['idW'] = $rezultat2[$i]['ID'];
            }
        }
    }

    private function wystawPrzedmiot()
    {
        if(!isset($_GET['active'])) $this->view->active = 1;
        else $this->view->active = $_GET['active'];
        $this->przedmiotyDoWystawienia();
        $this->przedmiotyWystawione();

        $this->view->render('targ/przedmiotWystaw');
    }

    private function przedmiotyDoWystawienia()
    {
        $rezultat = $this->model->przedmiotyDoWystawienia();
        $wiersz = $rezultat[0];
        for($i = 1, $j = 0 ; $i <= count($this->przedmiotJagody) ; $i++) {
            if($wiersz[$this->przedmiotJagody[$i]] > 0) {
                $this->view->jagoda[$j]['nazwa'] = $this->przedmiotJagody[$i];
                $this->view->jagoda[$j]['nazwaW'] = str_replace("_", " ", $this->przedmiotJagody[$i]);
                $this->view->jagoda[$j]['ilosc'] = $wiersz[$this->przedmiotJagody[$i]];
                $j++;
            }
        }
        for($i = 1, $j = 0 ; $i <= count($this->przedmiotPokeball) ; $i++) {
            if($wiersz[strtolower($this->przedmiotPokeball[$i])] > 0) {
                $this->view->pokeball[$j]['nazwa'] = $this->przedmiotPokeball[$i];
                $this->view->pokeball[$j]['ilosc'] = $wiersz[strtolower($this->przedmiotPokeball[$i])];
                $this->view->pokeball[$j]['ball'] = substr($this->przedmiotPokeball[$i], 0, -1);
                $j++;
            }
        }
        for($i = 1, $j = 0 ; $i <= count($this->przedmiotInne) ; $i++) {
            if($wiersz[strtolower($this->przedmiotInne[$i])] > 0) {
                $this->view->inne[$j]['nazwa'] = $this->przedmiotInne[$i];
                $this->view->inne[$j]['img'] = strtolower($this->przedmiotInne[$i]);
                $this->view->inne[$j]['ilosc'] = $wiersz[strtolower($this->przedmiotInne[$i])];
                $j++;
            }
        }
        for($i = 1, $j = 0 ; $i <= count($this->przedmiotKamienie) ; $i++) {
            if($wiersz[$this->przedmiotKamienie[$i]] > 0) {
                $this->view->kamienie[$j]['nazwa'] = $this->przedmiotKamienie[$i];
                $this->view->kamienie[$j]['ilosc'] = $wiersz[$this->przedmiotKamienie[$i]];
                switch ($this->przedmiotKamienie[$i]) {
                    case 'ksiezycowe':
                        $this->view->kamienie[$j]['nazwaW'] = 'księżycowy';
                        break;
                    case 'roslinne':
                        $this->view->kamienie[$j]['nazwaW'] = 'roślinny';
                        break;
                    case 'ogniste':
                        $this->view->kamienie[$j]['nazwaW'] = 'ognisty';
                        break;
                    case 'wodne':
                        $this->view->kamienie[$j]['nazwaW'] = 'wodny';
                        break;
                    default:
                        $this->view->kamienie[$j]['nazwaW'] = $this->przedmiotKamienie[$i];
                        break;
                }

                $j++;
            }
        }
    }

    private function przedmiotyWystawione()
    {
        $rezultat1 = $this->model->przedmiotyWystawione();
        $ile = $rezultat1['rowCount'];
        $this->view->iloscWystawionych = $ile;
        if (!$ile) {
            return;
        }
        for ($j = 0 ; $j < $ile ; $j++) {
            if(isset($przedmiot['rodzaj'])) unset($przedmiot['rodzaj']);
            $wiersz1 = $rezultat1[0];
            for ($i = 1 ; $i <= count($this->przedmiotJagody) ; $i++) {
                if ($this->przedmiotJagody[$i] == $wiersz1['co']) {
                    $przedmiot['rodzaj'] = 'jagoda';
                    $przedmiot['numer'] = $i;
                    break;
                }
            }
            if (!isset($przedmiot['rodzaj'])) {
                for ($i = 1; $i <= count($this->przedmiotPokeball); $i++) {
                    if ($this->przedmiotPokeball[$i] == $wiersz1['co']) {
                        $przedmiot['rodzaj'] = 'pokeball';
                        $przedmiot['numer'] = $i;
                        break;
                    }
                }
                if (!isset($przedmiot['rodzaj'])) {
                    for ($i = 1; $i <= count($this->przedmiotInne); $i++) {
                        if ($this->przedmiotInne[$i] == $wiersz1['co']) {
                            $przedmiot['rodzaj'] = 'inne';
                            $przedmiot['numer'] = $i;
                            break;
                        }
                    }
                    if (!isset($przedmiot['rodzaj'])) {
                        for ($i = 1; $i <= count($this->przedmiotKamienie); $i++) {
                            if ($this->przedmiotKamienie[$i] == $wiersz1['co']) {
                                $przedmiot['rodzaj'] = 'kamienie';
                                $przedmiot['numer'] = $i;
                                break;
                            }
                        }
                    }
                }
            }
            $co = str_replace("_", " ", $wiersz1['co']);
            $this->view->przedmiot[$j] = $przedmiot;
            $this->view->przedmiot[$j] = array_merge($this->view->przedmiot[$j], $wiersz1);

            if ($przedmiot['rodzaj'] == 'kamienie') {
                $this->view->przedmiot[$j]['coW'] = 'Kamień ';
                switch ($co)  {
                    case 'ksiezycowe':
                        $this->view->przedmiot[$j]['coW'] .= 'księżycowy';
                        break;
                    case 'roslinne':
                        $this->view->przedmiot[$j]['coW'] .= 'roślinny';
                        break;
                    case 'ogniste':
                        $this->view->przedmiot[$j]['coW'] .= 'ognisty';
                        break;
                    case 'wodne':
                        $this->view->przedmiot[$j]['coW'] .= 'wodny';
                        break;
                    default:
                        $this->view->przedmiot[$j]['coW'] .= $co;
                }
            } else {
                $this->view->przedmiot[$j]['coW'] = $co;
            }
        }
    }

    public function wycofaj($co = '', int $ID = 0)
    {
        if($co == '') {
            $this->index();
            return;
        }
        if ($co == 'przedmiot') {
            $this->wycofajPrzedmiot($ID);
        } else {
            $this->wycofajPokemon($ID);
        }
    }

    private function wycofajPokemon($ID)
    {
        $rezultat = $this->model->pokemonGraczaNaTargu($ID);
        $ile = $rezultat['rowCount'];
        if ($ile) {
            $rezultat = $rezultat[0];
            $this->model->pokemonWycofaj($ID, $rezultat['ID_pokemona']);
            $this->view->komunikat =  'Wycofano Pokemona z targu';
        } else {
            $this->view->blad = 'Błędny ID oferty do wycofania lub oferta nieaktualna.';
        }
        $this->wystawPokemona();
    }

    private function wycofajPrzedmiot($ID)
    {
        $rez = $this->model->przedmiotTargGracza($ID);
        $ile = $rez['rowCount'];
        if ($ile) {
            $w = $rez[0];
            for($i = 1 ; $i <= count($this->przedmiotJagody) ; $i++) {
                if ($this->przedmiotJagody[$i] == $w['co']) {
                    $przedmiot['rodzaj'] = 'jagoda';
                    $przedmiot['numer'] = $i;
                    break;
                }
            }
            if(!isset($przedmiot['rodzaj'])) {
                for ($i = 1; $i <= count($this->przedmiotPokeball); $i++) {
                    if ($this->przedmiotPokeball[$i] == $w['co']) {
                        $przedmiot['rodzaj'] = 'pokeball';
                        $przedmiot['numer'] = $i;
                        break;
                    }
                }

                if (!isset($przedmiot['rodzaj'])) {
                    for ($i = 1; $i <= count($this->przedmiotInne); $i++) {
                        if ($this->przedmiotInne[$i] == $w['co']) {
                            $przedmiot['rodzaj'] = 'inne';
                            $przedmiot['numer'] = $i;
                            break;
                        }
                    }
                    if (!isset($przedmiot['rodzaj'])) {
                        for ($i = 1; $i <= count($this->przedmiotKamienie); $i++) {
                            if ($this->przedmiotKamienie[$i] == $w['co']) {
                                $przedmiot['rodzaj'] = 'kamienie';
                                $przedmiot['numer'] = $i;
                                break;
                            }
                        }
                    }
                }
            }
            $this->model->usunOferte($ID);
            if($przedmiot['rodzaj'] == 'jagoda') $this->model->zmienJagody($w['co'], $w['ilosc']);
            else if($przedmiot['rodzaj'] == 'pokeball') $this->model->zmienPokeballe($w['co'], $w['ilosc']);
            else if($przedmiot['rodzaj'] == 'inne') $this->model->zmienInne($w['co'], $w['ilosc']);
            else if($przedmiot['rodzaj'] == 'kamienie') $this->model->zmienKamienie($w['co'], $w['ilosc']);

            $this->view->komunikat =  'Oferta wycofana';
        }else {
            $this->view->blad = 'Błędny ID oferty do wycofania lub oferta nieaktualna.';
        }
        $this->wystawPrzedmiot();
    }
}
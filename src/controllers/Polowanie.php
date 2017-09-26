<?php

namespace src\controllers;

use src\includes\functions\FunctionsPolowanie;
use src\libs\Controller;
use src\libs\Session;
use src\libs\User;

class Polowanie extends Controller
{
    use FunctionsPolowanie;

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('Polowanie - ' . NAME, 1);
        }
        switch (Session::_get('region')) {
            case 1:
                $this->wyswietlKanto();
                break;
            case 2:
                $this->wyswietlJohto();
                break;
        }

        $this->view->render('polowanie/index');
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('', 2, 2);
        }
    }

    public function polowanie($dzicz = '', $wydarzenie = '')
    {
        if (empty($dzicz)) {
            $this->index();
            return false;
        }
        $this->dzicz = strtolower($dzicz);
        $this->view->dzicz = $this->dzicz;
        Session::_set('dzicz', $dzicz);
        $this->sprawdzAktywnosc();
        $this->sprawdzDzicze();
        $this->sprawdzPoki();
        $this->sprawdzWydarzenie($wydarzenie);
        $this->wybierzWydarzenie();
    }

    public function walka($id = 0)
    {
        if (!Session::_isset('walka')) {
            header('Location: ' . URL . 'polowanie/?ajax');
            exit;
        }
        Session::_unset('walka');
        if (!$id) {
            $this->view->error = 'Wystąpił błąd z Pokemonem';
            $this->generujError();
        }
        $i = $this->sprawdzPokGracza($id);
        if (!$i) {
            $this->view->error = 'Wystąpił błąd z Pokemonem';
            $this->generujError();
        }
        $rezultat = $this->model->pokemon($id);
        $ile = $rezultat['rowCount'];
        if ($ile) {
            Session::_set('walkat', '');
            Session::_set('walkat1', '');
            Session::_set('walkat2', '');
            $this->pokemon = $rezultat[0];
            $this->pokemon = array_merge($this->pokemon, $this->pokemon_plik($this->pokemon['id_poka']));
            $this->sprawdzPokaWalka();
            Session::_set('twojpok', ['typ1' => $this->pokemon['typ1'], 'typ2' => $this->pokemon['typ2']]);
            $this->pokemon['idd'] = $i;
            $this->pokemon['i2'] = $id;
            $this->generujPokemona();
            $this->walkaPokemonow($this->pokemon, $this->model->db);
            $this->view->walka = 1;
            $this->view->dzicz = Session::_get('dzicz');
            $this->pokemonWalkaZapis();
            $this->generujWalka();
        } else {
            $this->view->error = 'Wystąpił błąd z Pokemonem';
            $this->generujError();
        }
    }

    public function lapanie($pokeball = '')
    {
        if (!Session::_isset('lap')) {
            header('Location: ' . URL . 'polowanie/?ajax');
            exit;
        }
        Session::_unset('lap');
        $pokeball = strtolower($pokeball);
        if (!$this->sprawdzPokeball(ucfirst($pokeball))) {
            $this->view->error = 'Błędna nazwa pokeballa.';
            $this->generujError();
        }
        $this->balle = $this->model->pokeballe();
        $this->balle = $this->balle[0];
        if ($this->balle[$pokeball . 'e']) {
            $kwer = 'UPDATE pokeballe SET ' . $pokeball . 'e = (' . $pokeball . 'e - 1) WHERE id_gracza = ?';
            $this->model->db->update($kwer, [Session::_get('id')]);
        } else {
            $this->view->error = 'Nie posiadasz danego pokeballa.';
            $this->generujError();
        }
        $this->view->dzicz = Session::_get('dzicz');
        $this->view->lapanie = $this->lap($pokeball, Session::_get('pokemon')['pok_poziom'], Session::_get('pokemon')['trudnosc'], $this->model->db);
        $this->generujLapanie();
    }

    private function sprawdzPokeball($pokeball)
    {
        if (Session::_get('dzicz') != 'safari') {
            return in_array($pokeball, ['Pokeball', 'Nestball', 'Repeatball', 'Greatball', 'Ultraball', 'Duskball', 'Lureball', 'Cherishball', 'Masterball']);
        } else {
            return in_array($pokeball, ['Safariball']);
        }
    }

    private function pokemonWalkaZapis()
    {
        $this->view->przedstawienie = Session::_get('walkat2');
        $plik = fopen('./pliki/pokemon/' . Session::_get('id') . '.txt', 'w');
        //$walkat = str_replace('<div class="walka_alert alert alert-info text-center"><span>', '{alert-info1}', Session::_get('walkat'));
        //$walkat = str_replace('<div class="walka_alert alert alert-info"><span>', '{alert-info}', $walkat);
        //$walkat = str_replace('<div class="alert alert-runda text-center margin-top"><span>', '{runda}', $walkat);
        fputs($plik, Session::_get('walkat')); //zapis do pliku
        //fputs($plik, $walkat); //zapis do pliku

        fclose($plik);
        $this->view->wynik = Session::_get('walkat1');
        $godzina = date('Y-m-d-H-i-s');
        $rodzaj = Session::_get('twojpoknazwa') . ' vs. ' . Session::_get('pokemon')['pok_nazwa'];
        Session::_unset('twojpoknazwa');
        $walka = Session::_get('walkat2') . Session::_get('walkat');
        $this->model->zapiszWalka($godzina, $walka, $rodzaj);
        Session::_unset('walkat');
        Session::_unset('walkat1');
        Session::_unset('walkat2');
        Session::_unset('walka');
    }

    private function generujPokemona()
    {
        $this->pokemon['id_poka'] = User::_get('pok', $this->pokemon['idd'])->get('id_p');
        $spadek = 0;
        if ($this->pokemon['glod'] > 50) {
            $spadek = round((($this->pokemon['glod'] - 50) * 2), 2);
            $_SESSION['walkat'] .= '<div class="alert alert-warning margin-top text-center"><span>' . $this->pokemon['imie'] . ' jest głodn';
            if ($this->pokemon['plec'] == 1)
                $_SESSION['walkat'] .= 'a. Jej';
            else
                $_SESSION['walkat'] .= 'y. Jego';
            $_SESSION['walkat'] .= ' statystyki spadają o ' . $spadek . '%.</span></div>';
        }
        $spadek = 1 - $spadek / 100;
        //staty
        $this->pokemon['Atak'] = round($spadek * $this->pokemon['Atak']);
        $this->pokemon['Sp_Atak'] = round($spadek * $this->pokemon['Sp_Atak']);
        $this->pokemon['Obrona'] = round($spadek * $this->pokemon['Obrona']);
        $this->pokemon['Sp_Obrona'] = round($spadek * $this->pokemon['Sp_Obrona']);
        $this->pokemon['Szybkosc'] = round($spadek * $this->pokemon['Szybkosc']);
        //jagody
        $this->pokemon['Jag_Atak'] = round($spadek * $this->pokemon['Jag_Atak']);
        $this->pokemon['Jag_Sp_Atak'] = round($spadek * $this->pokemon['Jag_Sp_Atak']);
        $this->pokemon['Jag_Obrona'] = round($spadek * $this->pokemon['Jag_Obrona']);
        $this->pokemon['Jag_Sp_Obrona'] = round($spadek * $this->pokemon['Jag_Sp_Obrona']);
        $this->pokemon['Jag_Szybkosc'] = round($spadek * $this->pokemon['Jag_Szybkosc']);
        //treningi
        $this->pokemon['tr_1'] = round($spadek * $this->pokemon['tr_1']);
        $this->pokemon['tr_2'] = round($spadek * $this->pokemon['tr_2']);
        $this->pokemon['tr_3'] = round($spadek * $this->pokemon['tr_3']);
        $this->pokemon['tr_4'] = round($spadek * $this->pokemon['tr_4']);
        $this->pokemon['tr_5'] = round($spadek * $this->pokemon['tr_5']);
        for ($ill = 1; $ill <= 4; $ill++)
            $this->pokemon['atak'][$ill]['id'] = $this->pokemon['atak' . $ill];
    }

    private function sprawdzPokaWalka()
    {
        if (!$this->pokemon['akt_HP']) {
            $this->view->error = 'Nie możesz walczyć Pokemonem, który ma 0 HP.';
            $this->generujError();
        }
        if ($this->pokemon['glod'] > 90) {
            $this->view->error = 'Nie możesz walczyć bardzo głodnym Pokemonem.';
            $this->generujError();
        }
    }

    private function sprawdzPokGracza($id)
    {
        $i = 0;
        for ($j = 1; $j < 7; $j++) {
            if (User::_isset('pok', $j) && User::_get('pok', $j)->get('id') == $id) {
                $i = $j;
                break;
            }
        }
        return $i;
    }

    private function sprawdzAktywnosc()
    {
        if (Session::_isset('aktywnosc') && Session::_get('aktywnosc') != '') {
            header('location: ' . URL . Session::_get('aktywnosc') . '/blad/1');
        }
    }

    private function sprawdzPoki()
    {
        $j = 0;
        for ($i = 1; $i < 7; $i++) {
            if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') > 0)
                $j++;
        }
        for ($i = 1; $i <= $j; $i++) {
            if (User::_get('pok', $i)->get('akt_zycie') > 0 && User::_get('pok', $i)->get('glod') <= 90)
                break;
            if ($i == $j && (User::_get('pok', $i)->get('akt_zycie') <= 0 || User::_get('pok', $i)->get('glod') > 90)) {
                $this->view->error = 'Nie możesz podróżować jeśli wszystkie Twoje pokemony są ranne lub głodne.';
                $this->generujError();
            }
        }
    }

    private function sprawdzDzicze()
    {
        if (Session::_isset('wydarzenie_dzicz')) {
            return true;
        }
        $kanto = ['polana', 'wyspa', 'grota', 'dom_strachow', 'gory', 'wodospad', 'safari'];
        $johto = ['laka', 'lodowiec', 'mokradla', 'wulkan', 'JOHTO5', 'jezioro', 'mroczny_las'];
        if (Session::_get('region') == 1) {
            if (!in_array($this->dzicz, $kanto)) {
                $this->view->error = 'Błędna nazwa dziczy';
                $this->generujError();
            }
        } else {
            if (!in_array($this->dzicz, $johto)) {
                $this->view->error = 'Błędna nazwa dziczy';
                $this->generujError();
            }
        }
    }

    private function sprawdzWydarzenie($wydarzenie)
    {
        if (Session::_isset('wydarzenie_dzicz')) {
            switch (Session::_get('wydarzenie_dzicz')) {
                case -16:
                    if (!in_array($wydarzenie, [1, 2, 3]))
                        Session::_unset('wydarzenie_dzicz');
                    break;
                case -18:
                    if ($wydarzenie == 2 || $wydarzenie == 0) {
                        $this->view->wydarzenieSp = '';
                        $wyd = explode('|', Session::_get('wydarzenie_dzicz_set'));
                        $suma = 0;
                        for ($i = 1; $i < count($wyd); $i++)
                            $suma += $wyd[$i];
                        if ($suma) {
                            $this->view->wydarzenieSp .= '<div class="well well-primary jeden_ttlo text-medium text-center">Wykopane przedmioty, które zabierasz ze sobą:<br/>';
                            $kamien = 'UPDATE kamienie SET';
                            $kamien_ilosc = 0;
                            $przedmiot = 'UPDATE przedmioty SET';
                            $przedmiot_ilosc = 0;
                            $ball = 'UPDATE pokeballe SET';
                            $ball_ilosc = 0;
                            $jagody = 'UPDATE jagody SET';
                            $jagody_ilosc = 0;
                            $where = 'WHERE id_gracza = ' . Session::_get('id');
                            if ($wyd[1] > 0) {
                                $this->view->wydarzenieSp .= 'Bryłki srebra o wartości ' . $wyd[1] . ' &yen;<br />';
                                Session::_set('kasa', (Session::_get('kasa') + $wyd[1]));
                                $this->model->wydarzeniePieniadze($wyd[1]);
                            }
                            if ($wyd[2] > 0) {
                                $this->view->wydarzenieSp .= $wyd[2] . 'x ognisty kamień<br />';
                                $kamien .= ' ogniste = (ogniste + ' . $wyd[2] . ') ';
                                $kamien_ilosc++;
                            }
                            if ($wyd[3] > 0) {
                                $this->view->wydarzenieSp .= $wyd[3] . 'x wodny kamień<br />';
                                if ($kamien_ilosc) $kamien .= ',';
                                $kamien .= ' wodne = (wodne + ' . $wyd[3] . ') ';
                                $kamien_ilosc++;
                            }
                            if ($wyd[4] > 0) {
                                $this->view->wydarzenieSp .= $wyd[4] . 'x kamień gromu<br />';
                                if ($kamien_ilosc) $kamien .= ',';
                                $kamien .= ' gromu = (gromu + ' . $wyd[4] . ') ';
                                $kamien_ilosc++;
                            }
                            if ($wyd[5] > 0) {
                                $this->view->wydarzenieSp .= $wyd[5] . 'x księzycowy kamień<br />';
                                if ($kamien_ilosc) $kamien .= ',';
                                $kamien .= ' ksiezycowe = (ksiezycowe + ' . $wyd[5] . ') ';
                                $kamien_ilosc++;
                            }
                            if ($wyd[6] > 0) {
                                $this->view->wydarzenieSp .= $wyd[6] . 'x słoneczny kamień<br />';
                                if ($kamien_ilosc) $kamien .= ',';
                                $kamien .= ' sloneczne = (sloneczne + ' . $wyd[6] . ') ';
                                $kamien_ilosc++;
                            }
                            if ($wyd[7] > 0) {
                                $this->view->wydarzenieSp .= $wyd[7] . 'x Rare Candy<br />';
                                $przedmiot .= ' candy = (candy + ' . $wyd[7] . ') ';
                                $przedmiot_ilosc++;
                            }
                            if ($wyd[8] > 0) {
                                $this->view->wydarzenieSp .= $wyd[8] . 'x Masterball<br />';
                                $ball .= ' masterballe = (masterballe + ' . $wyd[8] . ') ';
                                $ball_ilosc++;
                            }
                            if ($wyd[9] > 0) {
                                $this->view->wydarzenieSp .= $wyd[9] . 'x Chesto Berry<br />';
                                $jagody .= ' Chesto_Berry = (Chesto_Berry + ' . $wyd[9] . ') ';
                                $jagody_ilosc++;
                            }
                            if ($wyd[10] > 0) {
                                $this->view->wydarzenieSp .= $wyd[10] . 'x Aspear Berry<br />';
                                if ($jagody_ilosc) $jagody .= ',';
                                $jagody .= ' Aspear_Berry = (Aspear_Berry + ' . $wyd[10] . ') ';
                                $jagody_ilosc++;
                            }
                            if ($wyd[11] > 0) {
                                $this->view->wydarzenieSp .= $wyd[11] . 'x Lapapa Berry<br />';
                                if ($jagody_ilosc) $jagody .= ',';
                                $jagody .= ' Lapapa_Berry = (Lapapa_Berry + ' . $wyd[11] . ') ';
                                $jagody_ilosc++;
                            }
                            if ($wyd[12] > 0) {
                                $this->view->wydarzenieSp .= $wyd[12] . 'x Aguav Berry<br />';
                                if ($jagody_ilosc) $jagody .= ',';
                                $jagody .= ' Aguav_Berry = (Aguav_Berry + ' . $wyd[12] . ') ';
                                $jagody_ilosc++;
                            }
                            if ($wyd[13] > 0) {
                                $this->view->wydarzenieSp .= $wyd[13] . 'x Lemoniada<br />';
                                if ($przedmiot_ilosc) $przedmiot .= ',';
                                $przedmiot .= ' lemoniada = (lemoniada + ' . $wyd[13] . ') ';
                                $przedmiot_ilosc++;
                            }
                            if ($wyd[14] > 0) {
                                $this->view->wydarzenieSp .= $wyd[14] . 'x Repeatball<br />';
                                if ($ball_ilosc) $ball .= ',';
                                $ball .= ' repeatballe = (repeatballe + ' . $wyd[14] . ') ';
                                $ball_ilosc++;
                            }
                            if ($wyd[15] > 0) {
                                $this->view->wydarzenieSp .= $wyd[15] . 'x Skamielina<br />';
                                if ($przedmiot_ilosc) $przedmiot .= ',';
                                $przedmiot .= ' czesci = (czesci + ' . $wyd[15] . ') ';
                                $przedmiot_ilosc++;
                            }
                            $this->view->wydarzenieSp .= '</div>';
                            if ($kamien_ilosc)
                                $this->model->db->update($kamien . $where, []);
                            if ($ball_ilosc)
                                $this->model->db->update($ball . $where, []);
                            if ($przedmiot_ilosc)
                                $this->model->db->update($przedmiot . $where, []);
                            if ($jagody_ilosc)
                                $this->model->db->update($jagody . $where, []);
                        }
                        Session::_unset('wydarzenie_dzicz');
                        Session::_unset('wydarzenie_dzicz_set');
                    }
                    break;
                default:
                    Session::_unset('wydarzenie_dzicz');
                    break;
            }
        }
    }

    private function sprawdzPa()
    {
        $pa = $this->pa;
        if ($pa < 10)
            $pa = 10;
        if (Session::_get('pa') < $pa) {
            $this->view->error = 'Niestety masz za mało PA.';
            $this->generujError();
        }
        if ($this->dzicz == 'grota') {
            $baterie = $this->model->baterie();
            $baterie = $baterie[0];
            if (!$baterie['baterie']) {
                $this->view->error = 'Brak baterii.';
                $this->generujError();
            }
            $this->model->bateriaZaWyprawe();
        }
        Session::_set('pa', (Session::_get('pa') - $pa));
        $this->model->paZaWyprawe($pa);
    }

    private function wybierzWydarzenie()
    {
        if (Session::_isset('wydarzenie_dzicz')) {
            $coo = Session::_get('wydarzenie_dzicz');
        } else {
            $coo = $this->{$this->dzicz . 'Co'}();
            $this->sprawdzPa();
        }

        switch ($coo) {
            case 0:
                $this->pustaWyprawa();
                break;
            case -1:
                $this->jagodyPolana();
                break;
            case -2:
                $this->medrzec();
                break;
            case -3:
                $this->zgubienieWyspa();
                break;
            case -4:
                $this->oazaWyspa();
                break;
            case -5:
                $this->skarbGrota();
                break;
            case -6:
                $this->zgubienieGrota();
                break;
            case -7:
                $this->pokiDomStrachow();
                break;
            case -8:
                $this->przedmiotDomStrachow();
                break;
            case -9:
                $this->jagodyGory();
                break;
            case -11:
                $this->negatywneWodospad();
                break;
            case -18:
                $this->kopanieSafari();
                break;
            case -19:
                $this->wodospadBlysk();
                break;
            case -20:
                $this->kradziezSafari();
                break;
            case -999:
                $this->trener();
                break;
            case 1024:
                $this->pokemonDzicz();
                break;
            default:
                $this->view->error = 'Nieznany błąd';
                $this->generujError();
        }
        if ($coo == 1024) {
            $this->generujPokemon();
        } elseif ($coo == -999) {
            $this->generujTrener();
        } else {
            $this->generujWydarzenie();
        }
        if (!Session::_isset('wydarzenie_dzicz')) {
            $this->dodajOsiagniecie();
        }
    }

    private function polanaCo()
    {
        $this->pa = 10;
        $l = mt_rand(1, 1000);
        if ($l <= 200)
            return 0; //pusta wyprawa
        elseif ($l <= 300)
            return -1; //jagody
        elseif ($l <= 400)
            return -2; //mędrzec
        elseif ($l <= 550)
            return -999; //trener
        elseif ($l <= 1000)
            return 1024; //pokemon
    }

    private function wyspaCo()
    {
        $this->pa = 10;
        $l = mt_rand(1, 1000);
        if ($l <= 200)
            return 0; //pusta wyprawa
        elseif ($l <= 300) {
            $this->pa = 20;
            return -3; //zgubienie się
        } elseif ($l <= 400)
            return -4; //oaza
        elseif ($l <= 550)
            return -999; //trener
        elseif ($l <= 1000)
            return 1024; //pokemon
    }

    private function grotaCo()
    {
        $this->pa = 10;
        $l = mt_rand(1, 1000);
        if ($l <= 200)
            return 0; //pusta wyprawa
        elseif ($l <= 300)
            return -5; //skarb
        elseif ($l <= 400) {
            $this->pa = 25;
            return -6; //zgubienie się
        } elseif ($l <= 550)
            return -999; //trener
        elseif ($l <= 1000)
            return 1024; //pokemon            
    }

    private function dom_strachowCo()
    {
        $this->pa = 15;
        $l = mt_rand(1, 1000);
        if ($l <= 200)
            return 0; //pusta wyprawa
        elseif ($l <= 300)
            return -7; //pokemony
        elseif ($l <= 400)
            return -8; //przedmiot
        elseif ($l <= 550)
            return -999; //trener
        elseif ($l <= 1000)
            return 1024; //pokemon       
    }

    private function goryCo()
    {
        $this->pa = 20;
        $l = mt_rand(1, 1000);
        if ($l <= 200)
            return 0; //pusta wyprawa
        elseif ($l <= 300)
            return -9; //jagody
        elseif ($l <= 500)
            return -999; //trener
        elseif ($l <= 1000)
            return 1024; //pokemon
    }

    private function wodospadCo()
    {
        $this->pa = 10;
        $l = mt_rand(1, 1000);
        if ($l <= 200)
            return 0; //pusta wyprawa
        elseif ($l <= 300)
            return -19; //błysk na górze
        elseif ($l <= 400)
            return -11; //negatywne wydarzenie
        elseif ($l <= 550)
            return -999; //trener
        elseif ($l <= 1000)
            return 1024; //pokemon
    }

    private function safariCo()
    {
        $rezultat = $this->model->kupony();
        $rezultat = $rezultat[0];
        if ($rezultat['kupony'] > 0) {////sprawdzenie kuponów
            $this->pa = 20;
            $this->model->kuponZaWyprawe();
            $l = mt_rand(1, 1000);
            if ($l <= 200)
                return 0; //pusta wyprawa
            elseif ($l <= 300)
                return -1; //jagody
            elseif ($l <= 400)
                return -18; //kopanie
            elseif ($l <= 500)
                return -20; //pok zabierający jagodę z plecaka
            elseif ($l <= 650)
                return -999; //trener
            elseif ($l <= 1000)
                return 1024; //pokemon
        } else {
            $this->view->error = 'Nie posiadasz kuponów na safari';
            $this->generujError();
        }
    }

    private function dodajOsiagniecie()
    {
        $this->model->dodajOsiagniecie($this->dzicz);
    }

    private function pustaWyprawa()
    {
        $this->view->wydarzenie = '<div class="alert alert-danger text-medium text-center"><span>Niestety nie spotkało Cię nic ciekawego.</span></div>';
    }

    private function jagodyPolana()
    {
        $this->view->wydarzenie = '<div class="alert alert-success text-medium text-center"><span>Na swojej drodze znalazłeś drzewko z ';
        $r = mt_rand(0, 20);
        if ($r <= 1) {//tu będą te lepsze jagody, ilość od 1 do 3
            $r = mt_rand(1, 8);
            $il = mt_rand(1, 3);
            if ($r == 1)
                $jagody = 'Leppa Berry';
            elseif ($r == 2)
                $jagody = 'Oran Berry';
            elseif ($r == 3)
                $jagody = 'Persim Berry';
            elseif ($r == 4)
                $jagody = 'Lum Berry';
            elseif ($r == 5)
                $jagody = 'Sitrus Berry';
            elseif ($r == 6)
                $jagody = 'Figy Berry';
            elseif ($r == 7)
                $jagody = 'Mago Berry';
            elseif ($r == 8)
                $jagody = 'Razz Berry';
        } elseif ($r <= 8) {//tu będą te średnie, ilość od 4 do 8
            $r = mt_rand(1, 5);
            $il = mt_rand(4, 8);
            if ($r == 1)
                $jagody = 'Aspear Berry';
            elseif ($r == 2)
                $jagody = 'Chesto Berry';
            elseif ($r == 3)
                $jagody = 'Wiki Berry';
            elseif ($r == 4)
                $jagody = 'Aguav Berry';
            elseif ($r == 5)
                $jagody = 'Lapapa Berry';
        } else { //tu będą te gorsze, ilość od 9 do 20
            $r = mt_rand(1, 3);
            $il = mt_rand(9, 20);
            if ($r == 1)
                $jagody = 'Cheri Berry';
            elseif ($r == 2)
                $jagody = 'Pecha Berry';
            elseif ($r == 3)
                $jagody = 'Rawst Berry';
        }
        $this->view->wydarzenie .= $jagody . '. Zbierasz z niego <strong>' . $il . '</strong> sztuk.</span></div>';
        $jagody = str_replace(' ', '_', $jagody);
        $this->model->jagodyWyprawa($jagody, $il);
    }

    private function medrzec()
    {
        $this->view->wydarzenie = '<div class="alert alert-success text-medium text-center"><span>Na swojej drodze spotykasz mędrca, który przekazuje Tobie i Twoim Pokemonom część swojej wiedzy.<br />Otrzymujesz 5 pkt doświadczenia, a każdy Pokemon z Twojej drużyny dostaje 15 pkt doświadczenia.</span></div>';
        $this->model->medrzec();
        Session::_set('tr_exp', (Session::_get('tr_exp') + 5));
        for ($j = 1; $j < 7; $j++)
            if (User::_isset('pok', $j) && User::_get('pok', $j)->get('id') > 0)
                User::_get('pok', $j)->edit('dos', (User::_get('pok', $j)->get('dos') + 15));
    }

    private function zgubienieWyspa()
    {
        $this->view->wydarzenie = '<div class="alert alert-danger fade in text-medium text-center"><span>Zgubiłeś się na wyspie! Tracisz 10PA!</span></div>';
    }

    private function oazaWyspa()
    {
        $this->view->wydarzenie = '<div class="alert alert-success fade in text-medium text-center"><span>Znalazłeś oazę. 
                Odpoczywasz razem z Pokemonami co skutkuje zwiększeniem ich zaufania.</span></div>';
        $this->model->przywiazanieZaWyprawe();
    }

    private function skarbGrota()
    {
        $cena = floor((mt_rand(2000, 15000) / 10000) * Session::_get('poziom') * 1000);
        Session::_set('kasa', (Session::_get('kasa') + $cena));
        $this->view->wydarzenie = '<div class="alert alert-success fade in text-medium text-center"><span>Znalazłeś skarb! 
                Dostajesz za niego ' . $cena . ' &yen;</span></div>';
        $this->model->wydarzeniePieniadze($cena);
    }

    private function zgubienieGrota()
    {
        $this->view->wydarzenie = '<div class="alert alert-danger fade in text-medium text-center"><span>Zgubiłeś się w grocie! Tracisz 15PA na wydostanie się.</span></div>';
    }

    private function pokiDomStrachow()
    {
        $this->view->wydarzenie = '<div class="alert alert-success fade in text-medium text-center"><span>Na drodze spotykasz przyjazną grupę Pokemonów duchów, 
                które chętnie bawią się z Twoimi Pokemonami.<br />Zaufanie Twoich Pokemonów w drużynie zwiększa się.</span></div>';
        $this->model->przywiazanieZaWyprawe();
    }

    private function przedmiotDomStrachow()
    {
        //znalezienie przedmiotu
        while (1) {
            $r = mt_rand(0, 300);
            if ($r < 1) { //tu będą te lepsze przedmioty
                $r2 = mt_rand(1, 25);
                if ($r2 == 1) {
                    $r3 = mt_rand();
                    if ($r3 & 1) {//Masterball
                        $this->view->wydarzenie = '<div class="alert alert-success fade in text-medium text-center"><span>Znalazłeś Masterballa!</span></div>';
                        $this->model->pokeballZaWyprawe('masterball', 1);
                    } else {//kamień do mega ewo
                        $r3 = mt_rand(1, 9);
                        if ($r3 == 1) {//venusaurite
                            $this->view->wydarzenie = '<div class="alert alert-success fade in text-medium text-center"><span>Znalazłeś Venusaurite!</span></div>';
                            $prz = 'venusaurite';
                        } elseif ($r3 == 2) {//charizardite x
                            $this->view->wydarzenie = '<div class="alert alert-success fade in text-medium text-center"><span>Znalazłeś Charizardite X!</span></div>';
                            $prz = 'charizardite_x';
                        } elseif ($r3 == 3) {//charizardite_y
                            $this->view->wydarzenie = '<div class="alert alert-success fade in text-medium text-center"><span>Znalazłeś Charizardite Y!</span></div>';
                            $prz = 'charizardite_y';
                        } elseif ($r3 == 4) {//blastoisinite
                            $this->view->wydarzenie = '<div class="alert alert-success fade in text-medium text-center"><span>Znalazłeś Blastoisinite!</span></div>';
                            $prz = 'blastoisinite';
                        } elseif ($r3 == 5) {//alakazite
                            $this->view->wydarzenie = '<div class="alert alert-success fade in text-medium text-center"><span>Znalazłeś Alakazite!</span></div>';
                            $prz = 'alakazite';
                        } elseif ($r3 == 6) {//gengarite
                            $this->view->wydarzenie = '<div class="alert alert-success fade in text-medium text-center"<span>Znalazłeś Gengarite!</span></div>';
                            $prz = 'gengarite';
                        } elseif ($r3 == 7) {//kangaskhanite
                            $this->view->wydarzenie = '<div class="alert alert-success fade in text-medium text-center"><span>Znalazłeś Kangaskhanite!</span> </div>';
                            $prz = 'kangaskhanite';
                        } elseif ($r3 == 8) {//pinsirite
                            $this->view->wydarzenie = '<div class="alert alert-success fade in text-medium text-center"><span>Znalazłeś Pinsirite!</span></div>';
                            $prz = 'pinsirite';
                        } elseif ($r3 == 9) {//gyaradosite
                            $this->view->wydarzenie = '<div class="alert alert-success fade in text-medium text-center"><span>Znalazłeś Gyaradosite!</span></div>';
                            $prz = 'gyaradosite';
                        }
                        $this->model->kamienWyprawa($prz);
                    }
                } else
                    continue;
            } elseif ($r < 3) {//kamień
                $r2 = mt_rand(1, 5);
                if ($r2 == 1) {//ognisty
                    $this->view->wydarzenie = '<div class="alert alert-success fade in text-medium text-center"><span>Znalazłeś kamień ognisty!</span></div>';
                    $prz = 'ogniste';
                } elseif ($r2 == 2) {//wodny
                    $this->view->wydarzenie = '<div class="alert alert-success fade in text-medium text-center"><span>Znalazłeś kamień wodny!</span></div>';
                    $prz = 'wodne';
                } elseif ($r2 == 3) {//roslinny
                    $this->view->wydarzenie = '<div class="alert alert-success fade in text-medium text-center"><span>Znalazłeś kamień roślinny!</span></div>';
                    $prz = 'roslinne';
                } elseif ($r2 == 4) {//gromu
                    $this->view->wydarzenie = '<div class="alert alert-success fade in text-medium text-center"><span>Znalazłeś kamień gromu!</span></div>';
                    $prz = 'gromu';
                } elseif ($r2 == 5) {//ksiezycowy
                    $this->view->wydarzenie = '<div class="alert alert-success fade in text-medium text-center"><span>Znalazłeś kamień ksieżycowy!</span> </div>';
                    $prz = 'ksiezycowe';
                }
                $this->model->kamienWyprawa($prz);
            } elseif ($r < 35) {//karta
                $this->view->wydarzenie = '<div class="alert alert-success fade in text-medium text-center"><span>Znalazłeś kartę!<br />To będzie jeszcze dopracowane.</span></div>';
            } elseif ($r < 45) {//soda
                $this->view->wydarzenie = '<div class="alert alert-success fade in text-medium text-center"><span>Znalazłeś sodę!</span></div>';
                $this->model->soda();
            } elseif ($r < 150) {//pokeballe
                $r2 = mt_rand(1, 91);
                if ($r2 < 20) {//pokeball
                    $this->view->wydarzenie = '<div class="alert alert-success fade in text-medium text-center"><span>Znalazłeś 10 pokeballi!</span></div>';
                    $this->model->pokeballZaWyprawe('pokeballe', 10);
                } elseif ($r2 < 35) {//nestball
                    $this->view->wydarzenie = '<div class="alert alert-success fade in text-medium text-center"><span>Znalazłeś 10 nestballi!</span></div>';
                    $this->model->pokeballZaWyprawe('nestballe', 10);
                } elseif ($r2 < 50) {//greatball
                    $this->view->wydarzenie = '<div class="alert alert-success fade in text-medium text-center"><span>Znalazłeś 10 greatballi!</span></div>';
                    $this->model->pokeballZaWyprawe('greatballe', 10);
                } elseif ($r2 < 65) {//duskball
                    $this->view->wydarzenie = '<div class="alert alert-success fade in text-medium text-center"><span>Znalazłeś 5 duskballi!</span></div>';
                    $this->model->pokeballZaWyprawe('duskballe', 5);
                } elseif ($r2 < 80) {//lureball
                    $this->view->wydarzenie = '<div class="alert alert-success fade in text-medium text-center"><span>Znalazłeś 5 lureballi!</span></div>';
                    $this->model->pokeballZaWyprawe('lureballe', 5);
                } elseif ($r2 < 85) {//ultraball
                    $this->view->wydarzenie = '<div class="alert alert-success fade in text-medium text-center"><span>Znalazłeś ultraballa!</span></div>';
                    $this->model->pokeballZaWyprawe('ultraballe', 1);
                } elseif ($r2 < 90) {//repeatball
                    $this->view->wydarzenie = '<div class="alert alert-success fade in text-medium text-center"><span>Znalazłeś 5 repeatballi!</span></div>';
                    $this->model->pokeballZaWyprawe('repeatballe', 5);
                } else {//cherishball
                    $this->view->wydarzenie = '<div class="alert alert-success fade in text-medium text-center"><span>Znalazłeś cherishballa!</span></div>';
                    $this->model->pokeballZaWyprawe('cherishballe', 1);
                }
            } else { //tu będą te gorsze
                $this->view->wydarzenie = '<div class="alert alert-success fade in text-medium text-center margin-top"><span>Znalazłeś jakiś przedmiot!<br />To też będzie dopracowane</span></div>';
            }
            break;
        }
    }

    private function jagodyGory()
    {
        //jagody
        $this->view->wydarzenie = '<div class="alert alert-success fade in text-medium text-center"><span>Na swojej drodze znalazłeś drzewko z ';
        $r = mt_rand(0, 20);
        if ($r <= 8) { //tu będą te lepsze jagody, ilość od 2 do 6
            $r = mt_rand(1, 8);
            $il = mt_rand(2, 6);
            if ($r == 1) //leppa
                $jagody = 'Leppa Berry';
            elseif ($r == 2)//oran
                $jagody = 'Oran Berry';
            elseif ($r == 3)//Persim
                $jagody = 'Persim Berry';
            elseif ($r == 4)//Lum
                $jagody = 'Lum Berry';
            elseif ($r == 5)//Sitrus
                $jagody = 'Sitrus Berry';
            elseif ($r == 6)//Figy
                $jagody = 'Figy Berry';
            elseif ($r == 7)//Mago
                $jagody = 'Mago Berry';
            elseif ($r == 8)//Razz
                $jagody = 'Razz Berry';
        } elseif ($r <= 15) {//tu będą te średnie, ilość od 5 do 10
            $r = mt_rand(1, 5);
            $il = mt_rand(5, 10);
            if ($r == 1) //aspear
                $jagody = 'Aspear Berry';
            elseif ($r == 2)//chesto
                $jagody = 'Chesto Berry';
            elseif ($r == 3)//wiki
                $jagody = 'Wiki Berry';
            elseif ($r == 4)//aguav
                $jagody = 'Aguav Berry';
            elseif ($r == 5)//lapapa
                $jagody = 'Lapapa Berry';
        } else { //tu będą te gorsze, ilość od 7 do 14
            $r = mt_rand(1, 3);
            $il = mt_rand(7, 14);
            if ($r == 1)//cheri berry
                $jagody = 'Cheri Berry';
            elseif ($r == 2)//pecha berry
                $jagody = 'Pecha Berry';
            elseif ($r == 3)//rawst
                $jagody = 'Rawst Berry';
        }
        $this->view->wydarzenie .= $jagody . '. Zbierasz z niego <strong>' . $il . '</strong> sztuk.</span></div>';
        $jagody = str_replace(' ', '_', $jagody);
        $this->model->jagodyWyprawa($jagody, $il);
    }

    private function wodospadBlysk()
    {
        $this->view->wydarzenie = '<div class="well well-primary jeden_ttlo text-center"><div class="row nomargin"><div class="col-xs-12">Dostrzegasz błysk na górze wodospadu.</div></div></div>';
        $kwer = "SELECT id_poka FROM pokemon WHERE (typ1 = 6 OR typ2 = 6) AND id_poka in (";
        $kwer2 = "order by case id_poka";
        $aa = 0;
        for ($i = 1; $i <= 7; $i++) {
            if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') > 0) {
                //$rez = $rezultat[$i-1];
                $a = User::_get('pok', $i)->get('id_p');
                if ($i == 1)
                    $kwer .= "'$a'";
                else
                    $kwer .= ", '$a'";
                $kwer2 .= " WHEN '$a' THEN " . $i;
                $aa++;
            }
        }
        $kwer = $kwer . ")" . $kwer2 . " END";
        $rezultat = $this->model->db->select($kwer, []);
        if (!($rezultat['rowCount'])) {
            $this->view->wydarzenie .= '<div class="alert alert-danger text-center"><span>Niestety nie masz w drużynie latającego Pokemona, więc musisz ruszyć w dalszą podróż.</span></div>';
        } else {
            $this->view->wydarzenie .= '<div class="alert alert-info text-center"><span>Posyłasz swojego Pokemona, by zbadał to miejsce.<span></div>';
            $losuj = mt_rand(1, 1000);
            if ($losuj == 1) {//kamień 
                $kamien = mt_rand(1, 6);
                if ($kamien == 1) {
                    $kamien = 'ognisty';
                    $k = 'ogniste = (ogniste+1)';
                } elseif ($kamien == 2) {
                    $kamien = 'wodny';
                    $k = 'wodne = (wodne+1)';
                } elseif ($kamien == 3) {
                    $kamien = 'gromu';
                    $k = 'gromu = (gromu+1)';
                } elseif ($kamien == 4) {
                    $kamien = 'roślinny';
                    $k = 'roslinne = (roslinne+1)';
                } elseif ($kamien == 5) {
                    $kamien = 'księżycowy';
                    $k = 'ksiezycowe = (ksiezycowe+1)';
                } elseif ($kamien == 6) {
                    $kamien = 'słoneczny';
                    $k = 'sloneczne = (sloneczne+1)';
                }
                $this->view->wydarzenie .= '<div class="alert alert-success text-center"><span>Pokemon przyniósł kamień ' . $kamien . '.</span></div>';
                $this->model->kamien($k);
            } elseif ($losuj <= 600) {
                $cena = floor((mt_rand(1900, 8000) / 11000) * Session::_get('poziom') * 950);
                $this->view->wydarzenie .= '<div class="alert alert-success text-center"><span>Pokemon przyniósł kawałek srebra o wartości ' . $cena . ' &yen;<span></div>';
                $this->model->wydarzeniePieniadze($cena);
                Session::_set('kasa', (Session::_get('kasa') + $cena));
            } elseif ($losuj <= 610) {
                $this->view->wydarzenie .= '<div class="alert alert-success text-center"><span>Pokemon przyniósł puszkę wody.<span></div>';
                $this->model->woda();
            } else
                $this->view->wydarzenie .= '<div class="alert alert-danger text-center"><span>Niestety okazuje się, że to tylko słońce odbiło się w wodzie, a Pokemon wrócił z niczym.</span></div>'; //nic powyżej 600
        }
    }

    private function negatywneWodospad()
    {
        $this->view->wydarzenie = '<div class="alert alert-danger fade in text-medium text-center"><span>NEGATYWNE WYDARZENIE</span></div>';
    }

    private function kradziezSafari()
    {
        $this->view->wydarzenie = '<div class="alert alert-info text-center"><span>Zobaczyłeś Psyducka, który zagląda do Twojego plecaka.</span></div>';
        //cheri, chesto, pecha, rawst
        $rezultat = $this->model->jagody();
        $rezultat = $rezultat[0];
        $jagody = array(
            1 => array('nazwa' => 'Cheri_Berry', 'text' => 'Cheri Berry'),
            2 => array('nazwa' => 'Chesto_Berry', 'text' => 'Chesto Berry'),
            3 => array('nazwa' => 'Pecha_Berry', 'text' => 'Pecha Berry'),
            4 => array('nazwa' => 'Rawst_Berry', 'text' => 'Rawst Berry'),
        );
        $co = mt_rand(1, 5);

        if ($co != 5 && $rezultat[$jagody[$co]['nazwa']] > 0) {
            $this->view->wydarzenie .= '<div class="alert alert-danger text-center"><span>Okazało się, że Pokemon zabrał z plecaka ' . $jagody[$co]['text'] . '.</span></div>';
            $this->model->psyduckJagoda($jagody[$co]['nazwa']);
            $co = 6;
        } elseif ($co != 5) {
            $i = array();
            $i[1] = $co;
            $j = 1;
            while (1) {
                $co = mt_rand(1, 4);
                $show .= '<br />' . $co . '<br />';
                if (in_array($co, $i) && $rezultat[$jagody[$j]['nazwa']] > 0) {
                    $co = $j;
                    break;
                } else {
                    $j++;
                    $i[$j] = $co;
                }
                if ($j == 4) {
                    $co = 5;
                    break;
                }
            }
        }
        if ($co == 5)
            $this->view->wydarzenie .= '<div class="alert alert-info text-center"><span>Po obejrzeniu plecaka okazało się, że Pokemon niczego nie zabrał.</span></div>';
        elseif ($co < 5) {
            $this->view->wydarzenie .= '<div class="alert alert-danger text-center"><span>Okazało się, że Pokemon zabrał z plecaka ' . $jagody[$co]['text'] . '.</span></div>';
            $this->model->psyduckJagoda($jagody[$co]['nazwa']);
        }
    }

    private function kopanieSafari()
    {
        $this->view->wydarzenieSp = '';
        if (Session::_isset('wydarzenie_dzicz') && Session::_get('wydarzenie_dzicz') == -18) {
            $wyd = explode('|', Session::_get('wydarzenie_dzicz_set'));
            if ($wyd[0] == 0) {
                if (Session::_get('pa') < 5) {
                    $this->view->blad = '<div class="alert alert-danger fade in text-medium text-center"><span>Posiadasz za mało PA, żeby kopać.</span></div>';
                    $this->generujError();
                    exit;
                }
                $wyd[0] = 1;
                Session::_set('pa', (Session::_get('pa') - 5));
                $this->model->paZaWyprawe(5);
            }
            $suma = 0;
            for ($i = 1; $i < count($wyd); $i++)
                $suma += $wyd[$i];
            if ($suma == 0)
                $losuj = mt_rand(1, 800);
            else
                $losuj = mt_rand(1, (950 + ($wyd[0] - 1) * 30));
            if ($losuj <= 200) {//pusto
                $this->view->wydarzenieSp .= '<div class="alert alert-warning text-center"><span>Wykopałeś tylko kilka bezużytecznych korzeni.</span></div>';
                $wyd[0]--;
            } else if ($losuj <= 350) {
                $cena = floor((mt_rand(1900, 8000) / 11000) * Session::_get('poziom') * 950);
                $this->view->wydarzenieSp .= '<div class="alert alert-info text-center"><span>Wykopałeś kawałek srebra o wartości ' . $cena . ' &yen;<span></div>';
                $wyd[1] += $cena;
            } else if ($losuj <= (351 + $wyd[0])) {
                $this->view->wydarzenieSp .= '<div class="alert alert-info text-center"><span>Wykopałeś kamień ognisty.<span></div>';
                $wyd[2]++;
            } else if ($losuj <= (352 + $wyd[0])) {
                $this->view->wydarzenieSp .= '<div class="alert alert-info text-center"><span>Wykopałeś kamień wodny.<span></div>';
                $wyd[3]++;
            } else if ($losuj <= (353 + $wyd[0])) {
                $this->view->wydarzenieSp .= '<div class="alert alert-info text-center"><span>Wykopałeś kamień gromu.<span></div>';
                $wyd[4]++;
            } else if ($losuj <= (354 + $wyd[0])) {
                $this->view->wydarzenieSp .= '<div class="alert alert-info text-center"><span>Wykopałeś kamień ksieżycowy.<span></div>';
                $wyd[5]++;
            } else if ($losuj <= (355 + $wyd[0])) {
                $this->view->wydarzenieSp .= '<div class="alert alert-info text-center"><span>Wykopałeś kamień słoneczny.<span></div>';
                $wyd[6]++;
            } else if ($losuj <= (356 + $wyd[0])) {
                $this->view->wydarzenieSp .= '<div class="alert alert-info text-center"><span>Wykopałeś Rare Candy.<span></div>';
                $wyd[7]++;
            } else if ($losuj <= (357 + $wyd[0])) {
                $this->view->wydarzenieSp .= '<div class="alert alert-info text-center"><span>Wykopałeś Masterballa.<span></div>';
                $wyd[8]++;
            } else if ($losuj <= (358 + $wyd[0])) {
                $this->view->wydarzenieSp .= '<div class="alert alert-info text-center"><span>Wykopałeś skamielinę.<span></div>';
                $wyd[15]++;
            } else if ($losuj <= 475) {
                $los = mt_rand(5, 19);
                $this->view->wydarzenieSp .= '<div class="alert alert-info text-center"><span>Wykopałeś ' . $los . ' owoców Chesto Berry prawdopodobnie 
                        zakopanych wcześniej przez jakiegoś Pokemona.<span></div>';
                $wyd[9] += $los;
            } else if ($losuj <= 550) {
                $los = mt_rand(4, 10);
                $this->view->wydarzenieSp .= '<div class="alert alert-info text-center"><span>Wykopałeś ' . $los . ' owoców 
                        Aspear Berry prawdopodobnie zakopanych wcześniej przez jakiegoś Pokemona.<span></div>';
                $wyd[10] += $los;
            } else if ($losuj <= 600) {
                $los = mt_rand(5, 15);
                $this->view->wydarzenieSp .= '<div class="alert alert-info text-center"><span>Wykopałeś ' . $los . ' owoców 
                        Lapapa Berry prawdopodobnie zakopanych wcześniej przez jakiegoś Pokemona.<span></div>';
                $wyd[11] += $los;
            } else if ($losuj <= 660) {
                $los = mt_rand(5, 15);
                $this->view->wydarzenieSp .= '<div class="alert alert-info text-center"><span>Wykopałeś ' . $los . ' owoców 
                       Aguav Berry prawdopodobnie zakopanych wcześniej przez jakiegoś Pokemona.<span></div>';
                $wyd[12] += $los;
            } else if ($losuj <= 670) {
                $this->view->wydarzenieSp .= '<div class="alert alert-info text-center"><span>Wykopałeś lemoniadę.<span></div>';
                $wyd[13]++;
            } else if ($losuj <= 800) {
                $los = mt_rand(1, 3);
                $this->view->wydarzenieSp .= '<div class="alert alert-info text-center"><span>Wykopałeś ' . $los . ' Repeatballi.<span></div>';
                $wyd[14] += $los;
            }
            if ($losuj > 800) {//zakopał się dół
                $this->view->wydarzenieSp .= '<div class="alert alert-danger text-center"><span>Zauważyłeś, że ziemia zaczęła się osuwać i musiałeś uciekać z dołu. 
                        Niestety podczas ucieczki upuściłeś wszystkie znalezione rzeczy.</span></div>';
                $this->view->wydarzenieSp .= '<div class="col-xs-12 text-center margin-top"><button id="' . $this->dzicz .
                    '" class="btn btn-primary btn-lg button_kontynuuj">KONTYNUUJ</button></div>';
                Session::_unset('wydarzenie_dzicz');
                Session::_unset('wydarzenie_dzicz_set');
            } else {
                $this->view->wydarzenieSp .= '<div class="row nomargin">';
                $this->view->wydarzenieSp .= '<div class="col-xs-12 margin_2"><button type="button" class="btn btn-primary btn-block 
                        wydarzenie text-center kursor" name="1">Kop głębiej</button></div>';
                $this->view->wydarzenieSp .= '<div class="col-xs-12 margin_2"><button type="button" class="btn btn-primary btn-block 
                        wydarzenie text-center kursor" name="2">Nie chcę dłużej kopać i zabieram wszystkie znalezione przedmioty ze sobą.</button></div>';
                $this->view->wydarzenieSp .= '</div>';
                $wyd[0]++;
                Session::_set('wydarzenie_dzicz_set', implode('|', $wyd));
            }
        } else {
            if (User::$przedmioty->get('lopata')) {
                $this->view->wydarzenieSp .= '<div class="alert alert-success text-center"><span>Trafiasz na grząską glebę. Możesz poświęcić 5PA, 
                        żeby zacząć w niej kopać.</span></div>';
                $this->view->wydarzenieSp .= '<div class="row nomargin">';
                $this->view->wydarzenieSp .= '<div class="col-xs-12 margin_2"><button type="button" class="btn btn-primary btn-block wydarzenie text-center kursor" name="1">
                        Chcę kopać</button></div>';
                $this->view->wydarzenieSp .= '<div class="col-xs-12 margin_2"><button type="button" class="btn btn-primary btn-block wydarzenie text-center kursor" name="2">
                        Nie chcę kopać i idę przed siebie.</button></div>';
                $this->view->wydarzenieSp .= '</div>';
                Session::_set('wydarzenie_dzicz', -18);
                Session::_set('wydarzenie_dzicz_set', '0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0');
            } else {
                $this->view->wydarzenieSp .= '<div class="alert alert-danger text-center"><span>Trafiasz na grząską glebę, ale 
                        nie posiadasz łopaty, żeby w niej kopać.</span></div>';
            }
        }
    }

    private function trener()
    {
        $wygrana = 0;
        $ile_pokow = $this->ilePokowTrener();
        $wl_pokow = 0;
        for ($i = 1; $i < 7; $i++) {
            if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') > 0)
                $wl_pokow++;
        }
        $i1 = 1;
        $i2 = 1;
        for ($asd = 1; $asd < 7; $asd++) {
            $this->tabela[$asd] = 0;
        }
        for ($asd = 1; $asd <= $ile_pokow; $asd++) {
            $this->tabela2[$asd] = 0;
        }
        $walka = 1;
        $_SESSION['walkat'] = '';
        $_SESSION['walkat1'] = '';
        $_SESSION['walkat2'] = '';

        $_SESSION['walkat2'] = '<div class="alert alert-success text-center text-medium"><span>Na Twojej drodze staje trener, który odbywa z Tobą walkę Pokemon.</span></div>';
        unset($pok11);
        unset($pok22);
        $stan1 = 0;
        $runda1 = 0;
        $pulapka1 = 0;
        $at1 = 1;
        $stan2 = 0;
        $runda2 = 0;
        $pulapka2 = 0;
        $at2 = 1;
        for ($licznik = 1; $licznik <= $ile_pokow; $licznik++) {
            $this->pokemon_trenera[$licznik] = $this->pokemonTreneraGeneruj($licznik);
        }
        ///////////WYŚWIETLENIE INFO O GRACZU I TRENERZE
        $this->view->avatary = '<div class="row nomargin"><div class="col-xs-12 nopadding"><div class="row nomargin"><div class="col-xs-12 col-sm-6"><div class="row nomargin"><div class="col-xs-4">';
        for ($licznik = 1; $licznik < 7; $licznik++) {
            if (User::_isset('pok', $licznik) && User::_get('pok', $licznik)->get('id') > 0)
                if (User::_get('pok', $licznik)->get('akt_zycie') > 0 && User::_get('pok', $licznik)->get('glod') <= 90)
                    if (User::_get('pok', $licznik)->get('shiny') == 1)
                        $this->view->avatary .= '<img src="' . URL . 'public/img/poki/srednie/s' . User::_get('pok', $licznik)->get('id_p') . '.png" class="trener_img img-responsive center" />';
                    else
                        $this->view->avatary .= '<img src="' . URL . 'public/img/poki/srednie/' . User::_get('pok', $licznik)->get('id_p') . '.png" class="trener_img img-responsive center" />';
                else
                    $this->view->avatary .= '<img src="' . URL . 'public/img/poki/srednie/bw/' . User::_get('pok', $licznik)->get('id_p') . '.png" class="trener_img img-responsive center" />';
        }
        $avatar = $this->model->avatar();
        $avatar = $avatar[0];
        $this->view->avatary .= '</div><div class="col-xs-8">';
        if ($avatar['avatar'] != '')
            $this->view->avatary .= '<img src="' . $avatar['avatar'] . '" class="avatar img-responsive center" />';
        else
            $this->view->avatary .= '<img src="' . URL . 'public/img/no_avatar.png" class="avatar img-responsive center" />';


        //avatar, może tu się kiedyś doda losowanie tła
        $this->view->avatary .= '</div></div></div><div class="col-xs-12 col-sm-6"><div class="row nomargin"><div class="col-xs-8">'; //col row col
        $this->view->avatary .= '<img src="' . URL . 'public/img/trener/1.png" class="img-responsive center avatar"/></div><div class="col-xs-4">';

        for ($licznik = 1; $licznik <= $ile_pokow; $licznik++)
            if ($this->pokemon_trenera[$licznik]['shiny'] == 1)
                $this->view->avatary .= '<img src="' . URL . 'public/img/poki/srednie/s' . $this->pokemon_trenera[$licznik]['pok_id'] . '.png" class="trener_img img-responsive center" />';
            else
                $this->view->avatary .= '<img src="' . URL . 'public/img/poki/srednie/' . $this->pokemon_trenera[$licznik]['pok_id'] . '.png" class="trener_img img-responsive center" />';
        $this->view->avatary .= '</div></div></div></div></div></div>'; //col row col col col row

        while (1) {
            //pok dla trenera
            if (!isset($pok11)) {
                if ($i1 > $ile_pokow) {
                    $wygrana = 1;
                    break;
                }
                $stan1 = 0;
                $dos2 = 0;
                $at1 = 1;
                $pok11 = 1;
                $pok_runda1 = 0;
                $pok1 = $this->pokemon_trenera[$i1];
            }
            if (!isset($pok22)) {
                $pok22 = 1;
                $pok_runda2 = 0;
                $dos1 = 0;
                $stan2 = 0;
                while (1) {
                    if (User::_isset('pok', $i2) && (User::_get('pok', $i2)->get('akt_zycie') <= 0 || User::_get('pok', $i2)->get('glod') > 90) && $i2 < 7) {
                        if ($this->tabela[$i2] != 1)
                            $this->tabela[$i2] = 0;
                        $i2++;
                    } else
                        break;
                }
                if ($i2 > $wl_pokow) {//porażka
                    break;
                }
                $this->tabela[$i2] = 1;
                $at2 = 1;
                $pok2 = $this->wlasnyPokTrener(User::_get('pok', $i2)->get('id'), $i2);
                $spadek = 1 - (round((($pok2['glod'] - 50) * 2), 2)) / 100;
            }
            if ($stan1 == 10)
                $stan1 = 0;
            if ($stan2 == 10)
                $stan2 = 0;
            $_SESSION['walkat'] .= '<div class="alert alert-warning text-center margin-top"><span>WALKA <span class="zloty pogrubienie">' . $walka . '</span></span></div>';
            $tablica = $this->walkaPokemonow($pok2, $this->model->db, 1, $stan1, $stan2, $runda1, $runda2, $pulapka1, $pulapka2, $at1, $at2, $pok1, 0, $pok_runda1, $pok_runda2, $dos1, $dos2);
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
            } elseif ($tablica['kto'] == 2) {//wygrał pok 2
                $i1++;
                unset($pok11);
                unset($pok1);
                $dos1 = 1;
                $stan1 = 0;
                $runda1 = 0;
                $pulapka1 = 0;
                $pok_runda1 = 0;
                $pok_runda2 = $tablica['atak_runda'];
                $stan2 = $tablica['stan'];
                $runda2 = $tablica['runda'];
                $pulapka2 = $tablica['pulapka'];
                $pok2 = $this->pokPoWalceTrener($tablica, $pok2);
                $at2 = $tablica['at'];
                $pok2['jakosc'] = 100;
            } elseif ($tablica['kto'] == 1) {//wygrał pok 1
                $i2++;
                unset($pok22);
                unset($pok2);
                $dos2 = 1;
                $stan2 = 0;
                $runda2 = 0;
                $pulapka2 = 0;
                $pok_runda2 = 0;
                $pok_runda1 = $tablica['atak_runda'];
                $stan1 = $tablica['stan'];
                $runda1 = $tablica['runda'];
                $pulapka1 = $tablica['pulapka'];
                $pok1 = $this->pokPoWalceTrener($tablica, $pok1);
                $pok1['jakosc'] = 100;
                $at1 = $tablica['at'];
            }
            $walka++;
        }
        if ($wygrana) {
            $this->trenerWygrana($i2);
        } else {
            $this->trenerPorazka($i2);
        }
        $this->trenerZapis();
    }

    private function trenerZapis()
    {
        $this->view->trener = Session::_get('walkat2');
        $plik = fopen('./pliki/trener/' . Session::_get('id') . '.txt', 'w');
        fputs($plik, $_SESSION['walkat']); //zapis do pliku
        fclose($plik);
        //echo $_SESSION['walkat'];
        $this->view->trenerWynik = Session::_get('walkat1');
        $this->view->dzicz = $this->dzicz;
        $godzina = date('Y-m-d-H-i-s');
        $walka = Session::_get('walkat2') . Session::_get('walkat');
        $this->model->zapiszWalka($godzina, $walka, 'trener');
        Session::_unset('walkat');
        Session::_unset('walkat1');
        Session::_unset('walkat2');
    }

    private function trenerWygrana($i2)
    {
        $ile_pokow = $this->ilePokowTrener();
        $exp = 42;
        if (Session::_isset('karta')) {
            $karta = explode('|', Session::_get('karta'));
            if ($karta['0'] == '2') {
                $exp *= 1.25;
                $exp = round($exp);
            }
        }
        $_SESSION['walkat1'] .= '<div class="alert alert-success text-center text-medium"><span>GRATULACJE, WYGRAŁEŚ Z TRENEREM</span></div>';
        $_SESSION['walkat1'] .= '<div class="alert alert-info walka_alert text-center-alert"><span>';
        $kwer = "UPDATE pokemony SET exp = (exp + $exp) WHERE ";
        for ($i = 1, $j = 0; $i <= $i2; $i++) {
            if ($this->tabela[$i] == 1) {
                $_SESSION['walkat1'] .= User::_get('pok', $i)->get('imie') . ' otrzymuje ' . $exp . ' punkty doświadczenia.<br />';
                User::_get('pok', $i)->edit('dos', (User::_get('pok', $i)->get('dos') + $exp));
                $id = User::_get('pok', $i)->get('id');
                if ($j == 0)
                    $kwer .= " ID = '$id'";
                else
                    $kwer .= "OR ID = '$id'";
                $j++;
            }
        }
        $this->model->db->update($kwer, []);
        unset($kwer);
        $kwer = "UPDATE pokemony SET akt_HP = (CASE ID ";
        $kwer2 = '';
        for ($i = 1, $j = 0; $i <= $i2; $i++) {
            if ($this->tabela[$i] == 1) {
                //$pok[$i]->edit('dos', $pok[$i]->get('dos') + $exp);
                $id = User::_get('pok', $i)->get('id');
                $kwer .= " WHEN $id THEN " . User::_get('pok', $i)->get('akt_zycie');
                if ($j != 0)
                    $kwer2 .= ',' . $id;
                else
                    $kwer2 .= $id;
                $j++;
            }
        }
        //unset($_SESSION['pok1']['id']);
        $kwery = $kwer . ' END ) WHERE ID IN ( ' . $kwer2 . ' ); ';
        $this->model->db->update($kwery, []);
        unset($kwer);
        unset($kwer2);
        unset($kwery);

        $this->model->pokonanyTrenerOsiagniecie();
        $_SESSION['walkat1'] .= 'Dodatkowo zyskujesz 11 punktów doświadczenia trenera.</span></div>';
        Session::_set('tr_exp', (Session::_get('tr_exp') + 11));
        //pieniądze za walkę
        $sr = 0;
        for ($a = 1; $a <= $ile_pokow; $a++) {
            if ($this->pokemon_trenera[$a]['shiny'] == 1)
                $sr += 2 * $this->tabela2[$a];
            else
                $sr += $this->tabela2[$a];
        }
        $sr /= $ile_pokow;
        $rand = mt_rand(90, 115) / 100;
        if ($ile_pokow < 6)
            $pieniadze = round(($ile_pokow / 2) * ((($ile_pokow / 6) * $sr * 320) * $rand));
        else
            $pieniadze = round($ile_pokow * ((($ile_pokow / 6) * $sr * 160) * $rand));
        $_SESSION['walkat1'] .= '<div class="alert walka_alert alert-info text-center-alert"><span>Za walkę dostajesz ' . $pieniadze . ' &yen;</span></div>';
        Session::_set('kasa', (Session::_get('kasa') + $pieniadze));
        $this->model->doswiadczenieTrener($pieniadze, 11);
    }

    private function trenerPorazka($i2)
    {
        $exp = 5;
        if (Session::_isset('karta')) {
            $karta = explode('|', Session::_get('karta'));
            if ($karta['0'] == '2') {
                $exp *= 1.25;
                $exp = round($exp);
            }
        }
        $_SESSION['walkat1'] .= '<div class="alert alert-danger"><span>Niestety trener okazał się lepszy</span></div>';
        $_SESSION['walkat1'] .= '<div class="alert alert-info"><span>';
        $kwer = "UPDATE pokemony SET exp = (exp + $exp) WHERE ";
        for ($i = 1, $j = 0; $i < $i2; $i++) {
            if ($this->tabela[$i] == 1) {
                $_SESSION['walkat1'] .= User::_get('pok', $i)->get('imie') . ' otrzymuje ' . $exp . ' punkty doświadczenia.<br />';
                User::_get('pok', $i)->edit('dos', (User::_get('pok', $i)->get('dos') + $exp));
                $id = User::_get('pok', $i)->get('id');
                if ($j == 0)
                    $kwer .= " ID = '$id'";
                else
                    $kwer .= "OR ID = '$id'";
                $j++;
            }
        }
        $this->model->db->update($kwer, []);
        unset($kwer);
        $kwer = "UPDATE pokemony SET akt_HP = (CASE ID ";
        $kwer2 = '';
        for ($i = 1, $j = 0; $i <= $i2; $i++) {
            if ($this->tabela[$i] == 1) {
                $id = User::_get('pok', $i)->get('id');
                $kwer .= " WHEN $id THEN " . User::_get('pok', $i)->get('akt_zycie');
                if ($j != 0)
                    $kwer2 .= ',' . $id;
                else
                    $kwer2 .= $id;
                $j++;
            }
        }
        //unset($_SESSION['pok1']['id']);
        $kwery = $kwer . ' END ) WHERE ID IN ( ' . $kwer2 . ' ); ';
        $this->model->db->update($kwery, []);
        unset($kwer);
        unset($kwer2);
        unset($kwery);

        $_SESSION['walkat1'] .= 'Dodatkowo zyskujesz 3 punkty doświadczenia trenera.</span></div>';
        Session::_set('tr_exp', (Session::_get('tr_exp') + 3));
        $this->model->doswiadczenieTrener(3, 0);
    }

    /**
     *
     * @param array $tablica an array with changes in battle
     * @param pokemon $pok Player's or Trainer's Pokemon stats before battle
     * @return pokemon Player's or Trainer's Pokemon after battle
     */
    private function pokPoWalceTrener($tablica, $pok)
    {
        $pok2 = $pok;
        /*$pok2['Atak'] = $tablica['atak'];
        $pok2['Sp_Atak'] = $tablica['sp_atak'];
        $pok2['Obrona'] = $tablica['obrona'];
        $pok2['Sp_Obrona'] = $tablica['sp_obrona'];
        $pok2['Szybkosc'] = $tablica['szybkosc'];
        $pok2['celnosc'] = $tablica['celnosc'];*/
        $pok2['akt_HP'] = $tablica['hp'];
        $pok2['pok_hp'] = $tablica['hp'];
        $pok2['HP'] = $tablica['max_hp'];
        //$pok2['id_poka'] = $tablica['id_poka'];
        $pok2['typ1'] = $tablica['typ1'];
        $pok2['typ2'] = $tablica['typ2'];
        $pok2['tr_1'] = 0;
        $pok2['tr_2'] = 0;
        $pok2['tr_3'] = 0;
        $pok2['tr_4'] = 0;
        $pok2['tr_5'] = 0;
        $pok2['tr_6'] = 0;
        $pok2['Jag_HP'] = 0;
        $pok2['Jag_Atak'] = 0;
        $pok2['Jag_Sp_Atak'] = 0;
        $pok2['Jag_Obrona'] = 0;
        $pok2['Jag_Sp_Obrona'] = 0;
        $pok2['Jag_Szybkosc'] = 0;
        $pok2['Atak'] = $tablica['atak'];
        $pok2['Sp_Atak'] = $tablica['sp_atak'];
        $pok2['Obrona'] = $tablica['obrona'];
        $pok2['Sp_Obrona'] = $tablica['sp_obrona'];
        $pok2['Szybkosc'] = $tablica['szybkosc'];
        for ($i = 1; $i < 5; $i++)
            $pok2['atak' . $i]['id'] = $tablica['atak' . $i]['id'];
        return $pok2;
    }

    /**
     * generate variables with player's Pokemon
     * @param int $id id of player's Pokemon
     * @param int $i2 number of player's Pokemon from 1 to 6
     * @return array $pok array of variabes with player's pokemon attack, defense etc.
     */
    private function wlasnyPokTrener($id, $i2)
    {
        $rezultat = $this->model->pokemon($id);
        $wiersz = $rezultat[0];
        $wiersz = array_merge($wiersz, $this->pokemon_plik($wiersz['id_poka']));
        $pok2['poziom'] = $wiersz['poziom'];
        $pok2['imie'] = $wiersz['imie'];
        $pok2['idd'] = $wiersz['ID'];
        $pok2['id_poka'] = User::_get('pok', $i2)->get('id_p');
        $pok2['akt_HP'] = $wiersz['akt_HP'];
        $pok2['glod'] = $wiersz['glod'];

        $spadek = 0;
        if ($wiersz['glod'] > 50) {
            $spadek = round((($wiersz['glod'] - 50) * 2), 2);
            $_SESSION['walkat'] .= '<div class="alert alert-warning"><span>' . $wiersz['imie'] . ' jest głodn';
            if ($wiersz['plec'] == 1)
                $_SESSION['walkat'] .= 'a. Jej';
            else
                $_SESSION['walkat'] .= 'y. Jego';
            $_SESSION['walkat'] .= ' statystyki spadają o ' . $spadek . '%.</span></div>';
        }
        $spadek = 1 - $spadek / 100;
        //staty
        $pok2['Atak'] = round($spadek * $wiersz['Atak']);
        $pok2['Sp_Atak'] = round($spadek * $wiersz['Sp_Atak']);
        $pok2['Obrona'] = round($spadek * $wiersz['Obrona']);
        $pok2['Sp_Obrona'] = round($spadek * $wiersz['Sp_Obrona']);
        $pok2['Szybkosc'] = round($spadek * $wiersz['Szybkosc']);
        //jagody
        $pok2['Jag_Atak'] = round($spadek * $wiersz['Jag_Atak']);
        $pok2['Jag_Sp_Atak'] = round($spadek * $wiersz['Jag_Sp_Atak']);
        $pok2['Jag_Obrona'] = round($spadek * $wiersz['Jag_Obrona']);
        $pok2['Jag_Sp_Obrona'] = round($spadek * $wiersz['Jag_Sp_Obrona']);
        $pok2['Jag_Szybkosc'] = round($spadek * $wiersz['Jag_Szybkosc']);
        //treningi
        $pok2['tr_1'] = round($spadek * $wiersz['tr_1']);
        $pok2['tr_2'] = round($spadek * $wiersz['tr_2']);
        $pok2['tr_3'] = round($spadek * $wiersz['tr_3']);
        $pok2['tr_4'] = round($spadek * $wiersz['tr_4']);
        $pok2['tr_5'] = round($spadek * $wiersz['tr_5']);

        $pok2['przywiazanie'] = $wiersz['przywiazanie'];
        $pok2['Jag_HP'] = $wiersz['Jag_HP'];
        $pok2['tr_6'] = $wiersz['tr_6'];
        $pok2['HP'] = $wiersz['HP'];

        $pok2['typ1'] = $wiersz['typ1'];
        $pok2['typ2'] = $wiersz['typ2'];
        $pok2['shiny'] = $wiersz['shiny'];
        $pok2['i2'] = $i2;
        $pok2['celnosc'] = $wiersz['celnosc'];
        $pok2['plec'] = $wiersz['plec'];
        $pok2['jakosc'] = $wiersz['jakosc'];
        for ($ill = 1; $ill <= 4; $ill++)
            $pok2['atak' . $ill]['id'] = $wiersz['atak' . $ill];
        return $pok2;
    }

    /**
     *
     * @return int number of trainer's Pokemons
     */
    private function ilePokowTrener()
    {
        if (Session::_get('poziom') < 10)
            return 1;
        elseif (Session::_get('poziom') < 17)
            return 2;
        elseif (Session::_get('poziom') < 24)
            return 3;
        elseif (Session::_get('poziom') < 31)
            return 4;
        elseif (Session::_get('poziom') < 38)
            return 5;
        else
            return 6;
    }

    /**
     * generating trainer';s pokemon
     * @return pokemon trainer's pokemon
     */
    private function pokemonTreneraGeneruj($licznik)
    {
        $poz = 1000000;
        while ($poz > ((Session::_get('poziom') + 5))) {
            switch (Session::_get('region')) {
                case 1:
                    $poke = mt_rand(1, 151);
                    break;
                case 2:
                    $poke = mt_rand(152, 251);
                    break;
            }
            //if($poke == 132 || $poke == 144 || $poke == 145 || $poke == 146 || $poke == 150 || $poke == 151) continue;
            if (in_array($poke, [132, 144, 145, 146, 150, 151]))
                continue;
            $poz = $this->przyrost($poke);
            $poz = $poz['min_poziom'];
        }

        //$rezultat = $db->sql_query("SELECT * FROM pokemon WHERE id_poka = '$poke' ");
        //$wiersz = $rezultat->fetch_assoc();
        $wiersz = $this->pokemon_plik($poke);
        if (Session::_get('poziom') < 21) {
            $poziom = mt_rand(1, (Session::_get('poziom') + 5));
        }
        if ($this->przyrost($poke)['min_poziom'] > (Session::_get('poziom') - 10)) {
            $poziom = mt_rand($this->przyrost($poke)['min_poziom'], Session::_get('poziom') + 5);
        } else {
            if (Session::_get('poziom') >= 95) {
                $poziom = mt_rand(80, 100);
            } else {
                $poziom = mt_rand((Session::_get('poziom') - 20), (Session::_get('poziom') + 5));
            }
        }
        $this->tabela2[$licznik] = $poziom;
        $celnosc = mt_rand(70, 85);
        $ra = mt_rand(1, 40);
        if ($ra < 38)
            $shiny = 0; ////////////////TU KIEDYŚ DODAĆ LOSOWANIE SHINY
        else
            $shiny = 1;

        /////staty
        ////////staty pokemona://///////////////////////////////////////
        $lvl = $poziom;
        $co = $wiersz['id_poka'];

        /////staty koniec
        $pokemon_trenera = $this->generuj($co, $lvl, $wiersz['ataki']);
        //$pokemon_trenera['pok_id'] = $wiersz['id_poka'];
        $pokemon_trenera['plec'] = $this->pokemonPlec($wiersz['plec_k'], $wiersz['plec_m']);
        $pokemon_trenera['jakosc'] = mt_rand(40, 85);
        $pokemon_trenera['pok_nazwa'] = $wiersz['nazwa'];
        $pokemon_trenera['typ1'] = $wiersz['typ1'];
        $pokemon_trenera['typ2'] = $wiersz['typ2'];
        $pokemon_trenera['shiny'] = $shiny;
        $pokemon_trenera['max_hp'] = $pokemon_trenera['pok_hp'];



        for ($b = 0; $b < 4; $b++)
            $pokemon_trenera['atak' . $b]['id'] = $_SESSION['atak' . $b]['id'];
        ////////////////ATAKI KONIEC//////////////////////////////////////////
        return $pokemon_trenera;
    }

    private function pokemonDzicz()
    {
        //sprawdzenie warunków spotkania shiny u gracza
        //jeśli wszystko ok:
        /* $shiny = 0;
          //sprawdzenie przedmiotu itp.
          $sh = $this->model->db->select('SELECT * FROM shiny WHERE ID = 1', []);
          $sh = $sh[0];
          if($sh['ilosc_do_zlapania'] > 0){
          $zl = $this->model->db->select('SELECT zlapana_grupa FROM uzytkownicy WHERE ID = :id', [':id' => Session::_get('id')]);
          $zl = $zl[0];
          if($zl['zlapana_grupa'] == 0) $shiny = 1;
          }
          //sprawdzenie warunków spotkania shiny u gracza koniec
          if($shiny == 1){
          $sh_id = $sh['id_poka'];
          $sh_dzicz = $sh['dzicz'];
          //przypisanie dziczy do shiny:
          } */
        $co = $this->{$this->dzicz . 'PokemonLosuj'}();
        /* $czy_shiny = 0;
          if($shiny != 0){
          $co = $sh_id;
          $czy_shiny = 1;
          } */
        $pokemon_id_losowanie = $co;
        $jakosc = $this->pokemonJakosc();

        $wiersz = $this->pokemon_plik($co);
        $rrr = $co . "s"; ///dodać informacje czy złapany
        $rrr1 = $co . "z";
        ////////PŁEĆ
        $plec = $this->pokemonPlec($wiersz['plec_k'], $wiersz['plec_m']);
        $typ1 = $wiersz['typ1'];
        $typ2 = $wiersz['typ2'];

        $zlapany = $this->pokemonKolekcja($rrr, $rrr1);
        $lvl = $this->pokemonPoziom($this->przyrost($co)['min_poziom']);
        $nazwa = $wiersz['nazwa'];
        $id_p = $co;
        $trudnosc = $wiersz['trudnosc'];
        //WARTOŚĆ
        $wartosc = ((2500 + ($lvl * 290) + ($trudnosc * 1280)) * (0.71 * $trudnosc)) * (mt_rand(90, 110) / 100);
        if (User::$odznaki->kanto[1])
            $wartosc *= 1.1;
        $wartosc = floor($wartosc);
        //if($czy_shiny != 0) $wartosc *= 3;
        $pokemonn = $this->generuj($id_p, $lvl, $wiersz['ataki']);
        $pokemonn['plec'] = $plec;
        $pokemonn['zlapany'] = $zlapany;
        $pokemonn['pok_nazwa'] = $nazwa;
        $pokemonn['trudnosc'] = $trudnosc;
        $pokemonn['typ1'] = $typ1;
        $pokemonn['typ2'] = $typ2;
        $pokemonn['wartosc'] = $wartosc;
        $pokemonn['shiny'] = 0; //$czy_shiny;

        $this->pokemonToView([$pokemonn['shiny'], $nazwa, $lvl, $jakosc, $zlapany, $plec, $typ1, $typ2, $pokemonn['pok_atak'], $pokemonn['pok_sp_atak'],
            $pokemonn['pok_obrona'], $pokemonn['pok_sp_obrona'], $pokemonn['pok_szybkosc'], $pokemonn['pok_hp'], $pokemon_id_losowanie]);
        $this->trudnosc($trudnosc);
        Session::_set('pokemon', $pokemonn);


        Session::_set('walka', 1);
        $this->pokiGraczaView();
    }

    private function polanaPokemonLosuj()
    {

        $min_poz = 2000000000;
        //warunki shiny koniec
        $szansa = 10200;
        while ($min_poz >= Session::_get('poziom') + 6) {
            /* if($shiny == 1 && $sh_dzicz == 1){
              $szansa = $sh['szansa']*102;
              $szansa += 10200;
              $l = mt_rand(1, $szansa);
              }
              else */
            $l = mt_rand(1, $szansa); //prawdopodobieństwo do 0,01% (0,0001)
            if ($l <= 1)
                $co = 151;
            elseif ($l <= 851)
                $co = 10;
            elseif ($l <= 1701)
                $co = 13;
            elseif ($l <= 2551)
                $co = 16;
            elseif ($l <= 3401)
                $co = 23;
            elseif ($l <= 4251)
                $co = 43;
            elseif ($l <= 5101)
                $co = 69;
            elseif ($l <= 5551)
                $co = 1;
            elseif ($l <= 5901)
                $co = 11;
            elseif ($l <= 6251)
                $co = 14;
            elseif ($l <= 6601)
                $co = 17;
            elseif ($l <= 6951)
                $co = 24;
            elseif ($l <= 7301)
                $co = 25;
            elseif ($l <= 7651)
                $co = 44;
            elseif ($l <= 8001)
                $co = 70;
            elseif ($l <= 8351)
                $co = 108;
            elseif ($l <= 8701)
                $co = 114;
            elseif ($l <= 8801)
                $co = 2;
            elseif ($l <= 9101)
                $co = 12;
            elseif ($l <= 9301)
                $co = 15;
            elseif ($l <= 9501)
                $co = 18;
            elseif ($l <= 9801)
                $co = 102;
            elseif ($l <= 9831)
                $co = 3;
            elseif ($l <= 9951)
                $co = 37;
            elseif ($l <= 9961)
                $co = 26;
            elseif ($l <= 9971)
                $co = 38;
            elseif ($l <= 9981)
                $co = 45;
            elseif ($l <= 9999)
                $co = 71;
            elseif ($l <= 10100)
                $co = 103;
            elseif ($l <= 10200)
                $co = 133;
            else
                $co = 'shiny';

            if ($co != 'shiny') {
                $min_poz = $this->przyrost($co)['min_poziom'];
                $shiny = 0;
            } else {
                $min_poz = 1;
            }
        }
        return $co;
    }

    private function wyspaPokemonLosuj()
    {
        $min_poz = 2000000000;
        while ($min_poz >= Session::_get('poziom') + 6) {
            $szansa = 10000;
            /* if($shiny == 1 && $sh_dzicz == 2)
              {
              $szansa = $sh['szansa']*100;
              $szansa += 10000;
              $l = mt_rand(1, $szansa);
              }
              else */
            $l = mt_rand(1, $szansa); //prawdopodobieństwo do 0,01% (0,0001)
            if ($l <= 1)
                $co = 150;
            elseif ($l <= 701)
                $co = 19;
            elseif ($l <= 1401)
                $co = 23;
            elseif ($l <= 2101)
                $co = 29;
            elseif ($l <= 2801)
                $co = 32;
            elseif ($l <= 3501)
                $co = 46;
            elseif ($l <= 4201)
                $co = 52;
            elseif ($l <= 4901)
                $co = 98;
            elseif ($l <= 5301)
                $co = 24;
            elseif ($l <= 5701)
                $co = 33;
            elseif ($l <= 6101)
                $co = 30;
            elseif ($l <= 6501)
                $co = 48;
            elseif ($l <= 6901)
                $co = 47;
            elseif ($l <= 7301)
                $co = 58;
            elseif ($l <= 7701)
                $co = 96;
            elseif ($l <= 8501)
                $co = 100;
            elseif ($l <= 8901)
                $co = 20;
            elseif ($l <= 9001)
                $co = 49;
            elseif ($l <= 9101)
                $co = 53;
            elseif ($l <= 9301)
                $co = 79;
            elseif ($l <= 9401)
                $co = 97;
            elseif ($l <= 9501)
                $co = 101;
            elseif ($l <= 9601)
                $co = 108;
            elseif ($l <= 9701)
                $co = 99;
            elseif ($l <= 9751)
                $co = 80;
            elseif ($l <= 9801)
                $co = 124;
            elseif ($l <= 9868)
                $co = 31;
            elseif ($l <= 9934)
                $co = 34;
            elseif ($l <= 10000)
                $co = 59;
            else
                $co = 'shiny';

            if ($co != 'shiny') {
                $min_poz = $this->przyrost($co)['min_poziom'];
                $shiny = 0;
            } else {
                $min_poz = 1;
            }
        }
        return $co;
    }

    private function grotaPokemonLosuj()
    {
        $min_poz = 2000000000;
        while ($min_poz >= Session::_get('poziom') + 6) {
            $szansa = 10000;
            /* if($shiny == 1 && $sh_dzicz == 3)
              {
              $szansa = $sh['szansa']*100;
              $szansa += 10000;
              $l = mt_rand(1, $szansa);
              }
              else */
            $l = mt_rand(1, $szansa); //prawdopodobieństwo do 0,01% (0,0001)
            if ($l <= 1)
                $co = 146;
            elseif ($l <= 1101)
                $co = 23;
            elseif ($l <= 2201)
                $co = 41;
            elseif ($l <= 3301)
                $co = 50;
            elseif ($l <= 4401)
                $co = 109;
            elseif ($l <= 5501)
                $co = 88;
            elseif ($l <= 6001)
                $co = 24;
            elseif ($l <= 6501)
                $co = 42;
            elseif ($l <= 7001)
                $co = 51;
            elseif ($l <= 7501)
                $co = 92;
            elseif ($l <= 8001)
                $co = 110;
            elseif ($l <= 8501)
                $co = 89;
            elseif ($l <= 9001)
                $co = 27;
            elseif ($l <= 9501)
                $co = 95;
            elseif ($l <= 9601)
                $co = 93;
            elseif ($l <= 9751)
                $co = 35;
            elseif ($l <= 9901)
                $co = 39;
            elseif ($l <= 9951)
                $co = 28;
            elseif ($l <= 9981)
                $co = 94;
            elseif ($l <= 9991)
                $co = 36;
            elseif ($l <= 10000)
                $co = 40;
            else
                $co = 'shiny';

            if ($co != 'shiny') {
                $min_poz = $this->przyrost($co)['min_poziom'];
                $shiny = 0;
            } else {
                $min_poz = 1;
            }
            return $co;
        }
    }

    private function dom_strachowPokemonLosuj()
    {
        $min_poz = 2000000000;
        $szansa = 10000;
        while ($min_poz >= Session::_get('poziom') + 6) {
            /* if ($shiny == 1 && $sh_dzicz == 4) {
              $szansa = $sh['szansa'] * 100;
              $szansa += 10000;
              $l = mt_rand(1, $szansa);
              } else */
            $l = mt_rand(1, $szansa); //prawdopodobieństwo do 0,01% (0,0001)
            if ($l <= 1)
                $co = 145;
            elseif ($l <= 1301)
                $co = 19;
            elseif ($l <= 2601)
                $co = 41;
            elseif ($l <= 3601)
                $co = 88;
            elseif ($l <= 4801)
                $co = 96;
            elseif ($l <= 5801)
                $co = 104;
            elseif ($l <= 6301)
                $co = 20;
            elseif ($l <= 6801)
                $co = 42;
            elseif ($l <= 7101)
                $co = 63;
            elseif ($l <= 7501)
                $co = 89;
            elseif ($l <= 7801)
                $co = 92;
            elseif ($l <= 8201)
                $co = 97;
            elseif ($l <= 8901)
                $co = 122;
            elseif ($l <= 9201)
                $co = 105;
            elseif ($l <= 9351)
                $co = 64;
            elseif ($l <= 9501)
                $co = 93;
            elseif ($l <= 9681)
                $co = 106;
            elseif ($l <= 9861)
                $co = 107;
            elseif ($l <= 9961)
                $co = 124;
            elseif ($l <= 9980)
                $co = 137;
            elseif ($l <= 9990)
                $co = 65;
            elseif ($l <= 10000)
                $co = 94;
            else
                $co = 'shiny';

            if ($co != 'shiny') {
                $min_poz = $this->przyrost($co)['min_poziom'];
                $shiny = 0;
            } else {
                $min_poz = 1;
            }
        }
        return $co;
    }

    private function goryPokemonLosuj()
    {
        $min_poz = 2000000000;
        $szansa = 10000;
        while ($min_poz >= Session::_get('poziom') + 6) {
            /* if ($shiny == 1 && $sh_dzicz == 5) {
              $szansa = $sh['szansa'] * 100;
              $szansa += 10000;
              $l = mt_rand(1, $szansa);
              } else */
            $l = mt_rand(1, $szansa); //prawdopodobieństwo do 0,01% (0,0001)
            if ($l <= 1000)
                $co = 21;
            elseif ($l <= 2000)
                $co = 56;
            elseif ($l <= 3000)
                $co = 74;
            elseif ($l <= 4000)
                $co = 104;
            elseif ($l <= 5000)
                $co = 111;
            elseif ($l <= 6000)
                $co = 66;
            elseif ($l <= 7000)
                $co = 77;
            elseif ($l <= 7320)
                $co = 4;
            elseif ($l <= 7690)
                $co = 22;
            elseif ($l <= 8010)
                $co = 57;
            elseif ($l <= 8330)
                $co = 75;
            elseif ($l <= 8700)
                $co = 95;
            elseif ($l <= 9070)
                $co = 105;
            elseif ($l <= 9390)
                $co = 112;
            elseif ($l <= 9710)
                $co = 67;
            elseif ($l <= 9810)
                $co = 5;
            elseif ($l <= 9860)
                $co = 6;
            elseif ($l <= 9880)
                $co = 76;
            elseif ($l <= 9950)
                $co = 78;
            elseif ($l <= 9970)
                $co = 68;
            elseif ($l <= 9990)
                $co = 81;
            elseif ($l <= 10000)
                $co = 82;
            else
                $co = 'shiny';

            if ($co != 'shiny') {
                $min_poz = $this->przyrost($co)['min_poziom'];
                $shiny = 0;
            } else {
                $min_poz = 1;
            }
        }
        return $co;
    }

    private function wodospadPokemonLosuj()
    {
        $min_poz = 2000000000;
        $szansa = 10000;
        while ($min_poz >= Session::_get('poziom') + 6) {
            /*if ($shiny == 1 && $sh_dzicz == 6) {
                $szansa = $sh['szansa'] * 100;
                $szansa += 10000;
                $l = mt_rand(1, $szansa);
            } else*/
            $l = mt_rand(1, $szansa); //prawdopodobieństwo do 0,01% (0,0001)
            if ($l <= 1)
                $co = 144;
            elseif ($l <= 701)
                $co = 54;
            elseif ($l <= 1401)
                $co = 60;
            elseif ($l <= 2101)
                $co = 72;
            elseif ($l <= 2801)
                $co = 79;
            elseif ($l <= 3501)
                $co = 86;
            elseif ($l <= 4201)
                $co = 90;
            elseif ($l <= 4901)
                $co = 98;
            elseif ($l <= 5601)
                $co = 118;
            elseif ($l <= 6301)
                $co = 129;
            elseif ($l <= 6701)
                $co = 7;
            elseif ($l <= 7101)
                $co = 55;
            elseif ($l <= 7501)
                $co = 61;
            elseif ($l <= 7901)
                $co = 73;
            elseif ($l <= 8301)
                $co = 99;
            elseif ($l <= 8701)
                $co = 116;
            elseif ($l <= 9101)
                $co = 119;
            elseif ($l <= 9501)
                $co = 120;
            elseif ($l <= 9601)
                $co = 8;
            elseif ($l <= 9651)
                $co = 80;
            elseif ($l <= 9701)
                $co = 87;
            elseif ($l <= 9751)
                $co = 117;
            elseif ($l <= 9801)
                $co = 130;
            elseif ($l <= 9851)
                $co = 131;
            elseif ($l <= 9901)
                $co = 9;
            elseif ($l <= 9934)
                $co = 62;
            elseif ($l <= 9967)
                $co = 91;
            elseif ($l <= 10000)
                $co = 121;
            else
                $co = 'shiny';

            if ($co != 'shiny') {
                $min_poz = $this->przyrost($co)['min_poziom'];
                $shiny = 0;
            } else {
                $min_poz = 1;
            }
        }
        return $co;
    }

    private function safariPokemonLosuj()
    {
        $min_poz = 2000000000;
        while ($min_poz >= Session::_get('poziom') + 6) {
            $szansa = 10598;
            $l = mt_rand(1, $szansa); //prawdopodobieństwo do 0,01% (0,0001)

            if ($l <= 708)
                $co = 21;
            elseif ($l <= 1416)
                $co = 46;
            elseif ($l <= 2124)
                $co = 48;
            elseif ($l <= 2832)
                $co = 54;
            elseif ($l <= 3540)
                $co = 84;
            elseif ($l <= 4248)
                $co = 108;
            elseif ($l <= 4956)
                $co = 111;
            elseif ($l <= 5448)
                $co = 22;
            elseif ($l <= 5848)
                $co = 47;
            elseif ($l <= 6248)
                $co = 49;
            elseif ($l <= 6648)
                $co = 55;
            elseif ($l <= 7048)
                $co = 102;
            elseif ($l <= 7448)
                $co = 83;
            elseif ($l <= 7848)
                $co = 85;
            elseif ($l <= 8248)
                $co = 112;
            elseif ($l <= 8548)
                $co = 103;
            elseif ($l <= 8898)
                $co = 113;
            elseif ($l <= 9198)
                $co = 114;
            elseif ($l <= 9548)
                $co = 115;
            elseif ($l <= 9948)
                $co = 124;
            elseif ($l <= 10098)
                $co = 128;
            elseif ($l <= 10298)
                $co = 127;
            elseif ($l <= 10398)
                $co = 125;
            elseif ($l <= 10498)
                $co = 126;
            elseif ($l <= 10598)
                $co = 123;
            $shiny = 0;
            $min_poz = $this->przyrost($co)['min_poziom'];
        }
        return $co;
    }

    private function pokemonJakosc()
    {
        $jakosc = mt_rand(20, 110);
        if ($jakosc > 100) {
            $jakosc -= 10;
        } elseif ($jakosc > 50) {
            $jakosc -= 5;
        }
        Session::_set('jakosc', $jakosc);
        return $jakosc;
    }

    private function pokemonPlec($k, $m)
    {
        if ($k == 0 && $m == 0)
            $plec = 2;
        elseif ($k == 0)
            $plec = 0;
        elseif ($m == 0)
            $plec = 1;
        else {
            $_0 = $m;
            $_1 = $k;
            $p = mt_rand() % 1000;
            if ($p < $_0)
                $plec = 0;
            else
                $plec = 1;
        }
        return $plec;
    }

    private function pokemonKolekcja($rrr, $rrr1, $update = 1)
    {
        $this->kolekcja = $this->model->kolekcja();
        //$rez = $this->model->db->select("SELECT $rrr, $rrr1 FROM kolekcja WHERE ID = :id", [':id' => Session::_get('id')]);
        //$w = $rez[0];
        if ($update) {
            if ($this->kolekcja[$rrr] == 0)
                $this->view->pierwszy = '<div class="alert alert-success fade in text-medium text-center margin-top"><span>Jeszcze nie spotkałeś takiego pokemona!</span></div>';
            $this->model->dodajDoKolekcji($rrr);
        }
        return $this->kolekcja[$rrr1];
    }

    private function pokemonPoziom($min)
    {
        if ((Session::_get('poziom') + 6 - $min) == 0) {
            $popp = 1;
        } else {
            if (Session::_get('poziom') <= 10) {
                $popp = Session::_get('poziom') + 4 - $min;
            } else {
                $popp = Session::_get('poziom') + 6 - $min;
            }
        }
        $lvl = (mt_rand() % $popp) + $min;
        if ($lvl > 100) {
            if ($min == 100)
                $lvl = 100;
            else
                $lvl = mt_rand($min + 1, 100);
        }
        return $lvl;
    }

    private function trudnosc($trudnosc)
    {
        if ($trudnosc < 3)
            $show = 'zielony-tlo';
        elseif ($trudnosc < 5)
            $show = 'zolty-tlo';
        else
            $show = 'czerwony-tlo';
        $this->view->trudnosc = $show;
        $this->view->trudnoscLiczba = $this->rzymskie($trudnosc);
        $trudnosc1 = [
            1 => 'bardzo łatwa',
            2 => 'łatwa',
            3 => 'średnia',
            4 => 'średnio trudna',
            5 => 'trudna',
            6 => 'bardzo trudna',
            10 => 'Nie możliwy do złapania'
        ];
        $this->view->trudnoscOpis = $trudnosc1[$trudnosc];
    }

    private function pokemonToView($data)
    {
        $this->view->pokemon = 1;
        $this->view->shiny = $data[0];
        $this->view->nazwa = $data[1];
        $this->view->lvl = $data[2];
        $this->view->jakosc = $data[3];
        $this->view->zlapany = $data[4];
        $this->view->plec = $data[5];
        $this->view->typ1 = $data[6];
        $this->view->typ2 = $data[7];
        $this->view->typ1o = $this->rodzaj($data[6]);
        $this->view->typ2o = $this->rodzaj($data[7]);
        $this->view->atak = round($data[3] / 100 * $data[8]);
        $this->view->sp_atak = round($data[3] / 100 * $data[9]);
        $this->view->obrona = round($data[3] / 100 * $data[10]);
        $this->view->sp_obrona = round($data[3] / 100 * $data[11]);
        $this->view->szybkosc = round($data[3] / 100 * $data[12]);
        $this->view->HP = round($data[3] / 100 * $data[13]);
        $this->view->id = $data[14];
        $this->view->pokedex = User::$przedmioty->get('pokedex');
    }

    private function pokiGraczaView()
    {
        for ($i = 1; $i < 7; $i++) {
            if (User::_isset('pok', $i) && (User::_get('pok', $i)->get('id') > 0)) {
                $this->view->pokemonGracza[$i]['akt_zycie'] = User::_get('pok', $i)->get('akt_zycie');
                $this->view->pokemonGracza[$i]['zycie'] = User::_get('pok', $i)->get('zycie');
                $this->view->pokemonGracza[$i]['shiny'] = User::_get('pok', $i)->get('shiny');
                $this->view->pokemonGracza[$i]['glod'] = round(User::_get('pok', $i)->get('glod'), 2);
                $this->view->pokemonGracza[$i]['id'] = User::_get('pok', $i)->get('id');
                $this->view->pokemonGracza[$i]['imie'] = User::_get('pok', $i)->get('imie');
                $this->view->pokemonGracza[$i]['id_p'] = User::_get('pok', $i)->get('id_p');
            }
        }
    }

    private function generujWalka()
    {
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('Polowanie - ' . NAME, 1);
            $this->view->render('polowanie/walka');
            $this->loadTemplate('', 2, 2);
        } else {
            $this->view->render('polowanie/walka');
        }
    }

    private function generujWydarzenie()
    {
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('Polowanie - ' . NAME, 1);
            $this->view->render('polowanie/wydarzenie');
            $this->loadTemplate('', 2, 2);
        } else {
            $this->view->render('polowanie/wydarzenie');
        }
    }

    private function generujPokemon()
    {
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('Polowanie - ' . NAME, 1);
            $this->view->render('polowanie/pokemon');
            $this->loadTemplate('', 2, 2);
        } else {
            $this->view->render('polowanie/pokemon');
        }
    }

    private function generujLapanie()
    {
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('Polowanie - ' . NAME, 1);
            $this->view->render('polowanie/lapanie');
            $this->loadTemplate('', 2, 2);
        } else {
            $this->view->render('polowanie/lapanie');
        }
    }

    private function generujTrener()
    {
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('Polowanie - ' . NAME, 1);
            $this->view->render('polowanie/trener');
            $this->loadTemplate('', 2, 2);
        } else {
            $this->view->render('polowanie/trener');
        }
    }

    private function generujError()
    {
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('Polowanie - '.NAME);
        }
        $this->view->dzicz = $this->dzicz;
        $this->view->render('polowanie/error');
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('', 2, 2);
        }
        exit;
    }

    private function wyswietlKanto()
    {
        $this->view->region = 'KANTO';
        $this->view->dzicz = [
            'polana' => [
                'nazwaPl' => 'Polana',
                'opis' => 'Przemierzając ten obszar można natknąć się na pachnące drzewa z jagodami. 
                Dzicz ze względu na brak negatywnych wydarzeń jest dobrym miejscem na wyprawy dla początkujących trenerów. 
                Niewykluczone, że na naszej drodze stanie mędrzec, który pomoże zwiększyć doświadczenie nasze i naszych pokemonów.',
                'pokemony' => $this->pokemonyDziczZlapane('Polana')
            ],
            'wyspa' => [
                'nazwaPl' => 'Wyspa',
                'opis' => 'Na obszarze wyspy znajdują się oazy, gdzie wraz z pokemonami można odpocząć 
                    i zwiększyć ich przywiązanie do trenera. Należy jednak pamiętać, aby nie zapuszczać się zbyt daleko od brzegu, 
                    bo do odnalezienia drogi powrotnej trzeba będzie przeznaczyć większą ilość energii.',
                'pokemony' => $this->pokemonyDziczZlapane('Wyspa')
            ],
            'grota' => [
                'nazwaPl' => 'Grota',
                'opis' => 'Łowcy z całego regionu przybywają tutaj na poszukiwanie skarbów. Jednak schodząc do środka 
                    łatwo zgubić się w ciemnych komnatach, gdzie znalezienie wyjścia powoduje utratę dodatkowych punktów akcji. 
                    Do zwiedzania tego miejsca należy zaopatrzyć się w latarkę. Nie zapomnij kupić baterii w pokesklepie, ponieważ zejście 
                    do groty nie będzie możliwe.',
                'pokemony' => $this->pokemonyDziczZlapane('Grota')
            ],
            'dom_strachow' => [
                'nazwaPl' => 'Dom strachów',
                'opis' => 'Budynek nie zamieszkiwany od wielu lat przez ludzi, niektórzy twierdzą, że jest nawiedzony. 
                W środku znajduje się wiele sekretnych pomieszczeń a w nich ukryte są cenne przedmioty. Spotkanie przyjaznych 
                duchów zwiększa przywiązanie pokemonów.',
                'pokemony' => $this->pokemonyDziczZlapane('DomStrachow')
            ],
            'gory' => [
                'nazwaPl' => 'Góry',
                'opis' => 'Najbardziej kolorowe miejsce w regionie dzięki wszechobecnym jagodom. Można znaleźć tu bardzo rzadkie ich odmiany. 
                Poruszanie się po stromej powierzchni jest dość uciążliwe, dlatego zużycie energii jest większe niż podczas normalnej wyprawy.',
                'pokemony' => $this->pokemonyDziczZlapane('Gory')
            ],
            'wodospad' => [
                'nazwaPl' => 'Wodospad',
                'opis' => 'Przechodząc obok wodospadu można ujrzeć błysk na jego szczycie. Warto więc zabrać 
                ze sobą pokemona typu powietrznego. Należy jednak uważać na śliskie kamienie, bo jedno zachwianie może 
                spowodować zgubienie balla z plecaka. Królują tu wodne pokemony.',
                'pokemony' => $this->pokemonyDziczZlapane('Wodospad')
            ],
            'safari' => [
                'nazwaPl' => 'Safari',
                'opis' => 'Wejście na teren safari możliwy jest poprzez okazanie odpowiedniego kuponu strażnikowi. 
                Jest to miejsce występowania rzadkich gatunków pokemonów. Znajdują się tu również cenne przedmioty, 
                które można wydobyć za pomocą łopaty. Uważaj jednak na dzikiego Psyducka, który wykrada jagody z plecaków.',
                'pokemony' => $this->pokemonyDziczZlapane('Safari')
            ]
        ];
    }

    private function wyswietlJohto()
    {

    }

    private function pokemonyDziczZlapane(string $dzicz)
    {
        return $this->{'zlapane'.$dzicz}();
    }

    private function sprawdzZlapane(array $pokemony)
    {
        for ($i = 0 ; $i < count($pokemony) ; $i++) {
            $tabela[$i]['nazwa'] = $this->pokemon_plik($pokemony[$i])['nazwa'];
            $tabela[$i]['zlapany'] = $this->pokemonKolekcja($pokemony[$i].'s', $pokemony[$i].'z', 0);
        }
        return $tabela;
    }

    private function zlapanePolana()
    {
        $polana = [
            1, 2, 3, 10, 11, 12, 13, 14, 15, 16, 17, 18, 23, 24, 25, 26, 37, 38, 43, 44, 45, 69, 70, 71, 102, 103, 108, 114, 133
        ];
        return $this->sprawdzZlapane($polana);
    }

    private function zlapaneWyspa()
    {
        $wyspa = [
            19, 20, 23, 24, 29, 30, 31, 32, 33, 34, 46, 47, 48, 49, 52, 53, 58, 59, 79, 80, 96, 97, 98, 99, 100, 101, 108, 124
        ];
        return $this->sprawdzZlapane($wyspa);
    }

    private function zlapaneGrota()
    {
        $grota = [
            23, 24, 27, 28, 35 ,36 ,39, 40 ,41, 42, 50, 51, 88, 89, 92, 93, 94, 95, 109, 110
        ];
        return $this->sprawdzZlapane($grota);
    }

    private function zlapaneDomStrachow()
    {
        $dom = [
            19, 20, 41, 42, 63, 64, 65, 88, 89, 92, 93, 94, 96, 97, 104, 105, 106, 107, 122, 124, 137
        ];
        return $this->sprawdzZlapane($dom);
    }

    private function zlapaneGory()
    {
        $gory = [
            4, 5, 6, 21, 22, 56, 57, 66, 67, 68, 74, 75, 76, 77, 78, 81, 82, 95, 104, 105, 111, 112
        ];
        return $this->sprawdzZlapane($gory);
    }

    private function zlapaneWodospad()
    {
        $wodospad = [
            7, 8, 9, 54, 55, 60, 61, 62, 72, 73, 79, 80, 86, 87, 90, 91, 98, 99, 116, 117, 118, 119, 120, 121, 129, 130, 131
        ];
        return $this->sprawdzZlapane($wodospad);
    }

    private function zlapaneSafari()
    {
        $safari = [
            21, 22, 46, 47, 48, 49, 54, 55, 83, 84, 85, 102, 103, 108, 111, 112, 113, 114, 115, 123, 124, 125, 126, 127, 128
        ];
        return $this->sprawdzZlapane($safari);
    }
}

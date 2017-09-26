<?php

namespace src\controllers;

use src\libs\Controller;
use src\libs\Session;
use src\libs\User;

class Pokemony extends Controller
{
    public function  __construct()
    {
        parent::__construct();
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('Pokemony - '.NAME);
        }
        if (!isset($_GET['active'])) $this->view->active = 1;
        else $this->view->active = $_GET['active'];
    }

    public function index()
    {
        $this->druzynaZakladka();
        $this->rezerwaZakladka();
        $this->poczekalniaZakladka();
        $this->targZakladka();
        $this->view->render('pokemony/index');
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('',2);
        }
    }

    public function up(int $pok)
    {
        if ($pok < 2 || $pok > 7) {
            $this->view->error = 'Ten Pokemon nie może mieć wyższego priorytetu.';
            $this->index();
            return;
        }
        if (User::_isset('pok', $pok)  && User::_isset('pok', $pok - 1) && User::_get('pok', $pok - 1)->get('id') > 0) {
            $i1 = $pok;
            $i2 = $pok - 1;
            $poke1 = User::_get('pok', $i2)->get_all();
            $poke2 = User::_get('pok', $i1)->get_all();
            Session::_unset($pok[$i1]);
            Session::_unset($pok[$i2]);
            Session::_set('pok' . $i1, $poke1);
            Session::_set('pok' . $i2, $poke2);
            $id1 = User::_get('pok', $i1)->get('id');
            $id2 = User::_get('pok', $i2)->get('id');
            for ($i = 1; $i < 7; $i++) {
                User::_unset('pok', $i);
            }
            User::getInstance();
            $this->model->pokemonUp($i2, $id1, $i1, $id2);
            $this->view->komunikat =  'Poprawnie zmieniono priorytet Pokemona na wyższy.';
        } else {
            $this->view->error = 'Ten Pokemon nie może mieć wyższego priorytetu.';
        }

        $this->index();
    }

    public function down(int $pok)
    {
        if ($pok < 1 || $pok > 5) {
            $this->view->error = 'Ten Pokemon nie może mieć wyższego priorytetu.';
            $this->index();
            return;
        }
        if (User::_isset('pok', $pok)  && User::_isset('pok', $pok + 1) && User::_get('pok', $pok + 1)->get('id') > 0) {
            $i1 = $pok;
            $i2 = $pok + 1;
            $poke1 = User::_get('pok', $i2)->get_all();
            $poke2 = User::_get('pok', $i1)->get_all();
            Session::_unset($pok[$i1]);
            Session::_unset($pok[$i2]);
            Session::_set('pok' . $i1, $poke1);
            Session::_set('pok' . $i2, $poke2);
            $id1 = User::_get('pok', $i2)->get('id');
            $id2 = User::_get('pok', $i1)->get('id');
            for ($i = 1; $i < 7; $i++) {
                User::_unset('pok', $i);
            }
            User::getInstance();
            $this->model->pokemonDown($i1, $id1, $i2, $id2);
            $this->view->komunikat = 'Poprawnie zmieniono priorytet Pokemona na niższy.';
        } else {
            $this->view->error = 'Ten Pokemon nie może mieć niższego priorytetu.';
        }
        $this->index();
    }

    private function druzynaZakladka()
    {
        $kwer = 'SELECT * FROM pokemony WHERE ID in (';
        $kwer2 = 'order by case ID';
        $aa = 0;
        for ($i = 1 ; $i < 7 ; $i++) {
            if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') > 0) {
                $a = User::_get('pok', $i)->get('id');
                if($i == 1)$kwer = $kwer . " $a ";
                else $kwer = $kwer . ", $a ";
                $kwer2 = $kwer2 . " WHEN $a THEN ".$i;
                $aa++;
            }
        }
        $kwer = $kwer . ')' . $kwer2 . ' END';
        $rezultat = $this->model->db->select($kwer, []);
        $this->view->pokiDruzyna = $aa;
        for ($i = 1 ; $i <= $rezultat['rowCount'] ; $i++) {
            $wiersz = $rezultat[$i-1];
            $this->view->pokDruzyna[$i]['shiny'] = $wiersz['shiny'];
            $this->view->pokDruzyna[$i]['ID'] = $wiersz['ID'];
            $this->view->pokDruzyna[$i]['id_poka'] = $wiersz['id_poka'];
            $this->view->pokDruzyna[$i]['imie'] = $wiersz['imie'];
            $this->view->pokDruzyna[$i]['plec'] = $wiersz['plec'];
            $this->view->pokDruzyna[$i]['poziom'] = $wiersz['poziom'];
            $this->view->pokDruzyna[$i]['exp'] = $wiersz['exp'];
            $this->view->pokDruzyna[$i]['exp_p'] = User::_get('pok', $i)->get('dos_p');
            $this->view->pokDruzyna[$i]['zycie'] = User::_get('pok', $i)->get('zycie');
            $this->view->pokDruzyna[$i]['akt_zycie'] = User::_get('pok', $i)->get('akt_zycie');
            $this->view->pokDruzyna[$i]['przywiazanie'] = przywiazanie($wiersz['przywiazanie']);
        }
    }

    private function rezerwaZakladka()
    {
        $rezultat = $this->model->pokemonyRezerwa();
        $this->view->pokiRezerwa = $rezultat['rowCount'];

        for ($i = 1 ; $i <= $rezultat['rowCount'] ; $i++) {
            $wiersz = $rezultat[$i-1];
            $this->view->pokRezerwa[$i]['poziom'] = $wiersz['poziom'];
            $this->view->pokRezerwa[$i]['id_poka'] = $wiersz['id_poka'];
            $this->view->pokRezerwa[$i]['ID'] = $wiersz['ID'];
            $this->view->pokRezerwa[$i]['shiny'] = $wiersz['shiny'];
            $this->view->pokRezerwa[$i]['plec'] = $wiersz['plec'];
            $this->view->pokRezerwa[$i]['imie'] = $wiersz['imie'];
        }
    }

    private function poczekalniaZakladka()
    {
        $rezultat = $this->model->pokemonyPoczekalnia();
        $this->view->pokiPoczekalnia = $rezultat['rowCount'];

        for ($i = 1 ; $i <= $rezultat['rowCount'] ; $i++) {
            $wiersz = $rezultat[$i-1];
            $this->view->pokPoczekalnia[$i]['poziom'] = $wiersz['poziom'];
            $this->view->pokPoczekalnia[$i]['id_poka'] = $wiersz['id_poka'];
            $this->view->pokPoczekalnia[$i]['ID'] = $wiersz['ID'];
            $this->view->pokPoczekalnia[$i]['shiny'] = $wiersz['shiny'];
            $this->view->pokPoczekalnia[$i]['plec'] = $wiersz['plec'];
            $this->view->pokPoczekalnia[$i]['imie'] = $wiersz['imie'];
        }
    }

    private function targZakladka()
    {
        $rezultat = $this->model->pokemonyTarg();
        $this->view->pokiTarg = $rezultat['rowCount'];

        for ($i = 1 ; $i <= $rezultat['rowCount'] ; $i++) {
            $wiersz = $rezultat[$i-1];
            $this->view->pokTarg[$i]['poziom'] = $wiersz['poziom'];
            $this->view->pokTarg[$i]['id_poka'] = $wiersz['id_poka'];
            $this->view->pokTarg[$i]['ID'] = $wiersz['ID'];
            $this->view->pokTarg[$i]['shiny'] = $wiersz['shiny'];
            $this->view->pokTarg[$i]['plec'] = $wiersz['plec'];
            $this->view->pokTarg[$i]['imie'] = $wiersz['imie'];
        }
    }

    public function druzyna()
    {
        $rezultat = $this->model->druzynaIle();
        $rezultat = $rezultat[0];
        if ($rezultat['ile'] == 6) {
            $this->view->error = 'W drużynie może być maksymalnie 6 Pokemonów.';
            $this->index();
            return;
        }
        $ile_mozliwych = 6 - $rezultat['ile'];
        $rez = $this->model->rezerwaID();
        $klery = 'UPDATE pokemony SET druzyna = 1 WHERE ';
        $il = 0;
        for ($i = 1 ; $i <= $rez['rowCount'] ; $i++) {
            $rez1 = $rez[$i - 1];
            if(isset($_POST[$rez1['ID']])) {
                if(!$ile_mozliwych) break;
                if(!$il) $klery .= 'ID = '.$rez1['ID'];
                else $klery .= ' OR ID = '.$rez1['ID'];
                $il++;
                $poki[$il] = $rez1['ID'];
                $ile_mozliwych--;
            }
        }
        if(!$il) {
            $this->view->blad = 'Nieprawidłowy ID Pokemona.';
        } else {
                $klery1 = 'UPDATE druzyna SET ile = (ile+' . $il . ')';
                for ($i = 1; $i <= $il; $i++) {
                    $klery1 .= ' ,pok' . ($rezultat['ile'] + $i) . '=' . $poki[$i];
                }
                $klery1 .= ' WHERE id_gracza = '.Session::_get('id');
                $this->model->db->update($klery, []);
                $this->model->db->update($klery1, []);
                for ($i = 1; $i <= $il; $i++) {
                    $wiersz2 = $this->model->pokemonDane($poki[$i]);
                    $wiersz2 = $wiersz2[0];
                    $c = $wiersz2['ID'] . '|' . $wiersz2['exp'] . '|' . $wiersz2['poziom'] . '|' . $wiersz2['id_poka'] . '|' . $wiersz2['imie'] . '|' . ($wiersz2['HP'] + $wiersz2['jakosc'] / 100 * ($wiersz2['Jag_HP'] + $wiersz2['tr_6'] * 5)) . '|' . $wiersz2['ewolucja'] . '|' . $wiersz2['akt_HP'] . '|' . $wiersz2['shiny'] . '|';
                    require("./src/includes/pokemony/exp_na_poziom.php");
                    require("./src/includes/pokemony/pokemon.php");
                    $rez = $pokemon_plik[$wiersz2['id_poka']];
                    if ($wiersz2['poziom'] < 100) $exp_next = $exp_na_poziom[($wiersz2['poziom'] + 1)];
                    else $exp_next = 9999999999;
                    if ($wiersz2['imie'] == $rez['nazwa']) $imie = 0;
                    else $imie = 1;
                    $c .= $exp_next . '|' . $imie . '|' . $rez['ewolucja_p'] . '|' . $wiersz2['plec'] . '|' . $wiersz2['glod'] . '|' . $wiersz2['jakosc'];
                    Session::_set('poki_magazyn', (Session::_get('poki_magazyn') - 1));
                    Session::_set('pok'.($rezultat['ile'] + $i), $c);
                }
                for ($i = 1; $i <= 6; $i++) {
                    if (User::_isset('pok', $i)) {
                        User::_unset('pok', $i);
                    }
                }
                User::getInstance();
                $this->view->komunikat = 'Poprawnie dodano ' . $il . ' Pokemonów do drużyny.';
                $this->index();
        }
    }

    public function rezerwa(int $co = 0)
    {
        if (!$co) {
            $this->rezerwaZPoczekalni(1);
        } else {
            $this->rezerwaZDruzyny($co);
        }
        if(!isset($_GET['modal'])) {
            $this->index();
        }
    }

    public function poczekalnia(int $co = 0)
    {
        if (!$co) {
            $this->rezerwaZPoczekalni(0);
        } else {
            echo 'WHAT?';
        }
        $this->index();
    }


    private function rezerwaZDruzyny(int $i = 0)
    {
        $rezultat =  $this->model->pokemonyDruzyna();
        $wiersz = $rezultat[0];
        if(isset($_GET['modal'])){//do poprawy ??????????????co to było?
            for($i = 1 ; $i < 7 ; $i++)
                if(isset($pok[$i]) && $pok[$i]->get('id') == $co)
                    break;
        }
        if ($wiersz['ile']==1) {
            if(!isset($_GET['modal'])) $this->view->error = 'W drużynie musi być co najmniej 1 pokemon!';
            else $json = '<div class="alert alert-danger text-center"><span>W drużynie musi być co najmniej 1 pokemon!</span></div>';
            return;
        }
        if ($i < 1 || $i > 6 || !User::_isset('pok', $i)) {
            if(!isset($_GET['modal'])) $this->view->error = 'Błędny numer Pokemona!';
            else $json = '<div class="alert alert-danger text-center"><span>Błędny numer pokemona!</span></div>';
            return;
        }
        $id = User::_get('pok', $i)->get('id');
        $kwer = 'UPDATE druzyna SET ile = (ile-1)';
        $kwer2 = ', pok6 = 0 WHERE id_gracza = ?';
        for ($j = ($i +1) ; $j < 7 ; $j++) {
            $a = $j-1;
            if ($wiersz['pok'.$j] > 0) {
                $poke[$a] = $wiersz['pok' . $j];
                $kwer = $kwer . ", pok$a = $poke[$a]";
                $pokee = User::_get('pok', $j)->get_all();
                Session::_unset('pok' . $j);
                Session::_unset('pok' . $a);
                Session::_set('pok' . $a, $pokee);
            } else {
                $kwer = $kwer.", pok$a = 0";
                Session::_unset('pok' . $a);
            }
        }
        if ($i == 6) {
            Session::_unset('pok6');
        }
        for($k = 1 ; $k < 7 ; $k++) {
            if (User::_isset('pok',$k)) {
                User::_unset('pok',$k);
            }
        }
        User::getInstance();
        $kwer = $kwer . $kwer2;
        $this->model->pokemonDoRezerwy($id);
        $this->model->db->update($kwer, [Session::_get('id')]);
        Session::_set('poki_magazyn', ( Session::_get('poki_magazyn') + 1));
        if (!isset($_GET['modal'])) {
            $this->view->komunikat = 'Pokemon został poprawnie usunięty z drużyny.';
        } else {
            $json = '<div class="alert alert-success text-center"><span>Pokemon został poprawnie usunięty z drużyny.</span></div>';
        }
    }

    /**
     * @param $i 1 for poczekalnia -> rezerwa, 0 for rezerwa -> poczekalnia
     */
    private function rezerwaZPoczekalni($i)
    {
        if ($i) {
            $rez = $this->model->pokiIDZRezerwy();
            $klery1 = 'UPDATE pokemony SET blokada = 1 WHERE ';
        } else {
            $rez = $this->model->pokiIDZPoczekalni();
            $klery1 = 'UPDATE pokemony SET blokada = 0 WHERE ';
        }
        $il = 0;
        for ($i = 1 ; $i <= $rez['rowCount'] ; $i++) {
            $rez1 = $rez[$i - 1];
            if(isset($_POST[$rez1['ID']])) {
                if ($il == 0) $klery1 .= 'ID=' . $rez1['ID'];
                else $klery1 .= ' OR ID=' . $rez1['ID'];
                $il++;
            }
        }
        if ($il == 0) {
            $this->view->error = 'Nieprawidłowy ID Pokemona.';
            return;
        } else {
            $this->model->db->update($klery1, []);
            if ($i) {
                $this->view->komunikat = 'Poprawnie przeniesiono ' . $il . ' Pokemonów do rezerwy.';
            } else {
                $this->view->komunikat = 'Poprawnie przeniesiono ' . $il . ' Pokemonów do poczekalni.';
            }
        }
    }
}

?>
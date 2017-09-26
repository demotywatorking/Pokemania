<?php

namespace src\controllers;

use src\libs\Controller;
use src\libs\Session;

class Znajomi extends Controller
{

    function __construct() 
    {
        parent::__construct();
        if(!isset($_GET['ajax'])){
            $this->loadTemplate('Znajomi - '.NAME);
        }
    }
    
    public function index()
    {
        $znajomi = $this->model->znajomi();
        $this->view->iloscZnajomych = 0;
        $this->view->zaproszenia = 0;
        $this->view->wyslane = 0;
        if($znajomi['rowCount']){//wypisanie znajomych
            $this->znajomi($znajomi);
        }
        //zaproszenia
        $znajomi = $this->model->zaproszenia();
        if($znajomi['rowCount']){//zaproszenia do zaakceptowania
            $this->zaproszenia($znajomi);
        }
        //wysłane zaproszenia
        $znajomi = $this->model->wyslane();
        if($znajomi['rowCount']){
            $this->wyslane($znajomi);
        }
        $this->view->render('znajomi/index');
        if(!isset($_GET['ajax'])){
            $this->loadTemplate('', 2);
        }
    }
    
    public function dodaj($dodaj = 0)
    {
        if ($dodaj != 0) {
            if($dodaj != Session::_get('id')){
                //sprawdzenie czy dany gracz jest już znajomymi
                $znajomi = $this->model->sprawdzCzyZnajomy($dodaj);
                //$znajomi = $db->sql_query('SELECT * FROM znajomi WHERE id_gracza = '.$user->__get('id').' AND kto = '.$dodaj);
                if(!$znajomi['rowCount']){//można wysłać zaproszenie
                    $gracz = $this->model->login($dodaj);
                    if($gracz['rowCount']){
                        $this->model->dodaj($dodaj);
                        $tresc = '<div class="text-center"><a href="znajomi.php">Gracz '.Session::_get('nick').' zaprosił Cię do znajomych.</a></div>';
                        $godzina = date('Y-m-d H:i:s');
                        $this->model->wiadomosc($dodaj, $tresc, $godzina);
                        $this->view->dodaj =  '<span class="zielony">Zaproszenie wysłane.</span>';
                    }else{
                        $this->view->dodaj =  '<span class="czerwony">Gracz nie znaleziony.</span>';
                    }
                }else{
                    $this->view->dodaj = '<span class="czerwony">Gracz dodany do znajomych lub zaproszenie już wysłane.</span>';
                }
            }else{
                $this->view->dodaj = '<span class="czerwony">Nie możesz dodać siebie do znajomych.</span>';
            }
        }else{
            $this->view->dodaj = '<span class="czerwony">Gracz nie znaleziony.</span>';
        }
        if(!isset($_GET['ajax'])){
            $this->index();
        }else{
            $this->view->render('znajomi/ajax');
        }
    }

    public function zaakceptuj($id = 0)
    {
        if($id != 0){
            //sprawdzenie czy takie zaproszenie istnieje
            $exe = $this->model->sprawdzZaproszenie($id);
            if($exe['rowCount']){//zaproszenie istnieje
                $this->model->zaakceptuj($id);
                $login = $this->model->login($id);
                $login = $login[0];
                $this->view->zaakceptuj = '<div class="alert alert-success"><span>Jesteś teraz znajomym z '.$login['login'].'</span></div>';
                $tresc = '<div class="text-center">Gracz '.Session::_get('nick').' zaakceptował Twoje zaproszenie do znajomych.</div>';
                $godzina = date('Y-m-d H:i:s');
                $tytul = Session::_get('nick').' zaakceptował Twoje zaproszenie.';
                $this->model->dodajWiadomoscZId($id, $tresc, $godzina, $tytul);
            }else{
                $this->view->zaakceptuj = '<div class="alert alert-danger"><span>Nie znaleziono zaproszenia</span></div>';
            }
        }else{
            $this->view->zaakceptuj = '<div class="alert alert-danger"><span>Nie znaleziono zaproszenia</span></div>';
        }
        if(!isset($_GET['ajax'])){
        $this->index();
        }else{
            $this->view->render('znajomi/ajax');
        }
    }
    
    public function odrzuc($id = 0)
    {
        if($id != 0){
            //sprawdzenie czy takie zaproszenie istnieje
            $exe = $this->model->sprawdzZaproszenie($id);
            if($exe['rowCount']){//zaproszenie istnieje
                $this->model->db->delete('znajomi', '(id_gracza = '.Session::_get('id').' AND kto = '.$id.') OR (id_gracza = '.$id.' AND kto = '
                        . Session::_get('id').')', 2);
                $login = $this->model->login($id);
                $login = $login[0];
                $this->view->odrzuc = '<div class="alert alert-success"><span>Odrzucono zaproszenie.</span></div>';
                $tresc = '<div class="text-center">Gracz '.Session::_get('nick').' odrzucił Twoje zaproszenie do znajomych.</div>';
                $godzina = date('Y-m-d H:i:s');
                $tytul = Session::_get('nick').' odrzucił zaproszenie';
                $this->model->dodajWiadomoscZId($id, $tresc, $godzina, $tytul);
            }else{
                $this->view->odrzuc = '<div class="alert alert-danger"><span>Nie znaleziono zaproszenia</span></div>';
            }
        }else{
            $this->view->odrzuc = '<div class="alert alert-danger"><span>Nie znaleziono zaproszenia</span></div>';
        }
        if(!isset($_GET['ajax'])){
            $this->index();
        }else{
            $this->view->render('znajomi/ajax');
        }
    }
    
    public function anuluj($id = 0)
    {
        if($id != 0){
            //sprawdzenie czy takie zaproszenie istnieje
            $exe = $this->model->sprawdzZaproszenie($id);
            if($exe['rowCount']){//zaproszenie istnieje
                $this->model->db->delete('znajomi', '(id_gracza = '.Session::_get('id').' AND kto = '.$id.') OR (id_gracza = '.$id.' AND kto = '
                        . Session::_get('id').')', 2);
                $login = $this->model->login($id);
                $login = $login[0];
                $this->view->anuluj = '<div class="alert alert-success"><span>Anulowano zaproszenie.</span></div>';
                $tresc = '<div class="text-center">Gracz '.Session::_get('nick').' anulował swoje zaproszenie do znajomych.</div>';
                $godzina = date('Y-m-d H:i:s');
                $tytul = Session::_get('nick').' anulował zaproszenie';
                $this->model->dodajWiadomoscZId($id, $tresc, $godzina, $tytul);
            }
            else $this->view->anuluj = '<div class="alert alert-danger"><span>Nie znaleziono zaproszenia</span></div>';
        }else{
            $this->view->anuluj = '<div class="alert alert-danger"><span>Nie znaleziono zaproszenia</span></div>';
        }
        if(!isset($_GET['ajax'])){
            $this->index();
        }else{
            $this->view->render('znajomi/ajax');
        }
    }
    
    public function usun($id = 0, $potwierdz = 0)
    {
        if($id != 0){
            $exe = $this->model->znajdzZnajomego($id);
            if($exe['rowCount']){//znajomy istnieje
                if(!$potwierdz){
                    $login = $this->model->login($id);
                    $login = $login[0];
                    $login = $login['login'];
                    $this->view->usun = '<div class="alert alert-warning text-center"><span>Czy na pewno chcesz usunąć gracza '.$login.' znajomych?</span></div>';
                    $this->view->usun .= '<div class="row row-centered"><button class="btn btn-primary tak" name="'.$id.'">TAK</button><button class="btn btn-primary nie">NIE</button></div>';
                }else{
                    $this->model->usunZnajomego($id);
                    $this->view->usun = '<div class="alert alert-success"><span>Usunięto znajomość</span></div>';
                    $tresc = '<div class="text-center">Gracz '.Session::_get('nick').' usunął Cię ze  znajomych.</div>';
                    $godzina = date('Y-m-d H:i:s');
                    $tytul = Session::_get('nick').' usunął Cię ze znajomych.';
                    $this->model->dodajWiadomoscZId($id, $tresc, $godzina, $tytul);
                }
            }else{
                $this->view->usun = '<div class="alert alert-danger"><span>Nie znaleziono znajomego!</span></div>';
            }
        }else{
            $this->view->usun = '<div class="alert alert-danger"><span>Nie znaleziono znajomego</span></div>';
        }
        if(!isset($_GET['ajax'])){
            $this->index();
        }else{
            $this->view->render('znajomi/ajax');
        }
    }
    
    private function znajomi($znajomi)
    {
        $this->view->iloscZnajomych = $znajomi['rowCount'];
        $kwer = 'SELECT login, id_sesji FROM uzytkownicy WHERE ID in( ';
        $kwer2 = 'order by case ID';
        $kwer_pok = 'SELECT pok1 FROM druzyna WHERE id_gracza in ( ';
        $kwer_pok2 = 'order by case id_gracza';
        for($i = 1 ; $i <= $znajomi['rowCount'] ; $i++){
            $id_g = $znajomi[$i-1];
            $id[$i] = $id_g['kto'];
            if($i == 1){$kwer = $kwer . " $id[$i] "; $kwer_pok = $kwer_pok . " $id[$i] ";}
            else {$kwer = $kwer . ", $id[$i] "; $kwer_pok = $kwer_pok . ", $id[$i] ";}
            {$kwer2 = $kwer2 . " WHEN $id[$i] THEN $i"; $kwer_pok2 = $kwer_pok2 . " WHEN $id[$i] THEN $i";}
        }
        $kwer = $kwer . ')' . $kwer2 . ' END';
        $kwer_pok = $kwer_pok . ')' . $kwer_pok2 . ' END';
        $nazwy = $this->model->db->select($kwer, []);
        $poki_karmienie = $this->model->db->select($kwer_pok, []);
        for($i = 1 ; $i <= $znajomi['rowCount'] ; $i++){
            $karmienie = $poki_karmienie[$i-1];
            $nazwa = $nazwy[$i-1];
            $this->view->znajomy[$i-1] = $nazwa;
            $this->view->znajomy[$i-1]['id'] = $id[$i];
            $this->view->znajomy[$i-1]['karmienie'] = $karmienie['pok1'];
        }
    }
    
    private function zaproszenia($znajomi)
    {
        $this->view->zaproszenia = 1;
        $kwer = 'SELECT login FROM uzytkownicy WHERE ID in( ';
        $kwer2 = 'order by case ID';
        for($i = 1 ; $i <= $znajomi['rowCount'] ; $i++){
            $id_g = $znajomi[$i-1];
            $id[$i] = $id_g['kto'];
            if($i == 1)$kwer = $kwer . " $id[$i] ";
            else $kwer = $kwer . ", $id[$i] ";
            $kwer2 = $kwer2 . " WHEN $id[$i] THEN $i";
        }
        $kwer = $kwer . ')' . $kwer2 . ' END';
        $nazwy = $this->model->db->select($kwer, []);
        for($i = 1 ; $i <= $znajomi['rowCount'] ; $i++){
            $this->view->zaproszenieDane[$i-1] = $nazwy[$i-1];
            $this->view->zaproszenieDane[$i-1]['id'] = $id[$i];
        }
    }
    
    private function wyslane($znajomi)
    {
        $this->view->wyslane = 1;
        $kwer = 'SELECT login FROM uzytkownicy WHERE ID in( ';
        $kwer2 = 'order by case ID';
        for($i = 1 ; $i <= $znajomi['rowCount'] ; $i++){
            $id_g = $znajomi[$i-1];
            $id[$i] = $id_g['kto'];
            if($i == 1)$kwer = $kwer . " $id[$i] ";
            else $kwer = $kwer . ", $id[$i] ";
            $kwer2 = $kwer2 . " WHEN $id[$i] THEN $i";
        }
        $kwer = $kwer . ')' . $kwer2 . ' END';
        $nazwy = $this->model->db->select($kwer);
        for($i = 1 ; $i <= $znajomi['rowCount'] ; $i++){
            $this->view->wyslaneDane[$i-1] = $nazwy[$i-1];
            $this->view->wyslaneDane[$i-1]['id'] = $id[$i];
        }
    }
}


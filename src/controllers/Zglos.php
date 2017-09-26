<?php

namespace src\controllers;

use src\libs\Controller;
use src\libs\Session;

class Zglos extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('Zgłoś błąd - '.NAME);
        }
    }

    public function index()
    {
        if (isset($_POST['opis'])) {
            $this->view->opis = $_POST['opis'];
        }
        if (isset($_POST['tytul'])) {
            $this->view->tytul = $_POST['opis'];
        }
        $this->view->render('zglos/index');
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('', 2);
        }
    }

    public function wyswietl(int $np = 0)
    {
        if ($np) {
            if(Session::_get('admin'))  $rezultat = $this->model->bledyAdmin();
            else $rezultat = $this->model->bledyUser();
        } else {
            if(Session::_get('admin')) $rezultat = $this->model->bledyAdminWszystkie();
            else $rezultat = $this->model->bledyUserWszystkie();
        }
        $ile = $rezultat['rowCount'];
        $this->view->ile = $ile;
        if ($ile) {
            for ($i = 0 ; $i < $ile ; $i++) {
                $this->view->blad[$i] = $rezultat[$i];
                $this->view->blad[$i]['admin'] = Session::_get('admin');
            }
        }
        $this->view->render('zglos/wyswietl');
    }

    public function zglos()
    {
        if (!isset($_POST['tytul']) || $_POST['tytul'] == '') {
            $this->view->blad = 'Błędny tytuł';
            $this->index();
            return;
        }
        if (!isset($_POST['opis']) || $_POST['opis'] == '') {
            $this->view->blad = 'Błędny opis';
            $this->index();
            return;
        }
        $tytul = $_POST['tytul'];
        $opis = $_POST['opis'];
        $godzina = date('Y-m-d-H-i-s');
        $zgl = Session::_get('id');
        $tab = array();
        $tab['tytul'] = $tytul;
        $tab['opis'] = $opis;
        $tab['zgloszony'] = $zgl;
        $tab['data'] = $godzina;
        $this->model->dodajBlad($tytul, $opis, $zgl, $godzina);
        $this->view->komunikat = 'Zgłoszono błąd.';
        $this->index();
    }

    public function popraw(int $id)
    {
        if(!Session::_get('admin')) {
            $this->view->blad = 'Brak uprawnień.';
            $this->index();
            return;
        }

        $rez = $this->model->bladIdNiePoprawiony($id);
        if(!$rez['rowCount']) {
            $this->view->blad = 'Bład już poprawiony lub zły ID.';
            $this->index();
            return;
        }
        $rez = $rez[0];
        $godzina = date('Y-m-d-H-i-s');
        $this->model->poprawBlad($rez['zgloszony'], $rez['tytul'], $godzina, $id);
        $this->view->komunikat = 'Poprawiono błąd.';
        $this->index();
    }

    public function usun(int $id)
    {
        if(!Session::_get('admin')) {
            $this->view->blad = 'Brak uprawnień.';
            $this->index();
            return;
        }
        $rez = $this->model->bladId($id);
        if(!$rez['rowCount']) {
            $this->view->blad = 'Bład już usunięty lub zły ID.';
            $this->index();
            return;
        }
        $rez = $rez[0];
        $godzina = date('Y-m-d-H-i-s');
        $this->model->usunBlad($rez['zgloszony'], $rez['tytul'], $godzina, $id);
        $this->view->komunikat = 'Błąd został usunięty';
        $this->index();
    }
}
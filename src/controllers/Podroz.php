<?php

namespace src\controllers;

use src\libs\Controller;
use src\libs\Session;

class Podroz extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('Podróż - '.NAME);
        }
    }

    public function index()
    {
        $this->view->region = Session::_get('region');
        $this->view->render('podroz/index');
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('', 2);
        }
    }

    public function region(int $region = 0)
    {
        if (!$region || !in_array($region, [1, 2])) {
            $this->index();
            return;
        }
        if ($region == Session::_get('region')) {
            $this->view->blad = 'Nie możesz podóżować do tego regionu.';
            $this->index();
            return;
        }

        $podroz = $this->model->db->select('SELECT podroz FROM uzytkownicy WHERE ID= :id', ['id' => Session::_get('id')]);
        $podroz = $podroz[0];
        if ($podroz['podroz']) {
            $this->view->blad = 'Możesz odbyć jedną podróż dziennie.';
            $this->index();
            return;
        }

        if (Session::_get('kasa') < 250000) {
            $this->view->blad = 'Nie masz pieniędzy na podróż.';
            $this->index();
            return;
        }

        Session::_set('kasa', ( Session::_get('kasa') - 250000 ) );
        Session::_set('region', $region);
        $this->model->db->update('UPDATE uzytkownicy SET pieniadze = ?, podroz = 1, region = ? WHERE ID = ?', [
            Session::_get('kasa'),
            $region,
            Session::_get('id')
        ]);
        $podroz = [ 1=> 'Kanto', 2=> 'Johto' ];
        $this->view->regionNazwa = $podroz[$region];

        $this->view->render('podroz/komunikat');
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('', 2);
        }
    }
}
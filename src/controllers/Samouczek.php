<?php

namespace src\controllers;

use src\libs\Controller;
use src\libs\Session;

class Samouczek extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (Session::_get('samouczek') > ILOSC_SAMOUCZEK) {
            header('Location: '.URL.'gra');
            exit;
        }
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('Samouczek - '.NAME);
        }
    }

    public function index()
    {
        $this->view->samouczek = $this->switchSamouczek();
        $this->view->render('samouczek/index');
    }

    public function wybor(int $co,  $wybor = '')
    {
        switch ($co) {
            case 1:
                $this->wyborStylu($wybor);
                break;
        }
        $this->index();
    }

    private function wyborStylu($wybor)
    {
        if(!$wybor) {
            $this->view->blad =  'Błąd, wybrano zły styl';
            return;
        }
        $baza = 0;
        switch ($wybor) {
            case 'white':
                $baza = 1;
                break;
            case 'black':
                $baza = 2;
                break;
            default:
                $this->view->blad = 'Błąd, wybrano zły styl';
                return;
        }
        Session::_set('style', $baza);
        Session::_set('samouczek', Session::_get('samouczek') + 1);
        $this->model->styl($baza);
        $this->view->komunikat = 'Poprawnnie zmieniono styl.';
    }

    /**
     * @return string what to show
     */
    private function switchSamouczek()
    {
        switch (Session::_get('samouczek')) {
            case 1:
                $show = 'WYBIERZ JAKI STYL MA MIEĆ GRA. automatyczny podgląd:<div class="btn-group">';
                $show .= '<button type="button" id="styl=black" class="btn btn-primary black';
                $show .= '">CIEMNY</button>';
                $show .= '<button type="button" id="styl=white" class="btn btn-primary white';
                $show .= '">BIAŁY</button></div>';
                $show .= '<button class="btn btn-primary potwierdz1">POTWIERDŹ</button>';
                break;
            default:
                $show = '';
        }
        return $show;
    }
}
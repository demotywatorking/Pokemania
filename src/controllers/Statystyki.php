<?php
namespace src\controllers;

use src\libs\Controller;

class Statystyki extends Controller
{

    function __construct() 
    {
        parent::__construct();
        if(!isset($_GET['ajax'])){
            $this->loadTemplate('Statystyki ogólne - '.NAME);
        }
    }
    
    public function index()
    {
        $this->statystyki = $this->model->statystyki();
        $this->statystyki = $this->statystyki[0];
        $this->wyprawy();
        $this->lapanie();
        $this->pojedynki();
        $this->inne();
        $this->konto();
        $this->view->render('statystyki/index');
        if(!isset($_GET['ajax'])){
            $this->loadTemplate('', 2);
        }
    }
    
    private function wyprawy()
    {
       $this->view->wyprawyPolana = $this->statystyki['polana'];
       $this->view->wyprawyWyspa = $this->statystyki['wyspa'];
       $this->view->wyprawyGrota = $this->statystyki['grota'];
       $this->view->wyprawyDomStrachow = $this->statystyki['dom_strachow'];
       $this->view->wyprawyGory = $this->statystyki['gory'];
       $this->view->wyprawyWodospad = $this->statystyki['wodospad'];
       $this->view->wyprawySafari = $this->statystyki['safari'];
    }
    
    private function lapanie()
    {
        $this->view->shiny = $this->statystyki['shiny'];
        $this->view->zlapanePokemony = $this->statystyki['zlapane_poki'];
        $this->view->zlapanePokeball = $this->statystyki['zl_pokeball'];
        $this->view->zlapaneNestball = $this->statystyki['zl_nestball'];
        $this->view->zlapaneGreatball = $this->statystyki['zl_greatball'];
        $this->view->zlapaneUltraball = $this->statystyki['zl_ultraball'];
        $this->view->zlapaneDuskball = $this->statystyki['zl_duskball'];
        $this->view->zlapaneLureball = $this->statystyki['zl_lureball'];
        $this->view->zlapaneCherishball = $this->statystyki['zl_cherishball'];
        $this->view->zlapaneRepeatball = $this->statystyki['zl_repeatball'];
    }
    
    private function pojedynki()
    {
        $this->view->pokonanychTrenerow = $this->statystyki['pokonanych_trenerow'];
        $this->view->pokonanychPokemonow = $this->statystyki['pokonane_poki'];
    }
    
    private function inne()
    {
        $this->view->zebranychJagod = $this->statystyki['zebrane_jagody'];
        $this->view->zjedzonychPrzysmakow = $this->statystyki['przysmaki'];
        $this->view->treningi = $this->statystyki['treningi'];
    }
    
    private function konto()
    {
        $w1 = $this->model->online();
        $w1 = $w1[0];
        $this->view->online = $this->online($w1['online']);
        $this->view->onlineDzisiaj = $this->online($w1['online_dzisiaj']);
        $this->view->jagodyMpa = $w1['jagody_pa'];
    }
    
    private function online(int $ost):string
    {
        $return = '';
        if($ost < 60){
            $return .= $ost.' sekund.';
        }elseif($ost < 3600){
            $min = floor($ost / 60);
            $sek = $ost - 60*$min;
           $return .=  $min.' minut '.$sek.' sekund.';
        }else{
            $dni = 0;
            if($ost > 86400){
                $dni = floor($ost / 86400);
                $ost -= $dni * 86400;
            }
            $godz = floor($ost / 3600);
            $ost -= $godz * 3600;
            $min = floor($ost / 60);
            $sek = $ost - 60*$min;
            if($dni > 0){
                $return .=  $dni.' dni ';
            }
            $return .=  $godz.' godzin '.$min.' minut '.$sek.' sekund.';
        }
        return $return;
    }

}


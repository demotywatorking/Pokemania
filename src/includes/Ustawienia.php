<?php

namespace src\includes;

class Ustawienia 
{
    private $druzyna;
    private $targ;
    private $zegar;
    private $tooltip;
    private $leczenie;
    private $soda;
    private $woda;
    private $lemoniada;
    private $cheri;
    private $wiki;
    private $podpowiedz;
    private $panele;
    private $tlo;
    private $tabelka;
    private $nakarm;
  
    public function __construct($u)
    {
        $this->druzyna = $u[0];
        $this->targ = $u[1];
        $this->zegar = $u[2];
        $this->tooltip = $u[3];
        $this->leczenie = $u[4];
        $this->soda = $u[5];
        $this->woda = $u[6];
        $this->lemoniada = $u[7];
        $this->cheri = $u[8];
        $this->wiki = $u[9];         
        $this->podpowiedz = $u[10];
        $this->panele = $u[11];
        $this->nakarm = $u[12];
        $this->tlo = $u[13];        
        $this->tabelka = $u[14];
    }
    public function get($name)
    {
        return $this->$name;
    }
    public function edit($name, $ile)
    {
        $this->$name = $ile;
    }
    public function get_all()
    {
        $get = $this->druzyna . '|' . $this->targ . '|' . $this->zegar . '|' . $this->tooltip . '|' . $this->leczenie . '|' . $this->soda . '|' . $this->woda 
                . '|' . $this->lemoniada . '|' . $this->cheri . '|' . $this->wiki . '|' . $this->podpowiedz . '|' . $this->panele . '|' . $this->nakarm .'|' . $this->tlo . '|' . $this->tabelka;
        return $get;
    }
}
?>
<?php

namespace src\includes;

class PokemonWalka
{
    public $i2;
    public $plec;
    public $fury;
    public $pulapka;
    public $fury_t;
    public $atak_runda_jeden;
    public $stan;
    public $runda;
    public $atak_runda;
    public $shiny;
    public $nazwa;
    public $id_poka;
    public $atak;
    public $sp_atak;
    public $obrona;
    public $sp_obrona;
    public $szybkosc;
    public $max_hp;
    public $hp;
    public $typ1;
    public $typ2;
    public $poziom;
    public $celnosc;
    public $pocz_HP;
    public $nietykalnosc;
    public $id;
    public $ataki = array();
    private $ilosc_atakow;
    public $odp = array();
    
    public function __construct($id = 0)
    {
        $this->atak_runda_jeden = 0;
        $this->ilosc_atakow = 0;
        $this->id = $id;
    }

    public function ustaw_atak($atak, $atak_sp, $id)
    {
        $this->ilosc_atakow++;
        $this->ataki[$this->ilosc_atakow] = $atak;
        $this->ataki[$this->ilosc_atakow]['ID'] = $id;
        if($atak_sp != '')
            $this->ataki[$this->ilosc_atakow] = array_merge($this->ataki[$this->ilosc_atakow], $atak_sp);
        else throw new \Exception('Błąd z atakiem.');
    }

    public function odpornosci($odpornosci)
    {
        if($this->typ2)
            for($abcd = 1 ; $abcd < 19 ; $abcd++)
              $this->odp[$abcd] = $odpornosci[$this->typ1]['typ'.$abcd] * $odpornosci[$this->typ2]['typ'.$abcd];
        else
          for($abcd = 1 ; $abcd < 19 ; $abcd++)
            $this->odp[$abcd] = $odpornosci[$this->typ1]['typ'.$abcd];
    }
}

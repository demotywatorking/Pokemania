<?php

namespace src\includes;

class Pokemon 
{
    private $id;
    private $dos;
    private $lvl;
    private $id_p;
    private $imie;
    private $zycie;
    private $ewo;
    private $akt_zycie;
    private $shiny;
    private $dos_p;
    private $imie_z;
    private $ewo_p;
    private $plec;
    private $glod;
    private $jakosc;
    
    public function __construct($u) 
    {
        $this->id = $u[0];
        $this->dos = $u[1];
        $this->lvl = $u[2];
        $this->id_p = $u[3];
        $this->imie = $u[4];
        $this->zycie = $u[5];
        $this->ewo = $u[6];
        $this->akt_zycie = $u[7];
        $this->shiny = $u[8];
        $this->dos_p = $u[9];
        $this->imie_z = $u[10];
        $this->ewo_p = $u[11];
        $this->plec = $u[12];
        $this->glod = $u[13];
        $this->jakosc = $u[14];
    }
    public function get($what)
    {
        return $this->$what;
    }
    public function edit($what, $ile)
    {
        $this->$what = $ile;
    }
    public function get_all()
    {
        $get = $this->id . '|' . $this->dos . '|' . $this->lvl . '|' . $this->id_p . '|' . $this->imie . '|' . $this->zycie . '|' . $this->ewo 
                . '|' . $this->akt_zycie . '|' . $this->shiny . '|' . $this->dos_p . '|' . $this->imie_z . '|' . $this->ewo_p . '|' . $this->plec . '|' . $this->glod . '|' . $this->jakosc;
        return $get;
    }
}
?>
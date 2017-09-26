<?php

namespace src\includes;

class Przedmioty
{
    private $leczenia;
    private $apteczka;
    private $pokedex;
    private $lopata;
  
    public function __construct($u)
    {       
        $this->leczenia = $u[0]; 
        $this->apteczka = $u[1]; 
        $this->pokedex = $u[2]; 
        $this->lopata = $u[3];
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
        return $this->leczenia . '|' . $this->apteczka . '|' . $this->pokedex . '|' . $this->lopata;
    }
}
<?php

namespace src\includes;

class Statystyki 
{
    public $kanto;

    public $zlapanych;

    public function __construct($u)
    {
        for($i = 0 ; $i < 8 ; $i++)
            $this->kanto[$i+1] = $u[$i];
        $this->zlapanych = $u[8]; 
    }
}

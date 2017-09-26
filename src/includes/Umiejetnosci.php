<?php

namespace src\includes;

class Umiejetnosci
{
    private $lapanie;

    public function __construct($u)
    {
        $this->lapanie = $u[0];
    }

    public function get($u)
    {
        return $this->$u;
    }

    public function edit($u, $war)
    {
        $this->$u = $war;
    }

    public function get_all()
    {
        $u = $this->lapanie;
        return $u;
    }
}
?>
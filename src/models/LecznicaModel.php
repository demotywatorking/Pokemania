<?php

namespace src\models;

use src\libs\Model;
use src\libs\Session;

class LecznicaModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function darmoweLeczenie()
    {
        $this->db->update('UPDATE aktywnosc SET darmowe_leczenia = (darmowe_leczenia - 1) WHERE id_gracza = ?', [Session::_get('id')]);
    }

    public function leczenieZaPieniadze($koszt)
    {
        $this->db->update('UPDATE uzytkownicy SET pieniadze = (pieniadze - ?) WHERE ID = ?', [$koszt, Session::_get('id')]);
    }

    public function wyleczPokemon($id)
    {
        $this->db->update('UPDATE pokemony SET akt_HP = (round(jakosc * HP / 100) + Jag_HP + tr_6 * 5) WHERE ID = ?', [$id]);

    }

}

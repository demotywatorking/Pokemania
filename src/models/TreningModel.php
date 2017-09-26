<?php

namespace src\models;

use src\libs\Model;
use src\libs\Session;

class TreningModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function aktywnosc()
    {
        return $this->db->select('SELECT * FROM aktywnosc WHERE id_gracza = :id', [':id' => Session::_get('id')]);
    }

    public function updateAktywnosc($czas)
    {
        $this->db->update('UPDATE aktywnosc SET czas = ?, aktywnosc = \'trening\' WHERE id_gracza = ?', [$czas, Session::_get('id')]);
    }

    public function czas()
    {
        return $this->db->select('SELECT czas FROM aktywnosc WHERE id_gracza = :id', [':id' => Session::_get('id')]);
    }

    public function aktywnoscBrak()
    {
        $this->db->update('UPDATE aktywnosc SET czas = 0, aktywnosc = \'\' WHERE id_gracza = ?', [Session::_get('id')]);
    }

    public function updatePokiDruzyna($exp)
    {
        $this->db->update('UPDATE pokemony SET exp = (exp + ?) WHERE wlasciciel = ? AND druzyna = 1', [$exp, Session::_get('id')]);
    }

}


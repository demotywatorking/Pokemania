<?php

namespace src\models;

use src\libs\Model;
use src\libs\Session;

class KupiecModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function pokiMozliweDoSprzedania()
    {
        return $this->db->select('SELECT ID, wartosc FROM pokemony WHERE wlasciciel = :id AND druzyna = 0 AND blokada = 0 AND targ = 0',
            [':id' => Session::_get('id')]);
    }

    public function dodajPieniadze($wartosc)
    {
        $this->db->update("UPDATE uzytkownicy SET pieniadze = (pieniadze + ?) WHERE ID = ?", [$wartosc, Session::_get('id')]);
    }

    public function wszystkieBezShiny()
    {
        return $this->db->select('SELECT wartosc, ID FROM pokemony WHERE wlasciciel = :id AND druzyna = 0 AND blokada = 0 AND targ = 0 AND shiny = 0',
                [':id' => Session::_get('id')]);
    }

    public function usunWszystkie($ile)
    {
        $this->db->delete('DELETE FROM pokemony WHERE wlasciciel = ? AND druzyna = 0 AND blokada = 0 AND targ = 0 AND shiny = 0',
            [Session::_get('id')], $ile);
    }

    public function doSprzedania()
    {
        return $this->db->select('SELECT ID, id_poka, imie, wartosc, shiny, poziom FROM pokemony WHERE wlasciciel = :id 
                AND druzyna = 0 AND blokada = 0 AND targ = 0', [':id' => Session::_get('id')]);
    }

}
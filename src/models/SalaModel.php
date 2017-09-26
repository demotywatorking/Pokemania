<?php

namespace src\models;

use src\libs\Model;
use src\libs\Session;

class SalaModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function pokemonTrening($id)
    {
        return $this->db->select('SELECT pokemony.przywiazanie, pokemon_jagody.tr_1, pokemon_jagody.tr_2, pokemon_jagody.tr_3, 
                    pokemon_jagody.tr_4, pokemon_jagody.tr_5, pokemony.tr_6 FROM pokemon_jagody, pokemony 
                    WHERE pokemon_jagody.id_poka = :id AND pokemony.ID = pokemon_jagody.id_poka', [':id' => $id]);
    }

    public function pokemonNauczAtak($nr, $atak, $id)
    {
        $this->db->update("UPDATE pokemony SET atak$nr = ? WHERE wlasciciel = ? AND ID = ?", [$atak, Session::_get('id'), $id]);
    }

    public function pokemonAtaki($id)
    {
        return $this->db->select('SELECT id_poka, atak1, atak2, atak3, atak4 FROM pokemony WHERE ID= :id', [':id' => $id]);
    }

    public function treningKoszt($koszt)
    {
        $this->db->update('UPDATE uzytkownicy SET pieniadze = (pieniadze - ?) WHERE ID = ?', [$koszt, Session::_get('id')]);
    }

    public function osiagniecie($ile)
    {
        $this->db->update('UPDATE osiagniecia SET treningi = (treningi - ?) WHERE ID = ?', [$ile, Session::_get('id')]);
    }

    public function trening($co, $ile, $id, $wartosc, $exp, $przyw)
    {
        $this->db->update("UPDATE pokemon_jagody SET tr_$co = (tr_$co + ?) WHERE id_poka = ?", [$ile, $id]);
        $this->db->update("UPDATE pokemony SET wartosc = (wartosc + ?), exp = (exp + ?), przywiazanie = (przywiazanie + ?) WHERE ID = ?",
            [$wartosc, $exp, $przyw, $id]);
    }

    public function treningHP($co, $wyt, $wartosc, $exp, $przyw, $id)
    {
        $this->db->update("UPDATE pokemony SET tr_$co = (tr_$co + ?), wartosc = (wartosc + ?), exp = (exp + ?), przywiazanie = (przywiazanie + ?) WHERE ID = ?",
                [$wyt, $wartosc, $exp, $przyw, $id]);
    }
}


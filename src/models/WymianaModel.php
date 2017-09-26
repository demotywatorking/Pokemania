<?php

namespace src\models;

use src\libs\Model;
use src\libs\Session;

class WymianaModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function wymiana()
    {
        return $this->db->select('SELECT * FROM wymiana WHERE id_gracza = :id', [':id' => Session::_get('id')]);
    }

    public function kamienie()
    {
        return $this->db->select('SELECT * FROM kamienie WHERE id_gracza = :id', [':id' => Session::_get('id')]);
    }

    public function pokemonWymiana($id)
    {
        return $this->db->select('SELECT * FROM pokemony WHERE ID = :idP AND druzyna = 1 AND wymiana = 0 AND wlasciciel = :id',
            [':idP' => $id, ':id' => Session::_get('id')]);
    }

    public function dodajPoka($kamien, $id, $czas)
    {
        $this->db->update("UPDATE kamienie SET $kamien = ($kamien - 1), runa = (runa - 1) WHERE id_gracza = ?", [Session::_get('id')]);
        $this->db->insert('INSERT INTO wymiana VALUES (NULL, ?, ?, ?)', [$id, Session::_get('id'), $czas]);
    }

    public function druzyna()
    {
        return $this->db->select('SELECT * FROM druzyna WHERE id_gracza = :id', [':id' => Session::_get('id')]);
    }

    public function zmienPokemon($id)
    {
        $this->db->update('UPDATE pokemony SET druzyna = 0, blokada = 1, wymiana = 1 WHERE ID = ?', [$id]);
    }

    public function wymianaCzas($id)
    {
        return $this->db->select('SELECT * FROM wymiana WHERE id_poka = :idP AND id_gracza = :id AND czas <= :czas',
            [':idP' => $id, ':id' => Session::_get('id'), ':czas' => time()]);
    }

    public function pokemon($id)
    {
        return $this->db->select('SELECT * FROM pokemony WHERE ID = :idP AND wymiana = 1 AND wlasciciel = :id',
            [':idP' => $id, ':id' => Session::_get('id')]);
    }

    public function usunWymiana($id)
    {
        $this->db->delete('DELETE FROM wymiana WHERE id_poka = ? AND id_gracza = ?',
            [$id, Session::_get('id')], 100);
    }

    public function kolekcja($s, $z)
    {
        $this->db->update("UPDATE kolekcja SET $s = ($s + 1), $z = ($z + 1) WHERE ID = ?",
            [Session::_get('id')]);
    }
}

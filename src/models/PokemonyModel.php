<?php

namespace src\models;

use src\libs\Model;
use src\libs\Session;

class PokemonyModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function pokemonyTarg()
    {
        return $this->db->select('SELECT * FROM pokemony, pokemon WHERE pokemony.wlasciciel = :id
                          AND pokemony.druzyna=0 AND pokemony.targ = 1 AND pokemon.id_poka = pokemony.id_poka ORDER BY data_zlapania DESC',
                            [':id' => Session::_get('id')]);
    }

    public function pokemonyPoczekalnia()
    {
        return $this->db->select('SELECT * FROM pokemony, pokemon WHERE pokemony.wymiana = 0 AND pokemony.wlasciciel = :id
                     AND pokemony.druzyna = 0 AND pokemony.blokada = 0 AND pokemony.targ = 0 AND pokemon.id_poka = pokemony.id_poka ORDER BY pokemony.ID DESC',
                        [':id' => Session::_get('id')]);
    }

    public function pokemonyRezerwa()
    {
        return $this->db->select('SELECT * FROM pokemony, pokemon WHERE pokemony.wymiana = 0 AND pokemony.wlasciciel = :id
         AND pokemony.druzyna = 0 AND pokemony.blokada = 1 AND pokemony.targ = 0 AND pokemon.id_poka = pokemony.id_poka ORDER BY pokemony.ID DESC', [':id' => Session::_get('id')]);
    }

    public function pokemonDown($i1, $id1, $i2, $id2)
    {
        $this->db->update("UPDATE druzyna SET pok$i1 = $id1, pok$i2 = $id2 WHERE id_gracza = ?", [Session::_get('id')]);
    }

    public function pokemonUp($i2, $id1, $i1, $id2)
    {
        $this->db->update("UPDATE druzyna SET pok$i2 = $id1, pok$i1 = $id2 WHERE id_gracza = ?", [Session::_get('id')]);
    }

    public function druzynaIle()
    {
        return $this->db->select('SELECT ile FROM druzyna WHERE id_gracza = :id', [':id' => Session::_get('id')]);
    }

    public function rezerwaID()
    {
        return $this->db->select('SELECT ID FROM pokemony WHERE druzyna = 0 AND targ = 0 AND wlasciciel = :idW', [':idW' => Session::_get('id')]);
    }

    public function pokemonDane($id)
    {
        return $this->db->select('SELECT * FROM pokemony WHERE ID = :id', [':id' => $id]);
    }

    public function pokemonyDruzyna()
    {
        return $this->db->select('SELECT * FROM druzyna WHERE id_gracza = :id', [':id' => Session::_get('id')]);
    }

    public function pokemonDoRezerwy($id)
    {
        $this->db->update('UPDATE pokemony SET druzyna = 0, blokada = 1 WHERE ID = ?', [$id]);
    }

    public function pokiIDZRezerwy()
    {
        return $this->db->select('SELECT ID FROM pokemony WHERE blokada = 0 AND druzyna = 0 AND targ = 0 AND wlasciciel = :id',
            [':id' => Session::_get('id')]);
    }

    public function pokiIDZPoczekalni()
    {
        return $this->db->select('SELECT ID FROM pokemony WHERE blokada = 1 AND druzyna = 0 AND targ = 0 AND wlasciciel = :id',
            [':id' => Session::_get('id')]);
    }
}
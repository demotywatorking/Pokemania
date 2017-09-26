<?php

namespace src\models;

use src\libs\Model;
use src\libs\Session;

class SaleModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function kolekcja()
    {
        return $this->db->select('SELECT * FROM kolekcja WHERE ID = :id', [':id' => Session::_get('id')]);
    }

    public function sale()
    {
        return $this->db->select('SELECT * FROM sale_pokemon WHERE id_gracza = :id', [':id' => Session::_get('id')]);
    }

    public function paZaWalke()
    {
        $this->db->update('UPDATE uzytkownicy SET pa = (pa - 50) WHERE ID = ?', [Session::_get('id')]);
    }

    public function avatarLogin()
    {
        return $this->db->select('SELECT avatar, login FROM uzytkownicy WHERE ID= :id', [':id' => Session::_get('id')]);
    }

    public function pokemonDoWalki($id)
    {
        return $this->db->select('SELECT * FROM pokemony, pokemon, pokemon_jagody 
                                        WHERE pokemon_jagody.id_poka = pokemony.ID AND pokemony.id_poka = pokemon.id_poka AND pokemony.ID = :id',
            [':id' => $id]);
    }

    public function dodajDoSali($numer, $data)
    {
        $this->db->update("UPDATE sale_pokemon SET $numer = ? WHERE id_gracza = ?", [$data, Session::_get('id')]);
    }

    public function expZaPorazke()
    {
        $this->db->update('UPDATE uzytkownicy SET doswiadczenie = (doswiadczenie + 3) WHERE ID = ?', [Session::_get('id')]);
    }
}
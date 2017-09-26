<?php

namespace src\models;

use src\libs\Model;
use src\libs\Session;

class WymienModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function pokDoWymiany($pok)
    {
        return $this->db->select('SELECT * FROM wymien WHERE id_gracza = :id AND ID = :idP AND czas <= ' . time(),
                [':id' => Session::_get('id'), ':idP' => $pok]);
    }

    public function usunZWymiany($pok)
    {
        $this->db->delete('DELETE FROM wymien WHERE id_gracza = ? AND ID = ?', [Session::_get('id'), $pok], 1000);
    }

    public function wszystkiePokiWWymianie()
    {
        return $this->db->select('SELECT * FROM wymien WHERE id_gracza = :id', [':id' => Session::_get('id')]);
    }

    public function przedmiotyDoWymiany()
    {
        return $this->db->select('SELECT czesci, monety FROM przedmioty WHERE id_gracza = :id', [':id' => Session::_get('id')]);
    }

    public function masterball()
    {
        $this->db->update('UPDATE pokeballe SET masterballe = ( masterballe + 1 ) WHERE id_gracza = ?', [Session::_get('id')]);
    }

    public function monetyWymien($minus)
    {
        $this->db->update('UPDATE przedmioty SET monety = ( monety - ? ) WHERE id_gracza = ?', [$minus, Session::_get('id')]);
    }

    public function monetyPlusPrzedmiot($co, $minus)
    {
        $this->db->update("UPDATE przedmioty SET monety = ( monety - ? ), $co = ($co + 1) WHERE id_gracza = ?",
                [$minus, Session::_get('id')]);
    }

    public function dodajPokaKolekcja($id, $limit, $poke)
    {
        $this->db->insert('INSERT INTO pokemon_jagody (id_poka, Jag_Limit) VALUES (?, ?)', [$id, $limit]);
        $s = $poke . 's';
        $z = $poke . 'z';
        $this->db->update("UPDATE kolekcja SET $s = ($s +1), $z = ($z + 1) WHERE ID = ?", [Session::_get('id')]);
    }

    public function wymienSkamielina($wymien, $czesci)
    {
        $this->db->insert('INSERT INTO wymien VALUES (NULL, ?, ?, ?)', [Session::_get('id'), $wymien, (time() + 3600 * 24)]);
        $this->db->update('UPDATE przedmioty SET czesci = (czesci - ?) WHERE id_gracza = ?', [$czesci, Session::_get('id')]);
    }
}
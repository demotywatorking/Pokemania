<?php

namespace src\models;

use src\libs\Model;
use src\libs\Session;

class ZnajomiModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function sprawdzZaproszenie(int $id)
    {
        return $this->db->select('SELECT * FROM znajomi WHERE zaproszenie = 1 AND id_gracza = :id AND kto = :idT',
            [':id' => $id, ':idT' => Session::_get('id')]);
    }

    public function znajomi()
    {
        return $this->db->select('SELECT * FROM znajomi WHERE zaproszenie = 0 AND id_gracza = :id',
            [':id' => Session::_get('id')]);
    }

    public function zaproszenia()
    {
        return $this->db->select('SELECT * FROM znajomi WHERE zaproszenie = 1 AND akceptacja = 0 AND id_gracza = :idT',
            [':idT' => Session::_get('id')]);
    }

    public function wyslane()
    {
        return $this->db->select('SELECT * FROM znajomi WHERE zaproszenie = 1 AND akceptacja = 1 AND id_gracza = :idT',
            [':idT' => Session::_get('id')]);
    }

    public function znajdzZnajomego($id)
    {
        return $this->db->select('SELECT * FROM znajomi WHERE zaproszenie = 0 AND akceptacja = 1 AND id_gracza = :id AND kto = :idT',
            [':id' => $id, ':idT' => Session::_get('id')]);
    }

    public function usunZnajomego($id)
    {
        $this->db->delete('DELETE FROM znajomi WHERE zaproszenie = 0 AND akceptacja = 1 AND (id_gracza = ? AND kto = ?) OR (id_gracza = ? AND kto = ?)',
            [Session::_get('id'), $id, $id, Session::_get('id')], 2);
    }

    public function sprawdzCzyZnajomy($dodaj)
    {
        return $this->db->select("SELECT * FROM znajomi WHERE id_gracza = :id AND kto = :kto", [':id' => Session::_get('id'),':kto' => $dodaj]);
    }

    public function login($dodaj)
    {
        return $this->db->select('SELECT login FROM uzytkownicy WHERE ID = :id', [':id' => $dodaj]);
    }

    public function dodaj($dodaj)
    {
        $this->db->insert('INSERT INTO znajomi VALUES ( ?, 1, ?, 1)'
            . ', (?, 1, ?, 0)', [Session::_get('id'), $dodaj, $dodaj, Session::_get('id')]);
    }

    public function wiadomosc($dodaj, $tresc, $godzina)
    {
        $this->db->insert("INSERT INTO poczta VALUES ('NULL', ?, ?, ?, 0, 'Nowe zaproszenie do znajomych')", [$dodaj, $tresc, $godzina]);
    }

    public function zaakceptuj($id)
    {
        $this->db->update("UPDATE znajomi SET zaproszenie = 0, akceptacja = 1 WHERE (id_gracza = ? AND kto = ?) "
            . "OR (id_gracza = ? AND kto = ?)", [Session::_get('id'), $id, $id, Session::_get('id')]);
    }

    public function dodajWiadomoscZId($id, $tresc, $godzina, $tytul)
    {
        $this->db->insert("INSERT INTO poczta VALUES ('NULL', ?, ?, ?, 0, ?)", [$id, $tresc, $godzina, $tytul]);
    }
}
<?php

namespace src\models;

use src\libs\Model;
use src\libs\Session;

class WiadomosciModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function oznaczPrzeczytane()
    {
        $this->db->update('UPDATE wiadomosci SET odczytana = 1 WHERE id_twoj = ? AND odczytana = 0', [Session::_get('id')]);
    }

    public function login($id)
    {
        return $this->db->select('SELECT login FROM uzytkownicy WHERE ID = :id',
            [':id' => $id]);
    }

    public function pobierzWiadomosci()
    {
        return $this->db->select('SELECT * FROM wiadomosci WHERE id_twoj = :id ORDER BY data_ost DESC', [':id' => Session::_get('id')]);
    }

    public function idUzytkownika($odbiorca)
    {
        return $this->db->select('SELECT ID FROM uzytkownicy WHERE login = :login', [':login' => $odbiorca]);
    }

    public function czyWiadomoscIstnieje($id)
    {
        return $this->db->select('SELECT * FROM wiadomosci WHERE id_twoj = :id AND id_nadawca = :nad',
            [':id' => Session::_get('id'), ':nad' => $id]);
    }

    public function dodajDoWiadomosci($tresc1, $tresc2, $godzina, $id)
    {
        $this->db->update('UPDATE wiadomosci SET tresc = CONCAT(tresc, ?), data_ost = ?, odczytana = 1
              WHERE id_twoj = ? AND id_nadawca = ?', [$tresc1, $godzina, Session::_get('id'), $id]);
        $this->db->update('UPDATE wiadomosci SET tresc = CONCAT(tresc, ?), data_ost = ?, odczytana = 0
              WHERE id_twoj = ? AND id_nadawca = ?', [$tresc2, $godzina, $id, Session::_get('id')]);
    }

    public function dodajWiadomosc($id, $tresc1, $godzina, $tresc2)
    {
        $this->db->insert('INSERT INTO wiadomosci (id_twoj, id_nadawca, tresc, data_ost, odczytana) VALUES
              (?, ?, ?, ?, 1)', [Session::_get('id'), $id, $tresc1, $godzina]);
        $id1 = $this->db->lastInsertId();
        $this->db->insert('INSERT INTO wiadomosci (id_twoj, id_nadawca, tresc, data_ost, odczytana, id2) VALUES
              (?, ?, ?, ?, 0, ?)', [$id, Session::_get('id'), $tresc2, $godzina, $id1]);
        $id2 = $this->db->lastInsertId();
        $this->db->update('UPDATE wiadomosci SET id2 = ? WHERE ID = ?', [$id2, $id1]);
    }

    public function pobierzWiadomosc($id)
    {
        return $this->db->select('SELECT * FROM wiadomosci WHERE id_twoj = :id AND ID = :idW',
            [':id' => Session::_get('id'), ':idW' => $id]);
    }
}
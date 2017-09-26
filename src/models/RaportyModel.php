<?php

namespace src\models;

use src\libs\Model;
use src\libs\Session;

class RaportyModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function oznaczOdczytane()
    {
        $this->db->update('UPDATE poczta SET odczytana = 1 WHERE id_gracza = ? AND odczytana = 0', [Session::_get('id')]);
    }

    public function pobierzRaporty()
    {
        return $this->db->select('SELECT * FROM poczta WHERE id_gracza= :id ORDER BY ID DESC',
            [':id' => Session::_get('id')]);
    }

    public function raport($id)
    {
        return $this->db->select('SELECT * FROM poczta WHERE id_gracza= :id AND ID = :idW', [':id' => Session::_get('id'), ':idW' => $id]);
    }

    public function reportUsun($id)
    {
        $this->db->delete('DELETE FROM poczta WHERE ID = ? AND id_gracza = ?', [$id, Session::_get('id')], 1);
    }

    public function usunWszystkie()
    {
        $this->db->delete('DELETE FROM poczta WHERE id_gracza = ?', [Session::_get('id')], 1000000);
    }
}
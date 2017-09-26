<?php

namespace src\models;

use src\libs\Model;
use src\libs\Session;

class OsiagnieciaModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function achievementy()
    {
        return $this->db->select('SELECT * FROM achievementy WHERE id_gracza = :id', [':id' => Session::_get('id')]);
    }

    public function osiagniecia()
    {
        return $this->db->select('SELECT * FROM osiagniecia WHERE id_gracza = :id', [':id' => Session::_get('id')]);
    }

    public function uzytkownicy()
    {
        return $this->db->select('SELECT * FROM uzytkownicy WHERE ID = :id', [':id' => Session::_get('id')]);
    }

    public function znawcaKanto()
    {
        $this->db->update('UPDATE achievementy SET znawca_kanto = (znawca_kanto + 1) WHERE id_gracza = ?', [Session::_get('id')]);
    }

    public function monety($dukaty)
    {
        $this->db->update('UPDATE przedmioty SET monety = (monety + ?) WHERE id_gracza = ?', [$dukaty, Session::_get('id')]);
    }

    public function raport($tresc, $tytul)
    {
        $this->db->insert('INSERT INTO poczta (id_gracza, tresc, tytul, godzina) VALUES (?, ?, ?, NOW())',
            [Session::_get('id'), $tresc, $tytul]);
    }

    public function achievement($baza)
    {
        $this->db->update("UPDATE achievementy SET $baza = ($baza + 1) WHERE id_gracza = ?", [Session::_get('id')]);
    }
}
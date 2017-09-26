<?php

namespace src\models;

use src\libs\Model;
use src\libs\Session;

class ZglosModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function bledyAdmin()
    {
       return $this->db->select('SELECT * FROM bledy WHERE poprawiony = 0 ORDER BY ID DESC');
    }

    public function bledyUser()
    {
        return $this->db->select('SELECT * FROM bledy WHERE zgloszony = :id poprawiony = 0 ORDER BY ID DESC', [':id' => Session::_get('id')]);
    }

    public function bledyAdminWszystkie()
    {
        return $this->db->select('SELECT * FROM bledy ORDER BY ID DESC');
    }

    public function bledyUserWszystkie()
    {
        return $this->db->select('SELECT * FROM bledy WHERE zgloszony = :id ORDER BY ID DESC', [':id' => Session::_get('id')]);
    }

    public function dodajBlad($tytul, $opis, $zgl, $godzina)
    {
        $this->db->insert('INSERT INTO bledy (tytul, opis, zgloszony, data) VALUES (?, ?, ?, ?)', [$tytul, $opis, $zgl, $godzina]);
        $this->db->insert("INSERT INTO poczta (id_gracza, tytul, tresc, godzina) VALUES 
                    (34, 'Zgłoszono nowy błąd', '<div class=\"alert alert-info\"><span>ZGŁOSZONO NOWY BŁAD</span></div>', ?)", [$godzina]);
    }

    public function bladIdNiePoprawiony($id)
    {
        return $this->db->select('SELECT * FROM bledy WHERE poprawiony = 0 AND ID = :id', [':id' => $id]);
    }

    public function poprawBlad($zgloszony, $tytul, $godzina, $id)
    {
        $this->db->insert("INSERT INTO poczta (id_gracza, tytul, tresc, godzina) 
                    VALUES (?, ?, ?, ?)",
            [$zgloszony, 'Błąd został poprawiony', '<div class="text-center well well-primary jeden_ttlo"><span>Jeden z błędów został poprawiony.
                    <br />[tytuł: '.$tytul.']</span></div>', $godzina]);
        $this->db->update('UPDATE bledy SET poprawiony = 1 WHERE ID = ?', [$id]);
    }

    public function bladId($id)
    {
        return $this->db->select('SELECT * FROM bledy WHERE ID = :id', [':id' => $id]);
    }

    public function usunBlad($zgloszony, $tytul, $godzina, $id)
    {
        $this->db->insert("INSERT INTO poczta (id_gracza, tytul, tresc, godzina) 
                    VALUES (?, ?, ?, ?)",
            [$zgloszony, 'Błąd został usunięty', '<div class="text-center well well-primary jeden_ttlo"><span>Jeden z błędów został usunięty.
                    <br />[tytuł: '.$tytul.']</span></div>', $godzina]);
        $this->db->delete('DELETE FROM bledy WHERE ID = ?', [$id]);
    }
}
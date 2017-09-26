<?php

namespace src\models;

use src\libs\Model;
use src\libs\Session;
use src\libs\User;

class UstawieniaModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function zmienStyl($baza)
    {
        $this->db->update('UPDATE uzytkownicy SET styl = ? WHERE ID = ?', [$baza, Session::_get('id')]);
    }

    public function haslo()
    {
        return $this->db->select('SELECT haslo, sol FROM uzytkownicy WHERE ID = :id', [':id' => Session::_get('id')]);
    }

    public function zmienHaslo($haslo)
    {
        $this->db->update('UPDATE uzytkownicy SET haslo = ? WHERE ID = ?', [$haslo, Session::_get('id')]);
    }

    public function usunAvatar()
    {
        $this->db->update('UPDATE uzytkownicy SET avatar = \'\' WHERE ID = ?', [Session::_get('id')]);
    }

    public function ustawAvatar()
    {
        $avatar = htmlentities($_POST['link_a']);
        $this->db->update('UPDATE uzytkownicy SET avatar = ? WHERE ID = ?', [$avatar, Session::_get('id')]);
    }

    public function zapiszUstawienia()
    {
        $this->db->update('UPDATE uzytkownicy SET ustawienia = ? WHERE ID = ?', [User::$ustawienia->get_all(), Session::_get('id')]);
    }

    public function avatarPobierz()
    {
        return $this->db->select('SELECT avatar FROM uzytkownicy WHERE ID = :id', [':id' => Session::_get('id')])[0];
    }
}
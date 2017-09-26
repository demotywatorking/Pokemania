<?php

namespace src\models;

use src\libs\Model;
use src\libs\Session;

class WylogujModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function log($godzina, $ip, $komentarz)
    {
        $this->db->insert('INSERT INTO logowanie VALUES(NULL, ?, ?, ?, \'wylogowanie\', ?)', [Session::_get('id'), $godzina, $ip, $komentarz]);
    }

    public function wylogujUzytkownika()
    {
        $this->db->update("UPDATE uzytkownicy SET id_sesji = '', ost_aktywnosc = 0 WHERE ID = ?", [Session::_get('id')]);
    }

}


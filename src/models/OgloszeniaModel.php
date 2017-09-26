<?php

namespace src\models;

use src\libs\Model;
use src\libs\Session;

class OgloszeniaModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function ogloszenia()
    {
        return $this->db->select('SELECT * FROM ogloszenia ORDER BY ID DESC', []);
    }

    public function przeczytane()
    {
        $this->db->update('UPDATE uzytkownicy SET ogloszenie = 0 WHERE ID = ?', [Session::_get('id')]);
    }
}
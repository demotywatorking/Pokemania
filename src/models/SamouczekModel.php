<?php

namespace src\models;

use src\libs\Model;
use src\libs\Session;

class SamouczekModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function styl($baza)
    {
        $this->db->update('UPDATE uzytkownicy SET styl = ?, samouczek = (samouczek + 1 ) WHERE ID = ?', [$baza, Session::_get('id')]);
    }
}
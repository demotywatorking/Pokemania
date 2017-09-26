<?php

namespace src\models;

use src\libs\Model;
use src\libs\Session;

class GraModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function statystyki()
    {
        return $this->db->select('SELECT * FROM statystyki WHERE id_gracza = :id', [':id' => Session::_get('id')]);
    }
}
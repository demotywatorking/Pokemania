<?php

namespace src\models;

use src\libs\Model;
use src\libs\Session;

class StatystykiModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function statystyki()
    {
        return $this->db->select('SELECT * FROM osiagniecia WHERE id_gracza  = :id', [':id' => Session::_get('id')]);
    }

    public function online()
    {
        return $this->db->select('SELECT online, online_dzisiaj, jagody_pa FROM uzytkownicy WHERE ID = :id', [':id' => Session::_get('id')]);
    }
}

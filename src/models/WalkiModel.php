<?php

namespace src\models;

use src\libs\Model;
use src\libs\Session;

class WalkiModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function walki()
    {
        return $this->db->select('SELECT * FROM walki WHERE id_gracza = :id ORDER BY data DESC', [':id' => Session::_get('id')]);
    }

    public function walkaId($id)
    {
        return $this->db->select('SELECT * FROM walki WHERE ID = :id AND id_gracza = :idT',[':id' => $id, ':idT' => Session::_get('id')]);
    }

    public function usunWalka($id)
    {
        $this->db->delete('DELETE FROM walki WHERE ID = ?', [$id]);
    }

    public function walkaDoOdblokowania($id)
    {
        return $this->db->select('SELECT * FROM walki WHERE ID = :id AND id_gracza = :idT AND odblokowany = 0',
            [':id' => $id, ':idT' => Session::_get('id')]);
    }

    public function odblokuj($id)
    {
        $this->db->update('UPDATE walki SET odblokowany = 1 WHERE ID = ?', [$id]);
    }

    public function walkaIdZobacz($id)
    {
        return $this->db->select('SELECT * FROM walki WHERE ID = :id', [':id' => $id]);
    }
}
<?php

namespace src\models;

use src\libs\Model;

class SprawdzLoginModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login($login)
    {
        $ilosc = $this->db->select('SELECT login from uzytkownicy WHERE login = :login', ['login' => $login]);
        return $ilosc['rowCount'];
    }

    public function email($email)
    {
        $ilosc = $this->db->select('SELECT email from uzytkownicy WHERE email = :email', ['email' => $email]);
        return $ilosc['rowCount'];
    }
}
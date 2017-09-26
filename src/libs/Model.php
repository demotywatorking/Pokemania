<?php

namespace src\libs;

abstract class Model
{
    public function __construct()
    {
         $this->db = new Database(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASS);
         //If user is not logged in and want to watch someone's Pokemon, we don't want to check if he is logged in, has session saved in database etc.
         if(MODE != 'index' && !(MODE == 'pokemon' && (!Session::_isset('logged') || !Session::_get('logged')))) {
             $this->check = new Check($this->db);
         } else {
             Session::_set('podgladPoka', 1);
         }
    }
}


<?php

namespace src\models;

use src\libs\Model;
use src\libs\Session;

class KolekcjaModel extends Model
{
    private $nazwy;
    private $kolekcja;

    public function __construct()
    {
        parent::__construct();

        $this->nazwy = $this->db->select('SELECT nazwa FROM pokemon', []);
        $this->kolekcja = $this->db->select('SELECT * FROM kolekcja WHERE ID = :id', [':id' => Session::_get('id')]);
    }

    public function kanto()
    {
        $spotkane = 0;
        $zlapane = 0;
        $kanto = [];
        for ($i = 1; $i < 152; $i++) {
            $kanto[$i]['s'] = $this->kolekcja[0][$i . 's'];
            $kanto[$i]['z'] = $this->kolekcja[0][$i . 'z'];
            $kanto[$i]['id'] = $i;
            $kanto[$i]['nazwa'] = $this->nazwy[$i - 1]['nazwa'];
            if ($this->kolekcja[0][$i . 's'] >= 1) {
                if ($this->kolekcja[0][$i . 'z'] >= 1) {////złapany
                    $zlapane++;
                    $spotkane++;
                } else {/////spotkany, ale nie złapany
                    $spotkane++;
                };
            }
        }
        return [
            'kanto' => $kanto,
            'zlapane' => $zlapane,
            'spotkane' => $spotkane
        ];
    }

    public function johto()
    {
        $spotkane = 0;
        $zlapane = 0;
        $johto = [];
        for ($i = 152; $i < 252; $i++) {
            $johto[$i]['s'] = $this->kolekcja[0][$i . 's'];
            $johto[$i]['z'] = $this->kolekcja[0][$i . 'z'];
            $johto[$i]['id'] = $i;
            $johto[$i]['nazwa'] = $this->nazwy[$i - 1]['nazwa'];
            if ($this->kolekcja[0][$i . 's'] >= 1) {
                if ($this->kolekcja[0][$i . 'z'] >= 1) {////złapany
                    $zlapane++;
                    $spotkane++;
                } else {/////spotkany, ale nie złapany
                    $spotkane++;
                }
            }
        }
        return [
            'johto' => $johto,
            'zlapane' => $zlapane,
            'spotkane' => $spotkane
        ];
    }

}

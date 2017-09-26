<?php

namespace src\models;

use src\libs\Model;
use src\libs\Session;

class ProfilModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function sprawdzNick($id)
    {
        $res = $this->db->select('SELECT ID FROM uzytkownicy WHERE login = :log', [':log' => $id]);
        if (!$res['rowCount']) {
            return 0;
        }
        return $res[0]['ID'];
    }

    public function infoDb($id)
    {
        return $this->db->select('SELECT * FROM uzytkownicy, sale_pokemon WHERE uzytkownicy.ID = :id AND uzytkownicy.ID = sale_pokemon.id_gracza',
            [':id' => $id]);
        //$this->stow =  $this->model->db->select('SELECT nazwa FROM stowarzyszenie WHERE ID = :id',  [':id' => $id]);
    }

    public function znajomy($id)
    {
        return $this->db->select('SELECT * FROM znajomi WHERE akceptacja = 1 AND zaproszenie = 0 AND id_gracza = :idT AND kto = :id',
            ['idT' => Session::_get('id'), ':id' => $id]);
    }

    public function zaproszony($id)
    {
        return $this->db->select('SELECT * FROM znajomi WHERE akceptacja = 0 AND zaproszenie = 1 AND kto = :idT AND id_gracza = :id',
            ['idT' => Session::_get('id'), ':id' => $id]);
    }

    public function odznaki($id)
    {
        return $this->db->select('SELECT * FROM sale_pokemon WHERE id_gracza = :id', [':id' => $id]);
    }

    public function druzyna($id)
    {
        $rezultat = $this->db->select('SELECT * FROM druzyna WHERE id_gracza = :id', [':id' => $id]);;
        $rezultat = $rezultat[0];
        $ile = $rezultat['ile'];
        $kwer = 'SELECT ID, id_poka, shiny FROM pokemony WHERE ID in (';
        $kwer2 = 'order by case ID';
        for ($i = 1; $i <= $ile; $i++) {
            if ($rezultat['pok' . $i] > 0) {
                $a = $rezultat['pok' . $i];
                if ($i == 1) $kwer = $kwer . " $a ";
                else $kwer = $kwer . ", $a ";
                $kwer2 = $kwer2 . " WHEN $a THEN " . $i;
            }
        }
        $kwer = $kwer . ')' . $kwer2 . ' END';
        $rezultat = $this->db->select($kwer, []);
        return $rezultat;
    }

    public function punkty()
    {
        return $this->db->select('SELECT * FROM punkty WHERE id_gracza = :id', [':id' => Session::_get('id')]);
    }

    public function osiagniecia()
    {
        return $this->db->select('SELECT * FROM osiagniecia WHERE id_gracza = :id', [':id' => Session::_get('id')])[0];
    }

    public function updateUmiejetnosc($um, $pkt)
    {
        $this->db->update("UPDATE punkty SET $um = ($um + 1) WHERE id_gracza = ?", [Session::_get('id')]);
        $this->db->update('UPDATE uzytkownicy SET punkty = (punkty - ?) WHERE ID = ?', [$pkt, Session::_get('id')]);
    }
}


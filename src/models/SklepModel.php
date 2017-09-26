<?php

namespace src\models;

use src\libs\Model;
use src\libs\Session;

class SklepModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function pokeballe()
    {
        return $this->db->select('SELECT * FROM pokeballe WHERE id_gracza = :id', [':id' => Session::_get('id')]);
    }

    public function przedmioty()
    {
        return $this->db->select('SELECT * FROM przedmioty, statystyki, kamienie WHERE kamienie.id_gracza = statystyki.id_gracza 
                AND przedmioty.id_gracza = statystyki.id_gracza AND przedmioty.id_gracza = :id AND statystyki.id_gracza = :id',
            [':id' => Session::_get('id')]);
    }

    public function przedmiot($przedmiot)
    {
        return $this->db->select("SELECT $przedmiot FROM przedmioty WHERE id_gracza = :id", [':id' => Session::_get('id')]);
    }

    public function zmienPieniadze($ilosc)
    {
        $this->db->update('UPDATE uzytkownicy SET pieniadze = (pieniadze - ?) WHERE ID= ?', [$ilosc, Session::_get('id')]);
    }

    public function kupPrzedmiot($przedmiot)
    {
        $this->db->update("UPDATE przedmioty SET $przedmiot = 1 WHERE id_gracza= ?", [Session::_get('id')]);
    }

    public function zmienPrzedmiot($przedmiot, $ilosc)
    {
        $this->db->update("UPDATE przedmioty SET $przedmiot = ($przedmiot + ?) WHERE id_gracza= ?", [$ilosc, Session::_get('id')]);
    }

    public function zmienKamien($przedmiot, $ilosc)
    {
        $this->db->update("UPDATE kamienie SET $przedmiot = ($przedmiot + ?) WHERE id_gracza= ?", [$ilosc, Session::_get('id')]);
    }

    public function zmienStatystyki($przedmiot, $ilosc)
    {
        $this->db->update("UPDATE statystyki SET $przedmiot = ($przedmiot + ?) WHERE id_gracza= ?", [$ilosc, Session::_get('id')]);
    }

    public function kupMpa($cena)
    {
        $this->db->update('UPDATE uzytkownicy SET pieniadze = (pieniadze - ?), mpa = (mpa + 10) WHERE ID= ?', [$cena, Session::_get('id')]);
    }

    public function kupMagazyn($cena)
    {
        $this->zmienPrzedmiot('box', 1);
        $this->db->update('UPDATE uzytkownicy SET pieniadze = (pieniadze - ?), magazyn = (magazyn * 2) WHERE ID= ?', [$cena, Session::_get('id')]);
    }

}
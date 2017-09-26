<?php

namespace src\models;

use src\libs\Model;
use src\libs\Session;

class PolowanieModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function pokemon($id)
    {
        return $this->db->select('SELECT * FROM pokemon_jagody, pokemony WHERE pokemony.ID = :id AND pokemony.wlasciciel = :idT 
            AND pokemony.ID = pokemon_jagody.id_poka', [':id' => $id, ':idT' => Session::_get('id')]);
    }

    public function pokeballe()
    {
        return $this->db->select('SELECT * FROM pokeballe WHERE id_gracza = :id', [':id' => Session::_get('id')]);
    }

    public function zapiszWalka($godzina, $walka, $rodzaj)
    {
        $this->db->insert("INSERT INTO walki (id_gracza, data, tresc, rodzaj) VALUES (?, ?, ?, ?)", [Session::_get('id'), $godzina, $walka, $rodzaj]);
    }

    public function wydarzeniePieniadze($wyd)
    {
        $this->db->update('UPDATE uzytkownicy SET pieniadze = (pieniadze + ?) WHERE ID = ?', [$wyd, Session::_get('id')]);
    }

    public function baterie()
    {
        return $this->db->select('SELECT baterie FROM przedmioty WHERE id_gracza = :id', ['id' => Session::_get('id')]);
    }

    public function bateriaZaWyprawe()
    {
        $this->db->update('UPDATE przedmioty SET baterie = (baterie - ?) WHERE id_gracza = ?', [1, Session::_get('id')]);
    }

    public function paZaWyprawe($pa)
    {
        $this->db->update('UPDATE uzytkownicy SET pa = (pa - ?) WHERE ID = ?', [$pa, Session::_get('id')]);
    }

    public function kupony()
    {
        return $this->db->select('SELECT kupony FROM statystyki WHERE id_gracza = :id', [':id' => Session::_get('id')]);
    }

    public function kuponZaWyprawe()
    {
        $this->db->update('UPDATE statystyki SET kupony = (kupony - 1) WHERE id_gracza = ?', [Session::_get('id')]);
    }

    public function dodajOsiagniecie($dzicz)
    {
        $this->db->update("UPDATE osiagniecia SET $dzicz = ($dzicz + 1) WHERE id_gracza = ?", [Session::_get('id')]);
        $this->db->update('UPDATE statystyki SET wyprawy = (wyprawy + 1) WHERE id_gracza = ?', [Session::_get('id')]);
    }

    public function jagodyWyprawa($jagody, $il)
    {
        $this->db->update("UPDATE jagody SET $jagody = ($jagody + $il) WHERE id_gracza = ?", [Session::_get('id')]);
        $this->db->update("UPDATE osiagniecia SET zebrane_jagody = (zebrane_jagody + $il) WHERE id_gracza = ?", [Session::_get('id')]);
    }

    public function medrzec()
    {
        $this->db->update('UPDATE uzytkownicy SET doswiadczenie = (doswiadczenie + 5) WHERE ID = ?', [Session::_get('id')]);
        $this->db->update('UPDATE pokemony SET exp = (exp + 15) WHERE wlasciciel = ? AND druzyna = 1', [Session::_get('id')]);
    }

    public function przywiazanieZaWyprawe()
    {
        $this->db->update('UPDATE pokemony SET przywiazanie = (przywiazanie + 10) WHERE druzyna = 1 AND wlasciciel = ?', [Session::_get('id')]);
    }

    public function kamien($k)
    {
        $this->db->update("UPDATE kamienie SET $k WHERE id_gracza = ?", [Session::_get('id')]);
    }

    public function woda()
    {
        $this->db->update('UPDATE przedmioty SET woda = (woda + 1) WHERE id_gracza = ?', [Session::_get('id')]);
    }

    public function jagody()
    {
        return $this->db->select('SELECT * FROM jagody WHERE id_gracza= :id', [':id' => Session::_get('id')]);
    }

    public function psyduckJagoda($nazwa)
    {
        $this->db->update("UPDATE jagody SET $nazwa = ($nazwa - 1 ) WHERE id_gracza = ?", [ Session::_get('id')]);
    }

    public function avatar()
    {
        return $this->db->select('SELECT avatar, login FROM uzytkownicy WHERE ID= :id', [':id' => Session::_get('id')]);
    }

    public function pokonanyTrenerOsiagniecie()
    {
        $this->db->update('UPDATE osiagniecia SET pokonanych_trenerow = (pokonanych_trenerow + 1) WHERE id_gracza = ?', [Session::_get('id')]);
    }

    public function doswiadczenieTrener($pieniadze, $doswiadczenie)
    {
        $this->db->update('UPDATE uzytkownicy SET doswiadczenie = (doswiadczenie + ?), pieniadze = (pieniadze + ?) WHERE ID = ?',
                                [$doswiadczenie, $pieniadze, Session::_get('id')]);
    }

    public function kolekcja()
    {
        return $this->db->select('SELECT * FROM kolekcja WHERE ID = :id', [':id' => Session::_get('id')])[0];
    }

    public function dodajDoKolekcji($rrr)
    {
        $this->db->update("UPDATE kolekcja SET $rrr = ($rrr + 1) WHERE ID = ?", [Session::_get('id')]);
    }

    public function pokeballZaWyprawe($nazwa, $ilosc)
    {
        $this->db->update('UPDATE pokeballe SET $nazwa = ($nazwa + ?) WHERE id_gracza = ?', [$ilosc, Session::_get('id')]);
    }

    public function soda()
    {
        $this->db->update('UPDATE przedmioty SET soda = (soda + 1) WHERE id_gracza = ?', [Session::_get('id')]);
    }

    public function kamienWyprawa($prz)
    {
        $this->db->update("UPDATE kamienie SET $prz = ($prz + 1) WHERE id_gracza = ?", [Session::_get('id')]);
    }
}

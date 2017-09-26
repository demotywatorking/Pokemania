<?php

namespace src\models;

use src\libs\Model;
use src\libs\Session;

class PlecakModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function przedmioty($id)
    {
        return $this->db->select('SELECT * FROM przedmioty, pokeballe, jagody, kamienie, karty WHERE przedmioty.id_gracza = 
                :id AND pokeballe.id_gracza = przedmioty.id_gracza AND jagody.id_gracza = przedmioty.id_gracza AND kamienie.id_gracza = przedmioty.id_gracza 
                AND karty.id_gracza = przedmioty.id_gracza', [':id' => $id])[0];
    }

    public function jagodyPa()
    {
        return $this->db->select('SELECT jagody_pa FROM uzytkownicy WHERE ID = :id', [':id' => Session::_get('id')]);
    }

    public function updateJagody($rodzaj, $ilosc)
    {
        $this->db->update("UPDATE jagody SET $rodzaj = ($rodzaj - ?) WHERE id_gracza = ?", [$ilosc, Session::_get('id')]);
    }

    public function jagPokemon($id)
    {
        return $this->db->select('SELECT * FROM pokemon_jagody WHERE id_poka = :id', [':id' => $id]);
    }

    public function jagHP($id)
    {
        return $this->db->select('SELECT Jag_HP FROM pokemony WHERE ID = :id', [':id' => $id]);
    }

    public function updateJagHP($il, $pok)
    {
        $this->db->update('UPDATE pokemony SET Jag_HP = (Jag_HP + ?) WHERE ID = ?', [$il, $pok]);
    }

    public function updateSzybkosc($il, $pok)
    {
        $this->db->update('UPDATE pokemon_jagody SET Jag_Szybkosc = (Jag_Szybkosc + ?) WHERE id_poka = ?', [$il, $pok]);
    }

    public function updateSpAtak($il, $pok)
    {
        $this->db->update('UPDATE pokemon_jagody SET Jag_Sp_Atak = (Jag_Sp_Atak + ?) WHERE id_poka = ?', [$il, $pok]);
    }

    public function updateAtak($il, $pok)
    {
        $this->db->update('UPDATE pokemon_jagody SET Jag_Atak = (Jag_Atak + ?) WHERE id_poka = ?', [$il, $pok]);
    }

    public function updateSpObrona($il, $pok)
    {
        $this->db->update('UPDATE pokemon_jagody SET Jag_Sp_Obrona = (Jag_Sp_Obrona + ?) WHERE id_poka = ?', [$il, $pok]);
    }

    public function userAddExp($exp)
    {
        $this->db->update('UPDATE uzytkownicy SET doswiadczenie = (doswiadczenie + ?) WHERE ID = ?', [$exp, Session::_get('id')]);
    }

    public function updateObrona($il, $pok)
    {
        $this->db->update('UPDATE pokemon_jagody SET Jag_Obrona = (Jag_Obrona + ?) WHERE id_poka = ?', [$il, $pok]);
    }

    public function pokemonAddExp($exp, $pok)
    {
        $this->db->update('UPDATE pokemony SET exp = (exp + ?) WHERE wlasciciel = ? AND ID = ?', [$exp, Session::_get('id'), $pok]);
    }

    public function userSetPa($pa)
    {
        $this->db->update('UPDATE uzytkownicy SET pa = ? WHERE ID = ?', [$pa, Session::_get('id')]);
    }

    public function userUpdatePa($pa)
    {
        $this->db->update('UPDATE uzytkownicy SET pa = (pa + ?) WHERE ID = ?', [$pa, Session::_get('id')]);
    }

    public function updatePrzedmiot($co, $il)
    {
        $this->db->update("UPDATE przedmioty SET $co = ($co - ?) WHERE id_gracza = ?", [$il, Session::_get('id')]);
    }

    public function getPokInfo($pok)
    {
        return $this->db->select('SELECT pokemon.ewolucja_p, pokemon.wymagania, pokemon.nazwa, pokemony.imie, pokemony.jakosc  FROM pokemony, pokemon 
                    WHERE pokemony.ID = :id AND pokemon.id_poka = pokemony.id_poka AND pokemony.wlasciciel = :idT', [':id' => $pok, ':idT' => Session::_get('id')]);
    }

    public function getPokemonNazwa($id)
    {
        return $this->db->select('SELECT nazwa FROM pokemon WHERE id_poka = :id', [':id' => $id]);
    }

    public function updateKamienie($co)
    {
        $this->db->update("UPDATE kamienie SET $co = ($co - 1) WHERE id_gracza = ?", [Session::_get('id')]);
    }

    public function updateKolekcja($i, $ii)
    {
        $this->db->update("UPDATE kolekcja SET $i = ($i + 1), $ii = ($ii + 1) WHERE ID = ?", [Session::_get('id')]);
    }

    public function insertPoczta($raport, $godzina, $tytul)
    {
        $this->db->insert('INSERT INTO poczta (ID, id_gracza, tresc, godzina, odczytana, tytul)
                    VALUES (\'NULL\', ?, ?, ?, 0, ?)', [Session::_get('id'), $raport, $godzina, $tytul]);
    }

    public function updatePokemon($atak, $sp_atak, $obrona, $sp_obrona, $szybkosc, $hp, $id, $imie, $pok)
    {
        $this->db->update('UPDATE pokemony SET atak = (atak + ?), sp_atak = (sp_atak + ?), obrona = (obrona + ?),
                                sp_obrona = (sp_obrona + ?), szybkosc = (szybkosc + ?),hp = (hp + ?), akt_HP = hp, id_poka = ?, imie = ? WHERE ID = ?',
            [$atak, $sp_atak, $obrona, $sp_obrona, $szybkosc, $hp, $id, $imie, $pok]);
    }

    public function updatePokHP($hp, $id)
    {
        $this->db->update('UPDATE pokemony SET akt_HP = ? WHERE ID = ?', [$hp, $id]);
    }

    public function selectPokemon($pok)
    {
        return $this->db->select('SELECT * FROM pokemony WHERE ID = :idP AND wlasciciel = :id', [':idP' => $pok, ':id' => Session::_get('id')]);
    }

    public function getPrzysmaki($pok)
    {
        return $this->db->select('SELECT przysmaki FROM pokemony WHERE wlasciciel = :id AND ID = :idP', [':id' => Session::_get('id'), ':idP' => $pok]);
    }

    public function pokDodajPrzywiazanie($przyw, $il, $pok)
    {
        $this->db->update('UPDATE pokemony SET przywiazanie = (przywiazanie + ?), przysmaki = (przysmaki + ?) 
                                    WHERE ID = ?', [$przyw, $il, $pok]);
    }

    public function dodajOsiagniecie($il)
    {
        $this->db->update('UPDATE osiagniecia SET przysmaki = ( przysmaki + ? ) WHERE id_gracza = ?', [$il, Session::_get('id')]);
    }

    public function zmienGlod($minus, $pok)
    {
        $this->db->update('UPDATE pokemony SET glod = ? WHERE ID = ?', [$minus, $pok]);
    }
}
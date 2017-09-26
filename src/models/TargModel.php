<?php

namespace src\models;

use src\libs\Model;
use src\libs\Session;

class TargModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function ofertaId($id)
    {
        return $this->db->select('SELECT * FROM targ WHERE ID = :id', [':id' => $id]);
    }

    public function login($id)
    {
        return $this->db->select('SELECT login FROM uzytkownicy WHERE ID = :id', [':id' => $id]);
    }

    public function kup($co, $godzina, $wartosc, $id)
    {
        $this->db->insert('INSERT INTO logowanie (id_gracza, co, komentarz, ip, data) VALUES
                                  (?, \'targ\', ?, ?, ?)', [Session::_get('id'), $co, Session::_get('ip'), $godzina]);
        $this->db->update('UPDATE uzytkownicy SET pieniadze = (pieniadze - ?) WHERE ID = ?', [$wartosc, Session::_get('id')]);
        $this->db->update('UPDATE uzytkownicy SET pieniadze = (pieniadze + ?) WHERE ID = ?', [$wartosc, $id]);
    }

    public function jagody($co, $ilosc)
    {
        $this->db->update('UPDATE jagody SET '.$co.' = ('.$co.' + ?) WHERE id_gracza = ?',[ $ilosc, Session::_get('id')]);
    }

    public function pokeballe($co, $ilosc)
    {
        $this->db->update('UPDATE pokeballe SET '.$co.' = ('.$co.' + ?) WHERE id_gracza = ?', [ $ilosc, Session::_get('id')]);
    }

    public function inne($co, $ilosc)
    {
        $this->db->update('UPDATE przedmioty SET '.$co.' = ('.$co.' + ?) WHERE id_gracza = ?', [ $ilosc, Session::_get('id')]);
    }

    public function kamienie($co, $ilosc)
    {
        $this->db->update('UPDATE kamienie SET '.$co.' = ('.$co.' + ?) WHERE id_gracza = ?', [ $ilosc, Session::_get('id')]);
    }

    public function raport($id, $raport, $godzina, $tytul)
    {
        $this->db->insert('INSERT INTO poczta (ID, id_gracza, tresc, godzina, odczytana, tytul) VALUES
                                    (NULL, ?, ?, ?, 0, ?)', [$id, $raport, $godzina, $tytul]);
    }

    public function usunOferte($id)
    {
        $this->db->delete('DELETE FROM targ WHERE ID = ?', [$id]);
    }

    public function zmienOferte($ilosc, $id)
    {
        $this->db->update('UPDATE targ SET ilosc = (ilosc - ?) WHERE ID = ?', [$ilosc, $id]);
    }

    public function pokemony($id)
    {
        return $this->db->select('SELECT * FROM targ_pokemon WHERE ID = :id AND id_wlasciciela <> :idW',
            [':id' => $id, ':idW' => Session::_get('id')]);
    }

    public function pokemonImie($id)
    {
        return $this->db->select('SELECT imie FROM pokemony WHERE ID = :id', [':id' => $id]);
    }

    public function kupPokemon($id, $idTarg, $cena, $wlasciciel)
    {
        $this->db->update('UPDATE pokemony SET wlasciciel = ?, targ = 0, przywiazanie=0, blokada=1 WHERE ID = ?',
            [Session::_get('id'), $id]);
        $this->db->delete('DELETE FROM targ_pokemon WHERE ID = ?', [$idTarg]);
        $this->db->update('UPDATE uzytkownicy SET pieniadze = (pieniadze - ?) WHERE ID = ?', [$cena, Session::_get('id')]);
        $this->db->update('UPDATE uzytkownicy SET pieniadze = (pieniadze + ?) WHERE ID = ?', [$cena, $wlasciciel]);
    }

    public function pokemonWartosc($id)
    {
        return  $this->db->select('SELECT wartosc FROM pokemony WHERE ID = :id', [':id' => $id]);
    }

    public function wiadomosc($wlasciciel, $raport, $godzina, $tytul)
    {
        $this->db->insert("INSERT INTO logowanie (id_gracza, co, komentarz, ip, data) VALUES 
                                  (?, 'targ_pokemon', ?, ?, ?)",
            [Session::_get('id'), $raport, Session::_get('ip'), $godzina]);
        $this->db->insert("INSERT INTO poczta ( id_gracza, tresc, godzina, odczytana, tytul) VALUES
                                ( ?, ?, ?, 0, ?)",
            [$wlasciciel, $raport, $godzina, $tytul]);
    }

    public function pokemonGraczaNaTargu($id)
    {
        return $this->db->select('SELECT * FROM targ_pokemon WHERE ID = :id AND id_wlasciciela = :idT',
            [':id' => $id, ':idT' => Session::_get('id')]);
    }

    public function pokemonWycofaj($idTarg, $idPoka)
    {
        $this->db->delete('DELETE FROM targ_pokemon WHERE ID = ?', [$idTarg]);
        $this->db->update('UPDATE pokemony SET targ = 0 WHERE ID= ?  AND wlasciciel = ?', [$idPoka, Session::_get('id')]);
    }

    public function przedmiotTargGracza($id)
    {
        return $this->db->select('SELECT * FROM targ WHERE ID =  :id AND id_gracza = :idT',
            [':id' => $id, ':idT' => Session::_get('id')]);
    }

    public function zmienJagody($co, $ilosc)
    {
        $this->db->update("UPDATE jagody SET $co = ($co + $ilosc) WHERE id_gracza = ?", [Session::_get('id')]);
    }

    public function zmienPokeballe($co, $ilosc)
    {
        $this->db->update("UPDATE pokeballe SET $co = ($co + $ilosc) WHERE id_gracza = ?", [Session::_get('id')]);
    }

    public function zmienInne($co, $ilosc)
    {
        $this->db->update("UPDATE przedmioty SET $co = ($co + $ilosc) WHERE id_gracza = ?", [Session::_get('id')]);
    }

    public function zmienKamienie($co, $ilosc)
    {
        $this->db->update("UPDATE kamienie SET $co = ($co + $ilosc) WHERE id_gracza = ?", [Session::_get('id')]);
    }

    public function jagodyBaza()
    {
        return $this->db->select('SELECT * FROM jagody WHERE id_gracza = :id', [':id' => Session::_get('id')]);
    }

    public function pokeballeBaza()
    {
        return $this->db->select('SELECT * FROM pokeballe WHERE id_gracza = :id', [':id' => Session::_get('id')]);
    }

    public function inneBaza()
    {
        return $this->db->select('SELECT * FROM przedmioty WHERE id_gracza = :id', [':id' => Session::_get('id')]);
    }

    public function kamienieBaza()
    {
        return $this->db->select('SELECT * FROM kamienie WHERE id_gracza = :id', [':id' => Session::_get('id')]);
    }

    public function zabierzJagody($co, $ilosc)
    {
        $this->db->update("UPDATE jagody SET $co = ($co - $ilosc) WHERE id_gracza = ?", [Session::_get('id')]);
    }

    public function zabierzPokeballe($co, $ilosc)
    {
        $this->db->update("UPDATE pokeballe SET $co = ($co - $ilosc) WHERE id_gracza = ?", [Session::_get('id')]);
    }

    public function zabierzInne($co, $ilosc)
    {
        $this->db->update("UPDATE przedmioty SET $co = ($co - $ilosc) WHERE id_gracza = ?", [Session::_get('id')]);
    }

    public function zabierzKamienie($co, $ilosc)
    {
        $this->db->update("UPDATE kamienie SET $co = ($co - $ilosc) WHERE id_gracza = ?", [Session::_get('id')]);
    }

    public function wystaw($nazwa, $ilosc, $cena)
    {
        $this->db->insert('INSERT INTO targ (ID, id_gracza, co, ilosc, cena) VALUES 
                              (NULL, ?, ?, ?, ?)',
            [Session::_get('id'), $nazwa, $ilosc, $cena]);///dodanie oferty na targ
    }

    public function PokemonDoWystawienia($id)
    {
        return $this->db->select('SELECT * FROM pokemony WHERE wymiana = 0 AND ID = :id AND wlasciciel = :idW AND targ = 0',
            [':id' => $id, ':idW' => Session::_get('id')]);
    }

    public function wystawPokemon($id, $idPoka, $poziom, $cena, $shiny,
                $typ1, $typ2, $opis, $nazwa, $plec)
    {
        $this->db->update('UPDATE pokemony SET targ = 1 WHERE ID = ?', [$id]);
        $this->db->insert('INSERT INTO targ_pokemon (ID_pokemona, id_poka, poziom, cena, id_wlasciciela, shiny, typ1, typ2, wiadomosc, gatunek, plec)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [$id, $idPoka, $poziom, $cena, Session::_get('id'), $shiny,
                $typ1, $typ2, $opis, $nazwa, $plec]);
    }

    public function pokiWystawione()
    {
        return $this->db->select('SELECT * FROM targ_pokemon WHERE id_wlasciciela = :id', [':id' => Session::_get('id')]);
    }

    public function przedmiotyDoWystawienia()
    {
        return $this->db->select('SELECT * FROM jagody, pokeballe, przedmioty, kamienie WHERE jagody.id_gracza = :id AND jagody.id_gracza = kamienie.id_gracza 
                                AND jagody.id_gracza = pokeballe.id_gracza AND jagody.id_gracza = przedmioty.id_gracza',
                            [':id' => Session::_get('id')]);
    }

    public function pokemonyDoWystawienia()
    {
        return $this->db->select('SELECT * FROM pokemony WHERE wymiana = 0 AND druzyna = 0  AND targ= 0 AND blokada= 0 AND wlasciciel = :id',
                 [':id' => Session::_get('id')]);
    }

    public function przedmiotyWystawione()
    {
        return $this->db->select('SELECT * FROM targ WHERE id_gracza = :id',
            [':id' => Session::_get('id')]);
    }

}
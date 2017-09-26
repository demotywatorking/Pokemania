<?php

namespace src\models;

use src\libs\Model;
use src\libs\Session;

class LoteriaModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function losy()
    {
        return $this->db->select('SELECT loteria FROM statystyki WHERE id_gracza = :id', [':id' => Session::_get('id')]);
    }

    public function zabierzLos()
    {
        $this->db->update('UPDATE statystyki SET loteria = (loteria - 1) WHERE id_gracza = ?', [Session::_get('id')]);
    }

    public function statyDratini()
    {
        return $this->db->select('SELECT * FROM staty_poczatkowe WHERE id_poka = 147', []);
    }

    public function dodajDratini()
    {
        $rezultat = $this->statyDratini();
        $w = $rezultat[0];
        $p = rand() % 2;
        if ($p == 0) $plec = 1;
        else $plec = 0;
        $godzina = date('Y-m-d-H-i-s');
        $this->db->insert("INSERT INTO pokemony (zlapany, pierwszy_wlasciciel, id_poka, imie, poziom, wlasciciel, Atak, Obrona, Sp_Atak, 
                    Sp_Obrona, Szybkosc, HP, akt_HP, wartosc, plec, przywiazanie, data_zlapania, atak1, atak2, loteria, blokada, celnosc, jakosc)
                VALUES ('loteria', ?, 147, 'Dratini', 1, ?, ?, ?, ?, ?, ?, ?, ?, 100000, ?, 750, ?, 603, 291, 1, 1, 80, 90)",
            [Session::_get('id'), Session::_get('id'), $w['Atak'], $w['Obrona'], $w['Sp_Atak'], $w['Sp_Obrona'], $w['Szybkosc'], $w['HP'], $w['HP'], $plec, $godzina]);
        $id = $this->db->lastInsertId();
        $limit = rand(50, 75) * 5;
        $this->db->insert('INSERT INTO pokemon_jagody (id_poka, Jag_Limit) VALUES (?, ?)', [$id, $limit]);
        $this->db->update('UPDATE kolekcja SET 147s = (147s + 1), 147z = (147z + 1) WHERE ID = ?', [Session::_get('id')]);
    }

    public function wygranaPieniadze($kasa)
    {
        $this->db->update('UPDATE uzytkownicy SET pieniadze = (pieniadze + ?) WHERE ID = ?', [$kasa, Session::_get('id')]);
    }

    public function wygranaJagody($jagoda, $ilosc)
    {
        $this->db->update("UPDATE jagody SET $jagoda = ($jagoda + ?) WHERE id_gracza = ?", [$ilosc, Session::_get('id')]);
    }

    public function wygranaPokeball($pokeball, $ilosc)
    {
        $this->db->update("UPDATE pokeballe SET $pokeball = ($pokeball + ?) WHERE id_gracza = ?", [$ilosc, Session::_get('id')]);
    }

    public function wygranaLosy()
    {
        $this->db->update('UPDATE statystyki SET loteria = (loteria + 10) WHERE id_gracza = ?', [Session::_get('id')]);
    }

    public function wygranaKamienie($kamien)
    {
        $this->db->update("UPDATE kamienie SET $kamien = ($kamien + 1) WHERE id_gracza = ?", [Session::_get('id')]);
    }

}
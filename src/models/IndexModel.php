<?php

namespace src\models;

use src\libs\Model;

class IndexModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function idGraczaPoLoginie($login)
    {
        return $this->db->select('SELECT ID FROM uzytkownicy WHERE login=:login', [':login' => $login]);
    }

    public function idGraczaPoMailu($email)
    {
        return $this->db->select('SELECT ID FROM uzytkownicy WHERE email=:email', [':email' => $email]);
    }

    public function rejestracja($solusera, $login, $haslo_hash, $email, $godzina, $ip)
    {
        $this->db->insert('INSERT INTO uzytkownicy (ID, samouczek, sol, login, haslo, email, pieniadze, starter, poziom_trenera, 
                                      doswiadczenie, mpa, pa, region, punkty, magazyn, ustawienia) VALUES
                                     (NULL, 1, ?, ?, ?, ?, 500000, 0, 1, 0, 500, 500, 1, 6, 30, \'1|0|1|1|1|0|0|0|0|0|1|0|0\')',
            [$solusera, $login, $haslo_hash, $email]);
        $id = $this->db->lastInsertId();
        $this->db->insert('INSERT INTO logowanie VALUES (NULL, ?, ?, ?, \'rejestracja\', \'\')', [$id, $godzina, $ip]);
        $this->db->insert('INSERT INTO pokeballe ( id_gracza, pokeballe, nestballe, greatballe, ultraballe, duskballe, lureballe, cherishballe ) 
                                  VALUES ( ?, 15, 15, 15, 5, 15, 15, 10 )', [$id]);
        $this->db->insert('INSERT INTO jagody ( id_gracza, Cheri_Berry, Chesto_Berry, Aspear_Berry, Pecha_Berry,
                                            Rawst_Berry, Wiki_Berry, Mago_Berry, Aguav_Berry, Lapapa_Berry, Razz_Berry)
                                            VALUES(?, 30, 15, 5, 5, 5, 5, 3, 1, 1, 1)', [$id]);
        $this->db->insert('INSERT INTO kamienie (id_gracza)  VALUES ( ? )', [$id]);
        $this->db->insert('INSERT INTO statystyki (id_gracza, loteria)  VALUES ( ?, 2 )', [$id]);
        $this->db->insert('INSERT INTO przedmioty (id_gracza, box, karma)  VALUES ( ?, 1, 100)', [$id]);
        $this->db->insert('INSERT INTO punkty (id_gracza)  VALUES ( ? )', [$id]);
        $this->db->insert('INSERT INTO tmy (id_gracza)  VALUES ( ? )', [$id]);
        $this->db->insert('INSERT INTO osiagniecia (id_gracza)  VALUES ( ? )', [$id]);
        $this->db->insert('INSERT INTO aktywnosc (id_gracza, aktywnosc) VALUES ( ?, \'\')', [$id]);
        $this->db->insert('INSERT INTO achievementy (id_gracza) VALUES ( ? )', [$id]);
        $this->db->insert('INSERT INTO karty (id_gracza, brazowa_1, brazowa_2, brazowa_3, brazowa_4) VALUES (?, 3, 3, 3, 3)', [$id]);
        $this->db->insert('INSERT INTO sale_pokemon (id_gracza) VALUES ( ? )', [$id]);
        $this->db->insert('INSERT INTO kolekcja (ID) VALUES (? )', [$id]);
        return $id;
    }

    public function starter($ID, $idGracza)
    {
        $this->db->update('UPDATE uzytkownicy SET starter = ? WHERE ID = ? ', [$ID, $idGracza]);
    }

    public function pokemonJagody($ID, $idGracza)
    {
        $limit = rand(50,75) * 5;
        $this->db->insert('INSERT INTO pokemon_jagody (id_poka, Jag_Limit) VALUES (?, ?)', [$ID, $limit]);
        $this->db->insert('INSERT INTO druzyna (nr, id_gracza, pok1, pok2, pok3, pok4, pok5, pok6, ile) VALUES
        (NULL, ?, ?, 0, 0, 0, 0, 0, 1) ', [$idGracza, $ID]);
    }

    public function bulbasaur($wlasciciel, $idPoka, $nazwa, $plec, $wartosc)
    {
        $this->db->insert('INSERT INTO pokemony (zlapany, pierwszy_wlasciciel, ID, id_poka, imie, poziom, exp, starter, shiny, wlasciciel, Atak, Obrona, Sp_Atak, Sp_Obrona, Szybkosc,
            HP, druzyna, akt_HP, atak1, atak2, plec, wartosc, przywiazanie, data_zlapania, celnosc, jakosc)
            VALUES( \'starter\', ?, NULL, ?, ?, 5, 0, 1, 0, ?, 25, 25,30, 30, 25, 125, 1, 94, 541, 0, ?, ?, 900, ?, 72, 75) ',
            [$wlasciciel, $idPoka, $nazwa, $wlasciciel, $plec, $wartosc, date('Y-m-d-H-i-s')]);
        return $this->db->lastInsertId();
    }

    public function charmander($wlasciciel, $idPoka, $nazwa, $plec, $wartosc)
    {
        $this->db->insert('INSERT INTO pokemony (zlapany, pierwszy_wlasciciel, ID, id_poka, imie, poziom, exp, starter, shiny, wlasciciel, Atak, Obrona, Sp_Atak, Sp_Obrona, Szybkosc,
            HP, druzyna, akt_HP, atak1, atak2, plec, wartosc, przywiazanie, data_zlapania, celnosc, jakosc)
            VALUES(  \'starter\', ?, NULL, ?, ?, 5, 0, 1, 0, ?, 25, 25,
            30, 25, 30, 125, 1, 125, 451, 94, ?, ?, 900, ?, 72, 75) ',
            [$wlasciciel, $idPoka, $nazwa, $wlasciciel, $plec, $wartosc, date('Y-m-d-H-i-s')]);
        return $this->db->lastInsertId();
    }

    public function squirtle($wlasciciel, $idPoka, $nazwa, $plec, $wartosc)
    {
        $this->db->insert('INSERT INTO pokemony (zlapany, pierwszy_wlasciciel, ID, id_poka, imie, poziom, exp, starter, shiny, wlasciciel, Atak, Obrona, Sp_Atak, Sp_Obrona, Szybkosc,
            HP, druzyna, akt_HP, atak1, atak2, plec, wartosc, przywiazanie, data_zlapania, celnosc, jakosc)
            VALUES( \'starter\', ?, NULL, ?, ?, 5, 0, 1, 0, ?, 25, 30,
            25, 30, 25, 125, 1, 94, 541, 0, ?, ?, 900, ?, 72, 75) ',
            [$wlasciciel, $idPoka, $nazwa, $wlasciciel, $plec, $wartosc, date('Y-m-d-H-i-s')]);
        return $this->db->lastInsertId();
    }

    public function online()
    {
        return $this->db->select('SELECT COUNT(*) AS gracze FROM uzytkownicy WHERE id_sesji != \'\'', []);
    }

    public function ostatnie()
    {
        return $this->db->select('SELECT id_poka FROM pokemony WHERE shiny = 0 AND zlapany LIKE "%ball" ORDER BY ID DESC LIMIT 5', []);
    }

    public function zmienKod($kod, $id)
    {
        $this->db->update('UPDATE uzytkownicy SET kod = ? WHERE ID = ?', [$kod, $id]);
    }

    public function szukajMail($zmienna)
    {
        return $this->db->select('SELECT ID, aktywne FROM uzytkownicy WHERE email = :mail' , [':mail' => $zmienna]);
    }

    public function szukajLogin($zmienna)
    {
        return $this->db->select('SELECT ID, aktywne, email FROM uzytkownicy WHERE login = :login', [':login' => $zmienna]);
    }

    public function szukajKod($kod)
    {
        return $this->db->select('SELECT ID FROM uzytkownicy WHERE kod = :kod', [':kod' => $kod]);
    }

}
<?php

namespace src\models;

use src\libs\Model;

class CronModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function nieaktywni()
    {
        $data = date('Y-m-d H:i:s');
        $this->db->insert('INSERT INTO cron_log VALUES(NULL, \'wykonano cron, nieaktywni\', ?)', [$data]);
        $ost = time() - 900;
        $this->db->update('UPDATE uzytkownicy SET id_sesji = \'\', ost_aktywnosc = 0 WHERE ost_aktywnosc < ? AND id_sesji != \'\'', [$ost]);
        $czas = time();
        $this->db->update('UPDATE aktywnosc SET karta = 0, karta_czas = 0 WHERE karta_czas <= ?', [$czas]);
    }

    public function updatePA()
    {
        $this->db->update('UPDATE uzytkownicy  SET pa = ( pa + mpa * 0.1) WHERE pa < mpa', []);
        $this->db->update('UPDATE pokemony JOIN sale_pokemon ON sale_pokemon.id_gracza = pokemony.wlasciciel 
                                  SET pokemony.glod = (pokemony.glod + 2.08) 
                                  WHERE pokemony.druzyna = 1 AND pokemony.glod < 100 AND sale_pokemon.Kanto7 = ?', ['0000-00-00']);
        $this->db->update('UPDATE pokemony JOIN sale_pokemon ON sale_pokemon.id_gracza = pokemony.wlasciciel 
                                  SET pokemony.glod = (pokemony.glod + 1.04) 
                                  WHERE pokemony.druzyna = 1 AND pokemony.glod < 100 AND sale_pokemon.Kanto7 > ?', ['0000-00-00']);
        $d = date('Y-m-d-H-i-s');
        $this->db->insert('INSERT INTO cron_log VALUES (NULL, \'Powodzenie przy wywołaniu crona, pa.\', ?)', [$d]);
    }

    public function reset()
    {
        $this->db->update('UPDATE statystyki JOIN sale_pokemon ON sale_pokemon.id_gracza = statystyki.id_gracza 
                                  SET statystyki.zlapanych = 0, statystyki.loteria = (statystyki.loteria + 2), 
                                  statystyki.kupony = (statystyki.kupony + 15), statystyki.wyprawy = 0 
                                  WHERE sale_pokemon.Kanto2 = ?', ['0000-00-00']);
        $this->db->update('UPDATE statystyki JOIN sale_pokemon ON sale_pokemon.id_gracza = statystyki.id_gracza 
                                  SET statystyki.zlapanych = 0, statystyki.loteria = (statystyki.loteria + 3), 
                                  statystyki.kupony = (statystyki.kupony + 15), statystyki.wyprawy = 0 
                                  WHERE sale_pokemon.Kanto2 > ?', ['0000-00-00']);
        $this->db->update('UPDATE pokemony SET przysmaki = 0', []);
        $this->db->update('UPDATE uzytkownicy SET logowanie_pod_rzad = 0 WHERE logowanie_dzis = 0', []);
        $this->db->update('UPDATE uzytkownicy SET logowanie_dzis = 0, online_dzisiaj = 0 WHERE logowanie_dzis <> 0 ', []);
        $this->db->update('UPDATE uzytkownicy SET podroz = 0, karmienie = 0, karmienie_ip = \'\' ', []);
        $d = date('Y-m-d-H-i-s');
        $this->db->insert('INSERT INTO cron_log (log, data) VALUES (\'Powodzenie przy wywołaniu crona, reset.\', ?)', [$d]);
    }
}
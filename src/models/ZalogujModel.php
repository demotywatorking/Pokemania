<?php
namespace src\models;

use src\libs\Model;

class ZalogujModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }
    
    public function sol($login)
    {
        return $this->db->select('SELECT sol FROM uzytkownicy WHERE login = :login OR email = :login', [':login' => $login]);
    }
    
    public function login($kwer, $data)
    {
        return $this->db->select($kwer, $data);
    }
    
    public function kod($id)
    {
        //$this->db->update('uzytkownicy', ['kod' => ''], 'ID = '.$id);
    }

    public function aktywnosc($id)
    {
        return $this->db->select('SELECT * FROM aktywnosc WHERE id_gracza = :id', ['id' => $id]);
    }

    public function log($id, $godzina)
    {
        $this->db->insert('INSERT INTO logowanie VALUES( \'NULL\', ?, ?, ?, ?, \'\')', [$id, $godzina, $_SERVER['REMOTE_ADDR'], 'logowanie']);
    }

    public function osiagniecieLogowanie($id)
    {
        $this->db->update('UPDATE osiagniecia SET logowanie = (logowanie + 1) WHERE id_gracza = ? ', [$id]);
    }

    public function przedmioty($id)
    {
        return $this->db->select('SELECT * FROM przedmioty WHERE id_gracza = :id', [':id' => $id]);
    }

    public function ustawPA($id)
    {
        $this->db->update('UPDATE uzytkownicy SET pa = mpa WHERE ID = ?', [$id]);
    }

    public function liczbaPokemonow($id)
    {
        return $this->db->select('SELECT COUNT(*) AS abcd FROM pokemony WHERE wlasciciel = '.$id.' AND druzyna = 0', []);
    }

    public function kartaZeruj($id)
    {
        $this->db->update('UPDATE aktywnosc SET karta = 0, karta_czas = 0 WHERE id_gracza = ?', [$id]);
    }

    public function wiadomosci($id)
    {
        return $this->db->select('SELECT COUNT(*) AS abcd FROM wiadomosci WHERE odczytana = 0 AND id_twoj = :id', [':id' => $id]);
    }

    public function raporty($id)
    {
        return $this->db->select('SELECT COUNT(*) AS abcd FROM poczta WHERE odczytana = 0 AND id_gracza = :id', [':id' => $id]);
    }

    public function odznaki($id)
    {
        return $this->db->select('SELECT * FROM sale_pokemon WHERE id_gracza=' . $id, []);
    }

    public function zlapane($id)
    {
        return $this->db->select('SELECT zlapanych FROM statystyki WHERE id_gracza=' . $id, []);
    }

    public function punkty($id)
    {
        return $this->db->select('SELECT * FROM punkty WHERE id_gracza=' . $id, []);
    }

    public function druzyna($id)
    {
        return $this->db->select('SELECT * FROM druzyna WHERE id_gracza='. $id, []);
    }
}


<?php

namespace src\models;

use src\libs\Model;
use src\libs\Session;
use src\libs\User;

class PokemonModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function pobierz($id)
    {
        $klery = "SELECT * FROM pokemon_jagody, pokemony WHERE pokemony.ID = pokemon_jagody.id_poka AND pokemony.ID in (";
        $klery2 = "order by case ID";
        $bb = 0;
        for($i = 1 ; $i < 7 ; $i++) {
            if ( User::_isset('pok', $i) && User::_get('pok', $i)->get('id') > 0) {
                $aaa =  User::_get('pok', $i)->get('id');
                if ($i == 1) $klery .= "'$aaa'";
                else $klery .= ", '$aaa'";
                $klery2 .= " WHEN '$aaa' THEN " . $i;
                $bb++;
            }
        }
        $klery .= ')' . $klery2 . ' END';
        return $this->db->select($klery, []);
    }

    public function login($id)
    {
        return $this->db->select('SELECT login FROM uzytkownicy WHERE ID= :id', [':id' => $id]);
    }

    public function glod100($id)
    {
        $this->db->update('UPDATE pokemony SET glod = 100 WHERE ID = ?', [$id]);
    }

    public function pokemonInfo($id)
    {
        return $this->db->select('SELECT * FROM pokemon_jagody, pokemony WHERE pokemony.ID = :id AND pokemony.ID = pokemon_jagody.id_poka',
            [':id' => $id]);
    }

    public function czyIstnieje($id)
    {
        return $this->db->select('SELECT ID FROM pokemony WHERE ID = :id AND wlasciciel = :idW', [':id' => $id, ':idW' => Session::_get('id')]);
    }

    public function zmienImie($imie, $pokemon)
    {
        $this->db->update("UPDATE pokemony SET imie = ? WHERE ID = ?", [$imie, $pokemon]);
    }

    public function atakWyzszy($wyzsza, $i, $at1, $at2, $pokemon)
    {
        $this->db->update("UPDATE pokemony SET atak$wyzsza = ?, atak$i = ? WHERE ID = ? AND wlasciciel = ?",
            [$at1, $at2, $pokemon, Session::_get('id')]);
    }

    public function atakNizszy($wyzsza, $i, $at1, $at2, $pokemon)
    {
        $this->db->update("UPDATE pokemony SET atak$wyzsza = ?, atak$i = ? WHERE ID = ? AND wlasciciel = ?",
            [$at1, $at2, $pokemon, Session::_get('id')]);
    }

    public function karmienie($wlasciciel)
    {
        return $this->db->select('SELECT karmienie_ip FROM uzytkownicy WHERE ID= :id', [':id' => $wlasciciel]);
    }

    public function nakarm($ip, $wlasciciel)
    {
        $this->db->update('UPDATE pokemony SET exp = (exp + 2) WHERE druzyna = 1 AND wlasciciel = ?', [$wlasciciel]);
        $this->db->update('UPDATE uzytkownicy SET karmienie = 1, karmienie_ip = CONCAT(karmienie_ip, \'|'.$ip.'\') WHERE ID= ? ', [$wlasciciel]);
    }

    public function ewolucja($pokemon)
    {
        return $this->db->select('SELECT ewolucja FROM pokemony WHERE ID= :id AND wlasciciel= :idW ',
            [':id' => $pokemon, ':idW' => Session::_get('id')]);
    }

    public function zmienEwolucja($i, $pokemon)
    {
        $this->db->update('UPDATE pokemony SET ewolucja = ? WHERE ID = ?', [$i, $pokemon]);
    }

    public function podglad($pokemon)
    {
        return $this->db->select('SELECT blokada_podgladu FROM pokemony WHERE ID=:id AND wlasciciel= :idW',
            [':id' => $pokemon, ':idW' => Session::_get('id')]);
    }

    public function zmienBlokada($i, $pokemon)
    {
        $this->db->update('UPDATE pokemony SET blokada_podgladu = ? WHERE ID = ?', [$i, $pokemon]);
    }
}
<?php

namespace src\includes\functions;

use src\includes\PokemonWalka;
use src\libs\Debug;
use src\libs\Session;
use src\libs\User;

trait FunctionsPolowanie
{
    private function obliczenieCelnosci($celnosc)
    {
        $l = mt_rand(0, 100);
        if ($l < (100 - $celnosc))
            return 1;
        else
            return 2;
    }

    private function rodzaj($i)
    {
        require('./src/includes/pokemony/rodzaj.php');
        return $rodzaj[$i];
    }

    private function pokemon_plik($id)
    {
        require('./src/includes/pokemony/pokemon.php');
        return $pokemon_plik[$id];
    }

    private function przyrost($id)
    {
        require('./src/includes/pokemony/przyrosty.php');
        return $przyrost[$id];
    }

    private function generuj($poke, $lvl, $atakii)
    {
        require('./src/includes/pokemony/przyrosty.php');
        require('./src/includes/pokemony/staty_poczatkowe.php');
        require('./src/includes/ataki/ataki.php');
        $celnosc = rand(55, 80);
        $atak_atak = 0;
        $sp_atak = 0;
        $obrona = 0;
        $sp_obrona = 0;
        $szybkosc = 0;
        $hp = 0;
        if ($przyrost[$poke]['min_poziom'] > 1 && $przyrost[$poke]['min_poziom'] != 100)///jeśli minimalny poziom większy od 1, ale nie równy 100, to pobierz przyrosty preevolucji
        {
            $co = $przyrost[$poke]['poprzedni'];
            //echo "SELECT * FROM pokemon, przyrosty WHERE pokemon.id_poka = $co AND pokemon.id_poka = przyrosty.id_poka";
            $wiersz2 = $przyrost[$co];
            if ($wiersz2['min_poziom'] > 1)///jeśli minimalny poziom nadal większy od 1, to pobierz przyrosty preevolucji
            {//3 evo
                $co = $przyrost[$co]['poprzedni'];
                $wiersz3 = $przyrost[$co];
                //3forma
                $atak_atak += ($lvl - $przyrost[$co]['min_poziom'] + 3) * $przyrost[$co]['atak'];
                $sp_atak = $sp_atak + ($lvl - $przyrost[$co]['min_poziom'] + 3) * $przyrost[$co]['sp_atak'];
                $obrona = $obrona + ($lvl - $przyrost[$co]['min_poziom'] + 3) * $przyrost[$co]['obrona'];
                $sp_obrona = $sp_obrona + ($lvl - $przyrost[$co]['min_poziom'] + 3) * $przyrost[$co]['sp_obrona'];
                $szybkosc = $szybkosc + ($lvl - $przyrost[$co]['min_poziom'] + 3) * $przyrost[$co]['szybkosc'];
                $hp = $hp + ($lvl - $przyrost[$co]['min_poziom'] + 3) * $przyrost[$co]['hp'];
                //2forma
                $atak_atak += ($przyrost[$co]['min_poziom'] - $wiersz2['min_poziom'] + 2) * $wiersz2['atak'];
                $sp_atak = $sp_atak + ($przyrost[$co]['min_poziom'] - $wiersz2['min_poziom'] + 2) * $wiersz2['sp_atak'];
                $obrona = $obrona + ($przyrost[$co]['min_poziom'] - $wiersz2['min_poziom'] + 2) * $wiersz2['obrona'];
                $sp_obrona = $sp_obrona + ($przyrost[$co]['min_poziom'] - $wiersz2['min_poziom'] + 2) * $wiersz2['sp_obrona'];
                $szybkosc = $szybkosc + ($przyrost[$co]['min_poziom'] - $wiersz2['min_poziom'] + 2) * $wiersz2['szybkosc'];
                $hp = $hp + ($przyrost[$co]['min_poziom'] - $wiersz2['min_poziom'] + 2) * $wiersz2['hp'];
                //1forma
                $atak_atak += ($wiersz2['min_poziom'] - 2) * $wiersz3['atak'];
                $sp_atak = $sp_atak + ($wiersz2['min_poziom'] - 2) * $wiersz3['sp_atak'];
                $obrona = $obrona + ($wiersz2['min_poziom'] - 2) * $wiersz3['obrona'];
                $sp_obrona = $sp_obrona + ($wiersz2['min_poziom'] - 2) * $wiersz3['sp_obrona'];
                $szybkosc = $szybkosc + ($wiersz2['min_poziom'] - 2) * $wiersz3['szybkosc'];
                $hp = $hp + ($wiersz2['min_poziom'] - 2) * $wiersz3['hp'];

                $rezultat10 = $staty[$co];
            } else {//2 evo
                $rezultat10 = $staty[$co];
                //2forma
                $atak_atak += ((($lvl - $przyrost[$co]['min_poziom']) + 3) * $przyrost[$co]['atak']);
                $sp_atak = $sp_atak + ((($lvl - $przyrost[$co]['min_poziom']) + 3) * $przyrost[$co]['sp_atak']);
                $obrona = $obrona + ((($lvl - $przyrost[$co]['min_poziom']) + 3) * $przyrost[$co]['obrona']);
                $sp_obrona = $sp_obrona + ((($lvl - $przyrost[$co]['min_poziom']) + 3) * $przyrost[$co]['sp_obrona']);
                $szybkosc = $szybkosc + ((($lvl - $przyrost[$co]['min_poziom']) + 3) * $przyrost[$co]['szybkosc']);
                $hp = $hp + ((($lvl - $przyrost[$co]['min_poziom']) + 3) * $przyrost[$co]['hp']);
                //1forma
                $atak_atak += (($przyrost[$co]['min_poziom'] - 2) * $wiersz2['atak']);
                $sp_atak = $sp_atak + (($przyrost[$co]['min_poziom'] - 2) * $wiersz2['sp_atak']);
                $obrona = $obrona + (($przyrost[$co]['min_poziom'] - 2) * $wiersz2['obrona']);
                $sp_obrona = $sp_obrona + (($przyrost[$co]['min_poziom'] - 2) * $wiersz2['sp_obrona']);
                $szybkosc = $szybkosc + (($przyrost[$co]['min_poziom'] - 2) * $wiersz2['szybkosc']);
                $hp = $hp + (($przyrost[$co]['min_poziom'] - 2) * $wiersz2['hp']);
            }
        } else {/////przyrosty pokemona 1 evo
            $rezultat10 = $staty[$poke];
            $atak_atak += (($lvl - 1) * $przyrost[$poke]['atak']);
            $sp_atak = $sp_atak + (($lvl - 1) * $przyrost[$poke]['sp_atak']);
            $obrona = $obrona + (($lvl - 1) * $przyrost[$poke]['obrona']);
            $sp_obrona = $sp_obrona + (($lvl - 1) * $przyrost[$poke]['sp_obrona']);
            $szybkosc = $szybkosc + (($lvl - 1) * $przyrost[$poke]['szybkosc']);
            $hp = $hp + (($lvl - 1) * $przyrost[$poke]['hp']);
        }
        $wiersz10 = $rezultat10;
        $atak_atak += $wiersz10['atak'];
        $sp_atak += $wiersz10['sp_atak'];
        $obrona += $wiersz10['obrona'];
        $sp_obrona += $wiersz10['sp_obrona'];
        $szybkosc += $wiersz10['szybkosc'];
        $hp += $wiersz10['hp'];
        ///////////STATY KONIEC///////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////
        //ataki:
        $dl = strlen($atakii);
        $j = 0;
        $str = $atakii;
        $k = 0;
        $co = 1;
        unset($atakk);
        unset($pozz);
        while ($j < $dl) {
            if ($str[$j] == ';') {
                $k++;
                $co = 1;
            } else if ($str[$j] == ',') {
                $co = 2;
            } else {
                if ($co == 1) {
                    if (!(isset($atakk[$k])) || ($atakk[$k] == '')) {
                        $atakk[$k] = (string)$str[$j];
                    } else {
                        $atakk[$k] = $atakk[$k] . (string)$str[$j];
                    }
                } else {
                    if (!(isset($pozz[$k])) || ($pozz[$k] == '')) {
                        $pozz[$k] = (string)$str[$j];
                    } else {
                        $pozz[$k] = $pozz[$k] . (string)$str[$j];
                    }
                }
            }
            $j++;
        }
        $f1 = 0;
        for (; $f1 < $k; $f1++)
            if ($pozz[$f1] > $lvl) break;
            else $atak_t[$f1] = $atakk[$f1];

        if ($f1 <= 4)
            for ($abcd = 0; $abcd < 4; $abcd++) {
                if (!isset($atak_t[$abcd])) {
                    $atak_p[$abcd] = "-brak-";
                    $_SESSION['atak' . $abcd]['nazwa'] = "-brak-";
                } else $atak_p[$abcd] = $atak_t[$abcd];
            }
        else {
            $b['0'] = 0;
            $b['1'] = 0;
            $b['2'] = 0;
            $b['3'] = 0;
            //$ill = 1;
            while ($b['0'] == $b['1'] || $b['0'] == $b['2'] || $b['0'] == $b['3']
                || $b['1'] == $b['2'] || $b['1'] == $b['3'] || $b['2'] == $b['3']) {
                $b['0'] = (rand() % ($f1 - 1)) + 1;
                $b['1'] = (rand() % ($f1 - 1)) + 1;
                $b['2'] = (rand() % ($f1 - 1)) + 1;
                $b['3'] = (rand() % ($f1 - 1)) + 1;
                //echo $ill;
                //$ill++;
            }
            for ($abcd = 0; $abcd < 4; $abcd++)
                $atak_p[$abcd] = $atak_t[$b[$abcd]];
        }
        $att = 0;
        for ($b = 0; $b < 4; $b++)
            if (isset($ataki[$atak_p[$b]])) {
                $att++;
                $wiersz11 = $ataki[$atak_p[$b]];
                /*echo "nazwa: ".$wiersz11['nazwa']."<br />";
                    echo "moc: ".$wiersz11['moc']."<br />";
                    echo "typ: ".$wiersz11['typ']."<br />";
                    echo "celnosc: ".$wiersz11['celnosc']."<br />";*/

                $atak = array
                (
                    'id' => $atak_p[$b],
                );
                $atak_p[$b] = $wiersz11['nazwa'];
                $_SESSION['atak' . $b] = $atak;
            }

        if ($att < 4)
            for ($asd = $att; $asd < 4; $asd++)
                $_SESSION['atak' . $asd]['id'] = 0;
        ////////////////ATAKI KONIEC//////////////////////////////////////////
        //////////////////////WARTOŚĆ POKEMONA!///////////////////////
        $pokemonn = array/////////////POKEMON PRZECIWNIK DO WALKI
        (
            'pok_id' => $poke,
            'pok_atak' => $atak_atak,
            'pok_sp_atak' => $sp_atak,
            'pok_obrona' => $obrona,
            'pok_sp_obrona' => $sp_obrona,
            'pok_szybkosc' => $szybkosc,
            'pok_hp' => $hp,
            'pok_poziom' => $lvl,
            'celnosc' => $celnosc
        );
        return $pokemonn;
    }

    private function lap($ball, $poziom, $trudnosc, $db)
    {
        $show = '';
        if ($poziom == 1 && $trudnosc == 1) $szansa = 19;
        else if ($poziom == 1) $szansa = 19 / ($trudnosc * 0.75);
        else if ($trudnosc == 1) $szansa = 19 - ($poziom * 0.19);
        else $szansa = (19 - ($poziom * 0.19)) / ($trudnosc * 0.75);

        if ($ball == "repeatball") {///repeatball
            if (!Session::_isset('zm')) Session::_set('zm', 0);
            if (Session::_get('zm') > 0) {
                if (Session::_get('zm') < 26)
                    $szansa *= (1.70 - 0.05 * Session::_get('zm'));
            } else $szansa *= 1.70;
            Session::_set('zm', (Session::_get('zm') + 1));
        } elseif ($ball == "nestball") {
            if ($poziom < 16) {///poziom nizszy od 16 - nestball dziala najlepiej
                if ($trudnosc == 1) $szansa = 50 - ($poziom * 0.1);
                else $szansa = (55 - ($poziom * 0.1)) / ($trudnosc * 0.8);
            } else {
                if ($trudnosc == 1) $szansa = 30 - ($poziom * 0.4);
                else $szansa = (25 - ($poziom * 0.4)) / ($trudnosc * 0.8);
            }
        } elseif ($ball == "greatball") {
            $szansa *= 2;
        } elseif ($ball == "ultraball") {
            $szansa *= 4;
        } elseif ($ball == "lureball") {
            if ((Session::_get('pokemon')['typ1'] == Session::_get('twojpok')['typ1'] || Session::_get('pokemon')['typ1'] == Session::_get('twojpok')['typ2'])
                || (Session::_get('pokemon')['typ2'] == Session::_get('twojpok')['typ1'] || Session::_get('pokemon')['typ2'] == Session::_get('twojpok')['typ2'])
            )
                $szansa *= 4;
        } elseif ($ball == "duskball") {
            $godz = date('G');

            if ($godz > 21 || $godz < 6) {
                $szansa *= 3;
            }
        } elseif ($ball == "cherishball") {
            $szansa *= 7;
        } elseif ($ball == "safariball") {
            $szansa *= 1.7;
        }
        //if(){
        //echo '<p class="alert">DEBUG INFO</p>';
        //echo 'Pierwsza szansa: '.$szansa;
        //}
        Debug::addInfo('Pierwsza szansa', $szansa);
        if (User::$umiejetnosci->get('lapanie') > 0) $szansa *= (1 + User::$umiejetnosci->get('lapanie') / 10);
        //if(ADMIN){
        //echo '<br />Po umiejetnosci: '.$szansa;
        //}
        Debug::addInfo('Po umiejętności', $szansa);
        if ($trudnosc > 4) $szansa *= 0.75;
        if ($poziom <= 20 && $trudnosc == 1 && $trudnosc <= 4) $szansa *= 1.5;
        else if ($poziom <= 20 && $trudnosc != 1 && $trudnosc <= 4) $szansa *= 1.19;
        ///dorobić jeden kolejne pokeballe
        //if(ADMIN) echo "<br />Po bonusach: ".$szansa;
        Debug::addInfo('Po bonusach', $szansa);
        if (User::$przedmioty->get('pokedex') > 0) {
            //if(ADMIN) echo "<br />Pokedex:".(1+(User::$przedmioty->get('pokedex')/10));
            $szansa *= (1 + (User::$przedmioty->get('pokedex') / 10));
        }
        //if(ADMIN) echo "<br />Po pokedexie: ".$szansa;
        Debug::addInfo('Po pokedexie', $szansa);
        $rand = rand(90, 105) / 100;
        if (User::$odznaki->kanto[3]) $szansa *= 1.1;
        //if(ADMIN) echo "<br />Po odznace: ".$szansa;
        Debug::addInfo('Po odznace', $szansa);
        $szansa = $szansa * $rand;
        if ($poziom > 50) $szansa /= 2;
        if ($szansa < 3) $szansa = 3;///minimalna szansa zlapania
        if ($szansa > 85) $szansa = 85;///maksymalna szansa zlapania (nie licząc mastera)
        /////losowanie czy złapano poka
        $szansa = round($szansa * 100);////bierzemy pod uwagę tylko dwie liczby po przecinku.

        ///max = 10000
        //if(ADMIN) echo "<br />Szansa do funkcji (ostateczna szansa złapania) ".'<span class="alert">'.($szansa/100).'%</span>';
        if ($ball == "masterball") {
            $szansa = 100 * 100;
        }
        Debug::addInfo('Ostateczna szansa', $szansa);
        $liczba = (rand() % 10000) + 1;
        //if (ADMIN) {
        //echo '<br />Wylosowana liczba: '.$liczba;
        //echo '<br /><div class="alert">DEBUG INFO KONIEC</div>';
        //}
        Debug::addInfo('Wylosowana liczba', $liczba);
        if ($liczba <= $szansa) {
            if (Session::_isset('zm')) Session::_unset('zm');
            if (Session::_get('pokemon')['zlapany'] < 1) {
                if (Session::_get('poziom') < 15) {
                   $dos = Session::_get('poziom') * 12;
                } else {
                   $dos = 200;
                }
                $show .= '<div class="alert alert-success text-medium text-center"><span>PIERWSZY RAZ ŁAPIESZ TAKIEGO POKEMONA</span></div>';
                $show .= '<div class="alert alert-success text-medium text-center"><span>Otrzymujesz '. $dos .'PD</span></div>';
                Session::_set('tr_exp', (Session::_get('tr_exp') + $dos));
                $db->update('UPDATE uzytkownicy SET doswiadczenie = (doswiadczenie + ?) WHERE ID = ?', [$dos, Session::_get('id')]);
            }
            $godzina = date('Y-m-d-H-i-s');
            $show .= '<div class="alert alert-success text-medium text-center"><span>Udało Ci się złapać <span class="pogrubienie">' . Session::_get('pokemon')['pok_nazwa'] . '</span></span></div>';
            $i1 = Session::_get('pokemon')['pok_id'] . "z";
            $k = "UPDATE kolekcja SET $i1 = ($i1 + 1) WHERE ID = ?";
            $db->update($k, [Session::_get('id')]);
            if ($ball != "masterball") {
                $a = 'zl_' . $ball;
                $k = "UPDATE osiagniecia SET zlapane_poki = (zlapane_poki + 1), $a = ( $a + 1 )  WHERE id_gracza = ?";
                $db->update($k, [Session::_get('id')]);
            } else {
                $k = "UPDATE osiagniecia SET zlapane_poki = (zlapane_poki + 1) WHERE id_gracza = ?";
                $db->update($k, [Session::_get('id')]);
            }
            if (User::$odznaki->zlapanych == 0) {//dzienny bonus

                if (Session::_get('poziom') < 20) {
                    $show .= '<div class="alert alert-success text-medium text-center"><span>Udało Ci się złapać pierwszego Pokemona dzisiaj.
                <br />Otrzymujesz bonus w postaci 50 PD dla każdego Pokemona w drużynie.</span></div>';
                    $pokPd = 50;
                } else {
                    $show .= '<div class="alert alert-success text-medium text-center"><span>Udało Ci się złapać pierwszego Pokemona dzisiaj.
                    <br />Otrzymujesz bonus w postaci 150 PD i po 100 PD dla każdego Pokemona w drużynie.</span></div>';
                    $db->update('UPDATE uzytkownicy SET doswiadczenie = (doswiadczenie + 150) WHERE ID = ?', [Session::_get('id')]);
                    Session::_set('tr_exp', (Session::_get('tr_exp') + 150));
                    $pokPd = 100;
                }

                $db->update('UPDATE pokemony SET exp = (exp + ?) WHERE druzyna = 1 AND wlasciciel = ?', [$pokPd, Session::_get('id')]);
                for ($i = 1; $i < 7; $i++) {
                    if (User::_isset('pok', $i)) {
                        User::_get('pok', $i)->edit('dos', (User::_get('pok', $i)->get('dos') + $pokPd));
                    }
                }
            }
            User::$odznaki->zlapanych++;//?
            //$odznaka->zlapanych++;
            Session::_set('poki_magazyn', (Session::_get('poki_magazyn') + 1));
            /////dodanie pokemona do rezerwy
            $atak1 = Session::_get('atak0')['id'];
            $atak2 = Session::_get('atak1')['id'];
            $atak3 = Session::_get('atak2')['id'];
            $atak4 = Session::_get('atak3')['id'];

            if (Session::_get('poki_magazyn') <= Session::_get('magazyn')) {
                if (Session::_get('pokemon')['shiny'] == 0) {
                    $pok_id = Session::_get('pokemon')['pok_id'];
                    $pok_nazwa = Session::_get('pokemon')['pok_nazwa'];
                    $pok_poziom = Session::_get('pokemon')['pok_poziom'];
                    $pok_atak = Session::_get('pokemon')['pok_atak'];
                    $pok_obrona = Session::_get('pokemon')['pok_obrona'];
                    $pok_sp_atak = Session::_get('pokemon')['pok_sp_atak'];
                    $pok_sp_obrona = Session::_get('pokemon')['pok_sp_obrona'];
                    $pok_szybkosc = Session::_get('pokemon')['pok_szybkosc'];
                    $pok_hp = Session::_get('pokemon')['pok_hp'];
                    $plec = Session::_get('pokemon')['plec'];
                    $wartosc = Session::_get('pokemon')['wartosc'];
                    $celnosc = Session::_get('pokemon')['celnosc'];
                    $shiny = Session::_get('pokemon')['shiny'];
                    //////pok do kwerendy
                    $tab = [
                        $pok_id, $pok_nazwa, $pok_poziom, Session::_get('id'), $pok_atak, $pok_obrona, $pok_sp_atak, $pok_sp_obrona, $pok_szybkosc, $pok_hp, $pok_hp,
                        $atak1, $atak2, $atak3, $atak4, Session::_get('id'), $plec, $wartosc, $godzina, $celnosc, $shiny, Session::_get('jakosc'), ucfirst($ball)
                    ];
                } elseif (Session::_get('pokemon')['shiny'] == 1) {
                    require('./src/includes/pokemony/staty_poczatkowe.php');
                    require('./src/includes/pokemony/przyrosty.php');
                    $pok_id = Session::_get('pokemon')['pok_id'];
                    $pok_nazwa = Session::_get('pokemon')['pok_nazwa'];
                    $plec = Session::_get('pokemon')['plec'];
                    $wartosc = Session::_get('pokemon')['wartosc'];
                    $celnosc = Session::_get('pokemon')['celnosc'];
                    $shiny = Session::_get('pokemon')['shiny'];
                    $tab = array();
                    $tab['zlapany'] = ucfirst($ball);
                    $tab['id_poka'] = $pok_id;
                    $tab['imie'] = $pok_nazwa;
                    $tab['poziom'] = przyrost($pok_id)['min_poziom'];
                    $ataki = $db->sql_query("SELECT ataki FROM pokemon WHERE id_poka = $pok_id");
                    $ataki = $ataki->fetch_assoc();
                    $ataki = $ataki['ataki'];
                    unset($_SESSION['atak0']);
                    unset($_SESSION['atak1']);
                    unset($_SESSION['atak2']);
                    unset($_SESSION['atak3']);
                    $a = generuj_poka($tab['id_poka'], $tab['poziom'], $ataki, $db);
                    if ($tab['poziom'] > 1) {
                        $tab['Atak'] = $a['pok_atak'];
                        $tab['Obrona'] = $a['pok_obrona'];
                        $tab['Sp_Atak'] = $a['pok_sp_atak'];
                        $tab['Sp_Obrona'] = $a['pok_sp_obrona'];
                        $tab['Szybkosc'] = $a['pok_szybkosc'];
                        $tab['HP'] = $a['pok_hp'];
                        $tab['akt_HP'] = $a['pok_hp'];
                    } else {
                        $tab['Atak'] = $staty[$pok_id]['atak'];
                        $tab['Obrona'] = $staty[$pok_id]['obrona'];
                        $tab['Sp_Atak'] = $staty[$pok_id]['sp_atak'];
                        $tab['Sp_Obrona'] = $staty[$pok_id]['sp_obrona'];
                        $tab['Szybkosc'] = $staty[$pok_id]['szybkosc'];
                        $tab['HP'] = $staty[$pok_id]['hp'];
                        $tab['akt_HP'] = $staty[$pok_id]['hp'];
                    }
                    $tab['wlasciciel'] = $user->__get('id');
                    for ($asd = 1; $asd <= 4; $asd++)
                        $tab['atak' . $asd] = $_SESSION['atak' . ($asd - 1)]['id'];
                    $tab['plec'] = $plec;
                    $tab['wartosc'] = $wartosc;
                    $tab['data_zlapania'] = $godzina;
                    $tab['celnosc'] = $celnosc;
                    $tab['shiny'] = $shiny;
                    $tab['jakosc'] = $_SESSION['jakosc'];
                    $db->sql_query("UPDATE uzytkownicy SET zlapana_grupa = 1 WHERE ID = '" . $user->__get('id') . "'");
                    $db->sql_query("UPDATE shiny SET ilosc_do_zlapania = (ilosc_do_zlapania - 1) WHERE ID = 1");
                    $db->sql_query("UPDATE osiagniecia SET shiny = (shiny + '1') WHERE id_gracza = '" . $user->__get('id') . "'");
                }
                $kwer = 'INSERT INTO pokemony (id_poka, imie, poziom, wlasciciel, Atak, Obrona, Sp_Atak, Sp_Obrona, Szybkosc, HP, akt_HP, atak1, atak2, atak3, atak4,
                     pierwszy_wlasciciel, plec, wartosc, data_zlapania, celnosc, shiny, jakosc, zlapany) 
                     VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ) ';
                $db->insert($kwer, $tab);

                $id_pol = $db->lastInsertId();
                //$db->sql_query("INSERT INTO pokemony ");
                /*if(ADMIN)
                {
                  echo '<p class="alert">src\libs\Debug INFO:</p>';
                  echo "INSERT INTO pokemony (id_poka, imie, poziom, wlasciciel, Atak, Obrona, Sp_Atak, Sp_Obrona, Szybkosc, HP, akt_HP, atak1, atak2, atak3, atak4, plec, wartosc, data_zlapania, celnosc)
                  VALUES('$pok_id', '$pok_nazwa', '$pok_poziom', '".$user->__get('id')."', '$pok_atak', '$pok_obrona', '$pok_sp_atak', '$pok_sp_obrona',
                  '$pok_szybkosc', '$pok_hp', '$pok_hp', '$atak1', '$atak2', '$atak3', '$atak4', '$plec', '$wartosc', '$godzina', '$celnosc')";
                  echo '<br />ID: '.$id_pol;
                  echo '<br /><p class="alert">DEBUG INFO KONIEC</p>';
                }*/
                Debug::addInfo('Kwerenda', "INSERT INTO pokemony (id_poka, imie, poziom, wlasciciel, Atak, Obrona, Sp_Atak, Sp_Obrona, Szybkosc, HP, akt_HP, atak1, atak2, atak3, atak4, plec, wartosc, data_zlapania, celnosc, jakosc, shiny, zlapany)
              VALUES('$pok_id', '$pok_nazwa', '$pok_poziom', '" . Session::_get('id') . "', '$pok_atak', '$pok_obrona', '$pok_sp_atak', '$pok_sp_obrona',
              '$pok_szybkosc', '$pok_hp', '$pok_hp', '$atak1', '$atak2', '$atak3', '$atak4', '$plec', '$wartosc', '$godzina', '$celnosc', '" . Session::_get('jakosc') . "', '$shiny', '$ball')");
                Debug::addInfo('ID', $id_pol);
                $limit = 5 * rand(50, 75);

                $db->insert('INSERT INTO pokemon_jagody (id_poka, Jag_Limit) VALUES (?, ?)', [$id_pol, $limit]);
            } else {
                $show .= '<div class="alert alert-danger text-medium text-center"><span>Niestety nie masz już miejsca w magazynie na kolejnego pokemona!<br />Sprzedajesz pokemona do hodowli za cenę ' . $_SESSION['pokemon']['wartosc'] . ' &yen;</span></div>';
                $wartosc = Session::_get('pokemon')['wartosc'];
                $db->update('UPDATE uzytkownicy SET pieniadze = ( pieniadze + ?) WHERE ID = ?', [$wartosc, Session::_get('id')]);
                Session::_set('kasa', (Session::_get('kasa') + $wartosc));
                Session::_set('poki_magazyn', (Session::_get('poki_magazyn') - 1));
            }
            $db->update('UPDATE statystyki SET zlapanych = (zlapanych + 1) WHERE id_gracza = ?', [Session::_get('id')]);
        } else {
            if ($ball == 'repeatball') {
                $show .= '<div class="alert alert-warning text-medium text-center"><span>Pokemon jest ogłuszony, masz szansę rzucić kolejnego pokeballa</span></div>';
                Session::_set('lap', 1);
                $_SESSION['walkat1'] = '';
                $this->wyswietlPokeballe($db);
                $show .= $_SESSION['walkat1'];
            } else {
                $show .= '<div class="alert alert-danger text-medium text-center"><span>Niestety pokemon uwolnił się i uciekł</span></div>';
                Session::_unset('atak0');
                Session::_unset('atak1');
                Session::_unset('atak2');
                Session::_unset('atak3');
                Session::_unset('pokemon');
                Session::_unset('twojpok');
            }
        }
        return $show;
    }

    private function wyswietlPokeballe($db)
    {
        if (Session::_isset('zm')) Session::_set('zm', 0);
        $_SESSION['walkat1'] .= '<div class="walka_alert alert alert-success text-medium text-center-alert margin-top"><span class="span-text-center">Pokemon jest ogłuszony, możesz rzucić w niego pokeball</span></div>';
        $_SESSION['walkat1'] .= '<div class="row row-centered">';
        $rezultaty = $db->select("SELECT * FROM pokeballe WHERE id_gracza = :id", [':id' => Session::_get('id')]);
        $wiersz = $rezultaty[0];
        Session::_set('lap', Session::_get('dzicz'));
        if (Session::_get('lap') != 'safari') {
            if ($wiersz['pokeballe']) {
                $_SESSION['walkat1'] .= '<button class="btn btn-primary padding_button_walka pokeball" data-toggle="tooltip" data-title="Pokeball" id="Pokeball" >';
                $_SESSION['walkat1'] .= '<img src="' . URL . 'public/img/balle/Pokeball.png" class="pokeball_img" /><br /><span class="hidden-md hidden-lg">Pokeballe<br /></span>' . $wiersz['pokeballe'] . ' sztuk.</button>';
            }
            if ($wiersz['nestballe']) {
                $_SESSION['walkat1'] .= '<button class="btn btn-primary padding_button_walka pokeball" data-toggle="tooltip" data-title="Nestball" id="Nestball" >';
                $_SESSION['walkat1'] .= '<img src="' . URL . 'public/img/balle/Nestball.png" class="pokeball_img" /><br /><span class="hidden-md hidden-lg">Nestballe<br /></span>' . $wiersz['nestballe'] . ' sztuk.</button>';
            }
            if ($wiersz['greatballe']) {
                $_SESSION['walkat1'] .= '<button class="btn btn-primary padding_button_walka pokeball" data-toggle="tooltip" data-title="Greatball" id="Greatball" >';
                $_SESSION['walkat1'] .= '<img src="' . URL . 'public/img/balle/Greatball.png" class="pokeball_img" /><br /><span class="hidden-md hidden-lg">Greatballe<br /></span>' . $wiersz['greatballe'] . ' sztuk.</button>';
            }
            if ($wiersz['ultraballe']) {
                $_SESSION['walkat1'] .= '<button class="btn btn-primary padding_button_walka pokeball" data-toggle="tooltip" data-title="Ultraball" id="Ultraball" >';
                $_SESSION['walkat1'] .= '<img src="' . URL . 'public/img/balle/Ultraball.png" class="pokeball_img" /><br /><span class="hidden-md hidden-lg">Ultraballe<br /></span>' . $wiersz['ultraballe'] . ' sztuk.</button>';
            }
            if ($wiersz['duskballe']) {
                $_SESSION['walkat1'] .= '<button class="btn btn-primary padding_button_walka pokeball" data-toggle="tooltip" data-title="Duskball" id="Duskball" >';
                $_SESSION['walkat1'] .= '<img src="' . URL . 'public/img/balle/Duskball.png" class="pokeball_img" /><br /><span class="hidden-md hidden-lg">Duskballe<br /></span>' . $wiersz['duskballe'] . ' sztuk.</button>';
            }
            if ($wiersz['lureballe'] > 0) {
                $_SESSION['walkat1'] .= '<button class="btn btn-primary padding_button_walka pokeball" data-toggle="tooltip" data-title="Lureball" id="Lureball" >';
                $_SESSION['walkat1'] .= '<img src="' . URL . 'public/img/balle/Lureball.png" class="pokeball_img" /><br /><span class="hidden-md hidden-lg">Lureballe<br /></span>' . $wiersz['lureballe'] . ' sztuk.</button>';
            }
            if ($wiersz['repeatballe'] > 0) {
                $_SESSION['walkat1'] .= '<button class="btn btn-primary padding_button_walka pokeball" data-toggle="tooltip" data-title="Repeatball" id="Repeatball" >';
                $_SESSION['walkat1'] .= '<img src="' . URL . 'public/img/balle/Repeatball.png" class="pokeball_img" /><br /><span class="hidden-md hidden-lg">Repeatballe<br /></span>' . $wiersz['repeatballe'] . ' sztuk.</button>';
            }
            if ($wiersz['cherishballe'] > 0) {
                $_SESSION['walkat1'] .= '<button class="btn btn-primary padding_button_walka pokeball" data-toggle="tooltip" data-title="Cherishball" id="Cherishball" >';
                $_SESSION['walkat1'] .= '<img src="' . URL . 'public/img/balle/Cherishball.png" class="pokeball_img" /><br /><span class="hidden-md hidden-lg">Cherishballe<br /></span>' . $wiersz['cherishballe'] . ' sztuk.</button>';
            }
            if ($wiersz['masterballe'] > 0) {
                $_SESSION['walkat1'] .= '<button class="btn btn-primary padding_button_walka pokeball" data-toggle="tooltip" data-title="Masterball" id="Masterball" >';
                $_SESSION['walkat1'] .= '<img src="' . URL . 'public/img/balle/Masterball.png" class="pokeball_img" /><br /><span class="hidden-md hidden-lg">Masterballe<br /></span>' . $wiersz['masterballe'] . ' sztuk.</button>';
            }
        } else {
            if ($wiersz['safariballe'] > 0) {
                $_SESSION['walkat1'] .= '<button class="btn btn-primary padding_button_walka nomargin pokeball" data-toggle="tooltip" data-title="Safariball" id="Safariball" >';
                $_SESSION['walkat1'] .= '<img src="' . URL . 'public/img/balle/Safariball.png" class="pokeball_img" /><br /><span class="hidden-md hidden-lg">Safariballe<br /></span>' . $wiersz['safariballe'] . ' sztuk.</button>';
            }
        }
        $_SESSION['walkat1'] .= '</div>';//row
    }

    private function walkaPokemonow($wiersz, $db, $trener = 0, $stan1 = 0, $stan2 = 0, $runda1 = 0, $runda2 = 0, $pulapka1 = 0, $pulapka2 = 0, $at1 = 1, $at2 = 1, $pok2 = 0, $gracz = 0, $atak_runda1 = 0, $atak_runda2 = 0, $dos1 = 0, $dos2 = 0)
    {
        require('./src/includes/ataki/ataki.php');
        require('./src/includes/ataki/ataki_sp.php');
        require('./src/includes/odpornosci/odpornosci.php');
        Session::_set('twojpoknazwa', $wiersz['imie']);
        ////stan = 1 - podpalenie, zabiera 1/8 życia
        ////stan = 2 - paraliż
        ////stan = 3 - otępienie 50% szans na zaatakowanie samego siebie
        ////stan = 4 - otrucie
        ////stan = 5 - śmiertelne otrucie
        ////stan = 6 - sen
        ////stan = 7 - pułapka - obrażenia co rundę takie jak we wcześniejszej
        ////stan = 8 - zamrożenie
        ////stan = 9 - oszołomienie
        ////stan = 10 - zakochanie
        ////stan = 11 - klątwa
        if ($trener == 0) {
            $szcz1 = (rand(-100, 100)) / 10;
            $szcz2 = (rand(-100, 100)) / 10;
        }
        $alert1 = 1;
        $alert2 = 1;
        $_SESSION['walkat'] .= '<div class="row text-center margin-top">';
        if ($trener == 0 && $gracz == 0) {
            $_SESSION['walkat'] .= '<div class="col-xs-12">';
            if ($szcz1 > 0) {
                $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info text-center"><span><span class="pogrubienie">' . $_SESSION['pokemon']['pok_nazwa'] . '</span> ma szczęście w walce. ';
                $_SESSION['pokemon']['plec'] == 1 ? $_SESSION['walkat'] .= 'Jej' : $_SESSION['walkat'] .= 'Jego';
                $_SESSION['walkat'] .= ' statystyki zwiększają się o <span class="zielony">' . $szcz1 . ' %</span>.</span></div>';
            } elseif ($szcz1 < 0) {
                $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info text-center"><span><span class="pogrubienie">' . $_SESSION['pokemon']['pok_nazwa'] . '</span> ma pecha w walce. ';
                $_SESSION['pokemon']['plec'] == 1 ? $_SESSION['walkat'] .= 'Jej' : $_SESSION['walkat'] .= 'Jego';
                $_SESSION['walkat'] .= ' statystyki zmniejszają się o <span class="czerwony">' . (-$szcz1) . ' %</span>.</span></div>';
            }

            if ($szcz2 > 0) {
                $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info text-center"><span><span class="pogrubienie">' . $wiersz['imie'] . '</span> ma szczęście w walce. ';
                $wiersz['plec'] == 1 ? $_SESSION['walkat'] .= 'Jej' : $_SESSION['walkat'] .= 'Jego';
                $_SESSION['walkat'] .= ' statystyki zwiększają się o <span class="zielony">' . $szcz2 . ' %</span>.</span></div>';
            } elseif ($szcz2 < 0) {
                $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info text-center"><span><span class="pogrubienie">' . $wiersz['imie'] . '</span> ma pecha w walce. ';
                $wiersz['plec'] == 1 ? $_SESSION['walkat'] .= 'Jej' : $_SESSION['walkat'] .= 'Jego';
                $_SESSION['walkat'] .= ' statystyki zmniejszają się o <span class="czerwony">' . (-$szcz2) . ' %</span>.</span></div>';
            }
            $_SESSION['walkat'] .= '</div>';
        }
        if ($trener == 0 && $gracz == 0) {
            $szcz1 = $szcz1 / 100;
            $szcz2 = $szcz2 / 100;
            $alert1 += $szcz1;
            $alert2 += $szcz2;
        }
        //przywiazanie//
        if ($gracz == 0 && ($dos1 == 0 || $dos2 == 0)) {
            if ($dos1 == 0) {
                $tlo = przywiazanie($wiersz['przywiazanie']);
                $plus = 0;
                if ($tlo > 70) $plus = $tlo - 70;
                if ($plus > 0) {
                    $plus = round(($plus / 2), 2);
                    $alert2 += ($plus / 100);
                    $_SESSION['walkat'] .= '<div class="col-xs-12">';
                    $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info text-center"><span><span class="pogrubienie">' . $wiersz['imie'] . '</span> jest bardzo przywiązan';
                    $wiersz['plec'] == 1 ? $_SESSION['walkat'] .= 'a' : $_SESSION['walkat'] .= 'y';
                    $_SESSION['walkat'] .= ' do swojego trenera. ';
                    $wiersz['plec'] == 1 ? $_SESSION['walkat'] .= 'Jej' : $_SESSION['walkat'] .= 'Jego';
                    $_SESSION['walkat'] .= ' statystyki rosną o <span class="zielony">' . $plus . ' %</span>.</span></div></div>';
                }
            }
            //tu dodać do drugiego poka
            if ($wiersz['shiny'] == 1 && $dos1 == 0) {
                $ile = rand(899, 1101) / 100;
                $_SESSION['walkat'] .= '<div class="col-xs-12">';
                $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info text-center"><span>Ciało <span class="pogrubienie">' . $wiersz['imie'] . '</span> lśni przed walką. ';
                $wiersz['plec'] == 1 ? $_SESSION['walkat'] .= 'Jej' : $_SESSION['walkat'] .= 'Jego';
                $_SESSION['walkat'] .= ' statystyki rosną o <span class="zielony">' . $ile . ' %</span>.</span></div></div>';
                $alert2 += ($ile / 100);
            }
            if (((isset($_SESSION['pokemon']['shiny']) && $_SESSION['pokemon']['shiny'] == 1) || (isset($pok2['shiny']) && $pok2['shiny'] == 1)) && $dos2 == 0) {
                $ile = rand(899, 1101) / 100;
                $_SESSION['walkat'] .= '<div class="col-xs-12">';
                $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info text-center"><span>Ciało <span class="pogrubienie">';
                isset($_SESSION['pokemon']['imie']) ? $_SESSION['walkat'] .= $_SESSION['pokemon']['pok_nazwa'] : $_SESSION['walkat'] .= $pok2['pok_nazwa'];
                $_SESSION['walkat'] .= '</span> lśni przed walką.';
                $plec = isset($pok2['plec']) ? $pok2['plec'] : $_SESSION['pokemon']['plec'];
                $plec == 1 ? $_SESSION['walkat'] .= 'Jej' : $_SESSION['walkat'] .= 'Jego';
                $_SESSION['walkat'] .= ' statystyki rosną o  <span class="zielony">' . $ile . ' %</span>.</span></div></div>';
                $alert1 += ($ile / 100);
            }
        }
        $_SESSION['walkat'] .= '</div>';//row
        /////////////////////////////////////POKEMON1///////////////////////////////
        $pokemon[1] = new PokemonWalka();
        $pokemon[1]->stan = $stan1;
        $pokemon[1]->runda = $runda1;
        $pokemon[1]->pulapka = $pulapka1;
        $pokemon[1]->atak_runda = $atak_runda1;
        $pokemon[1]->shiny = isset($pok2['shiny']) ? $pok2['shiny'] : $_SESSION['pokemon']['shiny'];
        $pokemon[1]->nazwa = isset($pok2['pok_nazwa']) ? $pok2['pok_nazwa'] : $_SESSION['pokemon']['pok_nazwa'];
        $pokemon[1]->id_poka = isset($pok2['pok_id']) ? $pok2['pok_id'] : $_SESSION['pokemon']['pok_id'];
        $pokemon[1]->atak = isset($pok2['pok_atak']) ? floor($alert1 * $pok2['pok_atak']) : ($pok2['atak'] ? floor($alert1 * ($pok2['atak'] + $pok2['Jag_Atak'] + $pok2['tr_1'])) : floor($alert1 * $_SESSION['pokemon']['pok_atak']));
        $pokemon[1]->sp_atak = isset($pok2['pok_sp_atak']) ? floor($alert1 * $pok2['pok_sp_atak']) : ($pok2['sp_atak'] ? floor($alert1 * ($pok2['sp_atak'] + $pok2['Jag_Sp_Atak'] + $pok2['tr_2'])) : $_SESSION['pokemon']['pok_sp_atak']);
        $pokemon[1]->obrona = isset($pok2['pok_obrona']) ? floor($alert1 * $pok2['pok_obrona']) : ($pok2['obrona'] ? floor($alert1 * ($pok2['obrona'] + $pok2['Jag_Obrona'] + $pok2['tr_3'])) : floor($alert1 * $_SESSION['pokemon']['pok_obrona']));
        $pokemon[1]->sp_obrona = isset($pok2['pok_sp_obrona']) ? floor($alert1 * $pok2['pok_sp_obrona']) : ($pok2['sp_obrona'] ? floor($alert1 * ($pok2['sp_obrona'] + $pok2['Jag_Sp_Obrona'] + $pok2['tr_4'])) : floor($alert1 * $_SESSION['pokemon']['pok_sp_obrona']));
        $pokemon[1]->szybkosc = isset($pok2['pok_szybkosc']) ? floor($alert1 * $pok2['pok_szybkosc']) : ($pok2['szybkosc'] ? floor($alert1 * ($pok2['szybkosc'] + $pok2['Jag_Szybkosc'] + $pok2['tr_5'])) : floor($alert1 * $_SESSION['pokemon']['pok_szybkosc']));
        $pokemon[1]->hp = isset($pok2['pok_hp']) ? $pok2['pok_hp'] : ($pok2['akt_HP'] ? $pok2['akt_HP'] : $_SESSION['pokemon']['pok_hp']);
        $pokemon[1]->max_hp = isset($pok2['max_hp']) ? $pok2['max_hp'] : ($pok2['HP'] ? $pok2['HP'] : $_SESSION['pokemon']['pok_hp']);
        $pokemon[1]->typ1 = isset($pok2['typ1']) ? $pok2['typ1'] : $_SESSION['pokemon']['typ1'];
        $pokemon[1]->typ2 = isset($pok2['typ2']) ? $pok2['typ2'] : $_SESSION['pokemon']['typ2'];
        $pokemon[1]->poziom = isset($pok2['pok_poziom']) ? $pok2['pok_poziom'] : $_SESSION['pokemon']['pok_poziom'];
        $pokemon[1]->celnosc = isset($pok2['celnosc']) ? $pok2['celnosc'] : $_SESSION['pokemon']['celnosc'];
        $pokemon[1]->plec = isset($pok2['plec']) ? $pok2['plec'] : $_SESSION['pokemon']['plec'];
        $pokemon[1]->pocz_HP = $pokemon[1]->hp;
        $pokemon[1]->odpornosci($odpornosci);
        //echo '<pre>';
        //print_r($pokemon[1]);
        //echo '</pre>';
        for ($j = 0; $j < 4; $j++) {
            $at = isset($pok2['atak' . $j]['id']) ? $pok2['atak' . $j]['id'] : (isset($_SESSION['atak' . $j]['id']) ? $_SESSION['atak' . $j]['id'] : 612);
            if ($at != 0 && $at != 612) {
                isset($ataki_sp[$at]) ? $id_a = $ataki_sp[$at] : $id_a = $ataki_sp[613];
                $pokemon[1]->ustaw_atak($ataki[$at], $id_a, $at);
            } else {
                $pokemon[1]->ustaw_atak($ataki[612], $ataki_sp[612], 612);
            }
        }
        //if(Session::_get('admin'))echo '<pre>';print_r($pokemon[1]);echo '</pre>';

        /////////////////////////////////////POKEMON2///////////////////////////////
        isset($wiersz['idd']) ? $id_pok2 = $wiersz['idd'] : $id_pok2 = 0;
        $pokemon[2] = new PokemonWalka($id_pok2);
        $pokemon[2]->i2 = isset($wiersz['i2']) ? $wiersz['i2'] : 0;
        $pokemon[2]->stan = $stan2;
        $pokemon[2]->runda = $runda2;
        $pokemon[2]->pulapka = $pulapka2;
        $pokemon[2]->atak_runda = $atak_runda2;
        $pokemon[2]->shiny = $wiersz['shiny'];
        $pokemon[2]->nazwa = $wiersz['imie'];
        $pokemon[2]->id_poka = $wiersz['id_poka'];
        $pokemon[2]->atak = $wiersz['Jag_Atak'] + $wiersz['tr_1'] + round($wiersz['jakosc'] / 100 * $wiersz['Atak']);
        $pokemon[2]->sp_atak = $wiersz['Jag_Sp_Atak'] + $wiersz['tr_2'] + round($wiersz['jakosc'] / 100 * $wiersz['Sp_Atak']);
        $pokemon[2]->obrona = $wiersz['Jag_Obrona'] + $wiersz['tr_3'] + round($wiersz['jakosc'] / 100 * $wiersz['Obrona']);
        $pokemon[2]->sp_obrona = $wiersz['Jag_Sp_Obrona'] + $wiersz['tr_4'] + round($wiersz['jakosc'] / 100 * $wiersz['Sp_Obrona']);
        $pokemon[2]->szybkosc = $wiersz['Jag_Szybkosc'] + $wiersz['tr_5'] + round($wiersz['jakosc'] / 100 * $wiersz['Szybkosc']);
        $pokemon[2]->typ1 = $wiersz['typ1'];
        $pokemon[2]->typ2 = $wiersz['typ2'];
        $pokemon[2]->poziom = $wiersz['poziom'];
        $pokemon[2]->celnosc = $wiersz['celnosc'];
        $pokemon[2]->hp = $wiersz['akt_HP'];
        $pokemon[2]->plec = $wiersz['plec'];
        $pokemon[2]->max_hp = $gracz == 1 ? $wiersz['HP'] : ($wiersz['Jag_HP'] + $wiersz['tr_6'] * 5 + round($wiersz['jakosc'] / 100 * $wiersz['HP']));
        $pokemon[2]->pocz_HP = $pokemon[2]->hp;
        $pokemon[2]->odpornosci($odpornosci);
        for ($i = 1; $i < 5; $i++) {
            $at = isset($wiersz['atak' . $i]['id']) ? $wiersz['atak' . $i]['id'] : (isset($wiersz['atak'][$i]['id']) ? $wiersz['atak'][$i]['id'] : 612);
            if ($at != 0 && $at != 612) {
                isset($ataki_sp[$at]) ? $id_a = $ataki_sp[$at] : $id_a = $ataki_sp[613];
                $pokemon[2]->ustaw_atak($ataki[$at], $id_a, $at);
            } else
                $pokemon[2]->ustaw_atak($ataki[612], $ataki_sp[612], 612);
        }
        //if(ADMIN){echo '<pre>';print_r($pokemon[2]);echo '</pre>';}
        unset($at);
        $zmienna = '';
        //if(Session::_get('admin'))echo '<pre>';print_r($pokemon[2]);echo '</pre>';
        $zmienna .= '<div class="row nomargin"><div class="col-xs-12"><div class="panel panel-primary jeden_ttlo noborder"><div class="row nomargin">';
        //pokemon gracza
        $zmienna .= '<div class="col-xs-12 col-md-6"><div class="row nomargin">';
        $zmienna .= '<div class="col-xs-6 col-md-4 col-lg-3 padding_top">';
        if ($pokemon[2]->shiny == 1)
            $zmienna .= '<img src="' . URL . 'public/img/poki/srednie/s' . $pokemon[2]->id_poka . '.png" data-toggle="tooltip" data-title="' . $pokemon[2]->nazwa . '" class="center img-responsive" />';
        else
            $zmienna .= '<img src="' . URL . 'public/img/poki/srednie/' . $pokemon[2]->id_poka . '.png" data-toggle="tooltip" data-title="' . $pokemon[2]->nazwa . '" class="center img-responsive" />';
        $zmienna .= '</div>';//col
        $zmienna .= '<div class="col-xs-6 col-md-8 col-lg-9">';
        $zmienna .= '<div class="well well-stan noborder padding_2 margin_2 text-center alert-success">';
        $pokemon[2]->shiny ? $zmienna .= '<span>Shiny ' . $pokemon[2]->nazwa . ' (' . $pokemon[2]->poziom . ' poz)' : $zmienna .= '<span>' . $pokemon[2]->nazwa . ' (' . $pokemon[2]->poziom . ' poz)';
        if ($pokemon[2]->plec == 0) $zmienna .= ' <i class="icon-mars" class="text-extra-big" data-original-title="płeć męska" data-toggle="tooltip"></i>';
        else if ($pokemon[2]->plec == 1) $zmienna .= ' <i class="icon-venus" class="text-extra-big" data-original-title="płeć żeńska" data-toggle="tooltip"></i>';
        else $zmienna .= '<span title="Pokemon jest bezpłciowy">!</span>';
        $zmienna .= '</span></div>';//well

        $zmienna .= '<div class="well well-stan jeden_ttlo noborder padding_2 margin_2 text-center">A: ' . $pokemon[2]->atak . ' Sp.A:' . $pokemon[2]->sp_atak . '</div>';
        $zmienna .= '<div class="well well-stan jeden_ttlo noborder padding_2 margin_2 text-center">O: ' . $pokemon[2]->obrona . ' Sp.O: ' . $pokemon[2]->sp_obrona . '</div>';
        $zmienna .= '<div class="well well-stan jeden_ttlo noborder padding_2 margin_2 text-center">SZ: ' . $pokemon[2]->szybkosc . ' C: ' . $pokemon[2]->celnosc . '%</div>';
        $zmienna .= '<div class="progress progress-gra prog_HP" data-original-title="Życie pokemona" data-toggle="tooltip" data-placement="top">';
        $zmienna .= '<div class="progress-bar progress-bar-success progBarHP" role="progressbar" aria-valuenow="40"';
        $dl = floor($pokemon[2]->hp / $pokemon[2]->max_hp * 10000) / 100;
        $zmienna .= 'aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;">';
        $zmienna .= '<span>' . $pokemon[2]->hp . ' / ' . $pokemon[2]->max_hp . ' PŻ</span>';
        $zmienna .= '</div></div>';
        $zmienna .= '</div>';//col
        $zmienna .= '</div></div>';//col
        //przeciwnik
        $zmienna .= '<div class="col-xs-12 col-md-6"><div class="row nomargin">';
        $zmienna .= '<div class="col-xs-6 col-md-4 col-lg-3 padding_top">';
        if ($pokemon[1]->shiny)
            $zmienna .= '<img src="' . URL . 'public/img/poki/srednie/s' . $pokemon[1]->id_poka . '.png" data-toggle="tooltip" data-title="' . $pokemon[1]->nazwa . '" class="center img-responsive"/>';
        else
            $zmienna .= '<img src="' . URL . 'public/img/poki/srednie/' . $pokemon[1]->id_poka . '.png" data-toggle="tooltip" data-title="' . $pokemon[1]->nazwa . '" class="center img-responsive" />';
        $zmienna .= '</div>';//col
        $zmienna .= '<div class="col-xs-6 col-md-8 col-lg-9">';
        $zmienna .= '<div class="well well-stan noborder padding_2 margin_2 text-center alert-danger">';
        $pokemon[1]->shiny ? $zmienna .= '<span>Shiny ' . $pokemon[1]->nazwa . ' (' . $pokemon[1]->poziom . ' poz)</span>' : $zmienna .= '<span>' . $pokemon[1]->nazwa . ' (' . $pokemon[1]->poziom . ' poz)';
        if ($pokemon[1]->plec == 0) $zmienna .= ' <i class="icon-mars" class="text-extra-big" data-original-title="płeć męska" data-toggle="tooltip"></i>';
        else if ($pokemon[1]->plec == 1) $zmienna .= ' <i class="icon-venus" class="text-extra-big" data-original-title="płeć żeńska" data-toggle="tooltip"></i>';
        else $zmienna .= '<span title="Pokemon jest bezpłciowy">!</span>';
        $zmienna .= '</span></div>';//well
        $zmienna .= '<div class="well well-stan jeden_ttlo noborder padding_2 margin_2 text-center">A: ' . $pokemon[1]->atak . ' Sp.A:' . $pokemon[1]->sp_atak . '</div>';
        $zmienna .= '<div class="well well-stan jeden_ttlo noborder padding_2 margin_2 text-center">O: ' . $pokemon[1]->obrona . ' Sp.O: ' . $pokemon[1]->sp_obrona . '</div>';
        $zmienna .= '<div class="well well-stan jeden_ttlo noborder padding_2 margin_2 text-center">SZ: ' . $pokemon[1]->szybkosc . ' C: ' . $pokemon[1]->celnosc . '%</div>';
        $zmienna .= '<div class="progress progress-gra prog_HP" data-original-title="Życie pokemona" data-toggle="tooltip" data-placement="top">';
        $dl = floor($pokemon[1]->hp / $pokemon[1]->max_hp * 10000) / 100;
        $zmienna .= '<div class="progress-bar progress-bar-success progBarHP" role="progressbar" aria-valuenow="40"';
        $zmienna .= 'aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;">';
        $zmienna .= '<span>' . $pokemon[1]->hp . ' / ' . $pokemon[1]->max_hp . ' PŻ</span>';
        $zmienna .= '</div></div>';
        $zmienna .= '</div>';//col
        $zmienna .= '</div></div>';//col

        $zmienna .= '</div></div></div></div>';//panel i row, row i col
        if ($trener == 1) $_SESSION['walkat'] .= $zmienna;
        else $_SESSION['walkat2'] .= $zmienna;
        $pokemon[2]->atak = floor($alert2 * $pokemon[2]->atak);
        $pokemon[2]->sp_atak = floor($alert2 * $pokemon[2]->sp_atak);
        $pokemon[2]->obrona = floor($alert2 * $pokemon[2]->obrona);
        $pokemon[2]->sp_obrona = floor($alert2 * $pokemon[2]->sp_obrona);
        $pokemon[2]->szybkosc = floor($alert2 * $pokemon[2]->szybkosc);

        $at[1] = $at2;
        $at[2] = $at1;
        $i = 1;
        $seeded[1] = 0;
        $seeded[2] = 0;
        //$pokemon[1]['uniki'] = 5;
        //$pokemon[2]['uniki'] = 5;
        while ($i < 51 && $pokemon[1]->hp > 0 && $pokemon[2]->hp > 0) {////walka
            if ($at[1] > 4) $at[1] = 1;
            if ($at[2] > 4) $at[2] = 1;
            ////////////(atak || sp.atak) / (obrona || sp.obrona) * MOC (* 1,25//stab) * 1,15 * odporność || podatność * współczynnik losowy (0.8 - 1.2)
            $_SESSION['walkat'] .= '<div class="alert alert-runda text-center margin-top"><span>RUNDA <span class="zloty pogrubienie">' . $i . '</span></span></div>';
            //////////////stan w walce, wyświetlany co rundę/////////////////////////////
            $_SESSION['walkat'] .= '<div class="row nomargin margin-top margin-bottom"><div class="col-xs-12"><div class="row nomargin">';
            for ($a = 2; $a >= 1; $a--) {//tabelka
                //pokemon gracza
                $_SESSION['walkat'] .= '<div class="col-xs-12 col-md-6 nopadding jeden_ttlo"><div class="row nomargin">';
                $_SESSION['walkat'] .= '<div class="col-xs-4 col-lg-3 nopadding padding_top">';
                if ($pokemon[$a]->shiny)
                    $_SESSION['walkat'] .= '<img src="' . URL . 'public/img/poki/srednie/s' . $pokemon[$a]->id_poka . '.png" data-toggle="tooltip" data-title="' . $pokemon[$a]->nazwa . '" class="center img-responsive"/>';
                else
                    $_SESSION['walkat'] .= '<img src="' . URL . 'public/img/poki/srednie/' . $pokemon[$a]->id_poka . '.png" data-toggle="tooltip" data-title="' . $pokemon[$a]->nazwa . '" class="center img-responsive" />';
                $_SESSION['walkat'] .= '</div>';//col
                $_SESSION['walkat'] .= '<div class="col-xs-8 col-lg-9 napadding padding_top">';
                $_SESSION['walkat'] .= '<div class="well well-stan noborder padding_2 margin_2 text-center ';
                $a == 1 ? $_SESSION['walkat'] .= 'alert-danger' : $_SESSION['walkat'] .= 'alert-success';
                $_SESSION['walkat'] .= '">';
                $pokemon[$a]->shiny ? $_SESSION['walkat'] .= '<span>Shiny ' . $pokemon[$a]->nazwa . '</span>' : $_SESSION['walkat'] .= '<span>' . $pokemon[$a]->nazwa . '</span>';
                $_SESSION['walkat'] .= '</div>';//well

                $_SESSION['walkat'] .= '<div class="well well-stan jeden_ttlo noborder padding_2 margin_2 text-center">A: ' . $pokemon[$a]->atak . ' Sp.A:' . $pokemon[$a]->sp_atak . '</div>';
                $_SESSION['walkat'] .= '<div class="well well-stan jeden_ttlo noborder padding_2 margin_2 text-center">O: ' . $pokemon[$a]->obrona . ' Sp.O: ' . $pokemon[$a]->sp_obrona . '</div>';
                $_SESSION['walkat'] .= '<div class="well well-stan jeden_ttlo noborder padding_2 margin_2 text-center">SZ: ' . $pokemon[$a]->szybkosc . ' C: ' . $pokemon[$a]->celnosc . '%</div>';
                $_SESSION['walkat'] .= '<div class="progress progress-gra prog_HP" data-original-title="Życie pokemona" data-toggle="tooltip" data-placement="top">';
                $_SESSION['walkat'] .= '<div class="progress-bar progress-bar-success progBarHP" role="progressbar" aria-valuenow="40"';
                $dl = floor($pokemon[$a]->hp / $pokemon[$a]->max_hp * 10000) / 100;
                $_SESSION['walkat'] .= 'aria-valuemin="0" aria-valuemax="100" style="width:' . $dl . '%;">';
                $_SESSION['walkat'] .= '<span>' . $pokemon[$a]->hp . ' / ' . $pokemon[$a]->max_hp . ' PŻ</span>';
                $_SESSION['walkat'] .= '</div></div>';
                $_SESSION['walkat'] .= '</div></div></div>';//col
            }
            $_SESSION['walkat'] .= '</div></div></div>';//row col row
            if ($pokemon[1]->szybkosc > $pokemon[2]->szybkosc) {
                $kto = 1;
                $ktot = 'dwa';
            } else {
                $kto = 2;
                $ktot = 'jeden';
            }
            for ($a = 1; $a < 3; $a++) {
                if ($kto == 1) $aaaa = 2;
                else $aaaa = 1;
                $id_ataku = $pokemon[$kto]->ataki[$at[$kto]]['ID'];
                //$_SESSION['walkat'] .= "ZMIENNA A WYNOSI: ".$a."<br />";
                if ($pokemon[$kto]->atak_runda <= 0) {
                    if ($pokemon[$kto]->ataki[$at[$kto]]['ile_rund'] > 10) {
                        $l1 = $pokemon[$kto]->ataki[$at[$kto]]['ile_rund'] / 10;
                        $l2 = $pokemon[$kto]->ataki[$at[$kto]]['ile_rund'] % 10;
                        $pokemon[$kto]->atak_runda = rand($l1, $l2);
                    } else
                        $pokemon[$kto]->atak_runda = $pokemon[$kto]->ataki[$at[$kto]]['ile_rund'];
                }
                if ($pokemon[$kto]->atak_runda_jeden <= 0) {
                    if ($pokemon[$kto]->ataki[$at[$kto]]['ile_runda'] > 10) {
                        $l1 = $pokemon[$kto]->ataki[$at[$kto]]['ile_runda'] / 10;
                        $l2 = $pokemon[$kto]->ataki[$at[$kto]]['ile_runda'] % 10;
                        $pokemon[$kto]->atak_runda_jeden = rand($l1, $l2);
                    } else
                        $pokemon[$kto]->atak_runda_jeden = $pokemon[$kto]->ataki[$at[$kto]]['ile_runda'];
                    if ($id_ataku == 198) { //fury cutter
                        $pokemon[$kto]->fury = 1;
                        $pokemon[$kto]->fury_t = 1;
                    }
                }
                $can = 0;
                if ($seeded[$kto] == 1) {
                    if ($pokemon[$kto]->hp < 16) $obrazenia = 1;
                    else $obrazenia = floor($pokemon[$kto]->hp * (1 / 16));
                    $pokemon[$kto]->hp -= $obrazenia;
                    $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span>Nasiona zadają ' . $obrazenia . ' obrażeń Pokemonowi <span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span>. </span></div>';
                    $hp = $pokemon[$aaaa]->hp + $obrazenia;
                    if ($hp > $pokemon[$aaaa]->pocz_HP) $obrazenia = $pokemon[$aaaa]->pocz_HP - $pokemon[$aaaa]->hp;
                    $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$aaaa]->nazwa . '</span> leczy ' . $obrazenia . ' obrażeń.</span></div>';
                    $pokemon[$aaaa]->hp += $obrazenia;
                    if ($pokemon[$kto]->hp <= 0) {
                        $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> pada nieprzytomnie na ziemię.</span></div>';
                        break;
                    }
                }
                ////////////////stan pokemona w walce///////////////////////////////////
                if (in_array($pokemon[$kto]->stan, [2, 3, 6, 8, 9, 10, 7])) //coś tam, sprawdzić stany itp. czy może atakować itp.
                {
                    /////////////////////paraliż//////////////////////////////////////////
                    if ($pokemon[$kto]->stan == 2) {
                        $pokemon[$kto]->runda++;
                        if ($pokemon[$kto]->runda == 1) {
                            $r1 = rand() % 4;
                            if ($r1 < 2) $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> jest sparaliżowany i nie może wykonać ruchu.</span></div>';
                            else $can = 1;
                        }
                        if ($pokemon[$kto]->runda > 1) {
                            $r = rand() % 3;
                            if ($r < 1) {
                                $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> nie jest już pod wpływem paraliżu.</span></div>';
                                $pokemon[$kto]->stan = 0;
                                $pokemon[$kto]->runda = 0;
                                $can = 1;
                            } else {
                                $r1 = rand() % 4;
                                if ($r1 < 2) $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> jest sparaliżowany i nie może wykonać ruchu.</span></div>';
                                else $can = 1;
                            }
                        }
                    } ////////////////////////otępienie//////////////////////////////////////
                    else if ($pokemon[$kto]->stan == 3) {
                        $pokemon[$kto]->runda++;
                        if ($pokemon[$kto]->runda > 0) {
                            $ff = rand() % 3;
                            if ($ff == 0 && $pokemon[$kto]->runda > 1) {
                                $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> nie jest już otępiony.</span></div>';
                                $pokemon[$kto]->stan = 0;
                                $pokemon[$kto]->runda = 0;
                                $can = 1;
                            } else {
                                $rr = rand() % 2;
                                if ($rr == 0) $can = 1;  ////////pokemon może atakować
                                else ////pokemon atakuje sam siebie
                                {
                                    ///atak fizyczny o mocy 40.
                                    $o = ceil(($pokemon[$kto]->atak / $pokemon[$kto]->obrona) * 40 * 1.15);
                                    $pokemon[$kto]->hp -= $o;
                                    $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> jest otępiony i atakuje sam siebie zadając ' . $o . ' obrażeń.</span></div>';
                                    if ($pokemon[$kto]->hp <= 0) $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span class="pogrubienie">Pokemon pada nieprzytomny na ziemię.</span></div>';
                                }
                            }
                        }
                    } ////////////////////////sen///////////////////////////////////////////
                    else if ($pokemon[$kto]->stan == 6) {
                        $pokemon[$kto]->runda++;
                        if ($pokemon[$kto]->runda > 0) {
                            $ff = rand() % 3;
                            if ($ff == 0 && $pokemon[$kto]->runda > 1) {
                                $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> budzi się ze snu.</span></div>';
                                $pokemon[$kto]->stan = 0;
                                $pokemon[$kto]->runda = 0;
                                $can = 1;
                            } else
                                $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> śpi.</span></div>';
                        }
                    } /////////////////////zamrożenie//////////////////////////////////////
                    else if ($pokemon[$kto]->stan == 8) {
                        $pokemon[$kto]->runda++;
                        if ($pokemon[$kto]->runda > 0) {
                            $ff = rand() % 5;
                            if ($ff == 0 && $pokemon[$kto]->runda > 1) {
                                $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> nie jest już zamrożony.</span></div>';
                                $pokemon[$kto]->stan = 0;
                                $pokemon[$kto]->runda = 0;
                                $can = 1;
                            } else
                                $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> jest zamrożony i nie może wykonać ruchu.</span></div>';
                        }
                    } ////////////////////oszołomienie//////////////////////////////////////
                    else if ($pokemon[$kto]->stan == 9) {
                        $pokemon[$kto]->runda++;
                        if ($pokemon[$kto]->runda < 3) {
                            $ff = rand() % 3;
                            if ($ff == 0 && $pokemon[$kto]->runda > 1) {
                                $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> nie jest już oszołomiony.</span></div>';
                                $pokemon[$kto]->stan = 0;
                                $pokemon[$kto]->runda = 0;
                                $can = 1;
                            } else $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> jest oszołomiony i nie może wykonać ruchu.</span></div>';
                        } else {
                            $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> nie jest już oszołomiony.</span></div>';
                            $pokemon[$kto]->stan = 0;
                            $pokemon[$kto]->runda = 0;
                            $can = 1;
                        }
                    } ///////////////////zakochanie/////////////////////////////////////////
                    else if ($pokemon[$kto]->stan == 10) {
                        $ff = rand() % 5;
                        if ($ff == 0 && $pokemon[$kto]->runda > 1) {
                            $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> nie jest już zakochany.</span></div>';
                            $pokemon[$kto]->stan = 0;
                            $pokemon[$kto]->runda = 0;
                            $can = 1;
                        } else {
                            $fab = rand() % 4;
                            if ($fab > 1) $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> jest zakochany i nie skrzywdzi drugiego pokemona.</span></div>';
                            else $can = 1;
                        }
                    } //////////////////pułapka/////////////////////////////////////////////
                    else if ($pokemon[$kto]->stan == 7 && $pokemon[$kto]->pulapka > 0) {
                        $ff = rand() % 4;
                        if ($ff == 0 && $pokemon[$kto]->runda > 1) {
                            $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> wydostał się z pułapki.</span></div>';
                            $pokemon[$kto]->stan = 0;
                            $pokemon[$kto]->runda = 0;
                            $pokemon[$kto]->pulapka = 0;

                            $can = 1;
                        } else {
                            $pokemon[$kto]->hp -= $pokemon[$kto]->pulapka;
                            $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> jest uwięziony. Pułapka zadaje ' . $pokemon[$kto]->pulapka . ' obrażeń.</span></div>';
                            if ($pokemon[$kto]->hp <= 0) $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span class="pogrubienie">Pokemon pada nieprzytomny na ziemię.</span></div>';
                            else $can = 1;
                        }
                    }
                    //////////////////////////////////////////////////////////////////////tu ewentualnie będzie jeszcze klątwa.
                } else $can = 1;
                ///////////////////////stan pokemona w walce koniec////////////////////
                //////////////jeśli pokemon może atakować: /////////////////////////////////
                if ($can == 1) {
                    $s = $id_ataku;
                    $c = 1;
                    $dwurundowy = 0;
                    if (($id_ataku == '497') && ($pokemon[$kto]->atak_runda == 2))//solar beam
                    {
                        $dwurundowy = 1;
                        $pokemon[$kto]->atak_runda--;
                        $c = 0;
                        $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> zbiera energię słoneczną.</span></div>';
                    }
                    if (($id_ataku == '253') && ($pokemon[$kto]->atak_runda == 1))//hyper beam
                    {
                        $pokemon[$kto]->atak_runda--;
                        $c = 0;
                        $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> odpoczywa po ostatnim ataku.</span></div>';
                    }
                    if (($id_ataku == '107') && ($pokemon[$kto]->atak_runda == 1)) $pokemon[$kto]->nietykalnosc = 0;//dig 2 tura, usunięcie nietykalności
                    if (($id_ataku == '107') && ($pokemon[$kto]->atak_runda == 2))//dig 1 tura, dodanie nietykalnosci
                    {
                        $dwurundowy = 1;
                        $pokemon[$kto]->nietykalnosc = 1;
                        $pokemon[$kto]->atak_runda--;
                        $c = 0;
                        $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> zakopuje się pod ziemią.</span></div>';
                    }
                    if (($id_ataku == '182') && ($pokemon[$kto]->atak_runda == 1)) $pokemon[$kto]->nietykalnosc = 0;//fly 2 tura, usunięcie nietykalności
                    if (($id_ataku == '182') && ($pokemon[$kto]->atak_runda == 2))//fly 1 tura, dodanie nietykalnosci
                    {
                        $dwurundowy = 1;
                        $pokemon[$kto]->nietykalnosc = 1;
                        $pokemon[$kto]->atak_runda--;
                        $c = 0;
                        $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> wznosi się w powietrze.</span></div>';
                    }
                    if (($id_ataku == '111') && ($pokemon[$kto]->atak_runda == 1)) $pokemon[$kto]->nietykalnosc = 0;//dive 2 tura, usunięcie nietykalności
                    if (($id_ataku == '111') && ($pokemon[$kto]->atak_runda == 2))//dive 1 tura, dodanie nietykalnosci
                    {
                        $dwurundowy = 1;
                        $pokemon[$kto]->nietykalnosc = 1;
                        $pokemon[$kto]->atak_runda--;
                        $c = 0;
                        $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> nurkuje pod wodą.</span></div>';
                    }
                    if ($id_ataku == '171' || $id_ataku == '220'
                        || $id_ataku == '247' || $id_ataku == '466'
                    ) /////ataki KO
                        if ($pokemon[$kto]->poziom > $pokemon[$aaaa]->poziom)
                            $pokemon[$kto]->ataki[$at[$kto]]['celnosc'] = 30 + ($pokemon[$kto]->poziom - $pokemon[$aaaa]->poziom);
                    //////////////////////////////obliczanie celności ataku, gdy celność mniejsza niż 100%///
                    $celnosc_a = $pokemon[$kto]->ataki[$at[$kto]]['celnosc'] - ((100 - $pokemon[$kto]->celnosc) / 2);
                    if ($pokemon[$aaaa]->nietykalnosc && $pokemon[$aaaa]->ataki[$at[$aaaa]]['ID'] != 107)//jeśli nietykalność
                    {
                        $c = 0;
                        $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> nie trafia przeciwnika atakiem <span class="pogrubienie">' . $pokemon[$kto]->ataki[$at[$kto]]['nazwa'] . '</span>!</span></div>';
                        $pokemon[$kto]->atak_runda--;
                    } else if ($pokemon[$aaaa]->nietykalnosc && $pokemon[$aaaa]->ataki[$at[$aaaa]]['ID'] == 107 && ($id_ataku != '136'))//jeśli nietykalność
                    {
                        $c = 0;
                        $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> nie trafia przeciwnika atakiem <span class="pogrubienie">' . $pokemon[$kto]->ataki[$at[$kto]]['nazwa'] . '</span>!</span></div>';
                        $pokemon[$kto]->atak_runda--;
                    } else if ($pokemon[$kto]->ataki[$at[$kto]]['rodzaj'] == 'statusowy' && ($pokemon[$kto]->ataki[$at[$kto]]['kogo'] == 1 || $pokemon[$kto]->ataki[$at[$kto]]['kto'] == 1)) $c = 1;
                    else if (($celnosc_a < 100) && ($id_ataku != '610') && ($dwurundowy == 0)) {
                        $cel = $this->obliczenieCelnosci($celnosc_a);
                        if ($cel == 1) {
                            $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> nie trafia przeciwnika atakiem <span class="pogrubienie">' . $pokemon[$kto]->ataki[$at[$kto]]['nazwa'] . '</span>!</span></div>';
                            $c = 0;
                            if ($pokemon[$kto]->atak_runda > 1 && ($id_ataku != '253')) $pokemon[$kto]->atak_runda = 0;
                            else $pokemon[$kto]->atak_runda--;
                        }
                    }
                    //////////////////////////obliczanie celności ataku koniec/////////////////////

                    /////////////atak skrypt:
                    if ($c == 1) {
                        $pokemon[$kto]->atak_runda--;
                        $obr = 0;
                        if ($pokemon[$kto]->ataki[$at[$kto]]['moc'] > 0) $obr = 1;

                        if ($obr == 0)////ataki statusowe
                        {
                            $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> używa ataku <span class="pogrubienie">' . $pokemon[$kto]->ataki[$at[$kto]]['nazwa'] . '</span>.';
                            if ($id_ataku == '290')//LEECH SEED
                                if (($pokemon[$aaaa]->typ1 == 4) || ($pokemon[$aaaa]->typ2 == 4) || ($seeded[$aaaa] == 1))
                                    $_SESSION['walkat'] .= '<span class="pogrubienie"> ' . $pokemon[$aaaa]->nazwa . '</span> unika nasion.';
                                else {
                                    $_SESSION['walkat'] .= 'Nasiona przyklejają się do <span class="pogrubienie">' . $pokemon[$aaaa]->nazwa . '</span>.';
                                    $seeded[$aaaa] = 1;
                                }
                            else if ($id_ataku == 147)//ENDEAVOUR
                                if ($pokemon[$kto]->hp < $pokemon[$aaaa]->hp) {
                                    $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span>Życie <span class="pogrubienie">' . $pokemon[$aaaa]->nazwa . '</span> zrównuje się z życiem <span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span></span></div>';
                                    $pokemon[$aaaa]->hp = $pokemon[$kto]->hp;
                                } else $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span>Atak nie osiągnął żadnego efektu!</span></div>';
                            else if (($id_ataku == '20') || ($id_ataku == '231'))//AROMATHERAPY i HEAL BELL
                                if (in_array($pokemon[$kto]->stan, [1, 2, 4, 5, 8])) {
                                    $_SESSION['walkat'] .= '<span class="pogrubienie"> ' . $pokemon[$aaaa]->nazwa . '</span> leczy swój stan.';
                                    $pokemon[$kto]->stan = 0;
                                } else $_SESSION['walkat'] .= '<span class="pogrubienie"> ' . $pokemon[$kto]->nazwa . '</span> nie wymaga uleczenia';
                            else if ($id_ataku == 189)//FORESIGHT
                                if (($pokemon[$aaaa]->typ1 == 9) || ($pokemon[$aaaa]->typ2 == 9)) {
                                    $pokemon[$aaaa]->odp[1] = 1;
                                    $pokemon[$aaaa]->odp[10] = 1;
                                    $_SESSION['walkat'] .= '<span class="pogrubienie"> ' . $pokemon[$aaaa]->nazwa . '</span> traci odporność na ataki typu normalnego i walczącego!';
                                } else $_SESSION['walkat'] .= '<span class="pogrubienie"> ' . $pokemon[$aaaa]->nazwa . '</span> nie jest duchem!';
                            $_SESSION['walkat'] .= '</span></div>';
                        } else if ($obr == 1) {
                            ////////////////////////////////moc ataku////////////////////////////////////

                            if (($pokemon[$kto]->ataki[$at[$kto]]['typ'] == $pokemon[$kto]->typ1) || ($pokemon[$kto]->ataki[$at[$kto]]['typ'] == $pokemon[$kto]->typ2)) $moc = 1.25;
                            else $moc = 1;
                            /////////////////////////////////////ZMIENNA MOC ATAKU!//////////////////////////////////////////////
                            if ($id_ataku == 142)//ELECTRO BALL
                            {
                                $st_sz = $pokemon[$aaaa]->szybkosc / $pokemon[$kto]->szybkosc;
                                if ($st_sz > 0.5) $pokemon[$kto]->ataki[$at[$kto]]['moc'] = 60;
                                else if ($st_sz > 0.33) $pokemon[$kto]->ataki[$at[$kto]]['moc'] = 80;
                                else if ($st_sz > 0.24) $pokemon[$kto]->ataki[$at[$kto]]['moc'] = 100;
                                else  $pokemon[$kto]->ataki[$at[$kto]]['moc'] = 120;
                            }
                            else if ($id_ataku == 310)//MAGNITUDE
                            {
                                $random = rand(0, 100);
                                if ($random <= 5) {
                                    $pokemon[$kto]->ataki[$at[$kto]]['moc'] = 10;
                                    $pokemon[$kto]->ataki[$at[$kto]]['nazwa'] = "Magnitude 4";
                                } else if ($random <= 15) {
                                    $pokemon[$kto]->ataki[$at[$kto]]['moc'] = 30;
                                    $pokemon[$kto]->ataki[$at[$kto]]['nazwa'] = "Magnitude 5";
                                } else if ($random <= 35) {
                                    $pokemon[$kto]->ataki[$at[$kto]]['moc'] = 50;
                                    $pokemon[$kto]->ataki[$at[$kto]]['nazwa'] = "Magnitude 6";
                                } else if ($random <= 65) {
                                    $pokemon[$kto]->ataki[$at[$kto]]['moc'] = 70;
                                    $pokemon[$kto]->ataki[$at[$kto]]['nazwa'] = "Magnitude 7";
                                } else if ($random <= 85) {
                                    $pokemon[$kto]->ataki[$at[$kto]]['moc'] = 90;
                                    $pokemon[$kto]->ataki[$at[$kto]]['nazwa'] = "Magnitude 8";
                                } else if ($random <= 95) {
                                    $pokemon[$kto]->ataki[$at[$kto]]['moc'] = 110;
                                    $pokemon[$kto]->ataki[$at[$kto]]['nazwa'] = "Magnitude 9";
                                } else {
                                    $pokemon[$kto]->ataki[$at[$kto]]['moc'] = 150;
                                    $pokemon[$kto]->ataki[$at[$kto]]['nazwa'] = "Magnitude 10";
                                }
                            }
                            else if ($id_ataku == 125)//DRAGON RAGE
                            {
                                $pokemon[$kto]->ataki[$at[$kto]]['moc'] = $pokemon[$kto]->poziom;
                            } else if ($id_ataku == 151)//ERUPTION
                            {
                                $pokemon[$kto]->ataki[$at[$kto]]['moc'] = floor(150 * ($pokemon[$kto]->hp / $pokemon[$kto]->max_hp));
                            } else if ($id_ataku == 172)//FLAIL
                            {
                                $st_hp = $pokemon[$kto]->hp / $pokemon[$kto]->max_hp;
                                if ($st_hp >= 0.71) $pokemon[$kto]->ataki[$at[$kto]]['moc'] = 20;
                                else if ($st_hp >= 0.36) $pokemon[$kto]->ataki[$at[$kto]]['moc'] = 40;
                                else if ($st_hp >= 0.21) $pokemon[$kto]->ataki[$at[$kto]]['moc'] = 80;
                                else if ($st_hp >= 0.11) $pokemon[$kto]->ataki[$at[$kto]]['moc'] = 100;
                                else if ($st_hp >= 0.05) $pokemon[$kto]->ataki[$at[$kto]]['moc'] = 150;
                                else $pokemon[$kto]->ataki[$at[$kto]]['moc'] = 200;
                            } else if ($id_ataku == 196)//FRUSTRATION
                            {
                                $pokemon[$kto]->ataki[$at[$kto]]['moc'] = (255 - $tlo) / 2.5;
                            } else if ($id_ataku == 198)//FURY CUTTER
                            {
                                if ($pokemon[$kto]->fury == 1 || $pokemon[$kto]->fury_t == 0) $pokemon[$kto]->ataki[$at[$kto]]['moc'] = 40;
                                else $pokemon[$kto]->ataki[$at[$kto]]['moc'] *= 2;
                            }
                            /*else if($id_ataku == 97)//CRUSH GRIP <- TYLKO JEDNA LEGENDA, TO W KOMENTARZU
                    {
                      $pokemon[$kto]['atak'.$at[$kto]]['moc'] = 1 + ($pokemon[$aaaa]->hp / $pokemon[$aaaa]['max_hp']);
                    }
                    */
                            ///////////////////////////////ZMIENNA MOC ATAKU ///////////////KONIEC!/////////////////////////////////////////////
                            ////////////////////////////////moc ataku /////////koniec////////////////////////////
                            if ($pokemon[$kto]->ataki[$at[$kto]]['rodzaj'] == "fizyczny") {
                                if ($kto == 1) {
                                    $obrazenia = ($pokemon[1]->atak / $pokemon[2]->obrona) * $moc * $pokemon[1]->ataki[$at[1]]['moc'] * 1.15 * $pokemon[2]->odp[$pokemon[1]->ataki[$at[1]]['typ']];
                                } else $obrazenia = ($pokemon[2]->atak / $pokemon[1]->obrona) * $moc * $pokemon[2]->ataki[$at[2]]['moc'] * 1.15 * $pokemon[1]->odp[$pokemon[2]->ataki[$at[2]]['typ']];
                            } else if ($pokemon[$kto]->ataki[$at[$kto]]['rodzaj'] == "specjalny") {
                                if ($kto == 1) {
                                    $obrazenia = ($pokemon[1]->sp_atak / $pokemon[2]->sp_obrona) * $moc * $pokemon[1]->ataki[$at[1]]['moc'] * 1.15 * $pokemon[2]->odp[$pokemon[1]->ataki[$at[1]]['typ']];
                                } else $obrazenia = ($pokemon[2]->sp_atak / $pokemon[1]->sp_obrona) * $moc * $pokemon[2]->ataki[$at[2]]['moc'] * 1.15 * $pokemon[1]->odp[$pokemon[2]->ataki[$at[2]]['typ']];
                            }
                            $rand = rand(90, 110) / 100;
                            $obrazenia = ceil($obrazenia * $rand);
                            if ($at[$aaaa] > 0 && $at[$aaaa] < 5) {
                                $atak_oo = $at[$aaaa];
                                if (($id_ataku == '107') && ($id_ataku == '136')) {
                                    $obrazenia *= 2;
                                    $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$aaaa]->nazwa . '</span> jest zakopany, <span class="pogrubienie">Earthquake</span> zadaje podwójne obrażenia.</span></div>';
                                }
                            }
                            $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> używa ataku <span class="pogrubienie">' . $pokemon[$kto]->ataki[$at[$kto]]['nazwa'] . '</span>.<br />';
                            //////SPECJALNE ATAKI Z INNYMI OBRAŻENIAMI ITP.///////////////////////
                            if ($id_ataku == 56) //BRINE
                            {
                                $akt_h = $pokemon[$aaaa]->hp / $pokemon[$aaaa]->max_hp;
                                if ($akt_h <= 0.5) {
                                    $obrazenia *= 2;
                                    $_SESSION['walkat'] .= 'Przeciwnik ma 50% lub mniej życia, Brine zadaje 2x więcej obrażeń.';
                                }
                            } else if ($id_ataku == 166) {
                                $obrazenia = $pokemon[$kto]->hp;
                                $pokemon[$kto]->hp = 0;
                            }
                            //////SPECJALNE ATAKI Z INNYMI OBRAŻENIAMI ITP.////// KONIEC///////////////////////
                            ///obrażenia lub pokonanie przez KO
                            if (($id_ataku == '171' || $id_ataku == '220'
                                    || $id_ataku == '247' || $id_ataku == '466') && $obrazenia > 0
                            ) /////ataki KO
                            {
                                $_SESSION['walkat'] .= '<span class="pogrubienie">Jest to atak KO.</span></div>';
                                $pokemon[$aaaa]->hp = 0;
                            } else if ($id_ataku == '130')//Dream Eater
                            {
                                if ($pokemon[$aaaa]->stan == '6') {
                                    $pokemon[$aaaa]->hp -= $obrazenia;
                                    $_SESSION['walkat'] .= 'Pokemon zadaje <span class="pogrubienie">' . $obrazenia . "</span> obrażeń.";
                                    $obrazenia /= 2;
                                    $obrazenia = ceil($obrazenia);
                                    if (($pokemon[$kto]->hp + $obrazenia) > $pokemon[$kto]->pocz_HP) {
                                        $obrazenia = $pokemon[$kto]->pocz_HP - $pokemon[$kto]->hp;
                                        $pokemon[$kto]->hp = $pokemon[$kto]->pocz_HP;
                                    } else $pokemon[$kto]->hp += $obrazenia;
                                    $_SESSION['walkat'] .= '<span class="pogrubienie"> ' . $pokemon[$kto]->nazwa . '</span> leczy <span class="pogrubienie">' . $obrazenia . '</span> obrażeń.';
                                } else $_SESSION['walkat'] .= '<span class="pogrubienie"> ' . $pokemon[$aaaa]->nazwa . '</span> nie śpi.';
                                $_SESSION['walkat'] .= '</div>';
                                if ($pokemon[$aaaa]->hp <= 0) {
                                    $_SESSION['walkat'] .= '<div class="walka_alert alert ' . $ktot . '"><span class="pogrubienie"> ' . $pokemon[$aaaa]->nazwa . '</span> pada nieprzytomnie na ziemię.</div>';
                                    $at[$kto]++;
                                    break;
                                }

                                $at[$kto]++;
                                if ($kto == 1) {
                                    $kto = 2;
                                    $tlo = 'alert-success';
                                } else {
                                    $kto = 1;
                                    $tlo = 'alert-danger';
                                }
                                continue;
                            } else if ($id_ataku == 89)//COUNTER
                            {
                                if ($a == 2) {
                                    $atak_oo = $at[$aaaa] - 1;
                                    if ($atak_oo == 0) $atak_oo = 4;
                                    if ($pokemon[$aaaa]->ataki['atak' . $atak_oo]['rodzaj'] == 'fizyczny') {
                                        $pokemon[$kto]->ataki['atak' . $at[$kto]]['moc'] = $pokemon[$kto]->ataki['atak' . $at[$kto]]['moc'] * 2;
                                        if ($kto == 1) {
                                            $obrazenia = ($pokemon[1]->atak / $pokemon[2]->obrona) * $moc * $pokemon[$kto]->ataki['atak' . $at[$kto]]['moc'] * 1.15 * $pokemon[2]->odp[$pokemon[$kto]->ataki[$at[$kto]]['typ']];
                                        } else $obrazenia = ($pokemon[2]->atak / $pokemon[1]->obrona) * $moc * $pokemon[$kto]->ataki['atak' . $at[$kto]]['moc'] * 1.15 * $pokemon[1]->odp[$pokemon[$kto]->ataki[$at[$kto]]['typ']];
                                        $obrazenia = ceil($obrazenia);
                                        $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> zadaje <span class="pogrubienie">' . $obrazenia . '</span> obrażeń./span></div>';
                                        $pokemon[$aaaa]->hp -= $obrazenia;
                                    } else {
                                        $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> nie może skontrować ataku!</span></div>';
                                        $obrazenia = 0;
                                    }
                                } else {
                                    $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> nie może skontrować ataku!</span></div>';
                                    $obrazenia = 0;
                                }
                                $_SESSION['walkat'] .= '</div>';
                            } else {
                                $_SESSION['walkat'] .= 'Pokemon zadaje <span class="pogrubienie">' . $obrazenia . "</span> obrażeń.</span></div>";
                                if ($pokemon[$aaaa]->odp[$pokemon[$kto]->ataki[$at[$kto]]['typ']] > 1) $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span class="zielony">Ten ruch jest super efektywny!</span></div>';
                                else if ($pokemon[$aaaa]->odp[$pokemon[$kto]->ataki[$at[$kto]]['typ']] == 0) $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span class="czerwony">Ten ruch nie może zranić przeciwnika!</span></div>';
                                else if ($pokemon[$aaaa]->odp[$pokemon[$kto]->ataki[$at[$kto]]['typ']] < 1) $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span class="czerwony">Ten ruch jest mało efektywny!</span></div>';
                            }
                            //OBRAŻENIA ZWROTNE
                            if ($pokemon[$kto]->ataki[$at[$kto]]['obr_zwrotne']) {
                                $obrazenia_zwrotne = ceil($obrazenia * ($pokemon[$kto]->ataki[$at[$kto]]['obr_zwrotne'] / 100));
                                $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span>Pokemon otrzymuje <span class="pogrubienie">' . $obrazenia_zwrotne . '</span> obrażeń zwrotnych.</span></div>';
                                $pokemon[$kto]->hp -= $obrazenia_zwrotne;
                                if ($pokemon[$kto]->hp < 0) $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span class="pogrubienie">Pokemon pada nieprzytomny na ziemię.</span></div>';
                            }

                            $pokemon[$aaaa]->hp -= $obrazenia;
                            if (isset($fury)) $fury_t = 1;
                            if ($pokemon[$aaaa]->hp <= 0) {
                                $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span>Przeciwnik pada nieprzytomny na ziemię.</span></div>';
                            }
                        }
                        /////////szansa na stan specjalny -> podpalenie itp. ///////////////
                        if ($pokemon[$kto]->ataki[$at[$kto]]['stan'] > 0 && (($pokemon[1]->hp) > 0 && ($pokemon[2]->hp > 0))) {
                            if ($id_ataku == 168 && $a == 1)//fire fang
                            {
                                $s = 10;
                                $ab = $this->obliczenieCelnosci($s);///////obliczenie czy pok ma mieć nałożony stan.
                                if ($ab == 2) {
                                    if ($pokemon[$aaaa]->stan == 0)//jeśli pokemon ma nałożony stan, to nie może mieć drugiego stanu.
                                    {
                                        $pokemon[$aaaa]->stan = 3;
                                        $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$aaaa]->nazwa . '</span> zostaje otępiony.</span></div>';
                                    }
                                }
                            } ///////////////podpalenie/////////////////////////////////////////
                            else if ($pokemon[$kto]->ataki[$at[$kto]]['stan'] == 1)//podpalenie
                            {
                                $s = $pokemon[$kto]->ataki[$at[$kto]]['procent'];
                                $k = $pokemon[$kto]->ataki[$at[$kto]]['kto'];
                                ///jeśli pok jest wodny - mniejsza szansa na podpalenie, jeśli jest roślinny większa szansa
                                if ($k == 1)//siebie
                                {
                                    if ($pokemon[$kto]->typ1 == 4 || $pokemon[$kto]->typ2 == 4) $s *= 2;
                                    if ($pokemon[$kto]->typ1 == 3 || $pokemon[$kto]->typ2 == 3) $s /= 2;
                                } else if ($k == 2)//u przeciwnika
                                {
                                    if ($pokemon[$aaaa]->typ1 == 4 || $pokemon[$aaaa]->typ2 == 4) $s *= 2;
                                    if ($pokemon[$aaaa]->typ1 == 3 || $pokemon[$aaaa]->typ2 == 3) $s /= 2;
                                }
                                $ab = $this->obliczenieCelnosci($s);///////obliczenie czy pok ma mieć nałożony stan.
                                if ($ab == 2) {
                                    if ($k == 1) {
                                        if ($pokemon[$kto]->stan == 0 && $pokemon[$kto]->typ1 != 2 && $pokemon[$kto]->typ2 != 2)//jeśli pokemon ma nałożony stan, to nie może mieć drugiego stanu.
                                        {
                                            $pokemon[$kto]->stan = 1;
                                            $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> zostaje podpalony.</span></div>';
                                        }
                                    } else {
                                        if ($pokemon[$aaaa]->stan == 0 && $pokemon[$aaaa]->typ1 != 2 && $pokemon[$aaaa]->typ2 != 2)//jeśli pokemon ma nałożony stan, to nie może mieć drugiego stanu.
                                        {
                                            $pokemon[$aaaa]->stan = 1;
                                            $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$aaaa]->nazwa . '</span> zostaje podpalony.</span></div>';
                                        }
                                    }
                                }
                            }
                            //////////////////////podpalenie koniec///////////////////////////
                            ///////////////////////paraliż////////////////////////////////////
                            else if ($pokemon[$kto]->ataki[$at[$kto]]['stan'] == 2)//paraliż
                            {
                                $s = $pokemon[$kto]->ataki[$at[$kto]]['procent'];
                                $k = $pokemon[$kto]->ataki[$at[$kto]]['kto'];
                                $ab = $this->obliczenieCelnosci($s);///////obliczenie czy pok ma mieć nałożony stan.
                                if ($ab == 2) {
                                    if ($k == 1) {
                                        if ($pokemon[$kto]->stan == 0 && $pokemon[$kto]->typ1 != 5 && $pokemon[$kto]->typ2 != 5)//jeśli pokemon ma nałożony stan, to nie może mieć drugiego stanu.
                                        {
                                            $pokemon[$kto]->stan = 2;
                                            $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> zostaje sparaliżowany.</span></div>';
                                        }
                                    } else {
                                        if ($pokemon[$aaaa]->stan == 0 && $pokemon[$aaaa]->typ1 != 5 && $pokemon[$aaaa]->typ2 != 5)//jeśli pokemon ma nałożony stan, to nie może mieć drugiego
                                        {
                                            $pokemon[$aaaa]->stan = 2;
                                            $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$aaaa]->nazwa . '</span> zostaje sparaliżowany.</span></div>';
                                        }
                                    }
                                }
                            }
                            ///////////////////////paraliż koniec/////////////////////////////
                            //////////////////////otępienie///////////////////////////////////
                            else if ($pokemon[$kto]->ataki[$at[$kto]]['stan'] == 3)//otępienie
                            {
                                $s = $pokemon[$kto]->ataki[$at[$kto]]['procent'];
                                $k = $pokemon[$kto]->ataki[$at[$kto]]['kto'];
                                $ab = $this->obliczenieCelnosci($s);///////obliczenie czy pok ma mieć nałożony stan.
                                if ($ab == 2) {
                                    if ($k == 1) {
                                        if ($pokemon[$kto]->stan == 0)//jeśli pokemon ma nałożony stan, to nie może mieć drugiego stanu.
                                        {
                                            $pokemon[$kto]->stan = 3;
                                            $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> zostaje otępiony.</span></div>';
                                        }
                                    } else {
                                        if ($pokemon[$aaaa]->stan == 0)//jeśli pokemon ma nałożony stan, to nie może mieć drugiego stanu.
                                        {
                                            $pokemon[$aaaa]->stan = 3;
                                            $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$aaaa]->nazwa . '</span> zostaje otępiony.</span></div>';
                                        }
                                    }
                                }
                            }
                            //////////////////////otępienie koniec////////////////////////////
                            /////////////////////otrucie i śmiertelne otrucie/////////////////
                            else if ($pokemon[$kto]->ataki[$at[$kto]]['stan'] == 4)//otrucie i śmiertelne otrucie //4 i 5
                            {
                                $s = $pokemon[$kto]->ataki[$at[$kto]]['procent'];
                                $k = $pokemon[$kto]->ataki[$at[$kto]]['kto'];
                                $ab = $this->obliczenieCelnosci($s);///////obliczenie czy pok ma mieć nałożony stan.
                                if ($ab == 2) {

                                    if ($k == 1) {
                                        if ($pokemon[$kto]->stan == 0 && $pokemon[$kto]->typ1 != 8 && $pokemon[$kto]->typ2 != 8 && $pokemon[$kto]->typ1 != 11 && $pokemon[$kto]->typ2 != 11)//jeśli pokemon ma nałożony stan, to nie może mieć drugiego stanu.
                                        {
                                            $pokemon[$kto]->stan = 4;
                                            $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> zostaje otruty.</span></div>';
                                        } else if ($pokemon[$kto]->stan == 4 && $pokemon[$kto]->typ1 != 8 && $pokemon[$kto]->typ2 != 8 && $pokemon[$kto]->typ1 != 11 && $pokemon[$kto]->typ2 != 11) {
                                            $pokemon[$kto]->stan = 5;
                                            $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> zostaje śmiertelnie otruty.</span></div>';
                                        }
                                    } else {
                                        if ($pokemon[$aaaa]->stan == 0 && $pokemon[$aaaa]->typ1 != 8 && $pokemon[$aaaa]->typ2 != 8 && $pokemon[$aaaa]->typ1 != 11 && $pokemon[$aaaa]->typ2 != 11)//jeśli pokemon ma nałożony stan, to nie może mieć drugiego stanu.
                                        {
                                            $pokemon[$aaaa]->stan = 4;
                                            $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$aaaa]->nazwa . '</span> zostaje otruty.</span></div>';
                                        } else if ($pokemon[$aaaa]->stan == 4 && $pokemon[$aaaa]->typ1 != 8 && $pokemon[$aaaa]->typ2 != 8 && $pokemon[$aaaa]->typ1 != 11 && $pokemon[$aaaa]->typ2 != 11) {
                                            $pokemon[$aaaa]->stan = 5;
                                            $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$aaaa]->nazwa . '</span> zostaje śmiertelnie otruty.</span></div>';
                                        }
                                    }
                                }
                            }
                            /////////////////////otrucie i śmiertelne otrucie koniec//////////
                            ////////////////////sen///////////////////////////////////////////
                            else if ($pokemon[$kto]->ataki[$at[$kto]]['stan'] == 6)//sen
                            {
                                $s = $pokemon[$kto]->ataki[$at[$kto]]['procent'];
                                $k = $pokemon[$kto]->ataki[$at[$kto]]['kto'];
                                $ab = $this->obliczenieCelnosci($s);///////obliczenie czy pok ma mieć nałożony stan.
                                if ($ab == 2) {
                                    if ($k == 1) {
                                        if ($pokemon[$kto]->stan == 0)//jeśli pokemon ma nałożony stan, to nie może mieć drugiego stanu.
                                        {
                                            $pokemon[$kto]->stan = 6;
                                            $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> zostaje uśpiony.</span></div>';
                                        }
                                    } else {
                                        if ($pokemon[$aaaa]->stan == 0)//jeśli pokemon ma nałożony stan, to nie może mieć drugiego stanu.
                                        {
                                            $pokemon[$aaaa]->stan = 6;
                                            $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$aaaa]->nazwa . '</span> zostaje uśpiony.</span></div>';
                                        }
                                    }
                                }
                            }
                            ///////////////////sen koniec/////////////////////////////////////
                            //////////////////pułapka/////////////////////////////////////////
                            else if ($pokemon[$kto]->ataki[$at[$kto]]['stan'] == 7 && $obrazenia > 0)//pułapka
                            {
                                if ($pokemon[$aaaa]->stan == 0)//jeśli pokemon ma nałożony stan, to nie może mieć drugiego stanu.
                                {
                                    $pokemon[$aaaa]->stan = 7;
                                    $pokemon[$aaaa]->pulapka = $obrazenia;
                                    $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$aaaa]->nazwa . '</span> zostaje uwięziony.</span></div>';
                                }
                            }
                            //////////////////pułapka koniec//////////////////////////////////
                            //////////////////zamrożenie//////////////////////////////////////
                            else if ($pokemon[$kto]->ataki[$at[$kto]]['stan'] == 8)//zamrożenie
                            {
                                $s = $pokemon[$kto]->ataki[$at[$kto]]['procent'];
                                $k = $pokemon[$kto]->ataki[$at[$kto]]['kto'];
                                $ab = $this->obliczenieCelnosci($s);///////obliczenie czy pok ma mieć nałożony stan.
                                if ($ab == 2) {
                                    if ($k == 1) {
                                        if ($pokemon[$kto]->stan == 0)//jeśli pokemon ma nałożony stan, to nie może mieć drugiego stanu.
                                        {
                                            $pokemon[$kto]->stan = 8;
                                            $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> zostaje zamrożony.</span></div>';
                                        }
                                    } else {
                                        if ($pokemon[$aaaa]->stan == 0)//jeśli pokemon ma nałożony stan, to nie może mieć drugiego stanu.
                                        {
                                            $pokemon[$aaaa]->stan = 8;
                                            $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$aaaa]->nazwa . '</span> zostaje zamrożony.</span></div>';
                                        }
                                    }
                                }
                            }
                            //////////////////zamrożenie koniec///////////////////////////////
                            //////////////////oszołomienie////////////////////////////////////
                            if ($pokemon[$kto]->ataki[$at[$kto]]['stan'] == 9)//oszołomienie
                            {
                                $s = $pokemon[$kto]->ataki[$at[$kto]]['procent'];
                                $k = $pokemon[$kto]->ataki[$at[$kto]]['kto'];
                                $ab = $this->obliczenieCelnosci($s);///////obliczenie czy pok ma mieć nałożony stan.
                                if ($ab == 2) {
                                    if ($k == 1) {
                                        if ($pokemon[$kto]->stan == 0)//jeśli pokemon ma nałożony stan, to nie może mieć drugiego stanu.
                                        {
                                            $pokemon[$kto]->stan = 9;
                                            $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> zostaje oszołomiony.</span></div>';
                                        }
                                    } else {
                                        if ($pokemon[$aaaa]->stan == 0)//jeśli pokemon ma nałożony stan, to nie może mieć drugiego stanu.
                                        {
                                            $pokemon[$aaaa]->stan = 9;
                                            $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$aaaa]->nazwa . '</span> zostaje oszołomiony.</span></div>';
                                        }
                                    }
                                }
                            }
                            //////////////////oszołomienie koniec/////////////////////////////
                            //////////////////zakochanie//////////////////////////////////////
                            else if ($pokemon[$kto]->ataki[$at[$kto]]['stan'] == 10)//zakochanie
                            {
                                $s = $pokemon[$kto]->ataki[$at[$kto]]['procent'];
                                $ab = $this->obliczenieCelnosci($s);///////obliczenie czy pok ma mieć nałożony stan.
                                if ($ab == 2) {
                                    if ($pokemon[$aaaa]->stan == 0)//jeśli pokemon ma nałożony stan, to nie może mieć drugiego stanu.
                                    {
                                        $pokemon[$aaaa]->stan = 10;
                                        $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$aaaa]->nazwa . '</span> jest zakochany w przeciwniku.</span></div>';
                                    }
                                }
                            }
                            //////////////////zakochanie koniec///////////////////////////////
                        }
                        ////////////////szansa na stan specjalny koniec/////////////////////
                        /////////////////zwiększenie/zmniejszenie alertstyk/////////////////

                        ////////////////////////////////////////////////////////////////////
                        ////////1.Atak//////////////////////////////////////////////////////
                        ////////2.sp.atak///////////////////////////////////////////////////
                        ////////3.obrona////////////////////////////////////////////////////
                        ////////4.sp.obrona/////////////////////////////////////////////////
                        ////////5.szybkosc//////////////////////////////////////////////////
                        ////////6.losowo////////////////////////////////////////////////////
                        ////////7.wszystko//////////////////////////////////////////////////
                        ////////9.celność///////////////////////////////////////////////////
                        ////////-1.uniki////////////////////////////////////////////////////
                        ////////////////////////////////////////////////////////////////////

                        if ($pokemon[$kto]->ataki[$at[$kto]]['podwyzszenie'] == 1 && (($pokemon[1]->hp) > 0 && ($pokemon[2]->hp > 0)))/////podwyższenie alertstyk siebie lub przeciwnika
                        {
                            $l = 0;
                            $proc = $pokemon[$kto]->ataki[$at[$kto]]['procent_obn'];
                            $proc = $this->obliczenieCelnosci($proc);
                            if ($proc == 2) {
                                $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span>';
                                $ile = ($pokemon[$kto]->ataki[$at[$kto]]['obn_ile'] * 5) / 100;
                                $kogo = $pokemon[$kto]->ataki[$at[$kto]]['kogo'];
                                $czego2 = 0;
                                $czego3 = 0;
                                if ($pokemon[$kto]->atak[$at[$kto]]['czego'] > 100) {
                                    $czego = $pokemon[$kto]->ataki[$at[$kto]]['czego'];
                                    $czego3 = intval($czego / 100);
                                    $czego -= $czego3 * 100;
                                    $czego2 = intval($czego / 10);
                                    $czego -= $czego2 * 10;
                                    $czego1 = $czego;
                                } else if ($pokemon[$kto]->ataki[$at[$kto]]['czego'] > 10) {
                                    $czego = $pokemon[$kto]->ataki[$at[$kto]]['czego'];
                                    $czego2 = intval($czego / 10);
                                    $czego -= $czego2 * 10;
                                    $czego1 = $czego;
                                } else $czego1 = $pokemon[$kto]->ataki[$at[$kto]]['czego'];
                                if ($czego1 == 6 || $l == 1)//losowo
                                {
                                    $czego1 = rand(1, 5);
                                    $l = 1;
                                }
                                if ($czego1 == 7) {
                                    if ($kogo == 1) {
                                        $zw = ceil($ile * $pokemon[$kto]->atak);
                                        $_SESSION['walkat'] .= 'Atak <span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> zwiększa się o <span class="pogrubienie">' . $zw . '</span>.<br />';
                                        $pokemon[$kto]->atak += $zw;
                                        $zw = ceil($ile * $pokemon[$kto]->sp_atak);
                                        $_SESSION['walkat'] .= 'Specjlny atak <span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> zwiększa się o <span class="pogrubienie">' . $zw . '</span>.<br />';
                                        $pokemon[$kto]->sp_atak += $zw;
                                        $zw = ceil($ile * $pokemon[$kto]->obrona);
                                        $_SESSION['walkat'] .= 'Obrona <span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> zwiększa się o <span class="pogrubienie">' . $zw . '</span>.<br />';
                                        $pokemon[$kto]->obrona += $zw;
                                        $zw = ceil($ile * $pokemon[$kto]->sp_obrona);
                                        $_SESSION['walkat'] .= 'Specjala obrona <span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> zwiększa się o <span class="pogrubienie">' . $zw . '</span>.<br />';
                                        $pokemon[$kto]->sp_obrona += $zw;
                                        $zw = ceil($ile * $pokemon[$kto]->szybkosc);
                                        $_SESSION['walkat'] .= 'Szybkość <span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> zwiększa się o <span class="pogrubienie">' . $zw . '</span>.<br />';
                                        $pokemon[$kto]->szybkosc += $zw;
                                    }
                                } else if ($czego1 == 1 || $czego2 == 1 || $czego3 == 1)//atak
                                {
                                    if ($kogo == 1) {
                                        $zw = ceil($ile * $pokemon[$kto]->atak);
                                        $_SESSION['walkat'] .= 'Atak <span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> zwiększa się o <span class="pogrubienie">' . $zw . '</span>.<br />';
                                        $pokemon[$kto]->atak += $zw;
                                    }
                                    if ($kogo == 2) {
                                        $zw = ceil($ile * $pokemon[$aaaa]->atak);
                                        $_SESSION['walkat'] .= 'Atak <span class="pogrubienie">' . $pokemon[$aaaa]->nazwa . '</span> zwiększa się o <span class="pogrubienie">' . $zw . '</span>.<br />';
                                        $pokemon[$aaaa]->atak += $zw;
                                    }
                                } else if ($czego1 == 2 || $czego2 == 2 || $czego3 == 2)//sp.atak
                                {
                                    if ($kogo == 1) {
                                        $zw = ceil($ile * $pokemon[$kto]->sp_atak);
                                        $_SESSION['walkat'] .= 'Specjalny atak <span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> zwiększa się o <span class="pogrubienie">' . $zw . '</span>.<br />';
                                        $pokemon[$kto]->sp_atak += $zw;
                                    }
                                    if ($kogo == 2) {
                                        $zw = ceil($ile * $pokemon[$aaaa]->sp_atak);
                                        $_SESSION['walkat'] .= 'Specjalny atak <span class="pogrubienie">' . $pokemon[$aaaa]->nazwa . '</span> zwiększa się o <span class="pogrubienie">' . $zw . '</span>.<br />';
                                        $pokemon[$aaaa]->sp_atak += $zw;
                                    }
                                } else if ($czego1 == 3 || $czego2 == 3 || $czego3 == 3)//obrona
                                {
                                    if ($kogo == 1) {
                                        $zw = ceil($ile * $pokemon[$kto]->obrona);
                                        $_SESSION['walkat'] .= 'Obrona <span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> zwiększa się o <span class="pogrubienie">' . $zw . '</span>.<br />';
                                        $pokemon[$kto]->obrona += $zw;
                                    }
                                    if ($kogo == 2) {
                                        $zw = ceil($ile * $pokemon[$aaaa]->obrona);
                                        $_SESSION['walkat'] .= 'Obrona <span class="pogrubienie">' . $pokemon[$aaaa]->nazwa . '</span> zwiększa się o <span class="pogrubienie">' . $zw . '</span>.<br />';
                                        $pokemon[$aaaa]->obrona += $zw;
                                    }
                                } else if ($czego1 == 4 || $czego2 == 4 || $czego3 == 4)//sp.obrona
                                {
                                    if ($kogo == 1) {
                                        $zw = ceil($ile * $pokemon[$kto]->sp_obrona);
                                        $_SESSION['walkat'] .= 'Specjalna obrona <span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> zwiększa się o <span class="pogrubienie">' . $zw . '</span>.<br />';
                                        $pokemon[$kto]->sp_obrona += $zw;
                                    }
                                    if ($kogo == 2) {
                                        $zw = ceil($ile * $pokemon[$aaaa]->sp_obrona);
                                        $_SESSION['walkat'] .= 'Specjalna obrona <span class="pogrubienie">' . $pokemon[$aaaa]->nazwa . '</span> zwiększa się o <span class="pogrubienie">' . $zw . '</span>.<br />';
                                        $pokemon[$aaaa]->sp_obrona += $zw;
                                    }
                                } else if ($czego1 == 5 || $czego2 == 5 || $czego3 == 5)//szybkość
                                {
                                    if ($kogo == 1) {
                                        $zw = ceil($ile * $pokemon[$kto]->szybkosc);
                                        $_SESSION['walkat'] .= 'Szybkość <span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> zwiększa się o <span class="pogrubienie">' . $zw . '</span>.<br />';
                                        $pokemon[$kto]->szybkosc += $zw;
                                    }
                                    if ($kogo == 2) {
                                        $zw = ceil($ile * $pokemon[$aaaa]->szybkosc);
                                        $_SESSION['walkat'] .= 'Szybkość <span class="pogrubienie">' . $pokemon[$aaaa]->nazwa . '</span> zwiększa się o <span class="pogrubienie">' . $zw . '</span>.<br />';
                                        $pokemon[$aaaa]->szybkosc += $zw;
                                    }
                                }
                                $_SESSION['walkat'] .= '</span></div>';
                            }
                        } else if ($pokemon[$kto]->ataki[$at[$kto]]['obnizenie'] == 1 && (($pokemon[1]->hp) > 0 && ($pokemon[2]->hp > 0)))/////obniżenie alertstyk siebie lub przeciwnika
                        {
                            $l = 0;
                            $proc = $pokemon[$kto]->atak[$at[$kto]]['procent_obn'];
                            $proc = $this->obliczenieCelnosci($proc);
                            if ($proc == 2) {
                                $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span>';
                                $ile = ($pokemon[$kto]->ataki[$at[$kto]]['obn_ile'] * 5) / 100;
                                $kogo = $pokemon[$kto]->ataki[$at[$kto]]['kogo'];
                                $czego2 = 0;
                                $czego3 = 0;
                                if ($pokemon[$kto]->atak[$at[$kto]]['czego'] > 100) {
                                    $czego = $pokemon[$kto]->atak[$at[$kto]]['czego'];
                                    $czego3 = intval($czego / 100);
                                    $czego -= $czego3 * 100;
                                    $czego2 = intval($czego / 10);
                                    $czego -= $czego2 * 10;
                                    $czego1 = $czego;
                                } else if ($pokemon[$kto]->atak[$at[$kto]]['czego'] > 10) {
                                    $czego = $pokemon[$kto]->atak[$at[$kto]]['czego'];
                                    $czego2 = intval($czego / 10);
                                    $czego -= $czego2 * 10;
                                    $czego1 = $czego;
                                } else $czego1 = $pokemon[$kto]->atak[$at[$kto]]['czego'];
                                if ($czego1 == 6 || $l == 1)//losowo
                                {
                                    $czego1 = rand(1, 5);
                                    $l = 1;
                                }
                                if ($czego1 == 1 || $czego2 == 1 || $czego3 == 1)//atak
                                {
                                    if ($kogo == 1) {
                                        $zw = ceil($ile * $pokemon[$kto]->atak);
                                        $_SESSION['walkat'] .= 'Atak <span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> zmniejsza się o <span class="pogrubienie">' . $zw . '</span>.<br />';
                                        $pokemon[$kto]->atak -= $zw;
                                    } else if ($kogo == 2) {
                                        $zw = ceil($ile * $pokemon[$aaaa]->atak);
                                        $_SESSION['walkat'] .= 'Atak <span class="pogrubienie">' . $pokemon[$aaaa]->nazwa . '</span> zmniejsza się o <span class="pogrubienie">' . $zw . '</span>.<br />';
                                        $pokemon[$aaaa]->atak -= $zw;
                                    }
                                } else if ($czego1 == 2 || $czego2 == 2 || $czego3 == 2)//sp.atak
                                {
                                    if ($kogo == 1) {
                                        $zw = ceil($ile * $pokemon[$kto]->sp_atak);
                                        $_SESSION['walkat'] .= 'Specjalny atak <span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> zwmniejsza się o <span class="pogrubienie">' . $zw . '</span>.<br />';
                                        $pokemon[$kto]->sp_atak -= $zw;
                                    } else if ($kogo == 2) {
                                        $zw = ceil($ile * $pokemon[$aaaa]->sp_atak);
                                        $_SESSION['walkat'] .= 'Specjalny atak <span class="pogrubienie">' . $pokemon[$aaaa]->nazwa . '</span> zmniejsza się o <span class="pogrubienie">' . $zw . '</span>.<br />';
                                        $pokemon[$aaaa]->sp_atak -= $zw;
                                    }
                                } else if ($czego1 == 3 || $czego2 == 3 || $czego3 == 3)//obrona
                                {
                                    if ($kogo == 1) {
                                        $zw = ceil($ile * $pokemon[$kto]->obrona);
                                        $_SESSION['walkat'] .= 'Obrona <span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> zmniejsza się o <span class="pogrubienie">' . $zw . '</span>.<br />';
                                        $pokemon[$kto]->obrona -= $zw;
                                    } else if ($kogo == 2) {
                                        $zw = ceil($ile * $pokemon[$aaaa]->obrona);
                                        $_SESSION['walkat'] .= 'Obrona <span class="pogrubienie">' . $pokemon[$aaaa]->nazwa . '</span> zmniejsza się o <span class="pogrubienie">' . $zw . '</span>.<br />';
                                        $pokemon[$aaaa]->obrona -= $zw;
                                    }
                                } else if ($czego1 == 4 || $czego2 == 4 || $czego3 == 4)//sp.obrona
                                {
                                    if ($kogo == 1) {
                                        $zw = ceil($ile * $pokemon[$kto]->sp_obrona);
                                        $_SESSION['walkat'] .= 'Specjalna obrona <span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> zmniejsza się o <span class="pogrubienie">' . $zw . '</span>.<br />';
                                        $pokemon[$kto]->sp_obrona -= $zw;
                                    } else if ($kogo == 2) {
                                        $zw = ceil($ile * $pokemon[$aaaa]->sp_obrona);
                                        $_SESSION['walkat'] .= 'Specjalna obrona <span class="pogrubienie">' . $pokemon[$aaaa]->nazwa . '</span> zmniejsza się o <span class="pogrubienie">' . $zw . '</span>.<br />';
                                        $pokemon[$aaaa]->sp_obrona -= $zw;
                                    }
                                } else if ($czego1 == 5 || $czego2 == 5 || $czego3 == 5)//szybkość
                                {
                                    if ($kogo == 1) {
                                        $zw = ceil($ile * $pokemon[$kto]->szybkosc);
                                        $_SESSION['walkat'] .= 'Szybkość <span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> zmniejsza się o <span class="pogrubienie">' . $zw . '</span>.<br />';
                                        $pokemon[$kto]->szybkosc -= $zw;
                                    } else if ($kogo == 2) {
                                        $zw = ceil($ile * $pokemon[$aaaa]->szybkosc);
                                        $_SESSION['walkat'] .= 'Szybkość <span class="pogrubienie">' . $pokemon[$aaaa]->nazwa . '</span> zmniejsza się o <span class="pogrubienie">' . $zw . '</span>.<br />';
                                        $pokemon[$aaaa]->szybkosc -= $zw;
                                    }
                                } else if ($czego1 == 9)//celnosc
                                {
                                    $zw = ceil($ile * $pokemon[$aaaa]->celnosc);
                                    $_SESSION['walkat'] .= 'Celność <span class="pogrubienie">' . $pokemon[$aaaa]->nazwa . '</span> zmniejsza się o <span class="pogrubienie">' . $zw . '%</span>.<br />';
                                    $pokemon[$aaaa]->celnosc -= $zw;
                                }
                                $_SESSION['walkat'] .= '</span></div>';
                            }
                        }
                        /////////////////zwiększenie/zmniejszenie alertstyk koniec//////////
                        /////////////////leczenie///////////////////////////////////////////
                        if ($pokemon[$kto]->ataki[$at[$kto]]['procent_o'] > 0)///leczenie z zadanych obrażeń
                        {
                            $proc = $pokemon[$kto]->ataki[$at[$kto]]['procent_o'] / 100;
                            $lecz = ceil($obrazenia * $proc);
                            if ($pokemon[$kto]->hp + $lecz > $pokemon[$kto]->pocz_HP)
                                $lecz = $pokemon[$kto]->pocz_HP - $pokemon[$kto]->hp;
                            $pokemon[$kto]->hp += $lecz;
                            $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> leczy <span class="pogrubienie">' . $lecz . '</span> punktów zdrowia z zadanych obrażeń.</span></div>';
                        }
                        if ($pokemon[$kto]->ataki[$at[$kto]]['procent_l'] > 0) {
                            $proc = $pokemon[$kto]->ataki[$at[$kto]]['procent_l'] / 100;
                            $lecz = ceil($proc * $pokemon[$kto]->hp);
                            if (($pokemon[$kto]->hp + $lecz) > $pokemon[$kto]->pocz_HP)
                                $lecz = $pokemon[$kto]->pocz_HP - $pokemon[$kto]->hp;
                            $pokemon[$kto]->hp += $lecz;
                            $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> leczy <span class="pogrubienie">' . $lecz . '</span> punktów zdrowia.</span></div>';
                        }
                        /////////////////leczenie koniec////////////////////////////////////
                    }
                    //////////////////podpalenie//////////////////////////////////////////
                    if ($pokemon[$kto]->stan == 1) {
                        $pokemon[$kto]->runda++;
                        if ($pokemon[$kto]->runda > 0) {
                            $r = rand() % 3;
                            if ($r == 0 && $pokemon[$kto]->runda > 1) {
                                $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> udaje ugasić się płomienie otaczające jego ciało.</span></div>';
                                $pokemon[$kto]->stan = 0;
                                $pokemon[$kto]->runda = 0;
                            } else {
                                $o = ceil(0.125 * $pokemon[$kto]->max_hp);
                                $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> płonie. Płomienie zadają <span class="pogrubienie">' . $o . '</span> obrażeń.</span></div>';
                                $pokemon[$kto]->hp -= $o;
                                if ($pokemon[$kto]->hp < 0) $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span class="pogrubienie">Pokemon pada nieprzytomny na ziemię.</span></div>';
                            }
                        }
                    } /////////////////////otrucie////////////////////////////////////////
                    else if ($pokemon[$kto]->stan == 4) {
                        $pokemon[$kto]->runda++;
                        if ($pokemon[$kto]->runda > 0) {
                            $r = rand() % 3;
                            if ($r == 0 && $pokemon[$kto]->runda > 1) {
                                $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> nie jest już otruty.</span></div>';
                                $pokemon[$kto]->stan = 0;
                                $pokemon[$kto]->runda = 0;
                            } else {
                                $o = ceil(0.125 * $pokemon[$kto]->max_hp);
                                $pokemon[$kto]->hp -= $o;
                                $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> jest otruty. Trucizna zadaje <span class="pogrubienie">' . $o . '</span> obrażeń.</span></div>';
                                if ($pokemon[$kto]->hp <= 0) $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span class="pogrubienie">Pokemon pada nieprzytomny na ziemię.</span></div>';
                            }
                        }
                    } /////////////////śmiertelne otrucie///////////////////////////////////
                    else if ($pokemon[$kto]->stan == 5) {
                        $pokemon[$kto]->runda++;
                        if ($pokemon[$kto]->runda > 0) {
                            $r = rand() % 3;
                            if ($r == 0 && $pokemon[$kto]->runda > 1) {
                                $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> nie jest już śmiertelnie otruty.</span></div>';
                                $pokemon[$kto]->stan = 0;
                                $pokemon[$kto]->runda = 0;
                            } else {
                                $o = ceil(0.25 * $pokemon[$kto]->max_hp);
                                $pokemon[$kto]->hp -= $o;
                                $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> jest śmiertelnie otruty. Trucizna zadaje <span class="pogrubienie">' . $o . '</span> obrażeń.</span></div>';
                                if ($pokemon[$kto]->hp <= 0) $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span class="pogrubienie">Pokemon pada nieprzytomny na ziemię.</span></div>';
                            }
                        }
                    }
                    //////////////////////
                }
                $pokemon[$kto]->atak_runda_jeden--;
                if ($id_ataku == 198)//fury cutter
                {
                    $pokemon[$kto]->fury++;
                    if ($c == 1) $pokemon[$kto]->fury_t = 1;
                    else $pokemon[$kto]->fury_t = 0;
                }
                if ($pokemon[$kto]->atak_runda <= 0 && $pokemon[$kto]->atak_runda_jeden <= 0) $at[$kto] = $at[$kto] + 1;
                if ($pokemon[1]->hp <= 0 || $pokemon[2]->hp <= 0) break;
                if ($pokemon[$kto]->atak_runda_jeden > 0) {
                    $a--;
                    continue;
                }
                $atak = $at[$kto] - 1;
                if (($pokemon[$kto]->atak_runda <= 0) &&
                    (($pokemon[$kto]->atak[$atak]['ID'] == 360) || ($pokemon[$kto]->ataki[$atak]['ID'] == 553) || ($pokemon[$kto]->ataki[$atak]['ID'] == 370))
                ) //Outrage
                {
                    $_SESSION['walkat'] .= '<div class="walka_alert alert alert-info"><span><span class="pogrubienie">' . $pokemon[$kto]->nazwa . '</span> popada w otępienie.</span></div>';
                    $pokemon[$kto]->stan = 3;
                }
                if ($kto == 1) {
                    $kto = 2;
                    $ktot = 'jeden';
                } else {
                    $kto = 1;
                    $ktot = 'dwa';
                }
            }
            $i++;
        }
        //koniec pętli z walką między pokami
        if ($trener == 0 && $gracz == 0) {
            if (($pokemon[1]->hp <= 0 && $pokemon[2]->hp <= 0) || ($pokemon[1]->hp > 0 && $pokemon[2]->hp > 0)) {
                $_SESSION['walkat1'] .= '<div class="alert alert-warning text-big  margin-top"><span>WYNIK WALKI: Remis</span></div>';
                $_SESSION['walkat1'] .= '<div class="alert alert-info text-medium margin-top walka_alert text-center-alert"><span>Walka nauczyła coś twojego Pokemona, zyskuje on 5 punktów doświadczenia.<br />Dzięki walce zyskujesz 3 punkty doświadczenia.</span></div>';
                if ($pokemon[2]->hp <= 0) $hp = 0;
                else $hp = $pokemon[2]->hp;
                $db->update('UPDATE pokemony SET exp = (exp + 5), akt_HP = ? WHERE ID = ?', [$hp, $pokemon[2]->i2]);
                $db->update('UPDATE uzytkownicy SET doswiadczenie = (doswiadczenie + 3) WHERE ID = ?', [Session::_get('id')]);
                $id = $pokemon[2]->id;
                User::_get('pok', $id)->edit('dos', (User::_get('pok', $id)->get('dos') + 5));
                User::_get('pok', $id)->edit('akt_zycie', ($hp));
                User::_get('pok', $id)->edit('akt_zycie', $hp);
                Session::_set('tr_exp', (Session::_get('tr_exp') + 3));
            } else if ($pokemon[2]->hp <= 0) {
                $tlo = (rand() % 5) + 3;
                $_SESSION['walkat1'] .= '<div class="alert alert-danger text-big margin-top"><span>WYNIK WALKI: Porażka</span></div>';
                $_SESSION['walkat1'] .= '<div class="alert alert-info text-medium text-center-alert margin-top walka_alert"><span>Walka nauczyła coś twojego Pokemona, zyskuje on 2 punkty doświadczenia.<br />Dzięki walce zyskujesz 1 punkt doświadczenia.</span></div>';
                $db->update('UPDATE pokemony SET exp = (exp + 2), akt_HP = 0, przywiazanie = (przywiazanie - ?) WHERE ID = ?', [$tlo, $pokemon[2]->i2]);
                $db->update('UPDATE uzytkownicy SET doswiadczenie = (doswiadczenie + 1) WHERE ID = ?', [Session::_get('id')]);
                $id = $pokemon[2]->id;
                User::_get('pok', $id)->edit('dos', (User::_get('pok', $id)->get('dos') + 2));
                User::_get('pok', $id)->edit('akt_zycie', 0);
                Session::_set('tr_exp', (Session::_get('tr_exp') + 1));
            } else if ($pokemon[1]->hp <= 0) {
                $_SESSION['walkat1'] .= '<div class="alert alert-success text-big margin-top"><span>WYNIK WALKI: Wygrana</span></div>';
                $st = $_SESSION['pokemon']['pok_poziom'] / $wiersz['poziom'];
                $id = $pokemon[2]->id;
                User::_get('pok', $id)->edit('akt_zycie', $pokemon[2]->hp);
                if ($st <= 0.06) $exp = 3;
                else if ($st > 0.06 && $st <= 0.1) $exp = 5;
                else if ($st > 0.1 && $st <= 0.15) $exp = 7;
                else if ($st > 0.15 && $st <= 0.20) $exp = 8;
                else if ($st > 0.2 && $st <= 0.25) $exp = 10;
                else if ($st > 0.25 && $st <= 0.3) $exp = 13;
                else if ($st > 0.3 && $st <= 0.4) $exp = 14;
                else if ($st > 0.4 && $st <= 0.5) $exp = 17;
                else if ($st > 0.5 && $st <= 0.6) $exp = 18;
                else if ($st > 0.6 && $st <= 0.7) $exp = 20;
                else if ($st > 0.7 && $st <= 0.9) $exp = 22;
                else if ($st > 0.9 && $st <= 1) $exp = 24;
                else if ($st > 1 && $st <= 1.15) $exp = 28;
                else if ($st > 1.15 && $st <= 1.35) $exp = 35;
                else if ($st > 1.35) $exp = 40;
                $exp_t = (rand() % 3) + 3;
                if (Session::_get('pokemon')['trudnosc'] == 10) {
                    $exp *= 2;
                    $exp_t *= 2;
                }
                if (Session::_isset('karta')) {
                    $karta = explode('|', Session::_get('karta'));
                    if ($karta['0'] == '2') {
                        $exp *= 1.25;
                        $exp = round($exp);
                    }
                }
                User::_get('pok', $id)->edit('dos', (User::_get('pok', $id)->get('dos') + $exp));
                Session::_set('tr_exp', (Session::_get('tr_exp') + $exp_t));
                $hpp = $pokemon[2]->hp;
                $tlo = (rand() % 5) + 3;
                $_SESSION['walkat1'] .= '<div class="walka_alert alert alert-info text-medium margin-top text-center-alert"><span>Walka nauczyła coś twojego Pokemona, zyskuje on ' . $exp . ' punktów doświadczenia<br />Dzięki walce zyskujesz ' . $exp_t . ' punktów doświadczenia.</span></div>';
                $db->update('UPDATE pokemony SET exp = (exp + ?), akt_HP = ?, przywiazanie = (przywiazanie + ?) WHERE ID = ?', [$exp, $hpp, $tlo, $pokemon[2]->i2]);
                $db->update('UPDATE uzytkownicy SET doswiadczenie = (doswiadczenie + ?) WHERE ID = ?', [$exp_t, Session::_get('id')]);
                $db->update('UPDATE osiagniecia SET pokonane_poki = (pokonane_poki + 1) WHERE id_gracza = ?', [Session::_get('id')]);
                if (Session::_get('pokemon')['trudnosc'] < 10) {
                    $this->wyswietlPokeballe($db);
                } else {
                    $_SESSION['walkat1'] .= '<div class="walka_alert alert alert-warning text-medium margin-top text-center-alert"><span>Pokemon jest pod ochroną i nie możesz go łapać.</span></div>';
                    $kamien = 0;
                    if (in_array($pokemon[1]->id_poka, [59, 38, 136]))//ognisty
                    {
                        $rand = rand(1, 100);
                        if ($rand == 55) {
                            $kamien = 1;
                            $rodzaj = 'ogniste';
                            $_SESSION['walkat1'] .= '<div class="walka_alert alert alert-success text-center"><span>Pokemon gubi za sobą kamień ognisty!</span></div>';
                        }
                    } else if (in_array($pokemon[1]->id_poka, [62, 91, 121, 134]))//wodny
                    {
                        $rand = rand(1, 100);
                        if ($rand == 55) {
                            $kamien = 1;
                            $rodzaj = 'wodne';
                            $_SESSION['walkat1'] .= '<div class="walka_alert alert alert-success text-center"><span>Pokemon gubi za sobą kamień wodny!</span></div>';
                        }
                    } else if (in_array($pokemon[1]->id_poka, [45, 71, 102]))//roślinny
                    {
                        $rand = rand(1, 100);
                        if ($rand == 55) {
                            $kamien = 1;
                            $rodzaj = 'roslinne';
                            $_SESSION['walkat1'] .= '<div class="walka_alert alert alert-success text-center"><span>Pokemon gubi za sobą kamień roślinny!</span></div>';
                        }
                    } else if (in_array($pokemon[1]->id_poka, [16, 135]))//gromu
                    {
                        $rand = rand(1, 100);
                        if ($rand == 55) {
                            $kamien = 1;
                            $rodzaj = 'gromu';
                            $_SESSION['walkat1'] .= '<div class="walka_alert alert alert-success text-center"><span>Pokemon gubi za sobą kamień gromu!</span></div>';
                        }
                    } else if (in_array($pokemon[1]->id_poka, [34, 31, 36, 40]))//księżycowy
                    {
                        $rand = rand(1, 100);
                        if ($rand == 55) {
                            $kamien = 1;
                            $rodzaj = 'ksiezycowe';
                            $_SESSION['walkat1'] .= '<div class="walka_alert alert alert-success text-center"><span>Pokemon gubi za sobą kamień księżycowy!</span></div>';
                        }
                    } else if ($pokemon[1]->id_poka == 65)//kamień filozoficzny
                    {
                        $rand = rand(1, 16);
                        if ($rand == 11) {
                            $kamien = 1;
                            $rodzaj = 'kamien';
                            $_SESSION['walkat1'] .= '<div class="walka_alert alert alert-success text-center"><span>Pokemon gubi za sobą kamień filozoficzny!</span></div>';
                        }
                    } else if ($pokemon[1]->id_poka == 76)//obsydian
                    {
                        $rand = rand(1, 16);
                        if ($rand == 11) {
                            $kamien = 1;
                            $rodzaj = 'obsydian';
                            $_SESSION['walkat1'] .= '<div class="walka_alert alert alert-success text-center"><span>Pokemon gubi za sobą obsydian!</span></div>';
                        }
                    } else if ($pokemon[1]->id_poka == 68) {//czarny pas
                        $rand = rand(1, 16);
                        if ($rand == 11) {
                            $kamien = 1;
                            $rodzaj = 'pas';
                            $_SESSION['walkat1'] .= '<div class="walka_alert alert alert-success text-center"><span>Pokemon gubi za sobą czarny pas!</span></div>';
                        }
                    } else if ($pokemon[1]->id_poka == 94) {//ektoplazma
                        $rand = rand(1, 16);
                        if ($rand == 11) {
                            $kamien = 1;
                            $rodzaj = 'ektoplazma';
                            $_SESSION['walkat1'] .= '<div class="walka_alert alert alert-success text-center"><span>Pokemon gubi za sobą ektoplazmę!</span></div>';
                        }
                    }
                    if ($kamien == 1)
                        $db->sql_query("UPDATE kamienie SET $rodzaj = ($rodzaj + 1) WHERE id_gracza = " . $user->__get('id'));
                }
            }
        } elseif ($trener == 1) {
            if (($pokemon[1]->hp <= 0 && $pokemon[2]->hp <= 0)) {
                //$db->sql_query("UPDATE pokemony SET akt_HP = 0 WHERE ID = '".$pokemon[2]['id']."'");
                $id = $pokemon[2]->i2;
                User::_get('pok', $id)->edit('akt_zycie', 0);
                $tablica['kto'] = 3;//oba przegrały
                return $tablica;
            }
            if ($pokemon[1]->hp > 0 && $pokemon[2]->hp > 0) {//oba mają życie
                //$db->sql_query('UPDATE pokemony SET akt_HP = '.$pokemon[2]->hp.' WHERE ID = '.$pokemon[2]->id);
                $id = $pokemon[2]->i2;
                User::_get('pok', $id)->edit('akt_zycie', $pokemon[2]->hp);
                if (($pokemon[1]->hp / $pokemon[1]->max_hp) > ($pokemon[2]->hp / $pokemon[2]->max_hp)) $pokemon[2]->hp = 0;
                else if (($pokemon[1]->hp / $pokemon[1]->max_hp) < ($pokemon[2]->hp / $pokemon[2]->max_hp)) $pokemon[1]->hp = 0;
                else {
                    $tablica['kto'] = 3;
                    return $tablica;
                }
            }
            if ($pokemon[1]->hp <= 0) $i = 2;
            else if ($pokemon[2]->hp <= 0) $i = 1;
            //$db->sql_query("UPDATE pokemony SET akt_HP = '".$pokemon[2]['hp']."' WHERE ID = '".$pokemon[2]['id']."'");
            $id = $pokemon[2]->i2;
            if ($pokemon[2]->hp < 0) $pokemon[2]->hp = 0;
            User::_get('pok', $id)->edit('akt_zycie', $pokemon[2]->hp);
            $tablica['kto'] = $i;
            $tablica['atak'] = $pokemon[$i]->atak;
            $tablica['sp_atak'] = $pokemon[$i]->sp_atak;
            $tablica['obrona'] = $pokemon[$i]->obrona;
            $tablica['sp_obrona'] = $pokemon[$i]->sp_obrona;
            $tablica['szybkosc'] = $pokemon[$i]->szybkosc;
            $tablica['celnosc'] = $pokemon[$i]->celnosc;
            $tablica['hp'] = $pokemon[$i]->hp;
            $tablica['stan'] = $pokemon[$i]->stan;
            $tablica['runda'] = $pokemon[$i]->runda;
            $tablica['pulapka'] = $pokemon[$i]->pulapka;
            $tablica['id_poka'] = $pokemon[$i]->id_poka;
            $tablica['max_hp'] = $pokemon[$i]->max_hp;
            $tablica['typ1'] = $pokemon[$i]->typ1;
            $tablica['typ2'] = $pokemon[$i]->typ2;
            $tablica['at'] = $at[$i];
            $tablica['atak_runda'] = $pokemon[$i]->atak_runda;
            for ($j = 1; $j < 5; $j++)
                $tablica['atak' . $j]['id'] = $pokemon[$i]->ataki[$j]['ID'];

            return $tablica;
        } else if ($gracz == 1) {
            if (($pokemon[1]['hp'] <= 0 && $pokemon[2]['hp'] <= 0)) {
                $tablica['kto'] = 3;//oba przegrały
                return $tablica;
            }
            if ($pokemon[1]['hp'] > 0 && $pokemon[2]['hp'] > 0) {//oba mają życie
                if (($pokemon[1]['hp'] / $pokemon[1]['max_hp']) > ($pokemon[2]['hp'] / $pokemon[2]['max_hp'])) $pokemon[2]['hp'] = 0;
                elseif (($pokemon[1]['hp'] / $pokemon[1]['max_hp']) < ($pokemon[2]['hp'] / $pokemon[2]['max_hp'])) $pokemon[1]['hp'] = 0;
                else {
                    $tablica['kto'] = 3;
                    return $tablica;
                }
            }
            if ($pokemon[1]['hp'] <= 0) {//wygrał pok 2
                $tablica['kto'] = 2;
                $tablica['atak'] = $pokemon[2]['atak'];
                $tablica['sp_atak'] = $pokemon[2]['sp_atak'];
                $tablica['obrona'] = $pokemon[2]['obrona'];
                $tablica['sp_obrona'] = $pokemon[2]['sp_obrona'];
                $tablica['szybkosc'] = $pokemon[2]['szybkosc'];
                $tablica['celnosc'] = $pokemon[2]['celnosc'];
                $tablica['hp'] = $pokemon[2]['hp'];
                $tablica['stan'] = $pokemon[2]['stan'];
                $tablica['runda'] = $pokemon[2]['runda'];
                $tablica['pulapka'] = $pokemon[2]['pulapka'];
                $tablica['obrona'] = $pokemon[2]['obrona'];
                $tablica['id_poka'] = $pokemon[2]['id_poka'];
                $tablica['max_hp'] = $pokemon[2]['max_hp'];
                $tablica['typ1'] = $pokemon[2]['typ1'];
                $tablica['typ2'] = $pokemon[2]['typ2'];
                $tablica['at'] = $at['2'];
                $tablica['atak_runda'] = $pokemon[2]['atak_runda'];
                for ($i = 1; $i < 5; $i++) {
                    $tablica['atak' . $i]['id'] = $pokemon[2]['atak' . $i]['id'];
                    $tablica['atak' . $i]['nazwa'] = $pokemon[2]['atak' . $i]['nazwa'];
                    $tablica['atak' . $i]['moc'] = $pokemon[2]['atak' . $i]['moc'];
                    $tablica['atak' . $i]['typ'] = $pokemon[2]['atak' . $i]['typ'];
                    $tablica['atak' . $i]['celnosc'] = $pokemon[2]['atak' . $i]['celnosc'];
                    $tablica['atak' . $i]['rodzaj'] = $pokemon[2]['atak' . $i]['rodzaj'];
                }
                return $tablica;
            } elseif ($pokemon[2]['hp'] <= 0) {//wygrał pok 1
                $tablica['kto'] = 1;
                $tablica['atak'] = $pokemon[1]['atak'];
                $tablica['sp_atak'] = $pokemon[1]['sp_atak'];
                $tablica['obrona'] = $pokemon[1]['obrona'];
                $tablica['sp_obrona'] = $pokemon[1]['sp_obrona'];
                $tablica['szybkosc'] = $pokemon[1]['szybkosc'];
                $tablica['celnosc'] = $pokemon[1]['celnosc'];
                $tablica['hp'] = $pokemon[1]['hp'];
                $tablica['stan'] = $pokemon[1]['stan'];
                $tablica['runda'] = $pokemon[1]['runda'];
                $tablica['pulapka'] = $pokemon[1]['pulapka'];
                $tablica['obrona'] = $pokemon[1]['obrona'];
                $tablica['id_poka'] = $pokemon[1]['id_poka'];
                $tablica['max_hp'] = $pokemon[1]['max_hp'];
                $tablica['typ1'] = $pokemon[1]['typ1'];
                $tablica['typ2'] = $pokemon[1]['typ2'];
                $tablica['at'] = $at['1'];
                $tablica['atak_runda'] = $pokemon[1]['atak_runda'];
                for ($i = 1, $j = 0; $i < 5; $i++, $j++) {
                    $tablica['atak' . $j]['id'] = $pokemon[1]['atak' . $i]['id'];
                    $tablica['atak' . $j]['nazwa'] = $pokemon[1]['atak' . $i]['nazwa'];
                    $tablica['atak' . $j]['moc'] = $pokemon[1]['atak' . $i]['moc'];
                    $tablica['atak' . $j]['typ'] = $pokemon[1]['atak' . $i]['typ'];
                    $tablica['atak' . $j]['celnosc'] = $pokemon[1]['atak' . $i]['celnosc'];
                    $tablica['atak' . $j]['rodzaj'] = $pokemon[1]['atak' . $i]['rodzaj'];
                }
                return $tablica;
            }
        }
    }

    private function rzymskie($liczba)
    {
        switch ($liczba) {
            case 1:
                return 'I';
            case 2:
                return 'II';
            case 3:
                return 'III';
            case 4:
                return 'IV';
            case 5:
                return 'V';
            case 6:
                return 'VI';
            case 10:
                return 'X';
            default :
                return 'I';
        }
    }
}
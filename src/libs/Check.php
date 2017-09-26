<?php
namespace src\libs;

/**
 * class Check - checks user's session and protects from stealing session id
 * also checks if trainer experience if enough to get next level and if pokemon's hunger is the same as saved in session after full hour
 */
class Check 
{
    private $ip;
    private $info;
    private $name;
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
        require('./src/includes/trener/exp_trenera.php');
        require('./src/includes/pokemony/przyrosty.php');
        require('./src/includes/pokemony/pokemon.php');
        require('./src/includes/pokemony/exp_na_poziom.php');
        $this->przyrost = $przyrost;
        $this->exp_na_poziom = $exp_na_poziom;
        $this->exp_trenera = $exp_trenera;
        $this->pokemon_plik = $pokemon_plik;
        $this->check();
        User::setCookie();
    }

    private function check()
    {
        //we don't check when we are logging in, loging out and when error also don't check in cron
        if( !in_array(MODE, ['zaloguj', 'wyloguj', 'ERROR', 'index', 'INDEX', 'SprawdzLogin', 'cron'])){
            $this->ip = $_SERVER['REMOTE_ADDR'];
            $this->getFromDB();
            //if we are admin we don't check session's id and IP
            if(Session::_get('admin') != 1){
                $this->checkSession();
                $this->checkIp();
            }
            $this->checkPa();
            $this->checkAct();
            $this->checkSteal();
            $this->checkMails();
            $this->checkUserExp();
            $this->checkPokemonExp();
            $this->updateUser();
            $this->checkNagroda();
        }
    }

    private function getFromDb()
    {
        $this->info = $this->db->select('SELECT id_sesji, ogloszenie, ost_aktywnosc1, login, pa, karmienie FROM uzytkownicy WHERE ID='.Session::_get('id'), []);
        $this->info = $this->info[0];
    }

    private function checkSession()
    {

        if($this->info['id_sesji'] != session_id()){
            $loc = URL.'wyloguj/3';
            Session::_set('last', $_SERVER['REQUEST_URI']);
            header('Location: '.$loc);
            exit;
        }
    }

    private function checkIp()
    {
        //Jeśli IP nie zgodne z IP, na ktorym sie logowano, np. przy próbie kradzieży sesji
        if(Session::_get('ip') != $this->ip){
            Session::_set('proba', 1);
            setcookie ("sidc", "", time() - 3600);
            $loc = URL.'wyloguj/4';
            Session::_set('last', $_SERVER['REQUEST_URI']);
            header('Location: '.$loc);
            exit;
        }
    }

    private function checkPa()
    {
        if ($this->info['pa'] != Session::_get('pa')) {
            Session::_set('pa', $this->info['pa']);
        }
        if (Session::_get('pa') > Session::_get('mpa')) {
            Session::_set('pa', Session::_get('mpa'));
            $this->db->update('UPDATE uzytkownicy SET pa = mpa WHERE ID = ?',[ Session::_get('id')]);
        }
    }

    private function checkAct()
    {
        $t = time() - Session::_get('ost');
        if( $t > 900 ){
            $loc = URL.'wyloguj/2';
            Session::_set('last', $_SERVER['REQUEST_URI']);
            header('Location: '.$loc);
            exit();
        }
    }

    private function checkSteal()
    {
        if(Session::_isset('proba')){
          session_regenerate_id();
          Session::_unset('proba');
        }
    }

    private function checkMails()
    {
        if($this->info['ogloszenie'] > 0){
            Session::_set('ogloszenia', $this->info['ogloszenie']);
        }
        $rez = $this->db->select("SELECT (SELECT count(*) from wiadomosci WHERE odczytana = 0 AND id_twoj = :id_twoj) as Count1, (SELECT count(*) from poczta WHERE odczytana= 0 AND id_gracza= :id_twoj) as Count2", ['id_twoj' => Session::_get('id')]);
        $rez = $rez[0];
        if($rez['Count1']){
            Session::_set('nowe_w', $rez['Count1']);
        }
        if($rez['Count2']){
            Session::_set('nowe_p', $rez['Count2']);
        }
    }
    
    private function updateUser()
    {
        $plus = $this->checkHunger();
        $t = time() - Session::_get('ost');
        $this->db->update("UPDATE uzytkownicy SET ost_aktywnosc = ?, ost_aktywnosc1 = ?, online = (online + ?), online_dzisiaj = (online_dzisiaj + ?), karmienie = 0 WHERE ID = ?",
                [Session::_get('ost'), Session::_get('ost'), $t, $t, Session::_get('id')]);
        Session::_set('ost', time());//zapisanie nowego czasu aktywnosci
    }
    
    private function checkHunger()
    {
        if(!$this->info['karmienie']) {
            return;
        }
        $kwer = 'SELECT exp FROM pokemony WHERE ID in (';
        $kwer2 = 'order by case ID';
        $aa = 0;
        for ($i = 1 ; $i < 7 ; $i++) {
            if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id')  > 0) {
                $a = User::_get('pok', $i)->get('id');
                if($i == 1)$kwer = $kwer . " $a ";
                else $kwer = $kwer . ", $a ";
                $kwer2 = $kwer2 . " WHEN $a THEN ".$i;
                $aa++;
            }
        }
        $kwer = $kwer . ')' . $kwer2 . ' END';

        $poki = $this->db->select($kwer,[]);
        for ($i = 1 ; $i <= $poki['rowCount'] ; $i++) {
            $pokemon = $poki[$i-1];
            User::_get('pok', $i)->edit('dos', $pokemon['exp']);
        }
    }
    
    private function przywiazanie($x)
    {
        $przywiazanie = 0;
        if ($x < 6000)
            $przywiazanie += $x * 0.002843333;
        else {
            $przywiazanie = 17.06;
            $przywiazanie += ($x - 6000) * 0.00864818182;
        }
        $przywiazanie = -200 / ($przywiazanie + 1.98984) + 100.50054;
        if ($x == 0) 
            $przywiazanie = 0;
        $przywiazanie = round($przywiazanie, 2);
        if ($przywiazanie > 100)
            return 100;
        return $przywiazanie;
    }
    
    private function checkPokemonExp()
    {
        for ($i = 1 ; $i < 7 ; $i++) {
            if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id')) {
                while ( (User::_get('pok', $i)->get('dos') >= User::_get('pok', $i)->get('dos_p')) && (User::_get('pok', $i)->get('lvl') != 100)) {
                    $wsp = 1;
                    $ewo = 0;
                    $kwer = "UPDATE pokemony SET ";
                    if (User::_get('pok', $i)->get('ewo_p')) {//sprawdzenie ewolucji
                        $id = User::_get('pok', $i)->get('ewo_p');///zeby pobrac id ewo
                        if ($id == 80000199) {
                            $id = 80; 
                            User::_get('pok', $i)->edit('ewo_p', 80);
                            
                        }//slowpoke
                        if ($id > 10000) {
                            $w['wymagania'] = 999; 
                            $w['min_poziom'] = 200;
                            //brak ewo przez poziom.
                        } else {
                            $w['min_poziom'] = $this->pokemon_plik[$id]['min_poziom'];
                            $w['nazwa'] = $this->pokemon_plik[$id]['nazwa'];
                            $w['ewolucja_p'] = $this->pokemon_plik[$id]['ewolucja_p'];
                            $w['wymagania'] = $this->pokemon_plik[$id]['wymagania'];
                        }
                        //sprawdzenie warunków ewo
                        if(User::_get('pok', $i)->get('ewo_p') && !User::_get('pok', $i)->get('ewo') &&
                            $w['min_poziom'] <= (User::_get('pok', $i)->get('lvl')+1) && $w['wymagania'] != 999)
                        {
                            switch ($w['wymagania']) {
                                case 998:
                                    $x = $this->db->select('SELECT przywiazanie FROM pokemony WHERE ID = :id', [':id' => User::_get('pok', $i)->get('id')]);
                                    $x = $x[0];
                                    if ($this->przywiazanie($x['przywiazanie']) >= 90) {
                                        $ewo = 1; 
                                        $wsp = 3; 
                                        $kwer .= "id_poka = $id, ";
                                    }
                                    break;
                                case 0:
                                    $ewo = 1; 
                                    $wsp = 3; 
                                    $kwer .= "id_poka = $id, ";
                                    break;
                            }
                        }
                    }
                    if ($ewo) {
                        $wiersz = $this->przyrost[User::_get('pok', $i)->get('ewo_p')]; 
                        User::_get('pok', $i)->edit('id_p', User::_get('pok', $i)->get('ewo_p'));
                        User::_get('pok', $i)->edit('ewo_p', $w['ewolucja_p']);
                    }
                    else 
                        $wiersz = $this->przyrost[User::_get('pok', $i)->get('id_p')];
                        //id poka po ewo

                    if($wsp > 1) $ewo_id = User::_get('pok', $i)->get('ewo_p');
                    //przyrosty przy lvl/ewo
                    $atak = $wsp * $wiersz['atak'];
                    $sp_atak = $wsp * $wiersz['sp_atak'];
                    $obrona = $wsp * $wiersz['obrona'];
                    $sp_obrona = $wsp * $wiersz['sp_obrona'];
                    $szyb = $wsp * $wiersz['szybkosc'];
                    $hp = $wsp * $wiersz['hp'];
                    $lvl = User::_get('pok', $i)->get('lvl') + 1;
                    User::_get('pok', $i)->edit('zycie', (User::_get('pok', $i)->get('zycie') + round(User::_get('pok', $i)->get('jakosc')  * $hp / 100)));
                    User::_get('pok', $i)->edit('akt_zycie', User::_get('pok', $i)->get('zycie'));
                    User::_get('pok', $i)->edit('lvl', $lvl);
                    //dodać update bazy danych
                    $exp = User::_get('pok', $i)->get('dos') - User::_get('pok', $i)->get('dos_p');
                    User::_get('pok', $i)->edit('dos', $exp);
                    User::_get('pok', $i)->edit('dos_p', $this->exp_na_poziom[User::_get('pok', $i)->get('lvl')]);
                    $kwer = $kwer."Atak = (Atak + $atak), Sp_Atak = (Sp_Atak + $sp_atak), Obrona = (Obrona + $obrona), Sp_Obrona = (Sp_Obrona + $sp_obrona), Szybkosc = (Szybkosc + $szyb), HP = (HP + $hp), akt_HP = round((jakosc / 100) * (HP + Jag_HP)), poziom = $lvl, exp = $exp";
                    $stara_nazwa = User::_get('pok', $i)->get('imie');
                    if (!User::_get('pok', $i)->get('imie_z') && $ewo) {
                        $imie = $w['nazwa'];
                        User::_get('pok', $i)->edit('imie', $w['nazwa']);
                        $kwer = $kwer.", imie = '$imie'";
                    }
                    $kwer = $kwer.' WHERE ID = '.User::_get('pok', $i)->get('id').' AND wlasciciel = '.Session::_get('id');
                    $this->db->update($kwer, []);
                    if (!$ewo) {
                        $tytul = 'Twój Pokemon '.User::_get('pok', $i)->get('imie').' awansował na kolejny, '.$lvl.' poziom.';
                        $raport = '<div class="row nomargin text-center"><div class="col-xs-12">Twój Pokemon <span class="pogrubienie">'.User::_get('pok', $i)->get('imie').'</span> awansował na kolejny, '.$lvl.' poziom.</div>'
                                . '<div class="col-xs-12 pogrubienie">';
                        if(User::_get('pok', $i)->get('plec') == 1) $raport .= 'Jej';
                        else $raport .= 'Jego';
                        $raport .= ' statystyki rosną:</div><div class="col-xs-12"><div class="row nomargin">'
                                . '<div class="col-xs-4">Atak +'.$atak.'</div><div class="col-xs-4">Sp. Atak +'.$sp_atak.'</div><div class="col-xs-4">Obrona +'.$obrona.'</div></div></div> '
                                . '<div class="col-xs-12"><div class="row nomargin">'
                                . '<div class="col-xs-4">Sp.Obrona +'.$sp_obrona.'</div><div class="col-xs-4">Szybkość +'.$szyb.'</div><div class="col-xs-4">HP +'.$hp.'</div></div></div></div>';
                    } else {
                        $tytul = 'Twój Pokemon '.$stara_nazwa.' ewoluował w '.$w['nazwa'].'.';
                        $raport = '<div class="row nomargin text-center"><div class="col-xs-12">Twój Pokemon <span class="pogrubienie">'.$stara_nazwa.'</span> ewoluował w <span class="pogrubienie">'.$w['nazwa'].'</span>.</div>'
                                  . '<div class="col-xs-12 pogrubienie">';
                        if(User::_get('pok', $i)->get('plec') == 1) $raport .= 'Jej';
                        else $raport .= 'Jego';
                        $raport .= ' statystyki rosną:</div><div class="col-xs-12"><div class="row nomargin">'
                                  . '<div class="col-xs-4">Atak +'.$atak.'</div><div class="col-xs-4">Sp. Atak +'.$sp_atak.'</div><div class="col-xs-4">Obrona +'.$obrona.'</div></div></div> '
                                  . '<div class="col-xs-12"><div class="row nomargin">'
                                  . '<div class="col-xs-4">Sp.Obrona +'.$sp_obrona.'</div><div class="col-xs-4">Szybkość +'.$szyb.'</div><div class="col-xs-4">HP +'.$hp.'</div></div></div></div>';

                        if(User::_get('pok', $i)->get('id_p') == 148) $this->db->update('UPDATE kolekcja SET 148s = (148s + 1), 148z = (148z + 1) WHERE ID = ?', [Session::_get('id')]);
                        else if(User::_get('pok', $i)->get('id_p') == 149) $this->db->update('UPDATE kolekcja SET 149s = (149s + 1), 149z = (149z + 1) WHERE ID = ?', [Session::_get('id')]);
                        else if(User::_get('pok', $i)->get('id_p') == 139) $this->db->update('UPDATE kolekcja SET 139s = (139s + 1), 139z = (139z + 1) WHERE ID = ?', [Session::_get('id')]);
                        else if(User::_get('pok', $i)->get('id_p') == 141) $this->db->update('UPDATE kolekcja SET 141s = (141s + 1), 141z = (141z + 1) WHERE ID = ?', [Session::_get('id')]);
                    }
                    $godzina = date('Y-m-d-H-i-s');
                    $this->db->insert("INSERT INTO poczta (ID, id_gracza, tresc, godzina, odczytana, tytul)
                    VALUES ('NULL', ?, ?, ?, 0, ?)",
                            [Session::_get('id'), $raport, $godzina, $tytul]);
                }
            }
        }
    }
    
    private function checkUserExp()
    {
        if (Session::_get('tr_exp') >= Session::_get('exp_lvl_tr')) {
            if (Session::_get('poziom') <= 10) 
                $exp = 2;
            elseif (Session::_get('poziom') <= 20) 
                $exp = 3;
            elseif (Session::_get('poziom') <= 30) 
                $exp = 4;
            else $exp = 5;
            Session::_set('punkty', (Session::_get('punkty') + $exp));
            $exp_u = Session::_get('exp_lvl_tr');
            $this->db->update("UPDATE uzytkownicy SET poziom_trenera = (poziom_trenera + 1), doswiadczenie = (doswiadczenie - ? ), punkty = (punkty + ?) WHERE ID = ?", 
                    [$exp_u, $exp, Session::_get('id')]);
            $godzina = date('Y-m-d-H-i-s');
            if (Session::_get('poziom') < 11) { 
                $lemoniada = 1; 
                $woda = 3; 
                
            } elseif (Session::_get('poziom') < 21) {
                $lemoniada = 2; 
                $woda = 6;
            } elseif (Session::_get('poziom') < 51) 
                $lemoniada = 2;
            else 
                $lemoniada = 3;
            Session::_set('poziom', (Session::_get('poziom') + 1));
            Session::_set('tr_exp', (Session::_get('tr_exp') - Session::_get('exp_lvl_tr')));
            if (!isset($this->exp_trenera[Session::_get('poziom')])) {
                Session::_set('exp_lvl_tr', 10000000);
            } else {
                Session::_set('exp_lvl_tr', $this->exp_trenera[Session::_get('poziom')]);
            }
            $tytul = 'Nowy poziom trenera ('.Session::_get('poziom').')';
            $raport = '<div class="row nomargin text-center"><div class="col-xs-12">Awansowałeś na kolejny, '.Session::_get('poziom').' poziom.</div><div class="col-xs-12"> Otrzymujesz '.$exp.' punkty umiejętności.</div><div class="col-xs-12">';
            if($lemoniada == 1) $raport .= 'Otrzymujesz także '.$lemoniada.' lemoniadę';
            else $raport .= 'Otrzymujesz także '.$lemoniada.' lemoniady';
            if (isset($woda)) {
                if($woda == 3) $raport .= ' oraz '.$woda.' puszki wody.';
                else $raport .= ' oraz '.$woda.' puszek wody.';
                $this->db->update('UPDATE przedmioty SET lemoniada = ( lemoniada + ? ), woda = ( woda + ? ) WHERE id_gracza = ?', 
                        [$lemoniada, $woda, Session::_get('id')]);
            } else {
                $raport .= ".";
                $this->db->update('UPDATE przedmioty SET lemoniada = ( lemoniada + ? ) WHERE id_gracza = ?', 
                        [$lemoniada, Session::_get('id')]);
            }
            $raport .= '</div></div>';
            $this->db->insert("INSERT INTO poczta (ID, id_gracza, tresc, godzina, odczytana, tytul)
                               VALUES ('NULL', ?, ?, ?, 0, ?)",
                        [Session::_get('id'), $raport, $godzina, $tytul]);
        }
    }

    private function checkNagroda()
    {
        if (!Session::_isset('nagroda')) {
            return;
        }
        $dzien = Session::_get('nagroda') % 28;
        if($dzien == 0 )$dzien = 28;
        $tydzien = ceil($dzien / 7);
        $dzien -= ($tydzien-1)*7;
        if($tydzien > 4) $tydzien = ($tydzien % 5) + 1;
        $nagroda = 'To Twój '.Session::_get('nagroda').' dzień z rzędu kiedy logujesz się do gry. Oto Twoje nagrody.';
        if ($dzien == 1) {//pieniądze
            $kasa = 100000 * $tydzien;
            $nagroda .= '<br />'.$kasa.' &yen;';
            $this->db->update('UPDATE uzytkownicy SET pieniadze = (pieniadze + ?) WHERE ID = ?',[$kasa, Session::_get('id')]);
            Session::_set('kasa', (Session::_get('kasa') + $kasa));
        } elseif($dzien == 2) {
            $pokeb = 20 * $tydzien;
            $nestb = 20 * $tydzien;
            $greatb = 15 * $tydzien;
            $nagroda .= '<br />'.$pokeb.'x <img src="'.URL.'public/img/balle/Pokeball.png" class="pokeball_min" data-toggle="tooltip" data-title="pokeball" />';
            $nagroda .= '<br />'.$nestb.'x <img src="'.URL.'public/img/balle/Nestball.png" class="pokeball_min" data-toggle="tooltip" data-title="nestball" />';
            $nagroda .= '<br />'.$greatb.'x <img src="'.URL.'public/img/balle/Greatball.png" class="pokeball_min" data-toggle="tooltip" data-title="greatball" />';
            $this->db->update('UPDATE pokeballe SET pokeballe = (pokeballe + ?), nestballe = (nestballe + ?), greatballe = (greatballe + ?) 
                              WHERE id_gracza = ?', [$pokeb, $nestb, $greatb, Session::_get('id')]);
        } elseif($dzien == 3) {
            $chesto = 20 * $tydzien;
            $pecha = 30 * $tydzien;
            $rawst = 30 * $tydzien;
            $nagroda .= '<br />'.$chesto.'x <img src="'.URL.'public/img/jagody/Chesto_Berry.png" class="pokeball_min" data-toggle="tooltip" data-title="Chesto Berry" />';
            $nagroda .= '<br />'.$pecha.'x <img src="'.URL.'public/img/jagody/Pecha_Berry.png" class="pokeball_min" data-toggle="tooltip" data-title="Pecha Berry" />';
            $nagroda .= '<br />'.$rawst.'x <img src="'.URL.'public/img/jagody/Rawst_Berry.png" class="pokeball_min" data-toggle="tooltip" data-title="Rawst Berry" />';
            $this->db->update('UPDATE jagody SET Chesto_Berry = (Chesto_Berry + ?), Pecha_Berry = (Pecha_Berry + ?), Rawst_Berry = (Rawst_Berry + ?) 
                    WHERE id_gracza = ?', [$chesto, $pecha, $rawst, Session::_get('id')]);
        }
        else if($dzien == 4)
        {

        }
        else if($dzien == 5)
        {

        }
        else if($dzien == 6)
        {

        }
        else if($dzien == 7)
        {

        }
        $nagroda .= '<br />1x <img src="'.URL.'public/img/przedmioty/dukat.png" class="pokeball_min" data-toggle="tooltip" data-title="Dukat" />';
        $this->db->update('UPDATE przedmioty SET monety = (monety + 1) WHERE id_gracza = ?', [Session::_get('id')]);
        Session::_unset('nagroda');
        $this->nagroda = new View;
        $this->nagroda->nagroda =
        '<div id="nagroda_modal" class="modal fade in" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button>
              <span class="modal-title">NAGRODA ZA CODZIENNE LOGOWANIE.</span></div><div class="modal-body text-center" >'.$nagroda.'</div><div class="modal-footer"><button type="button" class="btn btn-warning" data-dismiss="modal">Zamknij</button></div>
                </div></div></div>';
        $this->nagroda->render('template/nagroda');
        Session::_unset('nagroda');
    }
    
}

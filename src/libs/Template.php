<?php

namespace src\libs;

class Template 
{
    public function __construct()
    {
        $this->header = new View;
        $this->menu = new View;
        $this->lewo = new View;
        $this->footer = new View;
    }
    
    public function menuHeaderTable($title, $dodatek = [])
    {
            $this->header($title, $dodatek);
            $this->menu();
            $this->lewo();
    }
    
    public function footer($mode)
    {
        $mode == 1 ? $this->footerNormal() : $this->footerPolowanie();
        
        if(User::$ustawienia->get('leczenie')){
            $this->footer->heal = '<span id="leczenie" style="margin-left:30px;"><img src="'.URL.'public/img/poki/113.png" width="40px" height="40px" title="Wylecz Pokemony" class="kursor"/></span>';
        }else{
            $this->footer->heal = '';
        }
        if(User::$ustawienia->get('soda')){
            $this->footer->soda = '<span id="puszka_sody"><img src="'.URL.'public/img/przedmioty/soda.png" width="40px" height="40px" title="Wypij sodę" class="kursor"/></span>';
        }else{
            $this->footer->soda = '';
        }
        if(User::$ustawienia->get('woda')){
            $this->footer->woda = '<span id="puszka_wody"><img src="'.URL.'public/img/przedmioty/woda.png" width="40px" height="40px" title="Wypij wodę" class="kursor"/></span>';
        }else{
            $this->footer->woda = '';
        }
        if(User::$ustawienia->get('lemoniada')){
            $this->footer->lemoniada = '<span id="puszka_lemoniady"><img src="'.URL.'public/img/przedmioty/lemoniada.png" width="40px" height="40px" title="Wypij lemoniadę" class="kursor"/></span>';
        }else{
            $this->footer->lemoniada = '';
        }
        if(User::$ustawienia->get('cheri')){ 
            $this->footer->cheri = '<span id="Cheri_Berry_stopka"><img src="'.URL.'public/img/jagody/Cheri_Berry.png" width="40px" height="40px" title="Ulecz drużynę używając Cheri Berry" class="kursor"/></span>';
        }else{
            $this->footer->cheri = '';
        }
        if(User::$ustawienia->get('wiki')){
            $this->footer->wiki = '<span id="Wiki_Berry_stopka"><img src="'.URL.'public/img/jagody/Wiki_Berry.png" width="40px" height="40px" title="Ulecz drużynę używając Wiki Berry" class="kursor"/></span>';
        }else{
            $this->footer->wiki = '';
        }
        if(User::$ustawienia->get('nakarm')){
            $this->footer->nakarm = '<span id="nakarm_stopka"><img src="'.URL.'public/img/przedmioty/karma.png" width="40px" height="40px" title="Nakarm drużynę" class="kursor"/></span>';
        }else{
            $this->footer->nakarm = ''; 
        }
        $this->footer->render('template/footer');
        
    }
        
    private function header($title, $dodatek = [])
    {
        $this->header->dodatek = ''; 
        if (!empty($dodatek)) {
            foreach ($dodatek as $value) {
                $this->header->dodatek .= $value;
            }
        }
        if (in_array(Session::_get('style'), [0,1])) {
            $this->header->styl = '<link rel="stylesheet" href="'.URL.'public/css/style_white.css" type="text/css" id="white" >';
        } else {
            $this->header->styl = '<link rel="stylesheet" href="'.URL.'public/css/style_black.css" type="text/css" id="black" >';
        }
        if (Session::_isset('desktop')) {
            $this->header->viewport = 'width=1200'; 
        } else {
            $this->header->viewport = 'width=device-width, initial-scale=1'; 
        }
        $this->header->language = 'pl';
        $this->header->title = $title;
        if (file_exists('./public/js/'.MODE.'.js')) {
                $this->header->dodatek .= '<script type="text/javascript" src="'.URL.'public/js/'.MODE.'.js"></script>';
        }
        if (file_exists('./pliki/css/'.Session::_get('id').'.css')) {
            $this->header->dodatek .= '<link rel="stylesheet" href="'.URL.'pliki/css/'.Session::_get('id').'.css" type="text/css">';
        }
        $this->header->render('template/header');
    }
    
    private function poki()
    {
        $poki = '<div class="table table-stripped">';
        for($i = 1 ; $i < 7 ; $i++){
            if( User::_isset('pok', $i) ){
                if($i % 2 == 1){
                    $poki .=  '<form id="pokemony" name="'.User::$pok[$i]->get('id').'" class="jeden tr" pok-i="'.$i.'" action="' . URL . 'pokemon" pok-imie="'.User::$pok[$i]->get('imie').'" method="post">';
                }else{
                    $poki .=  '<form id="pokemony" name="'.User::$pok[$i]->get('id').'" class="dwa tr" pok-i="'.$i.'" action="' . URL . 'pokemon" pok-imie="'.User::$pok[$i]->get('imie').'" method="post">';
                }
                $poki .=  '<label for="pok'.User::$pok[$i]->get('id').'">';

                $poki .= '<div class="td stan kursor">';
                if(User::$pok[$i]->get('shiny') == 1){
                    $poki .=  '<img src="'.URL.'public/img/poki/s'.User::$pok[$i]->get('id_p').'.png" class="img-responsive" />';
                }else{
                    $poki .=  '<img src="'.URL.'public/img/poki/'.User::$pok[$i]->get('id_p').'.png" class="img-responsive" />';
                }
                $poki .= '</div>';
                $poki .= '<div class="td stan2 kursor">';
                $poki .=  User::$pok[$i]->get('imie');
                if(User::$pok[$i]->get('plec') == 0){
                    $poki .=' <i class="icon-mars" class="text-extra-big" data-original-title="płeć męska" data-toggle="tooltip"></i>';
                }elseif(User::$pok[$i]->get('plec') == 1){
                    $poki .= ' <i class="icon-venus" class="text-extra-big" data-original-title="płeć żeńska" data-toggle="tooltip"></i>';
                }else{
                    $poki .= '<span title="Pokemon jest bezpłciowy">!</span>';
                }
                $poki .=  ' <span data-title="poziom" data-toggle="tooltip">(';
                $poki .= User::$pok[$i]->get('lvl');
                $poki .=  ')</span>';
                $poki .=  ' <span data-title="';
                $poki .=    User::$pok[$i]->get('glod') <= 50 ? 'Pokemon nie jest głodny' :
                           (User::$pok[$i]->get('glod') <= 90 ? 'Pokemon jest głodny' : 'Pokemon jest bardzo głodny!');
                $poki .= '('.round(User::$pok[$i]->get('glod')).'%)" data-toggle="tooltip">';
                if(User::$pok[$i]->get('glod') <= 50){
                    $poki .= '<span class="zielony_g">G';
                }elseif(User::$pok[$i]->get('glod') <= 90){
                    $poki .= '<span class="zolty">G';
                }else{
                    $poki .= '<span class="czerwony">G';
                }
                $poki .=  '</span></span>';
                $poki .= '<div class="progress progress-gra prog_HP" data-original-title="'.Lang::translate('Życie Pokemona').'" data-toggle="tooltip" data-placement="top">';
                $poki .= '<div class="progress-bar progress-bar-success progBarHP" role="progressbar" aria-valuenow="40" ';
                $dl = floor(User::$pok[$i]->get('akt_zycie') / User::$pok[$i]->get('zycie') * 10000) / 100; 
                $poki .= 'aria-valuemin="0" aria-valuemax="100" style="width:'.$dl.'%;">';
                $poki .= '<span>'.User::$pok[$i]->get('akt_zycie').' / '.User::$pok[$i]->get('zycie').' HP</span>';
                $poki .= '</div></div>';

                $poki .= '<div class="progress progress-gra prog_M" data-original-title="'.Lang::translate('Doświadczenie Pokemona').'" data-toggle="tooltip" data-placement="top">';
                $poki .= '<div class="progress-bar progress-bar-success progBarM" role="progressbar" aria-valuenow="40" ';
                if(User::$pok[$i]->get('lvl') == 100){
                    $dl = 100;
                }else{
                    $dl = (floor((User::$pok[$i]->get('dos')/User::$pok[$i]->get('dos_p')) * 10000)) /100;
                }
                $poki .= 'aria-valuemin="0" aria-valuemax="100" style="width:'.$dl.'%;">';
                $poki .= '<span>'.User::$pok[$i]->get('dos');
                if(User::$pok[$i]->get('lvl') != 100){
                    $poki .= '/ '.User::$pok[$i]->get('dos_p');
                }
                $poki .= ' '.Lang::translate('PD').'</span></div></div>';

                $poki .= '</div>';
                $poki .=  '<input type="hidden" name="id" value="'.User::$pok[$i]->get('id').'" />';
                $poki .=  '<input type="submit" style="display:none" id="pok'.User::$pok[$i]->get('id').'" />';
                $poki .= '</label></form>';
            }
        }
        return $poki;
    }
    
    private function menu()
    {
        $stan = '';
        $sala = '';
        for($i = 1 ; $i < 7 ; $i++){
            $to = '';
            if(User::_isset('pok', $i) && User::_get('pok', $i)->get('id') > 0){
                $to .= '<li><span>';
                $to .= '<img src="'.URL.'public/img/poki/';
                if(User::_get('pok', $i)->get('shiny') == 1){
                    $to .= 's';
                }
                $to .= User::_get('pok', $i)->get('id_p').'.png" class="menu_pok"/>';
                $sala .= $to;
                $stan .= $to;
                $sala .= '<a href="'.URL.'sala/'.User::_get('pok', $i)->get('id').'">'.User::_get('pok', $i)->get('imie').'</a></span></li>';
                $stan .= '<a href="'.URL.'pokemon/'.User::_get('pok', $i)->get('id').'">'.User::_get('pok', $i)->get('imie').'</a></span></li>';
            }
        }
        $this->menu->stan = $stan;
        $this->menu->sala = $sala;
        unset($stan);
        unset($sala);
        switch(Session::_get('region')){
            case 1:
                $this->menu->region = 'KANTO';
                break;
            case 2:
                $this->menu->region = 'JOHTO';
                break;
        }
        $this->menu->profil_id = Session::_get('id');
        if( Session::_get('admin') == 1){
            $this->menu->admin = '<li><a href="'.URL.'panel">PANEL ADMINA</a></li>';
        }else{
            $this->menu->admin = '';
        }
        
        
        $this->menu->render('template/menu');
    }
    
    public function lewo($i = 0)
    {
        $draw = '';
        if(Session::_isset('nowe_w')){
            $draw .=  '<i class="icon-mail-alt"></i>';
            $draw .=  '<span class="badge">'.Session::_get('nowe_w').'</span>';
        }else{
            $draw .=  '<i class="icon-mail"></i>';
        }
        $this->lewo->wiadomosc = $draw;
        $draw = '';
        if(Session::_isset('nowe_p')){
          $draw .=  '<i class="icon-doc-text-inv"></i>';
          $draw .=  '<span class="badge">'.Session::_get('nowe_p').'</span>';
        }else{
            $draw .=  '<i class="icon-doc-text"></i>';
        }
        $this->lewo->raport = $draw;
        unset($draw);
        $this->lewoUstawienia();
        $this->lewo->pa = Session::_get('pa');
        $this->lewo->mpa = Session::_get('mpa');
        $this->lewo->paa = round((Session::_get('pa') / Session::_get('mpa')) * 100, 2);
        $this->lewo->nick = Session::_get('nick');
        $this->lewo->tr_exp = Session::_get('tr_exp');
        $this->lewo->exp_lvl_tr = Session::_get('exp_lvl_tr');
        $this->lewo->pdd = round((Session::_get('tr_exp') / Session::_get('exp_lvl_tr')) * 100, 2);
        $this->lewo->lvl = Session::_get('poziom');
        $this->lewo->magazyn = Session::_get('magazyn');
        $this->lewo->poki_magazyn = Session::_get('poki_magazyn');
        $this->lewo->mag = round((Session::_get('poki_magazyn') /Session::_get('magazyn')) * 100, 2);
        $this->lewo->pieniadze = Session::_get('kasa');
        $this->lewo->druzyna = 'DRUŻYNA';
        $this->lewo->poki = $this->poki();
        if(!$i){
            $this->lewo->set = '';
        }
        if ($this->checkBeta()) {
           $this->lewo->beta = $this->sprawdz; 
        }
        $this->lewo->render('template/lewo');
    }
    
    private function lewoUstawienia()
    {
        if(user::$ustawienia->get('zegar') == 1 && !isset($_GET['ajax'])){
            $this->lewo->clock = '<div id="zegar"></div>';
        }else{
            $this->lewo->clock = '';
        }
        if(user::$ustawienia->get('tooltip') == 1 && !isset($_GET['ajax'])){
            $this->lewo->tooltip = '<div id="tooltip" class="d_none"></div>';
        }else{
            $this->lewo->tooltip = '';
        }
        if(!isset($_GET['ajax'])){
            $this->lewo->ajax = '<div id="tabelka">';
        }else{
            $this->lewo->ajax = '';
        }
        if(user::$ustawienia->get('podpowiedz') && !isset($_GET['ajax'])){
            $this->lewo->podpowiedz = '<div id="podpowiedz_" class="d_none"></div>';
        }else{
            $this->lewo->podpowiedz = '';
        }
        if(Session::_isset('karta')){
            $f = explode("|",Session::_get('karta'));
            $czas = $f['1'] - time();

            if($czas <= 0){
                Session::_unset('karta');
                Model::$db->update('UPDATE aktywnosc SET karta = 0, karta_czas = 0 WHERE id_gracza= ?',[Session::_get('id')]);
                //$db->sql_query("UPDATE aktywnosc SET karta = 0, karta_czas = 0 WHERE id_gracza=".$user->__get('id'));
            }elseif($f['0'] == 2){//doświadzenie
                $karta .=  '<div class="col-xs-12 nopadding">';
                $karta .=  "25% WIĘCEJ EXPA PRZEZ $czas SEKUND";
                $karta .=  '</div>';
            }elseif($f['0'] == 3){//PA
                $karta .=  '<div class="col-xs-12 nopadding">';
                $karta .=  "+10% MPA $czas SEKUND";
                $karta .=  '</div>';
            }else if($f['0'] == 4){//łapanie
                $karta .=  '<div class="col-xs-12 nopadding">';
                $karta .=  "25% WIĘKSZA SZANSA NA ZŁAPANIE PREZ $czas SEKUND";
                $karta .=  '</div>';
            }
        }else{
            $karta = '';
        }
        $this->lewo->karta = $karta;
        unset($karta);
    }
    
    private function footerNormal()
    {
        switch(Session::_get('region')){
            case 1:
                $this->footer->dzicze = '<a href="'.URL.'polowanie/polowanie/polana"><img src="'.URL.'public/img/dzicze/1.jpg" class="dzicz_img" data-toggle="tooltip" data-title="Polana" /></a>
                        <a href="'.URL.'polowanie/polowanie/wyspa"><img src="'.URL.'public/img/dzicze/2.jpg" class="dzicz_img" data-toggle="tooltip" data-title="Wyspa" /></a>
                        <a href="'.URL.'polowanie/polowanie/grota"><img src="'.URL.'public/img/dzicze/3.jpg" class="dzicz_img" data-toggle="tooltip" data-title="Grota" /></a>
                        <a href="'.URL.'polowanie/polowanie/dom_strachow"><img src="'.URL.'public/img/dzicze/4.jpg" class="dzicz_img" data-toggle="tooltip" data-title="Dom strachów" /></a>
                        <a href="'.URL.'polowanie/polowanie/gory"><img src="'.URL.'public/img/dzicze/5.jpg" class="dzicz_img" data-toggle="tooltip" data-title="Góry" /></a>
                        <a href="'.URL.'polowanie/polowanie/wodospad"><img src="'.URL.'public/img/dzicze/6.jpg" class="dzicz_img" data-toggle="tooltip" data-title="Wodospad" /></a>
                        <a href="'.URL.'polowanie/polowanie/safari"><img src="'.URL.'public/img/dzicze/7.jpg" class="dzicz_img" data-toggle="tooltip" data-title="Safari" /></a>';
                break;
            case 2:
                $this->footer->dzicze = '<a href="'.URL.'polowanie/polowanie/laka"><img src="'.URL.'public/img/dzicze/7.jpg" class="dzicz_img" data-toggle="tooltip" data-title="łąka" /></a>                  
                        <a href="'.URL.'polowanie/polowanie/lodowiec"><img src="'.URL.'public/img/dzicze/7.jpg" class="dzicz_img" data-toggle="tooltip" data-title="lodowiec" /></a>
                        <a href="'.URL.'polowanie/polowanie/mokradla"><img src="'.URL.'public/img/dzicze/7.jpg" class="dzicz_img" data-toggle="tooltip" data-title="mokradła" /></a>
                        <a href="'.URL.'polowanie/polowanie/wulkan"><img src="'.URL.'public/img/dzicze/7.jpg" class="dzicz_img" data-toggle="tooltip" data-title="wulkan" /></a>
                        <a href="'.URL.'polowanie/polowanie/JOHTO5"><img src="'.URL.'public/img/dzicze/7.jpg" class="dzicz_img" data-toggle="tooltip" data-title="JOHTO5" /></a>
                        <a href="'.URL.'polowanie/polowanie/jezioro"><img src="'.URL.'public/img/dzicze/7.jpg" class="dzicz_img" data-toggle="tooltip" data-title="jezioro" /></a>
                        <a href="'.URL.'polowanie/polowanie/mroczny_las"><img src="'.URL.'public/img/dzicze/7.jpg" class="dzicz_img" data-toggle="tooltip" data-title="mroczny las" /></a>';
                break;
        }
    }
    
    private function footerPolowanie()
    {
        switch(Session::_get('region')){
            case 1:
                $this->footer->dzicze = '<img src="'.URL.'public/img/dzicze/1.jpg" class="dzicz_img kursor" id="polana" data-toggle="tooltip" data-title="Polana" />
                        <img src="'.URL.'public/img/dzicze/2.jpg" class="dzicz_img kursor" id="wyspa" data-toggle="tooltip" data-title="Wyspa" />
                        <img src="'.URL.'public/img/dzicze/3.jpg" class="dzicz_img kursor" id="grota" data-toggle="tooltip" data-title="Grota" />
                        <img src="'.URL.'public/img/dzicze/4.jpg" class="dzicz_img kursor" id="dom_strachow" data-toggle="tooltip" data-title="Dom strachów" />
                        <img src="'.URL.'public/img/dzicze/5.jpg" class="dzicz_img kursor" id="gory" data-toggle="tooltip" data-title="Góry" />
                        <img src="'.URL.'public/img/dzicze/6.jpg" class="dzicz_img kursor" id="wodospad" data-toggle="tooltip" data-title="Wodospad" />
                        <img src="'.URL.'public/img/dzicze/7.jpg" class="dzicz_img kursor" id="safari" data-toggle="tooltip" data-title="Safari" />';
                break;
            case 2:
                $this->footer->dzicze = '<img src="'.URL.'public/img/dzicze/7.jpg" class="dzicz_img kursor" id="laka" data-toggle="tooltip" data-title="łąka" />
                        <img src="'.URL.'public/img/dzicze/7.jpg" class="dzicz_img kursor" id="lodowiec" data-toggle="tooltip" data-title="lodowiec" />
                        <img src="'.URL.'public/img/dzicze/7.jpg" class="dzicz_img kursor" id="mokradla" data-toggle="tooltip" data-title="mokradła" />
                        <img src="'.URL.'public/img/dzicze/7.jpg" class="dzicz_img kursor" id="wulkan" data-toggle="tooltip" data-title="wulkan" />
                        <img src="'.URL.'public/img/dzicze/7.jpg" class="dzicz_img kursor" id="JOHTO5" data-toggle="tooltip" data-title="JOHTO5" />
                        <img src="'.URL.'public/img/dzicze/7.jpg" class="dzicz_img kursor" id="jezioro" data-toggle="tooltip" data-title="jezioro" />
                        <img src="'.URL.'public/img/dzicze/7.jpg" class="dzicz_img kursor" id="mroczny_las" data-toggle="tooltip" data-title="mroczny las" />';
                break;
        }
    }
    
    private function checkBeta()
    {
        if (Session::_isset('beta')) {
            $this->sprawdz = '<div id="beta"></div>';
            //if($this->info['ost_aktywnosc1'] && ((time() - $this->info['ost_aktywnosc1']) > 1800))
            //   $this->sprawdz .= '<div id="witaj" name="'.$this->info['login'].'" href="'.(time() - $this->info['ost_aktywnosc1']).'"></div>';
            Session::_unset('beta');
            return 1;
        }
        return 0;
    }

}

 
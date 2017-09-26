<?php

function nl2br_str($string) 
{
  return str_replace(["\r\n", "\r", "\n"], '<br />', $string);
}

function html_zn($string)
{
    return str_replace(["&quot;", "&lt;", "&gt;"], ['"', '<', '>'], $string);
}

function przywiazanie($x) //x - ilosc przywiazania
{
    $przywiazanie = 0;
    if($x < 6000) {
        $przywiazanie += $x * 0.002843333;
    } else {
        $przywiazanie = 17.06;
        $przywiazanie += ($x - 6000) * 0.00864818182;
    }
    $przywiazanie = -200 / ($przywiazanie + 1.98984) + 100.50054;
    if($x == 0) $przywiazanie = 0;
    $przywiazanie = round($przywiazanie, 2);
    if($przywiazanie > 100)return 100;
    return $przywiazanie;
}

function docss($panel, $tlo, $tabelka, $ustawienia, $user)
{
    if($panel == '')
        $panel = $ustawienia->get('panele');
    if($tabelka == '')
        $tabelka = $ustawienia->get('tabelka');
    switch($panel)
    {
        case 0://zielony
            $css = '';
            $show = 'zielony';
        break;
        case 1://niebieski
            $css = '.modal-header,.panel-success>.panel-heading{background-color:rgba(51, 122, 183, 0.45);border-color:#337ab7;}.panel.panel-success{border-color:#337ab7;}';
            $show = 'niebieski';
        break;
        case 2://pomarańczowy
            $css = '.modal-header,.panel-success>.panel-heading{background-color:rgba(240, 173, 78, 0.45);border-color:#f0ad4e;}.panel.panel-success{border-color:#f0ad4e;}';
            $show = 'pomarańczowy';
        break;
        case 3://czerwony
            $css = '.modal-header,.panel-success>.panel-heading{background-color:rgba(217, 83, 79, 0.45);border-color:#d9534f;}.panel.panel-success{border-color:#d9534f;}';
            $show = 'czerwony';
        break;
        case 4://błękitny
            $css = '.modal-header,.panel-success>.panel-heading{background-color:rgba(91, 192, 222, 0.45);border-color:#5bc0de;}.panel.panel-success{border-color:#5bc0de;}';
            $show = 'błękitny';
        break;
        case 5://ZÓŁTY
            $css = '.modal-header,.panel-success>.panel-heading{background-color:rgba(255, 235, 59, 0.45);border-color:#ffeb3b;}.panel.panel-success{border-color:#ffeb3b;}';
            $show = 'żółty';
        break;
        case 6://FIOLETOWY
            $css = '.modal-header,.panel-success>.panel-heading{background-color:rgba(140, 114, 203, 0.45);border-color:#8C72CB;}.panel.panel-success{border-color:#8C72CB;}';
            $show = 'fioletowy';
        break;  
    }
    switch($tlo)
    {
        case '':
            $css .= '.container-fluid{background-color:'.$ustawienia->get('tlo').';}';
            break;
        case 'domyslne':
        case '#1c5b4e':
            $ustawienia->edit('tlo', '');
            break;
        default:
            $ustawienia->edit('tlo', $tlo);
            $css .= '.container-fluid{background-color:'.$tlo.';}';
        break;
    }
    
    switch($tabelka)
    {
        case '1':
            $ustawienia->edit('tabelka', '1');
            $css .= '#lewo{float:right;}';
        break;
    }
    $ustawienia->edit('tabelka', $tabelka);
    
    $plik = fopen('pliki/css/'.$user->__get('id').'.css', "w");
    fputs($plik, $css);//zapis do pliku
    fclose($plik);
    return $show;
}


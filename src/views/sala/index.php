<?php if(!isset($_GET['ajax'])){
    echo '<div class="col-xs-12 col-sm-8 col-md-9" id="prawo">';
} 
$a = [
    'o1' => 'Atak',
    'o2' => 'Sp. Atak',
    'o3' => 'Obrona',
    'o4' => 'Sp. Obrona',
    'o5' => 'Szybkość',
    'o6' => 'HP',
    'oo1' => 'Atak',
    'oo2' => 'Sp_Atak',
    'oo3' => 'Obrona',
    'oo4' => 'Sp_Obrona',
    'oo5' => 'Szybkosc',
    'oo6' => 'HP',
];
?>
<div class="panel panel-success jeden_ttlo"><div class="panel-heading text-medium"><span>SALA TRENINGOWA</span></div><div class="panel-body">
<?php if($this->mozliwosc) :?>
<div class="row nomargin">
    <div class="col-xs-12">
        <ul class="nav nav-tabs nav-justified margin-top">
            <?php
            foreach($this->pokTabelka as $value){
                echo $value;
            }
            if(isset($this->blad)) 
                echo $this->blad; 
            if(isset($this->info)) 
                echo $this->info;
            
            ?>
        </ul>
    </div>    
</div>
<div class="tab-content">
    <?php
    $a = [
            'o1' => 'Atak',
            'o2' => 'Sp. Atak',
            'o3' => 'Obrona',
            'o4' => 'Sp. Obrona',
            'o5' => 'Szybkość',
            'o6' => 'HP',
            'oo1' => 'Atak',
            'oo2' => 'Sp_Atak',
            'oo3' => 'Obrona',
            'oo4' => 'Sp_Obrona',
            'oo5' => 'Szybkosc',
            'oo6' => 'HP',
        ];
    $first['statusowy'] = 'ST';
    $first['fizyczny'] = 'FI';
    $first['specjalny'] = 'SP';
    foreach($this->pokInformacja as $value){
        echo '<div id="'.$value['ID'].'" class="tab-pane fade ';
        if($value['active']) echo 'in active';
        echo '"><div class="panel panel-success jeden_ttlo"><div class="panel-heading"><span>'.$value['imie'].'</span></div><div class="panel-body">';
        echo '<div class="row nomargin text-center margin-bottom"><div class="col-xs-4 col-sm-3">STATYSTYKA</div>';
        echo '<div class="col-xs-4 col-sm-1">WARTOŚĆ</div><div class="col-xs-4 col-sm-1">TRENING</div>';
        echo '<div class="col-xs-12 col-sm-7">TRENUJ</div></div>';
        
        for ($i = 1 ; $i < 7 ; $i++) {
            if(!($i & 1)) 
                echo '<div class="row nomargin jeden_ttlo text-center line30 margin-top"><div class="col-xs-4 col-md-3">'.$a['o'.$i].'</div>';
            else 
                echo '<div class="row nomargin dwa_ttlo text-center line30 margin-top"><div class="col-xs-4 col-md-3">'.$a['o'.$i].'</div>';
            echo '<div class="col-xs-4 col-md-1">'.($value['tr_'.$i] + $value['Jag_'.$a['oo'.$i]] + $value[$a['oo'.$i]]).'</div>';
            echo '<div class="col-xs-4 col-md-1">'.$value['tr_'.$i].'</div>';
            echo '<div class="col-xs-12 col-md-7"><div class="row nomargin">';
            echo '<div class="col-xs-6 col-md-4 nopadding"><button class="btn btn-primary trenuj_1 btn-block" id="'.$i.'_'.$value['ID'].'">+1 za '.number_format($value['koszt_'.$i],0,'','.').' &yen;</button></div>';
            echo '<div class="col-xs-3 col-md-4 nopadding"><input id="ile_'.$i.'_'.$value['ID'].'" type="text" class="ile form-control" placeholder="';
            (!$i % 6 ) ? 'Ilość x5' : 'Ilość';
            echo '"></input></div>';
            echo '<div class="col-xs-3 nopadding"><button class="btn btn-primary trenuj btn-block" id="'.$i.'_'.$value['ID'].'" >Trenuj</button></div></div></div></div>';//2xcol i 2xrow     
        }
        echo '</div></div>';
        
        //ATAKI
        if(!$value['atakLiczba'])
            echo '<div class="alert alert-info"><span>Pokemon nie może się nauczyć żadnego ataku!</span></div>';
        else {
            echo  '<div class="row margin-top margin-bottom"><div class="col-xs-12 text-center"><span class="pogrubienie">Naucz zamiast:</span></div><div class="col-xs-12 text-center"><div class="btn-group" data-toggle="buttons">';        
            for ($i = 1 ; $i < 5 ; $i++) {
                echo '<label class="btn btn-info btn-lg"><input name="zmien_atak_'.$value['ID'].'" value="'.$i.'" type="radio">'.$value['atak'.$i].'</label>';
            }
            echo '</div></div><div class="row nomargin">';
            for($i = 0 ; $i < $value['atakLiczba']; $i++) {
                echo '<div class="col-xs-6 margin-bottom text-center"><div data-id-poka="'.$value['ID'].'" id="'.$value['atak_'.$i]['ID'].'" class="btn btn-primary btn-lg atak ';
                if ($value['atak_'.$i]['znany']) {
                    echo 'disabled" data-toggle="tooltip" data-title="['.$value['atak_'.$i]['rodzaj'].'] | Znany"><img src="'.URL.'public/img/typy/'.$value['atak_'.$i]['typ'].'.gif" />'
                            . ' <span>'.$first[$value['atak_'.$i]['rodzaj']].'</span> '.$value['atak_'.$i]['nazwa'].'</div>';
                } elseif($value['atak_'.$i]['nizszy']) {
                    echo 'disabled" data-toggle="tooltip" data-title="['.$value['atak_'.$i]['rodzaj'].'] | Jeszcze nie dostępny"><img src="'.URL.'public/img/typy/'.$value['atak_'.$i]['typ'].'.gif" />'.$value['atak_'.$i]['nazwa'].' [od '.$value['atak_'.$i]['dos'].' poz] <span class="pull-right">'.$first[$value['atak_'.$i]['rodzaj']].'</span></div>';
                } else {
                    echo '" data-toggle="tooltip" data-title="['.$value['atak_'.$i]['rodzaj'].'] | Naucz"><img src="'.URL.'public/img/typy/'.$value['atak_'.$i]['typ'].'.gif" /> <span>'.$first[$value['atak_'.$i]['rodzaj']].'</span> '.$value['atak_'.$i]['nazwa'].'</div>';
                }
                echo '</div>';                       
            }
            echo '</div>';
        }
        echo '</div></div>';
    }
    
    
        
    ?>
</div>
<?php else :?>
<?=$this->blad?>
<?php endif; 
if (!isset($_GET['ajax'])) {
    echo '</div></div>';
}
?>
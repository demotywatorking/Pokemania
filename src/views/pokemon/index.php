<?php if (!isset($_GET['ajax'])): ?>
    <div class="col-xs-12 col-sm-8 col-md-9" id="prawo">
    <div class="panel panel-success jeden_ttlo">
    <div class="panel-heading"><span>STAN POKEMONA</span></div>
    <div class="panel-body">
<?php endif; ?>
<div class="row nomargin">
    <div class="col-xs-12">
<?php
if ($this->druzyna && !isset($_GET['ajax'])) :?>

            <ul class="nav nav-tabs nav-justified margin-top">
                <?php foreach ($this->tabelka as $value) : ?>
                    <li <?= $value['active'] ? 'class="active"' : '' ?> >
                        <a data-toggle="tab" href="#<?=$value['ID']?>" class="pok-tab-a">
                            <img src="<?=URL?>public/img/poki/srednie/<?=$value['shiny'] ? 's' : ''?><?=$value['id_p']?>.png" class="pok-tab center" />
                        </a>
                    </li>
                <?php endforeach;?>
            </ul>
        <?php endif;
        if (!isset($_GET['ajax'])) {
            echo '<div id="pokemony_content_ajax">';
        }
        if ($this->druzyna) {
            echo '<div class="tab-content">';
        }
        if (isset($this->blad)) echo '<div class="alert alert-danger text-center"><span>'.$this->blad.'</span></div>';
        if (isset($this->komunikat)) echo '<div class="alert alert-success text-center"><span>'.$this->komunikat.'</span></div>';
foreach ($this->pokemon as $value) :
    if ($value['druzyna']) {
        echo '<div id="'.$value['ID'].'" class="tab-pane fade ';
        if ($value['active']) echo 'in active';
        echo '">';
    }
    if (!isset($_GET['ajax'])) echo '<div id="pok_content_'.$value['ID'].'">'; ?>
    <div class="panel panel-success jeden_ttlo">
        <div class="panel-heading"><span><?=strtoupper($value['imie'])?></span></div>
        <div class="panel-body">
            <div class="well well-stan dwa_ttlo">
                <div class="row nomargin">
                    <div class="col-xs-12 col-sm-4 margin-top-big">
                        <img src="<?=URL?>public/img/poki/<?=$value['shiny'] ? 's' : ''?><?=$value['id_poka']?>.png" class="img-responsive center"/>
                    </div>
                    <div class="col-xs-12 col-sm-8">

                        <div class="row nomargin well well-stan alert-info margin_2">
                            <span>
                                <div class="col-xs-4">Gatunek</div>
                                <div class="col-xs-8">
                                    #<?=$value['id_poka']?> <a href="pokemon_info.php?n=<?=$value['id_poka']?>" TARGET="_blank" class="btn btn-link nopadding">
                                    <?=$value['shiny'] ? 'Shiny ' : ''?>
                                    <?=$value['nazwa']?>
                                    </a>
                                </div>
                            </span>
                        </div>

                        <div class="row nomargin well well-stan alert-info margin_2">
                            <span>
                                <div class="col-xs-4">Właściciel</div>
                                    <div class="col-xs-8">
                                        <a href="<?=URL?>profil/nick/<?=$value['wlasciciel']?>" TARGET="_blank" class="btn btn-link nopadding"><?=$value['wlasciciel']?></a>
                                    </div>
                            </span>
                        </div>

                        <div class="row nomargin well well-stan alert-info margin_2">
                            <span>
                                <div class="col-xs-4">POZIOM:</div>
                                <div class="col-xs-8"><?=$value['poziom']?></div>
                            </span>
                        </div>

                        <?php if ($value['swoj']) : ?>
                            <div class="row nomargin well well-stan alert-info margin_2">
                                <span>
                                    <div class="col-xs-4">EXP:</div>
                                    <div class="col-xs-8"><?=$value['exp']?></div>
                                </span>
                            </div>
                        <?php endif; ?>

                        <div class="row nomargin well well-stan alert-info margin_2">
                            <span>
                                <div class="col-xs-4">TYP<?= $value['typ2'] ? 'Y' : ''?>:</div>
                                <div class="col-xs-8">
                                    <img src="<?=URL?>public/img/typy/<?=$value['typ1']?>.gif" data-toggle="tooltip" data-title="<?=$value['typ1_o']?>"/>
                                    <?= $value['typ2'] ? '<img src="'.URL.'public/img/typy/'.$value['typ2'].'.gif" data-toggle="tooltip" data-title="'.$value['typ2_o'].'"/>' : '' ?>
                                </div>
                            </span>
                        </div>

                        <div class="row nomargin well well-stan alert-info margin_2">
                            <span>
                                <div class="col-xs-4">PŁEĆ:</div>
                                <div class="col-xs-8">
                                    <?php
                                    if (!$value['plec']) echo '<i class="icon-mars" data-title="Płeć męska" data-toggle="tooltip"></i>';
                                    elseif ($value['plec'] == 1) echo '<i class="icon-venus" data-title="Płeć żeńska" data-toggle="tooltip"></i>';
                                    else echo 'Pokemon jest bezpłciowy';
                                    ?>
                                </div>
                            </span>
                        </div>

                        <div class="row nomargin well well-stan alert-info margin_2">
                            <span>
                                <div class="col-xs-4">WARTOŚĆ:</div>
                                <div class="col-xs-8"><?=$value['wartosc']?> &yen;</div>
                            </span>
                        </div>

                        <?php if($value['swoj']) : ?>
                            <div class="row nomargin well well-stan alert-info margin_2">
                                <span>
                                    <div class="col-xs-4">ZŁAPANY:</div>
                                    <div class="col-xs-8 text-center">
                                    <?=$value['data_zlapania']?><br />
                                    <?php
                                        if($value['zlapany'] == 'loteria') echo 'Wygrany w loterii';
                                        else if($value['zlapany'] == 'wymiana') echo 'Otrzymany za dukaty';
                                        else if($value['zlapany'] == '') echo 'Brak danych';
                                        else if($value['zlapany'] == 'starter') echo 'Starter';
                                        else echo '<img src="'.URL.'public/img/balle/'.$value['zlapany'].'.png" class="pokeball_min" data-toggle="tooltip" data-title="'.$value['zlapany'].'" />';
                                    ?>
                                    </div>
                                </span>
                            </div>

                            <div class="row nomargin well well-stan alert-info margin_2">
                                <span>
                                    <div class="col-xs-4" data-toggle="tooltip" data-title="przywiązanie">PRZYW:</div>
                                    <div class="col-xs-8">
                                        <div class="progress progress-gra prog_EXP" data-original-title="Przywiązanie pokemona" data-toggle="tooltip" data-placement="top">
                                            <div class="progress-bar progress-bar-success progBarEXP" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                                 style="width:<?=$value['przywiazanie']?>%;"><span><?=$value['przywiazanie']?> %</span>
                                            </div>
                                        </div>
                                    </div>
                                </span>
                            </div>

                            <div class="row nomargin well well-stan alert-info margin_2">
                                <span>
                                    <div class="col-xs-4">JAKOŚĆ</div>
                                    <div class="col-xs-8"><?=$value['jakosc']?> %</div>
                                </span>
                            </div>
                        <?php endif;
                        if ($value['druzyna']) {
                            echo '<div class="row nomargin well well-stan alert-info margin_2"><span>';
                            echo '<div class="col-xs-4">GŁÓD:</div><div class="col-xs-8">';
                            if ($value['glod'] <= 50) {
                                echo '<div class="progress progress-gra prog_EXP" data-original-title="Głód Pokemona" data-toggle="tooltip" data-placement="top">';
                                echo '<div class="progress-bar progress-bar-success progBarEXP" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:'.$value['glod'].'%;"><span>'.$value['glod'].' %</span></div></div>';
                            } elseif($value['glod'] <= 90) {
                                echo '<div class="progress progress-gra prog_Z" data-original-title="Głód Pokemona" data-toggle="tooltip" data-placement="top">';
                                echo '<div class="progress-bar progress-bar-success progBarZ" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:'.$value['glod'].'%;"><span>'.$value['glod'].' %</span></div></div>';
                            } else {
                                echo '<div class="progress progress-gra prog_HP" data-original-title="Głód Pokemona" data-toggle="tooltip" data-placement="top">';
                                echo '<div class="progress-bar progress-bar-success progBarHP" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:'.$value['glod'].'%;"><span>'.$value['glod'].' %</span></div></div>';
                            }
                            echo  '</div></span></div>';
                        }?>
                        <div class="col-xs-12 text-center margin-top">
                            <button class="btn btn-primary nakarm" pokemon-id="<?=$value['ID']?>">Nakarm Pokemona</button>
                        </div>
                            <div id="nakarm_<?=$value['ID']?>"></div>
                    </div>
                </div>
            </div>

            <ul class="nav nav-tabs margin-top
                <?php if(isset($_GET['modal'])) $show .= 'jeden_ttlo'; ?>
                "><li class="active"><a data-toggle="tab" href="#statystyki_<?=$value['ID']?>">Statystyki</a></li>
               <li><a data-toggle="tab" href="#opis_<?=$value['ID']?>">Opis</a></li>
                <li><a data-toggle="tab" href="#odpornosci_<?=$value['ID']?>">Odporności</a></li>
                <li><a data-toggle="tab" href="#ataki_<?=$value['ID']?>">Ataki</a></li>
            </ul>
            <div class="tab-content">
                <div id="statystyki_<?=$value['ID']?>" class="tab-pane active fade in">
                    <div class="panel panel-success jeden_ttlo">
                        <div class="panel-heading"><span>Statystyki</span></div>
                        <div class="panel-body">

                            <div class="table-responsive">
                                <table class="table table-bordered table-condensed jeden_ttlo">
                                    <thead>
                                    <tr><th> </th><th>ATAK</th><th>SP.ATAK</th><th>OBRONA</th><th>SP.OBRONA</th><th>SZYBKOŚĆ</th><th>HP</th><th>CELNOŚĆ</th></tr>
                                    </thead>
                                    <tbody>
                                    <tr><td>PRZYROSTY</td><td><?=$value['Atak']?></td><td><?=$value['Sp_Atak']?></td><td><?=$value['Obrona']?></td><td><?=$value['Sp_Obrona']?></td>
                                        <td><?=$value['Szybkosc']?></td><td><?=$value['HP']?></td><td><?=$value['celnosc']?>%</td></tr>
                                    <tr><td>JAGODY<span data-toggle="tooltip" data-title="limit jagód">(<?=$value['Jag_Limit']?>)</span></td><td><?=$value['Jag_Atak']?></td><td><?=$value['Jag_Sp_Atak']?></td>
                                        <td><?=$value['Jag_Obrona']?></td><td><?=$value['Jag_Sp_Obrona']?></td><td><?=$value['Jag_Szybkosc']?></td><td><?=$value['Jag_HP']?></td><td>---</td></tr>
                                    <tr><td>TRENINGI</td><td><?=$value['tr_1']?></td><td><?=$value['tr_2']?></td><td><?=$value['tr_3']?></td>
                                        <td><?=$value['tr_4']?></td><td><?=$value['tr_5']?></td><td><?=$value['tr_6']?></td><td>---</td></tr>
                                    <tr class="pogrubienie"><td>ŁĄCZNIE</td><td><?=$value['Atak_caly']?></td><td><?=$value['Sp_Atak_caly']?></td><td><?=$value['Obrona_caly']?></td><td><?=$value['Sp_Obrona_caly']?></td>
                                        <td><?=$value['Szybkosc_caly']?></td><td><?=$value['HP_caly']?></td><td><?=$value['celnosc']?>%</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="opis_<?=$value['ID']?>" class="tab-pane">
                    <div class="panel panel-success jeden_ttlo">
                        <div class="panel-heading"><span>Opis</span></div>
                        <div class="panel-body">
                            <?=$value['opis']?>
                        </div>
                    </div>
                </div>

                <div id="odpornosci_<?=$value['ID']?>" class="tab-pane">
                    <div class="panel panel-success jeden_ttlo">
                        <div class="panel-heading"><span>Odporności</span></div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-condensed jeden_ttlo text-center">
                                    <thead><tr>
                                        <?php
                                        for ($v = 1 ; $v < 19 ; $v++) {
                                            echo '<th class="center"><img src="'.URL.'public/img/typy/'.$v.'.gif" data-title="'.$this->rodzaj[$v].'" data-toggle="tooltip" /></th>';
                                        }
                                        ?>
                                    </tr></thead>
                                    <tbody><tr>
                                        <?php
                                        for ($v = 1 ; $v < 19 ; $v++) {
                                           echo '<td>'.$value['odp_'.$v].'</td>';
                                        }
                                        ?>
                                    </tr></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="ataki_<?=$value['ID']?>" class="tab-pane">
                    <div class="panel panel-success jeden_ttlo">
                        <div class="panel-heading"><span>Ataki</span></div>
                        <div class="panel-body">
                            <?= $value['swoj'] ? '<a class="btn btn-primary" href="'.URL.'sala/'.$value['ID'].'">SALA TRENINGOWA</a>' : '' ?>
                            <?php
                            if ($value['ataki']) {//tabelka
                                echo '<div class="table-responsive"><table class="table table-bordered table-condensed jeden_ttlo">';
                                echo '<thead><tr><th>#</th><th>TYP</th><th>RODZAJ</th><th>NAZWA</th><th> </th></tr></thead><tbody>';
                                for ($i = 1 ; $i < 5 ; $i++) {
                                    if (!isset($value['atak'][$i])) {
                                        echo '<td>'.$i.'</td><td>-brak-</td><td>-brak-</td><td>-brak-</td>';
                                    } else {
                                        echo '<td>' . $i . '</td><td><img src="' . URL . 'public/img/typy/' . $value['atak'][$i]['typ'] . '.gif" 
                                        data-title="' . $this->rodzaj[$value['atak'][$i]['typ']] . '" data-toggle="tooltip" /></td><td>' . $value['atak'][$i]['rodzaj'] . '</td><td>' . $value['atak'][$i]['nazwa'] . '</td>';
                                    }
                                    echo '<td>';
                                    if ($value['swoj']) {
                                        if ($i != 1) {
                                            $a = ' <i class="icon-up"></i>';
                                            echo '<button class="btn btn-primary action" id="up/'.$i.'/'.$value['ID'].'" data-title="zmień priorytet na wyższy" data-toggle="tooltip" >'.$a.'</button> ';
                                        }
                                        if ($i != 4) {
                                            $a = ' <i class="icon-down"></i>';
                                            echo ' <button class="btn btn-primary action" id="down/'.$i.'/'.$value['ID'].'" data-title="zmień priorytet na niższy" data-toggle="tooltip" >'.$a.'</button> ';
                                        }
                                    }
                                    echo '</td>';

                                    echo '</tr>';
                                }
                                echo '</tbody></table></div>';
                            } else {
                                echo '<div class="alert alert-warning margin-top"><span>Pokemon nie zna żadnego ataku</span></div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>

            </div>

            <?php if ($value['swoj']) : ?>
                <div class="panel panel-success jeden_ttlo">
                    <div class="panel-heading"><span>Ustawienia</span></div>
                    <div class="panel-body">

                        <div class="alert alert-info"><span>Zmień imię Pokemona</span></div>
                        <div class="input-group">
                            <input class="form-control" type="text" name="nazwa_<?=$value['ID']?>" value="<?=$value['imie']?>"/>
                            <span class="input-group-btn nazwa" name="<?=$value['ID']?>"><button class="btn btn-primary">Zapisz</button></span>
                        </div>

                        <div class="alert alert-info margin-top"><span>Zmień opis Pokemona</span></div>
                        <div class="form-group">
                            <textarea name="opis" id="opis_t_<?=$value['ID']?>" class="wysibb-texarea form-control" rows="6"></textarea>
                        </div>
                            <!--$script .= '$("#opis_t_'.$wiersz['ID'].'").wysibb(wbbOpt);';
                            if($wiersz['opis'] != '<span></span><br>' && $wiersz['opis'] != '')$script .= '$(\'#opis_t_'.$wiersz['ID'].'\').htmlcode(\''.html_zn($wiersz['opis']).'\');';
                            else $script .= '$("#opis_t_'.$wiersz['ID'].'").htmlcode(" ");';-->
                        <button class="btn btn-primary zapisz" id="<?=$value['ID']?>">Zapisz opis</button>


                        <div class="well jeden_ttlo margin-top">Ukryj Pokemona:
                            <div class="btn-group">
                                <button type="button" id="zablokuj/0/<?=$value['ID']?>" class="btn action btn-primary
                                <?= !$value['blokada_podgladu'] ? 'primary-active' : '' ?>
                                ">NIE</button>
                                <button type="button" id="zablokuj/1/<?=$value['ID']?>" class="btn action btn-primary
                                <?= $value['blokada_podgladu'] ? 'primary-active' : '' ?>
                                ">TAK</button>
                            </div><br />Jeśli ukryjesz pokemona, inni gracze nie będą mogli zobaczyć jego statystyk.
                        </div>

                        <div class="well jeden_ttlo margin-top">Zabroń ewolucji:
                            <div class="btn-group">
                                <button  id="ewo/0/<?=$value['ID']?>" class="btn action btn-primary
                                <?= !$value['ewolucja'] ? ' primary-active' : '' ?>
                                ">NIE</button>
                                <button id="ewo/1/<?=$value['ID']?>" class="btn action btn-primary
                                <?= $value['ewolucja'] ? ' primary-active' : '' ?>
                                ">TAK</button>
                            </div>
                            <br />Jeśli zabronisz pokemonowi ewolucji, ten nie ewoluuje nawet gdy spełni wszystkie wymagania by tego dokonać.
                        </div>

                        <div class="well jeden_ttlo margin-top">Link do Twojego Pokemona:
                            <textarea rows="1" class="form-control"><?=URL?>pokemon/<?=$value['ID']?></textarea>
                        </div>

                    </div>
                </div>
    <?php endif; ?>
        </div>
    </div>
    <?php
        if (!isset($_GET['ajax'])) echo '</div>';
        if ($value['druzyna']) echo '</div>';
    endforeach;
    if ($this->druzyna) echo '</div>';//tab-content
    ?>
    </div>
    </div>
</div>
<?php if (!isset($_GET['ajax'])): ?>
</div></div></div>
<?php endif; ?>


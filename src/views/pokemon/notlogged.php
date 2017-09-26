<div class="col-xs-12" id="prawo">
    <div class="panel panel-success jeden_ttlo">
        <div class="panel-heading"><span>STAN POKEMONA</span></div>
        <div class="panel-body">
            <div class="row nomargin">
                <div class="col-xs-12">
                    <?php
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
                            </div>
                        </div>
                        <?php
                    endforeach;
                    ?>
                </div>
            </div>
        </div>
    </div></div></div>


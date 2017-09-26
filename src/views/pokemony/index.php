<?php if (!isset($_GET['ajax'])) : ?>
<div class="col-xs-12 col-sm-8 col-md-9" id="prawo">
<div class="panel panel-success jeden_ttlo"><div class="panel-heading text-medium"><span>POKEMONY</span></div><div class="panel-body">
        <ul class="nav nav-tabs"><li
            <?= $this->active == 1 ? 'class="in active"' : '' ?>
            ><a data-toggle="tab" href="#druzyna">Drużyna</a></li><li
            <?= $this->active == 2 ? 'class="in active"' : '' ?>
            ><a data-toggle="tab" href="#rezerwa">Rezerwa</a></li><li
            <?= $this->active == 3 ? 'class="in active"' : '' ?>
            ><a data-toggle="tab" href="#poczekalnia">Poczekalnia</a></li><li
            <?= $this->active == 4 ? 'class="in active"' : '' ?>
            ><a data-toggle="tab" href="#targ">Targ</a></li>
        </ul>
    <div class="tab-content" id="content">
<?php endif;
if (isset($this->error)) {
    echo '<div class="alert alert-danger"><span>'.$this->error.'</span></div>';
}
if (isset($this->komunikat)) {
    echo '<div class="alert alert-success"><span>'.$this->komunikat.'</span></div>';
}
?>


<div id="druzyna" class="tab-pane fade <?= $this->active == 1 ? 'in active"' : '' ?>">
    <div class="panel panel-success jeden_ttlo">
        <div class="panel-heading"><span>DRUŻYNA</span></div>
        <div class="panel-body">
    <div class="alert alert-success text-center text-medium"><span>Pokemony w drużynie:(<?=$this->pokiDruzyna?>/6)</span></div>
        <div class="row nomargin">
    <?php $i = 1;
            foreach($this->pokDruzyna as $pokemon) : ?>
    <div class="col-xs-12
            <?php if ($i % 2) echo  'jeden';
                   else       echo  'dwa';
            ?>">
                <div class="row nomargin">
                    <div class="col-xs-3 col-lg-2">
                        <?= $pokemon['shiny'] ?  '<img src="img/poki/srednie/s'.$pokemon['id_poka'].'.png" class="img-responsive center" />' :
                             '<img src="img/poki/srednie/'.$pokemon['id_poka'].'.png" class="img-responsive center" />'?>
                    </div>
                    <div class="col-xs-7 col-lg-8">
                        <span class="text-medium kursywa pogrubienie" data-toggle="tooltip" data-title="Imię Pokemona"><?=$pokemon['imie']?></span>
                        <?php if (!$pokemon['plec']) echo ' <i class="icon-mars" class="text-extra-big" data-original-title="płeć męska" data-toggle="tooltip"></i>';
                            elseif ($pokemon['plec'] == 1) echo ' <i class="icon-venus" class="text-extra-big" data-original-title="płeć żeńska" data-toggle="tooltip"></i>';
                            else echo '<span title="Pokemon jest bezpłciowy">!</span>';
                        ?><br />
                        <span class="pogrubienie">Poziom: <?=$pokemon['poziom']?></span> (<?=$pokemon['exp']?>
                        <?= ($pokemon['poziom'] != 100) ?  ' / '.$pokemon['exp_p'] : '' ?> PD)<br />
                        <span class="pogrubienie">Życie: <?=$pokemon['akt_zycie']?> / <?=$pokemon['zycie']?></span><br />
                        <span class="pogrubienie">Przywiązanie: <?=$pokemon['przywiazanie']?> %</span>
                    </div>
                    <div class="col-xs-2 margin-top">
                        <div class="row nomargin">
                            <div class="col-xs-2">
                                <button type="button" class="btn btn-info rezerwa" data-title="Wyślij Pokemona do rezerwy" data-toggle="tooltip" pok-id="<?=$i?>">R</button>
                                <a class="btn btn-info sprawdz" data-title="Sprawdź dane Pokemona" data-toggle="tooltip" target="_blank" href="<?=URL?>pokemon/<?=$pokemon['ID']?>">?</a>
                            </div>
                            <div class="col-xs-2">
                            <?php
                                if ($i != 1) {
                                    echo '<button type="button" class="btn btn-info up" pok-id="'.$i.'" data-title="Zmień priorytet tego Pokemona na wyższy" data-toggle="tooltip"><i class="icon-up"></i></button>';
                                } else {
                                    echo '<button type="button" class="btn btn-info disabled" data-title="Nie możesz zmienić priotytetu tego Pokemona na wyższy" data-toggle="tooltip"><i class="icon-up"></i></button>';
                                }
                                if ($i != $this->pokiDruzyna && $this->pokiDruzyna > 1) {
                                    echo '<button type="button" class="btn btn-info down" pok-id="'.$i.'" data-title="Zmień priorytet tego Pokemona na niższy" data-toggle="tooltip" ><i class="icon-down"></i></button>';
                                } else {
                                    echo '<button type="button" class="btn btn-info disabled" data-title="Nie możesz zmienić priotytetu tego Pokemona na niższy" data-toggle="tooltip"><i class="icon-down"></i></button>';
                                }
                            ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <?php $i++;
    endforeach; ?>
    </div>
        </div>
    </div>

</div>

<div id="rezerwa" class="tab-pane fade <?= $this->active == 2 ? 'in active"' : '' ?>">
    <div class="panel panel-success jeden_ttlo">
        <div class="panel-heading"><span>REZERWA</span></div>
        <div class="panel-body">
            <?php if (!$this->pokiRezerwa) : ?>
                    <div class="alert alert-info"><span>Brak Pokemonów w rezerwie</span></div>
            <?php else :
                echo '<div class="alert alert-success text-center text-medium"><span>Pokemony w rezerwie: '.$this->pokiRezerwa.'</span></div><div class="row nomargin"><div data-toggle="buttons">
                    <div id="zaznaczonych_rezerwa" class="d_none alert alert-info"></div>';
                    foreach ($this->pokRezerwa as $pokemon) : ?>
                    <label class="btn btn-primary col-xs-3 col-md-2 text-center rezerwa-btn" name="<?=$pokemon['ID']?>">
                        <input autocomplete="off" name="<?=$pokemon['ID']?>" type="checkbox" class="d_none rezerwa_zaz" />
                        <?= $pokemon['shiny'] ? '<img src="img/poki/srednie/s'.$pokemon['id_poka'].'.png" class="img-responsive center" />' :
                            '<img src="img/poki/srednie/'.$pokemon['id_poka'].'.png" class="img-responsive center" />' ?>
                        <span><?=$pokemon['imie']?>
                        <?php if (!$pokemon['plec']) echo ' <i class="icon-mars" data-original-title="płeć męska" data-toggle="tooltip"></i>';
                            elseif ($pokemon['plec'] == 1) echo ' <i class="icon-venus" data-original-title="płeć żeńska" data-toggle="tooltip"></i>';
                            else echo '<span title="Pokemon jest bezpłciowy">!</span>';
                        ?>
                         (<?=$pokemon['poziom']?>)</span>
                    </label>
             <?php endforeach;
                endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="poczekalnia" class="tab-pane fade <?= $this->active == 3 ? 'in active"' : '' ?>">
    <div class="panel panel-success jeden_ttlo">
        <div class="panel-heading"><span>POCZEKALNIA</span></div>
        <div class="panel-body">
            <?php if (!$this->pokiPoczekalnia) : ?>
                <div class="alert alert-info"><span>Brak Pokemonów w poczekalni</span></div>
            <?php else :
                echo '<div class="alert alert-success text-center text-medium"><span>Pokemony w poczekalni: '.$this->pokiPoczekalnia.'</span></div><div class="row nomargin"><div data-toggle="buttons">
                    <div id="zaznaczonych_poczekalnia" class="d_none alert alert-info"></div>';
                foreach ($this->pokPoczekalnia as $pokemon) : ?>
                    <label class="btn btn-primary col-xs-3 col-md-2 text-center poczekalnia-btn" name="<?=$pokemon['ID']?>">
                        <input autocomplete="off" name="<?=$pokemon['ID']?>" type="checkbox" class="d_none poczekalnia_zaz" />
                        <?= $pokemon['shiny'] ? '<img src="img/poki/srednie/s'.$pokemon['id_poka'].'.png" class="img-responsive center" />' :
                            '<img src="img/poki/srednie/'.$pokemon['id_poka'].'.png" class="img-responsive center" />' ?>
                        <span><?=$pokemon['imie']?>
                            <?php if (!$pokemon['plec']) echo ' <i class="icon-mars" data-original-title="płeć męska" data-toggle="tooltip"></i>';
                            elseif ($pokemon['plec'] == 1) echo ' <i class="icon-venus" data-original-title="płeć żeńska" data-toggle="tooltip"></i>';
                            else echo '<span title="Pokemon jest bezpłciowy">!</span>';
                            ?>
                            (<?=$pokemon['poziom']?>)</span>
                    </label>
                <?php endforeach;
                echo '</div></div>';
                    endif; ?>
        </div>
    </div>
</div>

<div id="targ" class="tab-pane fade <?= $this->active == 4 ? 'in active"' : '' ?>">
    <div class="panel panel-success jeden_ttlo">
        <div class="panel-heading"><span>POKEMONY WYSTAWIONE NA TARG</span></div>
        <div class="panel-body">
            <?php if (!$this->pokiTarg) : ?>
                <div class="alert alert-info"><span>Brak Pokemonów wystawionych na targ</span></div>
            <?php else :
                echo '<div class="alert alert-success text-center text-medium"><span>Pokemony wystawione na targ: '.$this->pokiTarg.'</span></div><div class="row nomargin"><div data-toggle="buttons">';
                foreach ($this->pokTarg as $pokemon) : ?>
                    <label class="btn btn-primary col-xs-3 col-md-2 text-center targ-btn" name="<?=$pokemon['ID']?>">
                        <?= $pokemon['shiny'] ? '<img src="img/poki/srednie/s'.$pokemon['id_poka'].'.png" class="img-responsive center" />' :
                            '<img src="img/poki/srednie/'.$pokemon['id_poka'].'.png" class="img-responsive center" />' ?>
                        <span><?=$pokemon['imie']?>
                            <?php if (!$pokemon['plec']) echo ' <i class="icon-mars" data-original-title="płeć męska" data-toggle="tooltip"></i>';
                            elseif ($pokemon['plec'] == 1) echo ' <i class="icon-venus" data-original-title="płeć żeńska" data-toggle="tooltip"></i>';
                            else echo '<span title="Pokemon jest bezpłciowy">!</span>';
                            ?>
                            (<?=$pokemon['poziom']?>)</span>
                    </label>
                <?php endforeach;
                        endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>


    </div>
</div>

<?php if (!isset($_GET['ajax'])) : ?>
    </div>
    </div></div></div>
    <div class="d_none menu_dr" id="menu_poczekalnia"><ul class="dropdown-menu dropdown-menu_dr"><li class="info kursor"><a>INFO</a></li><li class="divider"></li><li class="dropdown-header">PRZENIEŚ DO:</li><li class="przenies_pocz kursor" id="druzynaa"><a>DRUŻYNY</a></li><li><a class="przenies_pocz kursor" id="rezerwaa">REZERWY</a></li><li class="divider"></li><li class="hodowla kursor"><a>SPRZEDAJ</a></li><li class="divider"></li><li class="targ kursor wystaw"><a>WYSTAW NA TARG</a></li></ul></div>
    <div class="d_none menu_dr" id="menu_rezerwa"><ul class="dropdown-menu dropdown-menu_dr"><li class="info kursor"><a>INFO</a></li><li class="divider"></li><li class="dropdown-header">PRZENIEŚ DO:</li><li class="przenies_rez kursor" id="druzynaa"><a>DRUŻYNY</a></li><li><a class="przenies_rez kursor" id="poczekalniaa">POCZEKALNI</a></li></ul></div>

    <div class="modal fade in" id="pokemon_modal" role="dialog">';
        <div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button>
                    <span name="pokemon_modal" class="modal-title"></span></div><div name="pokemon_modal"  class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-warning" data-dismiss="modal">Zamknij</button></div>
               </div></div></div>
<?php endif; ?>
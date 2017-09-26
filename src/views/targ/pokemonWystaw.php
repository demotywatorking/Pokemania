<?php if(!isset($_GET['ajax'])) : ?>
<div class="col-xs-12 col-sm-8 col-md-9" id="prawo">
    <div class="panel panel-success jeden_ttlo">
        <div class="panel-heading"><span>TARG - WYSTAW POKEMONA</span></div>
        <div class="panel-body">
            <ul class="nav nav-tabs">
                <li <?= $this->active == 1 ? 'class="active"' : ''?>><a data-toggle="tab" href="#wystaw">Wystaw Pokemona</a></li>
                <li <?= $this->active == 2 ? 'class="active"' : ''?>><a data-toggle="tab" href="#wlasne">Wystawione Pokemony</a></li>
            </ul>
            <div class="tab-content">
<?php endif; ?>
                <?= isset($this->blad) ? '<div class="alert alert-danger"><span>'.$this->blad.'</span></div>' : '' ?>
                <?= isset($this->komunikat) ? '<div class="alert alert-success"><span>'.$this->komunikat.'</span></div>' : '' ?>
                <div id="wystaw" class="tab-pane fade<?= $this->active == 1 ? ' in active' : ''?>">
                    <div class="panel panel-success jeden_ttlo">
                        <div class="panel-heading">
                            <span>Wystaw Pokemona</span>
                        </div>
                        <div class="panel-body">
                            <?php if ($this->iloscDoWystawienia) : ?>
                                <div class="row nomargin text-center">
                                    <div class="col-xs-2 col-xs-offset-2 targ-line">Imię (lvl)</div>
                                    <div class="col-xs-3 targ-line">Wartość</div>
                                    <div class="col-xs-1 targ-line">Płeć i info</div>
                                    <div class="col-xs-3 targ-line">Cena i opis</div>
                                    <div class="col-xs-1 targ-line">Wystaw</div>
                                </div>
                                <?php foreach ($this->pokemonDoWystawienia as $pokemon) : ?>
                                <div class="row nomargin text-center">
                                    <div class="col-xs-12 nopadding">
                                        <div class="well nopadding targ_oferta<?=$pokemon['h'] ? '_wlasna" id="'.$pokemon['h'] : ''?>">
                                        <?=$pokemon['h'] ? '<script>$(document).ready(function(){$.scrollTo($(\'#'.$pokemon['h'].'\'), 150, {offset:-100});});</script>' : ''?>
                                            <div class="row nomargin">
                                                <div class="col-xs-2 targ-line">
                                                    <img src="<?=URL?>public/img/poki/srednie/<?=$pokemon['shiny'] ? 's' : '' ?><?=$pokemon['id_poka']?>.png" class="img-responsive center targ_pok" />
                                                </div>
                                                <div class="col-xs-2 targ-line"><?=$pokemon['imie']?> (<?=$pokemon['poziom']?>)</div>
                                                <div class="col-xs-3 targ-line"><?=$pokemon['wartosc']?> &yen;</div>
                                                <div class="col-xs-1 targ-line">
                                                    <div class="btn-group">
                                                        <?php
                                                        if(!$pokemon['plec']) echo '<button data-toggle="tooltip" data-title="Płeć męska" class="btn btn-primary btn-sm"><i class="icon-mars"></i></button>';
                                                        elseif($pokemon['plec'] == 1) echo '<button data-toggle="tooltip" data-title="Płeć żeńska" class="btn btn-primary btn-sm"><i class="icon-venus"></i></button>';
                                                        elseif($pokemon['plec'] == 2) echo '<button data-toggle="tooltip" data-title="Pokemon bezpłciowy" class="btn btn-primary btn-sm">BP</button>';
                                                        ?>
                                                        <button class="btn btn-primary btn-sm data_pok_info" data-pok-id="<?=$pokemon['ID']?>" data-toggle="tooltip"
                                                                data-title="Atak: <?=$pokemon['Atak']?><br />
                                                                Sp.Atak: <?=$pokemon['Sp_Atak']?><br />
                                                                Obrona: <?=$pokemon['Obrona']?><br />
                                                                Sp.Obrona: <?=$pokemon['Sp_Obrona']?><br />
                                                                Szybkość: <?=$pokemon['Szybkosc']?><br />
                                                                Życie: <?=$pokemon['HP']?><br />
                                                                Celność: <?=$pokemon['celnosc']?>">?
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-xs-3 targ-line">
                                                    <input class="cena_pok form-control margin-top" id="cena_pok_<?=$pokemon['ID']?>" placeholder="Cena w Y" type="text" />
                                                    <input class="wiadomosc_pok form-control" id="wiadomosc_pok_<?=$pokemon['ID']?>" type="text" placeholder="Opis, nieobowiązkowy" />
                                                </div>
                                                <div class="col-xs-1 targ-line">
                                                    <button class="wystaw_poka btn btn-primary" id="<?=$pokemon['ID']?>">WYSTAW</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php endforeach;
                                else : ?>
                                <div class="alert alert-warning"><span>Brak Pokemonów, które można wystawić na targ.</span></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div id="wlasne" class="tab-pane fade<?= $this->active == 2 ? ' in active' : ''?>">
                    <div class="panel panel-success jeden_ttlo">
                        <div class="panel-heading">
                            <span>Wystawione Pokemony</span>
                        </div>
                        <div class="panel-body">
                            <?php if ($this->iloscPokiWystawione) : ?>
                            <div class="row nomargin text-center">
                                <div class="col-xs-2 col-xs-offset-2 targ-line">Imię (lvl)</div>
                                <div class="col-xs-3 targ-line">Wartość</div>
                                <div class="col-xs-1 targ-line">Płeć i info</div>
                                <div class="col-xs-3 targ-line">Cena</div>
                                <div class="col-xs-1 targ-line">Wycofaj</div>
                            </div>
                            <?php foreach ($this->pokemonWystawiony as $pokemon) : ?>
                            <div class="row nomargin text-center">
                                <div class="col-xs-12 nopadding">
                                    <div class="well targ_oferta nopadding">
                                        <div class="row nomargin">
                                            <div class="col-xs-2 targ-line">
                                                <img src="<?=URL?>public/img/poki/<?=$pokemon['shiny'] ? 's' : ''?><?=$pokemon['id_poka']?>.png" class="img-responsive center targ_pok" />
                                            </div>
                                            <div class="col-xs-2 targ-line"><?=$pokemon['imie']?> (<?=$pokemon['poziom']?>)</div>
                                            <div class="col-xs-3 targ-line"><?=$pokemon['wartosc']?> &yen;</div>
                                            <div class="col-xs-1 targ-line">
                                                <div class="btn-group">
                                                    <?php
                                                    if(!$pokemon['plec']) echo '<button class="btn btn-primary btn-sm" data-title="Płeć męska" data-toggle="tooltip"><i class="icon-mars"></i></button>';
                                                    elseif($pokemon['plec'] == 1) echo '<button class="btn btn-primary btn-sm" data-title="Płeć żeńska" data-toggle="tooltip"><i class="icon-venus"></i></button>';
                                                    elseif($pokemon['plec'] == 2) echo '<button class="btn btn-primary btn-sm" data-title="Pokemon bezpłciowy" data-toggle="tooltip">B</button>';
                                                    ?>
                                                    <button class="btn btn-primary btn-sm" data-title="<?=$pokemon['wiadomosc'] ? $pokemon['wiadomosc'] : 'Brak opisu'?>" data-toggle="tooltip">D</button>
                                                    <button class="btn btn-primary btn-sm" data-toggle="tooltip"
                                                            data-title="Atak: <?=$pokemon['Atak']?><br />
                                                            Sp.Atak: <?=$pokemon['Sp_Atak']?><br />
                                                            Obrona: <?=$pokemon['Obrona']?><br />
                                                            Sp.Obrona: <?=$pokemon['Sp_Obrona']?><br />
                                                            Szybkość: <?=$pokemon['Szybkosc']?><br />
                                                            Życie: <?=$pokemon['HP']?><br />
                                                            Celność: <?=$pokemon['celnosc']?>">?
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-xs-3 targ-line"><?=$pokemon['cena']?> &yen;</div>
                                            <div class="col-xs-1 targ-line">
                                                <button class="wycofaj_poka btn btn-primary" id="<?=$pokemon['idW']?>">WYCOFAJ</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach;;
                                else : ?>
                                    <div class="alert alert-warning"><span>Brak wystawionych Pokemonów.</span></div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>

            <?php if(!isset($_GET['ajax'])) {
                echo '</div><div class="modal fade in" id="pokemon_modal" role="dialog">';//tab content
                echo '<div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <span class="modal-title"></span></div><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-warning" data-dismiss="modal">Zamknij</button></div>';
                echo '</div></div></div>';
                echo '</div></div></div>';//panel itp.
            } ?>


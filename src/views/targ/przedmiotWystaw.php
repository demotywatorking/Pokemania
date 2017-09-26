<?php if(!isset($_GET['ajax'])) : ?>
    <div class="col-xs-12 col-sm-8 col-md-9" id="prawo">
    <div class="panel panel-success jeden_ttlo">
    <div class="panel-heading"><span>TARG - WYSTAW PRZEDMIOT</span></div>
    <div class="panel-body">
    <ul class="nav nav-tabs">
        <li <?= $this->active == 1 ? 'class="active"' : ''?>><a data-toggle="tab" href="#wystaw">Wystaw ofartę</a></li>
        <li <?= $this->active == 2 ? 'class="active"' : ''?>><a data-toggle="tab" href="#wlasne">Wystawione oferty</a></li>
    </ul>
    <div class="tab-content">
<?php endif; ?>
<?= isset($this->blad) ? '<div class="alert alert-danger"><span>'.$this->blad.'</span></div>' : '' ?>
<?= isset($this->komunikat) ? '<div class="alert alert-success"><span>'.$this->komunikat.'</span></div>' : '' ?>

        <div id="wystaw" class="tab-pane fade<?= $this->active == 1 ? ' in active"' : ''?>">
            <div class="panel panel-success jeden_ttlo">
                <div class="panel-heading">
                    <span>Wystaw ofertę</span>
                </div>
                <div class="panel-body">
                    <div class="row nomargin text-center">
                        <div class="col-xs-12">
                            <div class="col-xs-2 col-xs-offset-2">Przedmiot</div>
                            <div class="col-xs-2">Posiadana ilość</div>
                            <div class="col-xs-2">Ilość</div>
                            <div class="col-xs-2">Cena</div>
                            <div class="col-xs-2">Wystaw</div>
                        </div>
                    </div>
                    <?php if (isset($this->jagoda)) :
                        foreach ($this->jagoda as $jagoda) :?>
                        <div class="row nomargin text-center">
                            <div class="col-xs-12 nopadding">
                                <div class="well targ_oferta nopadding">
                                    <div class="row nomargin">
                                        <div class="col-xs-2 targ-line">
                                            <img src="<?=URL?>public/img/jagody/<?=$jagoda['nazwa']?>.png" class="img-responsive center" />
                                        </div>
                                        <div class="col-xs-2 pogrubienie targ-line">
                                                <?=$jagoda['nazwaW']?>
                                        </div>
                                        <div class="col-xs-2 pogrubienie targ-line">
                                            <?=$jagoda['ilosc']?>
                                        </div>
                                        <div class="col-xs-2 targ-line">
                                            <input type="text" placeholder="Ilość"  class="wystaw_przedmiot_ilosc form-control margin-top-big" id="ilosc_<?=$jagoda['nazwa']?>" />
                                        </div>
                                        <div class="col-xs-2 targ-line">
                                            <input type="text" placeholder="Cena w Y"  class="wystaw_przedmiot_cena form-control margin-top-big" id="cena_<?=$jagoda['nazwa']?>" />
                                        </div>
                                        <div class="col-xs-2 targ-line">
                                            <button class="wystaw_przedmiot btn btn-primary" id="<?=$jagoda['nazwa']?>">WYSTAW</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach;
                    endif;
                    if (isset($this->pokeball)) :
                    foreach ($this->pokeball as $pokeball) :?>
                        <div class="row nomargin text-center">
                            <div class="col-xs-12 nopadding">
                                <div class="well targ_oferta nopadding">
                                    <div class="row nomargin">
                                        <div class="col-xs-2 targ-line">
                                            <img src="<?=URL?>public/img/balle/<?=$pokeball['ball']?>.png" class="img-responsive center" />
                                        </div>
                                        <div class="col-xs-2 pogrubienie targ-line">
                                            <?=$pokeball['nazwa']?>
                                        </div>
                                        <div class="col-xs-2 pogrubienie targ-line">
                                            <?=$pokeball['ilosc']?>
                                        </div>
                                        <div class="col-xs-2 targ-line">
                                            <input type="text" placeholder="Ilość"  class="wystaw_przedmiot_ilosc form-control margin-top-big" id="ilosc_<?=$pokeball['nazwa']?>"/>
                                        </div>
                                        <div class="col-xs-2 targ-line">
                                            <input type="text" placeholder="Cena w Y"  class="wystaw_przedmiot_cena form-control margin-top-big" id="cena_<?=$pokeball['nazwa']?>"/>
                                        </div>
                                        <div class="col-xs-2 targ-line">
                                            <button class="wystaw_przedmiot btn btn-primary" id="<?=$pokeball['nazwa']?>">WYSTAW</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach;
                    endif;
                    if (isset($this->inne)) :
                        foreach ($this->inne as $inne) :?>
                        <div class="row nomargin text-center">
                            <div class="col-xs-12 nopadding">
                                <div class="well targ_oferta nopadding">
                                    <div class="row nomargin">
                                        <div class="col-xs-2 targ-line">
                                            <img src="<?=URL?>public/img/przedmioty/<?=$inne['img']?>.png" class="img-responsive center" />
                                        </div>
                                        <div class="col-xs-2 pogrubienie targ-line">
                                            <?=$inne['nazwa']?>
                                        </div>
                                        <div class="col-xs-2 pogrubienie targ-line">
                                            <?=$inne['ilosc']?>
                                        </div>
                                        <div class="col-xs-2 targ-line">
                                            <input type="text" placeholder="Ilość"  class="wystaw_przedmiot_ilosc form-control margin-top-big" id="ilosc_<?=$inne['nazwa']?>"/>
                                        </div>
                                        <div class="col-xs-2 targ-line">
                                            <input type="text" placeholder="Cena w Y"  class="wystaw_przedmiot_cena form-control margin-top-big" id="cena_<?=$inne['nazwa']?>"/>
                                        </div>
                                        <div class="col-xs-2 targ-line">
                                            <button class="wystaw_przedmiot btn btn-primary" id="<?=$inne['nazwa']?>">WYSTAW</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach;
                    endif;
                    if (isset($this->kamienie)) :
                    foreach ($this->kamienie as $kamien) :?>
                        <div class="row nomargin text-center">
                            <div class="col-xs-12 nopadding">
                                <div class="well targ_oferta nopadding">
                                    <div class="row nomargin">
                                        <div class="col-xs-2 targ-line">
                                            <img src="<?=URL?>public/img/kamienie/<?=$kamien['nazwa']?>.png" class="img-responsive center" />
                                        </div>
                                        <div class="col-xs-2 pogrubienie targ-line">
                                            Kamień <?=$kamien['nazwaW']?>
                                        </div>
                                        <div class="col-xs-2 pogrubienie targ-line">
                                            <?=$kamien['ilosc']?>
                                        </div>
                                        <div class="col-xs-2 targ-line">
                                            <input type="text" placeholder="Ilość"  class="wystaw_przedmiot_przedmiot_ilosc form-control margin-top-big" id="ilosc_<?=$kamien['nazwa']?>"/>
                                        </div>
                                        <div class="col-xs-2 targ-line">
                                            <input type="text" placeholder="Cena w Y"  class="wystaw_przedmiot_przedmiot_cena form-control margin-top-big" id="cena_<?=$kamien['nazwa']?>"/>
                                        </div>
                                        <div class="col-xs-2 targ-line">
                                            <button class="wystaw_przedmiot btn btn-primary" id="<?=$kamien['nazwa']?>">WYSTAW</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach;
                    endif; ?>
                </div>
            </div>
        </div>

        <div id="wlasne" class="tab-pane fade<?= $this->active == 2 ? ' in active"' : ''?>">
            <div class="panel panel-success jeden_ttlo">
                <div class="panel-heading">
                    <span>Własne oferty</span>
                </div>
                <div class="panel-body">
                    <?php if (!$this->iloscWystawionych) : echo '<div class="alert alert-info"><span>Brak wystawionych ofert</span></div>';
                    else : ?>
                        <div class="alert alert-success">
                            <span>Wystawione przedmioty</span>
                        </div>
                        <div class="row nomargin text-center">
                            <div class="col-xs-2 col-xs-offset-2">Przedmiot</div>
                            <div class="col-xs-2">Ilość</div>
                            <div class="col-xs-2">Cena</div>
                        </div>
                    <?php foreach ($this->przedmiot as $przedmiot) : ?>
                        <div class="row nomargin text-center">
                            <div class="col-xs-12 nopadding">
                                <div class="well targ_oferta nopadding">
                                    <div class="row nomargin">
                                        <div class="col-xs-2 targ-line">
                                            <?php
                                            if($przedmiot['rodzaj'] == 'jagoda') echo '<img src="'.URL.'public/img/jagody/'.$przedmiot['co'].'.png" class="imr-responsive targ_pok center" />';
                                            elseif($przedmiot['rodzaj'] == 'pokeball') echo '<img src="'.URL.'public/img/balle/'.(substr($przedmiot['co'], 0, -1)).'.png" class="imr-responsive targ_pok center" />';
                                            elseif($przedmiot['rodzaj'] == 'inne') echo '<img src="'.URL.'public/img/przedmioty/'.(strtolower($przedmiot['co'])).'.png" class="imr-responsive targ_pok center" />';
                                            elseif($przedmiot['rodzaj'] == 'kamienie') echo '<img src="'.URL.'public/img/kamienie/'.$przedmiot['co'].'.png" class="imr-responsive targ_pok center" />';
                                            ?>
                                        </div>
                                        <div class="col-xs-2 targ-line">
                                            <span class="pogrubienie">
                                                <?=$przedmiot['coW']?>
                                            </span>
                                        </div>
                                        <div class="col-xs-2 targ-line">
                                            <?=$przedmiot['ilosc']?>
                                        </div>
                                        <div class="col-xs-2 targ-line">
                                            <?=$przedmiot['cena']?> &yen;
                                        </div>
                                        <div class="col-xs-2 targ-line">
                                            <button class="wycofaj btn btn-primary" id="<?=$przedmiot['ID']?>">WYCOFAJ</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach;
                    endif;?>
                </div>
            </div>
        </div>
 <?php if (!isset($_GET['ajax'])) echo '</div></div></div>';?>

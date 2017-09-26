<?= isset($this->blad) ? '<div class="alert alert-danger"><span>'.$this->blad.'</span></div>' : '' ?>
<?= isset($this->komunikat) ? '<div class="alert alert-success"><span>'.$this->komunikat.'</span></div>' : '' ?>
<div id="oferty" class="panel panel-success jeden_ttlo">
    <div class="panel-heading">
        <span class="panel_uc">OFERTY<?=$this->przedmiot?></span>
    </div>
    <div class="panel-body">
        <div id="przedmiot" class="d_none"><?=$this->przedmiotDiv?></div>
        <?php if ($this->ilosc) : ?>
        <div class="row nomargin text-center">
            <div class="col-xs-12">
                <div class="alert alert-success text-medium">
                    <span>Znaleziono <?=$this->ilosc?> ofert.</span>
                </div>
            </div>
            <div class="col-xs-2 col-xs-offset-2">Ilość</div>
            <div class="col-xs-2">Cena za sztukę</div>
            <div class="col-xs-3">Kup</div>
            <div class="col-xs-2">Info</div>
            <div class="clearfix"></div>
        </div>
            <?php foreach ($this->przedmioty as $przedmiot) : ?>
                <div class="row nomargin text-center">
                    <div class="col-xs-12 nopadding">
                    <?php if (!$przedmiot['wlasna']) : ?>
                        <div class="well targ_oferta nopadding">
                    <?php else : ?>
                        <div class="well targ_oferta_wlasna nopadding">
                    <?php endif;?>

                            <div class="row nomargin">
                                <div class="col-xs-2">
                                    <?php
                                        if($przedmiot['rodzaj'] == 'jagoda') echo '<img src="'.URL.'public/img/jagody/'.$przedmiot['co'].'.png" class="img-responsive center" />';
                                        else if($przedmiot['rodzaj'] == 'pokeball') echo '<img src="'.URL.'public/img/balle/'.(substr($przedmiot['co'], 0, -1)).'.png" class="img-responsive center" />';
                                        else if($przedmiot['rodzaj'] == 'inne') echo '<img src="'.URL.'public/img/przedmioty/'.(strtolower($przedmiot['co'])).'.png" class="img-responsive center" />';
                                        else if($przedmiot['rodzaj'] == 'kamienie') echo '<img src="'.URL.'public/img/kamienie/'.($przedmiot['co']).'.png" class="img-responsive center" />';
                                    ?>
                                </div>
                                <div class="col-xs-2 pogrubienie targ-line">
                                    <?=/*str_replace("_", " ", $przedmiot['co'])*/
                                    $przedmiot['ilosc']?>
                                </div>
                                <div class="col-xs-2 pogrubienie targ-line">
                                    <?=$przedmiot['cena']?>
                                </div>
                                <div class="col-xs-3">
                                    <?php if (!$przedmiot['wlasna']) : ?>
                                        <input type="text" id="ilosc_<?=$przedmiot['ID']?>" class="targ_ilosc form-control margin-top" placeholder="Ilość" />
                                        <button class="kup btn btn-success margin_2" id="<?=$przedmiot['ID']?>">KUP</button>
                                    <?php else : ?>
                                        <span class="targ-line">To Twoja oferta</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
        <?php else : ?>
        <div class="alert alert-success text-medium">
            <span>Brak ofert.</span>
        </div>
        <?php endif;?>
    </div>
</div>

<?php if (!isset($_GET['ajax'])) echo '</div></div></div></div>'; ?>

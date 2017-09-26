<div class="col-xs-12 col-sm-8 col-md-9" id="prawo">
    <div class="panel panel-success jeden_ttlo">
        <div class="panel-heading">
            <span>TARG - KUP PRZEDMIOTY</span>
        </div>
        <div class="panel-body">
            <ul class="nav nav-tabs">
                <li
                    <?= $this->active == 1 ? 'class="active"' : ''?>
                ><a data-toggle="tab" href="#jagody">Jagody</a></li><li
                    <?= $this->active == 2 ? 'class="active"' : ''?>
                ><a data-toggle="tab" href="#pokeballe">Pokeballe</a></li><li
                    <?= $this->active == 3 ? 'class="active"' : ''?>
                ><a data-toggle="tab" href="#inne">Inne</a></li><li
                    <?= $this->active == 4 ? 'class="active"' : ''?>
                ><a data-toggle="tab" href="#kamienie">Kamienie</a></li>
            </ul>
            <div class="tab-content">
                <div id="jagody" class="tab-pane fade<?= $this->active == 1 ? ' in active"' : ''?>">
                    <div class="panel panel-success jeden_ttlo">
                        <div class="panel-heading">
                            <span>Jagody</span>
                        </div>
                        <div class="panel-body">
                            <div class="row nomargin">
                                <div class="col-xs-12">
                                    <?php foreach ($this->jagoda as $jagoda) : ?>
                                        <div id="<?=$jagoda['nazwa']?>" class="kursor przedmiot btn btn-primary text-center col-xs-3 col-md-2 margin-bottom" data-toggle="tooltip" data-title="<?=str_replace('_', ' ', $jagoda['nazwa'])?>">
                                            <img src="<?=URL?>public/img/jagody/<?=$jagoda['nazwa']?>.png"/><br />
                                            <span class="hidden-lg hidden-md"><?=str_replace('_', ' ', $jagoda['nazwa'])?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="pokeballe" class="tab-pane fade<?= $this->active == 2 ? ' in active"' : ''?>">
                    <div class="panel panel-success jeden_ttlo">
                        <div class="panel-heading">
                            <span>Pokeballe</span>
                        </div>
                        <div class="panel-body">
                            <div class="row nomargin">
                                <div class="col-xs-12">
                                    <?php foreach ($this->pokeball as $pokeball) : ?>
                                        <div id="<?=$pokeball['nazwa']?>" class="kursor btn btn-primary przedmiot col-xs-3 col-md-2 margin-bottom przedmiot" data-toggle="tooltip" data-title="<?=substr($pokeball['nazwa'], 0, -1)?>">
                                            <img src="<?=URL?>public/img/balle/<?=substr($pokeball['nazwa'], 0, -1)?>.png" /><br />
                                            <span class="hidden-lg hidden-md"><?=substr($pokeball['nazwa'], 0, -1)?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="inne" class="tab-pane fade<?= $this->active == 3 ? ' in active"' : ''?>">
                    <div class="panel panel-success jeden_ttlo">
                        <div class="panel-heading">
                            <span>Inne</span>
                        </div>
                        <div class="panel-body">
                            <div class="row nomargin">
                                <div class="col-xs-12">
                                    <?php foreach ($this->inne as $przedmiot) : ?>
                                        <div id="<?=$przedmiot['nazwa']?>" class="kursor btn btn-primary przedmiot col-xs-3 col-md-2 margin-bottom przedmiot" data-toggle="tooltip" data-title="<?=$przedmiot['nazwa']?>">
                                            <img src="<?=URL?>public/img/przedmioty/<?=strtolower($przedmiot['nazwa'])?>.png" /><br />
                                            <span class="hidden-lg hidden-md"><?=$przedmiot['nazwa']?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="kamienie" class="tab-pane fade<?= $this->active == 4 ? ' in active"' : ''?>">
                    <div class="panel panel-success jeden_ttlo">
                        <div class="panel-heading">
                            <span>Kamienie</span>
                        </div>
                        <div class="panel-body">
                            <div class="row nomargin">
                                <div class="col-xs-12">
                                    <?php foreach ($this->kamien as $kamien) : ?>
                                        <div id="<?=$kamien['nazwa']?>" class="kursor btn btn-primary przedmiot col-xs-3 col-md-2 margin-bottom przedmiot" data-toggle="tooltip" data-title="Kamień<?=$kamien['nazwa1']?>">
                                            <img src="<?=URL?>public/img/kamienie/<?=$kamien['nazwa']?>.png" /><br />
                                            <span class="hidden-lg hidden-md">Kamień <?=$kamien['nazwa1']?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="zawartosc">
        <?php if (!isset($this->mode)) {
                    echo '</div></div></div></div>';
                }   ?>

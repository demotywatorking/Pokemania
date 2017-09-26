<?php if (isset($this->set)) : ?>
    <div class="col-xs-12 col-sm-4 col-md-3" id="lewo">
    <?= $this->clock ?>
    <?= $this->tooltip ?>
    <?= $this->ajax ?>
    <?= $this->podpowiedz ?>
    <div id="tabelka">
<?php endif; ?>
    <div class="panel panel-success jeden_ttlo">
        <div class="panel-heading text-medium"><span>
        <?= $this->nick ?>
    </span></div>
        <div class="row nomargin margin-top">

            <div class="col-xs-12 nopadding">
                <div class="progress progress-gra prog_EXP progress2" data-title="Punkty Akcji" data-toggle="tooltip"
                     data-placement="top">
                    <div class="progress-bar progress-bar-success progBarEXP line30" role="progressbar"
                         aria-valuenow="40"
                         aria-valuemin="0" aria-valuemax="100" style="width:<?= $this->paa ?>%;">
                        <span><?= $this->pa ?> / <?= $this->mpa ?> PA</span>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 nopadding">
                <div class="progress progress-gra prog_HP progress2" data-title="Doświdaczenie trenera"
                     data-toggle="tooltip" data-placement="top">
                    <div class="btn btn-danger btn-lvl" data-title="Poziom Trenera" data-toggle="tooltip"
                         data-placement="top"><?= $this->lvl ?></div>
                    <div class="progress-bar progress-bar-success progBarHP line30" role="progressbar"
                         aria-valuenow="40"
                         aria-valuemin="0" aria-valuemax="100" style="width:<?= $this->pdd ?>%;">
                        <span><?= $this->tr_exp ?> / <?= $this->exp_lvl_tr ?> PD</span>
                    </div>
                </div>

            </div>

            <div class="col-xs-12 nopadding">
                <div class="progress progress-gra prog_M progress2" data-original-title="Magazyn na Pokemony"
                     data-toggle="tooltip" data-placement="top">
                    <div class="progress-bar progress-bar-success progBarM line30" role="progressbar" aria-valuenow="40"
                         aria-valuemin="0" aria-valuemax="100" style="width:<?= $this->mag ?>%;">
                        <span><?= $this->poki_magazyn ?> / <?= $this->magazyn ?> MAG</span>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 nopadding">
                <div class="well well-stan dwa_ttlo noborder">
                    <div class="row nomargin">
                        <div class="col-xs-12 nopadding text-center" data-toggle="tooltip" data-title="Pieniądze">
                            <?= number_format($this->pieniadze, 0, '', '.') ?> <span
                                    class="glyphicon glyphicon-yen"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 nopadding">
                <div class="row nomargin">
                    <div class="col-xs-12">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary"
                                    id="wiadomosci"><?= $this->wiadomosc ?></button>
                            <button type="button" class="btn btn-primary" id="raporty"><?= $this->raport ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-success jeden_ttlo panel-pokemony-bottom">
        <div class="panel-heading panel-pokemony"><span>
        <?= $this->druzyna ?>
    </span></div>
        <?= $this->poki ?>
    </div></div>
    <form id="gracz" method="post" action="<?=URL?>profil">
        <input class="form-control" type="text" name="gracz" placeholder="Znajdź gracza">
        <input class="form-control" type="submit" value="Wyszukaj">
    </form>
<?= $this->karta ?>
    </div>
<?= isset($this->set) ? '</div>' : '' ?>
<?= isset($this->beta) ? $this->beta : '' ?>
<?php if (!isset($_GET['ajax'])) : ?>
</div>
<div class="d_none menu_dr" id="menu_pokemona">
    <ul class="dropdown-menu" id="menu_pokemon_list">
        <li class="dropdown-header dropdown_imie"></li>
        <li class="divider"></li><li class="info kursor"><a>INFO</a></li>
        <li><a class="kursor sala">SALA TRENINGOWA</a></li>
        <li class="nakarm kursor"><a>NAKARM</a></li>
        <li class="divider"></li>
        <li class="dropdown-header">PRZENIEŚ DO:</li>
        <li><a class="przenies_pocz kursor">REZERWY</a></li>
        <li class="divider"></li>
        <li class="dropdown-header">WYLECZ:</li>
        <li><a class="kursor wylecz_centrum">W CENTRUM</a></li>
        <li><a class="kursor wylecz_jagody">JAGODAMI</a></li>
    </ul>
</div>
<div id="__modal" class="modal fade in" role="dialog">
    <div id="_modal" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <span name="_modal" class="modal-title"></span>
            </div>
            <div class="modal-body" name="_modal"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Zamknij</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

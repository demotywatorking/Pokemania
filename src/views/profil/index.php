<?php if(!isset($_GET['ajax'])) echo '<div class="col-xs-12 col-sm-8 col-md-9" id="prawo">'; ?>
<?= isset($this->blad) ? '<div class="alert alert-danger"><span>' . $this->blad . '</span></div>' : '' ?>
<?php if(!isset($_GET['ajax'])) echo '<div class="panel panel-success jeden_ttlo"><div class="panel-heading"><span>PROFIL GRACZA ' . strtoupper($this->login) . '</span></div><div class="panel-body">' ?>
<?= isset($this->um) ? '<div class="col-xs-12 text-center">' . $this->um . '</div>' : '' ?>
<div class="col-xs-12 col-sm-6"><!--avatar-->
    <?= $this->avatar ?>
</div>
<div class="col-xs-12 col-sm-6 text-center">
    <div class="row nomargin"><!--info-->
        <?= isset($this->walka) ? '<div class="col-xs-12"><div class="well well-primary jeden_ttlo">' . $this->walka . '</div></div>' : '' ?>
        <?php if ($this->znajomy) : ?>
            <div class="col-xs-12">
                <div class="well well-primary jeden_ttlo"><!--znajomi-->
                    <?php if ($this->znajomy == 1) : ?>
                        <span class="zielony">Jesteście znajomymi</span><br/>
                        <?= $this->online ?>
                    <?php elseif ($this->znajomy == 2) : ?>
                        <span class="zielony">Wysłano zaproszenie do znajomych.</span>
                    <?php elseif ($this->znajomy == 3) : ?>
                        <button class="btn btn-primary btn-lg dodaj" id="<?= $this->id ?>">DODAJ JAKO ZNAJOMEGO</button>
                        <div id="zaproszenie"></div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="col-xs-12">
            <div class="well well-primary jeden_ttlo">Poziom trenera: <?= $this->poziom ?></div>
        </div>
        <div class="col-xs-12">
            <div class="well well-primary jeden_ttlo">Czas online:
                <?= $this->czasOnline ?>
            </div>
        </div>
    </div>
</div>
<div class="row nomargin">
    <div class="col-xs-12">
        <ul class="nav nav-tabs margin-top">
            <li class="active"><a data-toggle="tab" href="#opis">Opis</a></li>
            <li><a data-toggle="tab" href="#druzyna">Drużyna</a></li>
            <li><a data-toggle="tab" href="#odznaki">Odznaki</a></li>
            <li><a data-toggle="tab" href="#osiagniecia">Osiągnięcia</a></li>
        </ul>
    </div>
</div>
<div class="tab-content">
    <div id="opis" class="tab-pane fade in active"><!--OPIS-->
        <div class="panel panel-success jeden_ttlo">
            <div class="panel-heading"><span>OPIS</span></div>
            <div class="panel-body">
                <div class="well well-primary jeden_ttlo">
                    <?= $this->opis ? $this->opis : 'Ten gracz nie ustawił swojego opisu.' ?>
                </div>
            </div>
        </div>
    </div>

    <div id="druzyna" class="tab-pane fade">
        <div class="row nomargin"><!--DRUŻYNA-->
            <div class="panel panel-success jeden_ttlo">
                <div class="panel-heading"><span>DRUŻYNA</span></div>
                <div class="panel-body text-center">
                    <?php if (!$this->podglad) : ?>
                        <div class="alert alert-warning"><span>Ten gracz postanowił ukryć swoje Pokemony.</span></div>
                    <?php else:
                        foreach ($this->pokemonDruzyna as $value) {
                            echo '<button class="btn btn-primary pok_modal" data-id="' . $value['ID'] . '"><img src="' . URL . 'public/img/poki/srednie/';
                            if ($value['shiny'] == 1) echo 's';
                            echo $value['id_poka'] . '.png" /></button>';
                        }
                        ?>
                        <div class="modal fade in" id="pokemon_modal" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <span class="modal-title" name="profil_pok"></span></div>
                                    <div class="modal-body" name="profil_pok"></div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-warning" data-dismiss="modal">Zamknij
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div id="odznaki" class="tab-pane fade"><!--ODZNAKI-->
        <div class="panel panel-success jeden_ttlo">
            <div class="panel-heading"><span>ODZNAKI</span></div>
            <div class="panel-body">
                <div class="well well-primary jeden_ttlo">
                    <?php
                    for ($i = 1; $i < 9; $i++) {
                        if ($this->odznaki['Kanto' . $i] > '0000-00-00') echo '<img src="' . URL . 'public/img/odznaki/Kanto' . $i . '.png" />';
                        else echo '<img src="' . URL . 'public/img/odznaki/Kanto' . $i . '_c.png" />';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div id="osiagniecia" class="tab-pane fade"><!--OSIĄGNIĘCIA-->
        <div class="panel panel-success jeden_ttlo">
            <div class="panel-heading"><span>OSIĄGNIĘCIA</span></div>
            <div class="panel-body">
                Tu będą osiągnięcia.
            </div>
        </div>
    </div>

</div>

<?php if (isset($this->umiejetnosci)) : ?>
    <div class="panel panel-success jeden_ttlo">
        <div class="panel-heading"><span>Wykorzystaj swoje punkty umiejętności</span></div>
        <div class="panel-body">
            <div id="osiagniecia_panel">
                <div class="alert alert-success">
                    <span>Twoje punkty umiejętności do wykorzystania: <?= $this->punktyUmiejetnosci ?></span></div>
                <div class="row nomargin">
                    <?php
                    foreach ($this->umiejetnosc as $value) {
                        echo '<div class="col-xs-6 col-md-4"><div class="panel panel-success jeden_ttlo">
                    <div class="panel-heading text-center"><span>' . $value['nazwa'] . '</span></div><div class="panel-body">';
                        echo '<div class="well well-success jeden_ttlo text-center"><span class="pogrubienie">Aktualny poziom: ' . $value['poziom'] . '</span><br/>' . $value['opis'] . '</div>';
                        if ($value['max']) {
                            echo '<div class="well well-success jeden_ttlo text-center"><span class="zielony pogrubienie">Osiągnięto maksymalny poziom.</span></div>';
                        } else {
                            echo '<div class="well well-success jeden text-center"><span class="pogrubienie">Poziom ' . ($value['poziom'] + 1) . ':</span><br />' . $value['opis2'];
                            echo $value['wymagania'];
                            echo $value['kup'];
                        }
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>

    
<?= isset($_GET['ajax']) ? '' : '<div class="col-xs-12 col-sm-8 col-md-9" id="prawo">' ?>
    <div class="panel panel-success jeden_ttlo">
    <div class="panel-heading"><span>POLOWANIE</span></div>
    <div class="panel-body" id="panel_polowanie">
        <?php if (isset($this->wydarzenieSp)) echo $this->wydarzenieSp; ?>
        <div id="dzicz_ajax" class="d_none"><?= $this->dzicz ?></div>
        <?= isset($this->pierwszy) ? $this->pierwszy : '' ?>
        <div class="d_none" id="id_poka"><?= $this->id ?></div>
        <div class="alert alert-info text-medium text-center"><span>Na Twojej drodze staje dziki Pokemon</span></div>
        <div class="panel panel-primary
<?= $this->shiny ? 'shiny_tlo' : 'dwa_ttlo' ?>
 noborder">
            <div class="row">
                <div class="col-xs-4 col-sm-3 text-center">
                    <?= $this->shiny ? '<img src="' . URL . 'public/img/poki/s' . $this->id . '.png" class="polowanie_img margin-top" data-original-title="' . $this->nazwa . '" data-toggle="tooltip" />'
                        : '<img src="' . URL . 'public/img/poki/' . $this->id . '.png" class="polowanie_img margin-top" data-original-title="' . $this->nazwa . '" data-toggle="tooltip" />' ?>
                </div>
                <div class="col-xs-9 col-sm-8 text-center text-medium">
                    <?= $this->shiny ? '<span class="span_nazwa">Shiny ' . $this->nazwa . '</span>'
                        : '<span class="span_nazwa">' . $this->nazwa . '</span>' ?>
                    <?= $this->pokedex ? ' na poziomie: <span class="span_nazwa">' . $this->lvl . '</span><br />'
                        : '' ?>
                    <?= $this->pokedex > 2 ? 'Jakość: <span class="pogrubienie">' . $this->jakosc . '%</span><br />' : '' ?>
                    <div class="polowanie_trudnosc <?= $this->trudnosc ?>
" data-original-title="trudność łapania: <?= $this->trudnoscOpis ?>"
                         data-toggle="tooltip"><?= $this->trudnoscLiczba ?></div>
                    <?= $this->zlapany ? '<img src="' . URL . 'public/img/zl/1.png" data-original-title="Złapany" data-toggle="tooltip" />'
                        : '<img src="' . URL . 'public/img/zl/0.png" data-original-title="Nie złapany" data-toggle="tooltip" />' ?>
                    <img src="<?= URL ?>public/img/typy/<?= $this->typ1 ?>.gif"
                         data-original-title="<?= $this->typ1o ?>" data-toggle="tooltip"/>
                    <?php if ($this->typ2 > 0) echo ' <img src="' . URL . 'public/img/typy/' . $this->typ2 . '.gif" data-original-title="' . $this->typ2o . '" data-toggle="tooltip" />';
                    if ($this->plec == 0) echo ' <i class="icon-mars" class="text-extra-big" data-original-title="płeć męska" data-toggle="tooltip"></i>';
                    else if ($this->plec == 1) echo ' <i class="icon-venus" class="text-extra-big" data-original-title="płeć żeńska" data-toggle="tooltip"></i>';
                    else echo '<span title="Pokemon jest bezpłciowy">!</span>';
                    ?>

                    <img src="<?= URL ?>public/img/przedmioty/pokedex.png" class="polowanie_pokedex kursor"
                         id="polowanie_pokedex" data-original-title="Przeczytaj więcej o pokemonie"
                         data-toggle="tooltip"/>

                    <div class="col-xs-12 text-center margin-top">
                        <?php if ($this->pokedex > 1) : ?>
                            <div class="col-xs-6 text-right margin-top">
                                <span data-original-title="Atak"
                                      data-toggle="tooltip">Atak: <?= $this->atak ?></span><br/>
                                <span data-original-title="Obrona"
                                      data-toggle="tooltip">Obrona: <?= $this->obrona ?></span><br/>
                                <span data-original-title="Szybkość"
                                      data-toggle="tooltip">Szybkość: <?= $this->szybkosc ?></span>
                            </div>
                            <div class="col-xs-6 text-left margin-top">
                                <span data-original-title="Specjalny Atak"
                                      data-toggle="tooltip">Sp.Atak: <?= $this->sp_atak ?></span><br/>
                                <span data-original-title="Specjalna Obrona"
                                      data-toggle="tooltip">Sp.Obrona: <?= $this->sp_obrona ?></span><br/>
                                <span data-original-title="Życie" data-toggle="tooltip">Życie: <?= $this->HP ?></span>
                            </div>
                        <?php else : ?>
                            Aby dowiedzieć się więcej o Pokemonie, kup lepszy Pokedex.
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="well well-stan alert-success noborder padding text-center margin-bottom"><span>WYBIERZ POKEMONA DO WALKI</span>
        </div>
        <div class="row row-centered">
            <div class="btn-group">
                <?php
                foreach ($this->pokemonGracza as $value) {
                    if ($value['akt_zycie'] > 0 && $value['glod'] <= 90) {
                        echo '<button class="btn btn-primary padding_button_walka polowanie_wlasny_pok" id="' . $value['id'] . '">';
                        if ($value['shiny'] == 1) echo '<img src="' . URL . 'public/img/poki/s' . $value['id_p'] . '.png" class="img-responsive width100 walka_pok_img" />';
                        else echo '<img src="' . URL . 'public/img/poki/' . $value['id_p'] . '.png" class="img-responsive width100 walka_pok_img" />';
                    } else {
                        echo '<button class="btn btn-primary disabled padding_button_walka" id="' . $value['id'] . '" data-original-title="Ten pokemon nie może walczyć!';
                        if ($value['akt_zycie'] == 0) echo '<br />Pokemon ma 0 HP!';
                        if ($value['glod'] > 90) echo '<br />Pokemon jest zbyt głodny by walczyć!';
                        echo '" data-toggle="tooltip">';
                        echo '<img src="' . URL . 'public/img/poki/bw/' . $value['id_p'] . '.png" class="img-responsive width100 walka_pok_img" />';
                    }
                    echo '<br />' . $value['imie'];
                    $zycie = floor($value['akt_zycie'] / $value['zycie'] * 100);
                    echo '<div class="progress progress-gra prog_HP">';
                    echo '<div class="progress-bar progress-bar-success progBarHP" role="progressbar" aria-valuenow="40"';
                    echo 'aria-valuemin="0" aria-valuemax="100" style="width:' . $zycie . '%;">';
                    echo '<span>HP: ' . $zycie . '%</span></div></div>';

                    echo '<div class="progress progress-gra ';
                    if ($value['glod'] <= 60) echo 'prog_EXP';
                    else echo 'prog_HP';
                    echo '"><div class="progress-bar progress-bar-success ';
                    if ($value['glod'] <= 60) echo 'progBarEXP';
                    else echo 'progBarHP';
                    echo '" role="progressbar" aria-valuenow="40"';
                    echo 'aria-valuemin="0" aria-valuemax="100" style="width:' . $value['glod'] . '%;">';
                    echo '<span>GŁÓD: ' . $value['glod'] . '%</span></div></div>';
                    echo '</button>';
                }
                ?>
            </div>
        </div>
        <div class="col-xs-12 text-center">
            <button id="<?= $this->dzicz ?>" type="button" class="btn btn-primary btn-lg button_kontynuuj margin-top">
                POMIŃ I KONTYNUUJ
            </button>
        </div>
    </div>
<?= isset($_GET['ajax']) ? '' : '</div>';
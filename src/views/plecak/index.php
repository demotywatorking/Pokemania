<?php
if (!isset($_GET['ajax']) || (isset($_GET['ajax']) && $_GET['ajax'] == 2)) {
    echo '<div class="col-xs-12 col-sm-8 col-md-9" id="prawo">';
    echo '<div class="panel panel-success jeden_ttlo"><div class="panel-heading"><span>PLECAK</span></div><div class="panel-body">';
    echo '<ul class="nav nav-tabs"><li ';
    if ($this->active == 1) echo 'class="active"';
    echo '><a data-toggle="tab" href="#przedmioty">Użytkowe</a></li><li ';
    if ($this->active == 2) echo 'class="active"';
    echo '><a data-toggle="tab" href="#pokeballe">Pokeballe</a></li><li ';
    if ($this->active == 3) echo 'class="active"';
    echo '><a data-toggle="tab" href="#jagody">Jagody</a></li><li ';
    if ($this->active == 4) echo 'class="active"';
    echo '><a data-toggle="tab" href="#ewolucyjne">Ewolucyjne</a></li><li ';
    if ($this->active == 5) echo 'class="active"';
    echo '><a data-toggle="tab" href="#tm">TM</a></li><li ';
    if ($this->active == 6) echo 'class="active"';
    echo '><a data-toggle="tab" href="#inne">Inne</a></li><li ';
    if ($this->active == 7) echo 'class="active"';
    echo '><a data-toggle="tab" href="#karty">Karty</a></li>';
    echo '</ul>';
    echo '<div class="tab-content">';
}
?>
<?= isset($this->komunikat) ? '<div class="alert alert-success text-center"><span>' . $this->komunikat . '</span></div>' : '' ?>
<?= isset($this->blad) ? '<div class="alert alert-danger text-center"><span>' . $this->blad . '</span></div>' : '' ?>
    <div id="przedmioty" class="tab-pane fade <?= $this->active == 1 ? 'in active' : '' ?>">
        <div class="row">
            <?php if ($this->lemoniada) : ?>
                <div class="col-xs-4 col-sm-3 col-md-2 text-center padding" data-toggle="modal"
                     data-target="#lemoniada_opis">
                    <div class="jeden kursor" id="lemoniada"
                         title="Przywraca 100% punktów PA (<?= $this->lemoniadaPA ?> PA)">
                        <img src="<?= URL ?>public/img/przedmioty/lemoniada.png"/>
                        <div>Lemoniada<br/>x <?= $this->lemoniada ?></div>
                    </div>
                </div>

                <div class="modal fade" id="lemoniada_opis" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <span class="text-medium">Lemoniada</span>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <img src="<?= URL ?>public/img/przedmioty/lemoniada.png"
                                             class="img-responsive"/></div>
                                    <div class="col-xs-12 col-md-9">
                                        Przywraca 100% punktów PA (<?= $this->lemoniadaPA ?> PA)
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body text-center">
                                <button class="btn btn-info nomargin kursor wypij" id="lemoniada">Wypij</button>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($this->soda) : ?>
                <div class="col-xs-4 col-sm-3 col-md-2 text-center padding" data-toggle="modal"
                     data-target="#soda_opis">
                    <div class="jeden kursor" id="soda"
                         title="Przywraca 50% punktów PA (<?= $this->lemoniadaPA / 2 ?> PA)">
                        <img src="<?= URL ?>public/img/przedmioty/soda.png"/>
                        <div>Soda<br/>x <?= $this->soda ?></div>
                    </div>
                </div>

                <div class="modal fade" id="soda_opis" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <span class="text-medium">Soda</span>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <img src="<?= URL ?>public/img/przedmioty/soda.png" class="img-responsive"/>
                                    </div>
                                    <div class="col-xs-12 col-md-9">
                                        Przywraca 50% punktów PA (<?= $this->lemoniadaPA / 2 ?> PA)
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body text-center">
                                <input type="text" size="15" id="soda_ilosc" class="ilosc_wypij"
                                       placeholder="Ilość, domyślnie 1"></input>
                                <button class="btn btn-info nomargin kursor wypij" id="soda">Wypij</button>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($this->woda) : ?>
                <div class="col-xs-4 col-sm-3 col-md-2 text-center padding" data-toggle="modal"
                     data-target="#woda_opis">
                    <div class="jeden kursor" id="woda"
                         title="Przywraca 25% punktów PA (<?= $this->lemoniadaPA / 4 ?> PA)">
                        <img src="<?= URL ?>public/img/przedmioty/woda.png"/>
                        <div>Woda<br/>x <?= $this->woda ?></div>
                    </div>
                </div>

                <div class="modal fade" id="woda_opis" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <span class="text-medium">Woda</span>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <img src="<?= URL ?>public/img/przedmioty/woda.png" class="img-responsive"/>
                                    </div>
                                    <div class="col-xs-12 col-md-9">
                                        Przywraca 25% punktów PA (<?= $this->lemoniadaPA / 4 ?> PA)
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body text-center">
                                <input type="text" size="15" id="woda_ilosc" class="ilosc_wypij"
                                       placeholder="Ilość, domyślnie 1"></input>
                                <button class="btn btn-info nomargin kursor wypij" id="woda">Wypij</button>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($this->karma) : ?>
                <div class="col-xs-4 col-sm-3 col-md-2 text-center padding" data-toggle="modal"
                     data-target="#karma_opis">
                    <div class="jeden kursor" id="karma"
                         title="Pokemony w drużynie muszą jeść karmę, aby miały siłę do walki.">
                        <img src="<?= URL ?>public/img/przedmioty/karma.png"/>
                        <div>Pudełko karmy<br/>x <?= $this->karma ?></div>
                    </div>
                </div>

                <div class="modal fade" id="karma_opis" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <span class="text-medium">Karma</span>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <img src="<?= URL ?>public/img/przedmioty/karma.png" class="img-responsive"/>
                                    </div>
                                    <div class="col-xs-12 col-md-9">
                                        Pokemony w drużynie muszą jeść karmę, aby miały siłę do walki.
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body text-center">
                                <fieldset>
                                    <label for="karma_pok">Wybierz Pokemona:</label>
                                    <select id="karma_pok" name="karma_pok">
                                        <?php
                                        foreach ($this->pokemonSelect as $value) {
                                            echo '<option data-class="id_';
                                            if ($value['shiny'])
                                                echo 's';
                                            echo $value['id_p'] . '" value="' . $value['id'] . '">' . $value['imie'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </fieldset>
                                <input type="text" size="15" id="karma_ilosc" class="ilosc_wypij"
                                       placeholder="Ilość, domyślnie 1"></input>
                                <button class="btn btn-info nomargin kursor wypij" id="karma">Zjedz</button>
                                <button class="btn btn-info nomargin kursor wypij_all" id="karma">Nakarm wszystkie
                                </button>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($this->batony) : ?>
                <div class="col-xs-4 col-sm-3 col-md-2 text-center padding" data-toggle="modal"
                     data-target="#baton_opis">
                    <div class="jeden kursor" id="baton" title="Zwiększa nieznacznie przywiązanie pokemona.">
                        <img src="<?= URL ?>public/img/przedmioty/baton.png"/>
                        <div>Baton<br/>x <?= $this->batony ?></div>
                    </div>
                </div>

                <div class="modal fade" id="baton_opis" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <span class="text-medium">Baton</span>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <img src="<?= URL ?>public/img/przedmioty/baton.png" class="img-responsive"/>
                                    </div>
                                    <div class="col-xs-12 col-md-9">
                                        Zwiększa nieznacznie przywiązanie pokemona.
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body text-center">
                                <fieldset>
                                    <label for="baton_pok">Wybierz Pokemona:</label>
                                    <select id="baton_pok" name="baton_pok">
                                        <?php
                                        foreach ($this->pokemonSelect as $value) {
                                            echo '<option data-class="id_';
                                            if ($value['shiny'])
                                                echo 's';
                                            echo $value['id_p'] . '" value="' . $value['id'] . '">' . $value['imie'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </fieldset>
                                <input type="text" size="15" id="baton_ilosc" class="ilosc_wypij"
                                       placeholder="Ilość, domyślnie 1"></input>
                                <button class="btn btn-info nomargin kursor wypij" id="baton">Zjedz</button>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($this->ciastka) : ?>
                <div class="col-xs-4 col-sm-3 col-md-2 text-center padding" data-toggle="modal"
                     data-target="#ciastko_opis">
                    <div class="jeden kursor" id="ciastko" title="Zwiększa przywiązanie pokemona.">
                        <img src="<?= URL ?>public/img/przedmioty/ciastko.png"/>
                        <div>Ciastko<br/>x <?= $this->ciastka ?></div>
                    </div>
                </div>

                <div class="modal fade" id="ciastko_opis" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <span class="text-medium">Ciastko</span>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <img src="<?= URL ?>public/img/przedmioty/ciastko.png" class="img-responsive"/>
                                    </div>
                                    <div class="col-xs-12 col-md-9">
                                        Zwiększa przywiązanie pokemona.
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body text-center">
                                <fieldset>
                                    <label for="ciastko_pok">Wybierz Pokemona:</label>
                                    <select id="ciastko_pok" name="ciastko_pok">
                                        <?php
                                        foreach ($this->pokemonSelect as $value) {
                                            echo '<option data-class="id_';
                                            if ($value['shiny'])
                                                echo 's';
                                            echo $value['id_p'] . '" value="' . $value['id'] . '">' . $value['imie'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </fieldset>
                                <input type="text" size="15" id="ciastko_ilosc" class="ilosc_wypij"
                                       placeholder="Ilość, domyślnie 1"></input>
                                <button class="btn btn-info nomargin kursor wypij" id="ciastko">Zjedz</button>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($this->candy) : ?>
                <div class="col-xs-4 col-sm-3 col-md-2 text-center padding" data-toggle="modal"
                     data-target="#candy_opis">
                    <div class="jeden kursor" id="candy" title="Zwiększa proziom Pokemona.">
                        <img src="<?= URL ?>public/img/przedmioty/candy.png"/>
                        <div>Rare Candy<br/>x <?= $this->candy ?></div>
                    </div>
                </div>

                <div class="modal fade" id="candy_opis" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <span class="text-medium">Ciastko</span>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <img src="<?= URL ?>public/img/przedmioty/candy.png" class="img-responsive"/>
                                    </div>
                                    <div class="col-xs-12 col-md-9">
                                        Zwiększa proziom Pokemona.
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body text-center">
                                <fieldset>
                                    <label for="candy_pok">Wybierz Pokemona:</label>
                                    <select id="candy_pok" name="candy_pok">
                                        <?php
                                        foreach ($this->pokemonSelect as $value) {
                                            echo '<option data-class="id_';
                                            if ($value['shiny'])
                                                echo 's';
                                            echo $value['id_p'] . '" value="' . $value['id'] . '">' . $value['imie'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </fieldset>
                                <button class="btn btn-info nomargin kursor wypij" id="candy">Użyj</button>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div id="pokeballe" class="tab-pane fade <?= $this->active == 2 ? 'in active' : '' ?>">
        <div class="row">
            <?php
            foreach ($this->pokeball as $value) {
                $nazwa = ucfirst($value['nazwa']);
                if ($value['ilosc']) {
                    echo '<div class="col-xs-4 col-sm-3 col-md-2 text-center padding" data-toggle="modal" data-target="#' . $value['nazwa'] . '_opis">';
                    echo '<div class="jeden kursor" id="' . $nazwa . '" title="' . $value['opis'] . '">';
                    echo '<img src="' . URL . 'public/img/balle/' . $nazwa . '.png"/>';
                    echo '<div>' . $nazwa . '<br/ >x ' . $value['ilosc'] . '</div>';
                    echo '</div>';
                    echo '</div>';//col

                    echo '<div class="modal fade" id="' . $value['nazwa'] . '_opis" role="dialog">';
                    echo '<div class="modal-dialog">';
                    echo '<div class="modal-content">';
                    echo '<div class="modal-header">';
                    echo '<button type="button" class="close" data-dismiss="modal">&times;</button>';
                    echo '<span class="text-medium">' . $nazwa . '</span>';
                    echo '</div>';
                    echo '<div class="modal-body">';
                    echo '<div class="row"><div class="col-xs-3">';
                    echo '<img src="' . URL . 'public/img/balle/' . $nazwa . '.png" class="img-responsive"/></div>';
                    echo '<div class="col-xs-12 col-md-9 text-center">';
                    echo $value['opis'];
                    echo '<br />Posiadasz ' . $value['ilosc'] . ' sztuk.';
                    echo '</div></div></div>';
                    echo '<div class="modal-body text-center">';
                    echo '</div>';

                    echo '<div class="modal-footer">';
                    echo '<button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>';
                    echo '</div>';

                    echo '</div></div></div>';
                }
            }
            ?>
        </div>
    </div>
    <div id="jagody" class="tab-pane fade <?= $this->active == 3 ? 'in active' : '' ?>">
        <div class="row">
            <?php
            foreach ($this->jagoda as $value) {
                if ($value['ilosc']) {
                    echo '<div class="col-xs-4 col-sm-3 col-md-2 text-center padding" data-toggle="modal" data-target="#' . $value['nazwa'] . '_opis">';
                    echo '<div class="jeden kursor"title="' . $value['opis'] . '">';
                    echo '<img src="' . URL . 'public/img/jagody/' . $value['nazwa'] . '.png"/>';
                    echo '<div>' . $value['nazwa2'] . '<br/ >x ' . $value['ilosc'] . '</div>';
                    echo '</div>';
                    echo '</div>';//col

                    echo '<div class="modal fade" id="' . $value['nazwa'] . '_opis" role="dialog">';
                    echo '<div class="modal-dialog">';
                    echo '<div class="modal-content">';
                    echo '<div class="modal-header">';
                    echo '<button type="button" class="close" data-dismiss="modal">&times;</button>';
                    echo '<span class="text-medium">' . $value['nazwa2'] . '</span>';
                    echo '</div>';
                    echo '<div class="modal-body">';
                    echo '<div class="row"><div class="col-xs-3">';
                    echo '<img src="' . URL . 'public/img/jagody/' . $value['nazwa'] . '.png" class="img-responsive"/></div>';
                    echo '<div class="col-xs-12 col-md-9">';
                    echo $value['opis'];
                    echo '</div></div></div>';
                    echo '<div class="modal-body text-center">';
                    if ($value['rodzaj'] == 1 || $value['rodzaj'] == 3) {
                        echo '<fieldset>';
                        echo '<label for="' . $value['nazwa'] . '_pok">Wybierz Pokemona:</label>';
                        echo '<select id="' . $value['nazwa'] . '_pok" name="' . $value['nazwa'] . '_pok">';
                        foreach ($this->pokemonSelect as $value1) {
                            echo '<option data-class="id_';
                            if ($value1['shiny'])
                                echo 's';
                            echo $value1['id_p'] . '" value="' . $value1['id'] . '">' . $value1['imie'] . '</option>';
                        }
                        echo '</select>';
                        echo '</fieldset>';
                    }
                    echo '<input type="text" size="15" id="' . $value['nazwa'] . '_ilosc" class="ilosc_jagoda" placeholder="Ilość, domyślnie 1"></input>';
                    echo '<button class="btn btn-info nomargin jagoda" id="' . $value['nazwa'] . '">Zjedz</button>';
                    echo '<button class="btn btn-info nomargin jagoda_max" id="' . $value['nazwa'] . '">Zjedz max</button>';
                    if ($value['rodzaj'] == 1) echo '<button class="btn btn-info nomargin jagoda_all" id="' . $value['nazwa'] . '">Ulecz wszystkie</button>';
                    echo '</div>';

                    echo '<div class="modal-footer">';
                    echo '<button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>';
                    echo '</div>';

                    echo '</div></div></div>';
                }
            }
            ?>
        </div>
    </div>

    <div id="ewolucyjne" class="tab-pane fade <?= $this->active == 4 ? 'in active' : '' ?>">
        <div class="row">
            <?php
            foreach ($this->kamien as $value) {
                if ($value['ilosc']) {
                    echo '<div class="col-xs-4 col-sm-3 col-md-2 text-center padding" data-toggle="modal" data-target="#' . $value['nazwa'] . '_opis">';
                    echo '<div class="jeden kursor" id="' . $value['nazwa'] . '" title="' . $value['opis'] . '">';
                    echo '<img src="' . URL . 'public/img/kamienie/' . $value['nazwa'] . '.png"/>';
                    echo '<div>Kamień ' . $value['nazwa2'] . '<br />x ' . $value['ilosc'] . '</div>';
                    echo '</div>';
                    echo '</div>';//col

                    echo '<div class="modal fade" id="' . $value['nazwa'] . '_opis" role="dialog">';
                    echo '<div class="modal-dialog">';
                    echo '<div class="modal-content">';
                    echo '<div class="modal-header">';
                    echo '<button type="button" class="close" data-dismiss="modal">&times;</button>';
                    echo '<span class="text-medium">Kamień ' . $value['nazwa2'] . '</span>';
                    echo '</div>';
                    echo '<div class="modal-body">';
                    echo '<div class="row"><div class="col-xs-3">';
                    echo '<img src="' . URL . 'public/img/kamienie/' . $value['nazwa'] . '.png" class="img-responsive"/></div>';
                    echo '<div class="col-xs-12 col-md-9 text-center">';
                    echo $value['opis'];
                    echo '<br />Posiadasz ' . $value['ilosc'] . ' sztuk.';
                    echo '</div></div></div>';
                    echo '<div class="modal-body text-center">';
                    echo '<fieldset>';
                    echo '<label for="' . $value['nazwa'] . '_pok">Wybierz Pokemona:</label>';
                    echo '<select id="' . $value['nazwa'] . '_pok" name="' . $value['nazwa'] . '_pok">';
                    foreach ($this->pokemonSelect as $value1) {
                        echo '<option data-class="id_';
                        if ($value1['shiny'])
                            echo 's';
                        echo $value1['id_p'] . '" value="' . $value1['id'] . '">' . $value1['imie'] . '</option>';
                    }
                    echo '</select>';
                    echo '</fieldset>';
                    echo '<div class="kursor kamien walka_button" id="' . $value['nazwa'] . '">Daj kamień</div>';
                    echo '</div>';

                    echo '<div class="modal-footer">';
                    echo '<button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>';
                    echo '</div>';

                    echo '</div></div></div>';
                }
            }
            ?>
        </div>
    </div>
    <div id="tm" class="tab-pane fade <?= $this->active == 5 ? 'in active' : '' ?>">
        <div class="row">
            TMy jeszcze nie działają.
        </div>
    </div>

    <div id="inne" class="tab-pane fade <?= $this->active == 6 ? 'in active' : '' ?>">
        <div class="row">
            <?php if ($this->latarka) : ?>
                <div class="col-xs-4 col-sm-3 col-md-2 text-center padding" data-toggle="modal"
                     data-target="#latarka_opis">
                    <div class="jeden kursor" title="Niezbędna w wyprawach do groty.">
                        <img src="<?= URL ?>public/img/przedmioty/latarka.png"/>
                        <div><span style="color:transparent">a</span></div>
                        <div>Latarka</div>
                    </div>
                </div>

                <div class="modal fade" id="latarka_opis" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times</button>
                                <span class="text-medium">Latarka</span>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <img src="<?= URL ?>public/img/przedmioty/latarka.png" class="img-responsive"/>
                                    </div>
                                    <div class="col-xs-12 col-md-9 text-center">
                                        Niezbędna w wyprawach do groty.
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body text-center">
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                            </div>

                        </div>
                    </div>
                </div>

                <?php if ($this->baterie) : ?>
                    <div class="col-xs-4 col-sm-3 col-md-2 text-center padding" data-toggle="modal"
                         data-target="#baterie_opis">
                        <div class="jeden kursor" title="Latarka nie będzie działać bez baterii.">
                            <img src="<?= URL ?>public/img/przedmioty/bateria.png"/>
                            <div>Baterie<br><?= $this->baterie ?> sztuk</div>
                        </div>
                    </div>

                    <div class="modal fade" id="baterie_opis" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times</button>
                                    <span class="text-medium">Baterie</span>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <img src="<?= URL ?>public/img/przedmioty/bateria.png"
                                                 class="img-responsive"/></div>
                                        <div class="col-xs-12 col-md-9 text-center">
                                            Latarka nie będzie działać bez baterii.
                                            <br/>Posiadasz <?= $this->baterie ?> sztuk.
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-body text-center">
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php endif ?>
            <?php endif ?>

            <div class="col-xs-4 col-sm-3 col-md-2 text-center padding" data-toggle="modal" data-target="#magazyn_opis">
                <div class="jeden kursor" title="Pozwala przechowywać do <?= $this->boxPoki ?> pokemonów.">
                    <img src="<?= URL ?>public/img/przedmioty/box.png"/>
                    <div>Magazyn na Pokemony <br/><?= $this->box ?> poziomu</div>
                </div>
            </div>

            <div class="modal fade" id="magazyn_opis" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times</button>
                            <span class="text-medium">Magazyn na Pokemony <?= $this->box ?> poziomu</span>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-xs-3">
                                    <img src="<?= URL ?>public/img/przedmioty/box.png" class="img-responsive"/></div>
                                <div class="col-xs-12 col-md-9 text-center">
                                    Pozwala przechowywać do <?= $this->boxPoki ?> pokemonów.
                                </div>
                            </div>
                        </div>
                        <div class="modal-body text-center">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                        </div>

                    </div>
                </div>
            </div>
            <?php if ($this->pokedex) : ?>
                <div class="col-xs-4 col-sm-3 col-md-2 text-center padding" data-toggle="modal"
                     data-target="#pokedex_opis">
                    <div class="jeden kursor" title="Zwiększa szansę złapania pokemona o <?= $this->pokedex * 10 ?> %">
                        <img src="<?= URL ?>public/img/przedmioty/pokedex<?= $this->pokedex ?>.png"/>
                        <div>Pokedex <br/><?= $this->pokedex ?> poziomu</div>
                    </div>
                </div>

                <div class="modal fade" id="pokedex_opis" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times</button>
                                <span class="text-medium">Pokedex <?= $this->pokedex ?> poziomu</span>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <img src="<?= URL ?>public/img/przedmioty/pokedex<?= $this->pokedex ?>.png"
                                             class="img-responsive"/></div>
                                    <div class="col-xs-12 col-md-9 text-center">
                                        Zwiększa szansę złapania pokemona o <?= $this->pokedex * 10 ?> %
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body text-center">
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endif ?>
            <?php if ($this->apteczka) : ?>
                <div class="col-xs-4 col-sm-3 col-md-2 text-center padding" data-toggle="modal"
                     data-target="#apteczka_opis">
                    <div class="jeden kursor" title="Zmniejsza koszt leczenia o <?= $this->apteczka * 10 ?> %.">
                        <img src="<?= URL ?>public/img/przedmioty/apteczka<?= $this->apteczka ?>.png"/>
                        <div>Apteczka <br/><?= $this->apteczka ?> poziomu</div>
                    </div>
                </div>

                <div class="modal fade" id="apteczka_opis" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times</button>
                                <span class="text-medium">Apteczka <br/><?= $this->apteczka ?> poziomu</span>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <img src="<?= URL ?>public/img/przedmioty/apteczka<?= $this->apteczka ?>.png"
                                             class="img-responsive"/></div>
                                    <div class="col-xs-12 col-md-9 text-center">
                                        Zmniejsza koszt leczenia o <?= $this->apteczka * 10 ?> %.
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body text-center">
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endif ?>
            <?php if ($this->lopata) : ?>
                <div class="col-xs-4 col-sm-3 col-md-2 text-center padding" data-toggle="modal"
                     data-target="#lopata_opis">
                    <div class="jeden kursor" title="Bez łopaty nie możesz wykopać cennych przedmiotów na safari.">
                        <img src="<?= URL ?>public/img/przedmioty/lopata.png"/>
                        <div><span style="color:transparent">a</span></div>
                        <div>Złota łopata</div>
                    </div>
                </div>

                <div class="modal fade" id="lopata_opis" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <span class="text-medium">Złota łopata</span>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <img src="<?= URL ?>public/img/przedmioty/lopata.png" class="img-responsive"/>
                                    </div>
                                    <div class="col-xs-12 col-md-9 text-center">
                                        Bez łopaty nie możesz wykopać cennych przedmiotów na safari.
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body text-center">
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endif ?>
            <?php if ($this->czesci) : ?>
                <div class="col-xs-4 col-sm-3 col-md-2 text-center padding" data-toggle="modal"
                     data-target="#czesci_opis">
                    <div class="jeden kursor" data-toggle="tooltip"
                         data-title="Części skamieliny można wymienić na Pokemona skamielinę.">
                        <img src="<?= URL ?>public/img/przedmioty/skamielina.png"/>
                        <div>Część skamieliny <br/> x <?= $this->czesci ?></div>
                    </div>
                </div>

                <div class="modal fade" id="czesci_opis" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <span class="text-medium">Część skamieliny</span>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <img src="<?= URL ?>public/img/przedmioty/skamielina.png"
                                             class="img-responsive"/></div>
                                    <div class="col-xs-12 col-md-9 text-center">
                                        Części skamieliny można wymienić na Pokemona skamielinę.
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body text-center">
                                <button class="btn btn-primary wymien" name="1">Wymień</button>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endif ?>
            <?php if ($this->monety) : ?>
                <div class="col-xs-4 col-sm-3 col-md-2 text-center padding" data-toggle="modal"
                     data-target="#dukaty_opis">
                    <div class="jeden kursor" data-toggle="tooltip" data-title="Dukaty">
                        <img src="<?= URL ?>public/img/przedmioty/dukat.png"/>
                        <div>Dukaty <br/> x <?= $this->monety ?></div>
                    </div>
                </div>

                <div class="modal fade" id="dukaty_opis" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <span class="text-medium">DUKATY</span>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <img src="<?= URL ?>public/img/przedmioty/dukat.png" class="img-responsive"/>
                                    </div>
                                    <div class="col-xs-12 col-md-9 text-center">
                                        Dukaty
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body text-center">
                                <button class="btn btn-primary wymien" name="2">Wymień</button>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endif ?>
        </div>
    </div>

    <div id="karty" class="tab-pane fade <?= $this->active == 7 ? 'in active' : '' ?>">
        <div class="row">
            <?php
            $i = 1;
            foreach ($this->karta as $value) {
                if ($value['brazowa']['ilosc']) {
                    echo '<div class="col-xs-12 col-md-6 text-center" data-toggle="modal" data-target="#brazowa_' . $i . '_opis">';
                    echo '<div class="jeden kursor" title="Karta brązowa - ' . $value['opis'] . '<br />x ' . $value['brazowa']['ilosc'] . '">';
                    echo '<img src="' . URL . 'public/img/karty/brazowa' . $i . '.png" class="img-responsive center padding"/>';
                    echo '<div>' . $value['opis'] . ' - karta brązowa.</div>';
                    echo '</div>';
                    echo '</div>';//col

                    echo '<div class="modal fade" id="brazowa_' . $i . '_opis" role="dialog">';
                    echo '<div class="modal-dialog">';
                    echo '<div class="modal-content">';
                    echo '<div class="modal-header">';
                    echo '<button type="button" class="close" data-dismiss="modal">&times;</button>';
                    echo '<span class="text-medium">' . $value['opis'] . ' - karta brązowa.</span>';
                    echo '</div>';
                    echo '<div class="modal-body">';
                    echo '<div class="row"><div class="col-xs-3">';
                    echo '<img src="' . URL . 'public/img/karty/brazowa' . $i . '.png" class="img-responsive"/></div>';
                    echo '<div class="col-xs-12 col-md-9 text-center">';
                    echo 'Karta brązowa - ' . $value['opis'] . '<br />x ' . $value['brazowa']['ilosc'];
                    echo '<br />Posiadasz ' . $value['brazowa']['ilosc'] . ' sztuk.';
                    echo '</div></div></div>';
                    echo '<div class="modal-body text-center">';
                    echo '<div class="kursor karta walka_button" name="plecak.php?numer_karty=' . $i . '&rodzaj_karty=brazowa"  id="brazowa_' . $i . '">Użyj karty</div>';
                    echo '</div>';

                    echo '<div class="modal-footer">';
                    echo '<button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>';
                    echo '</div>';

                    echo '</div></div></div>';
                }
                if ($value['srebrna']['ilosc']) {
                    echo '<div class="col-xs-12 col-md-6 text-center" data-toggle="modal" data-target="#srebrna_' . $i . '_opis">';
                    echo '<div class="jeden kursor" title="Karta srebrna - ' . $value['opis'] . '<br />x ' . $value['srebrna']['ilosc'] . '">';
                    echo '<img src="' . URL . 'public/img/karty/srebrna' . $i . '.png" class="img-responsive center padding"/>';
                    echo '<div>' . $value['opis'] . ' - karta srebrna.</div>';
                    echo '</div>';
                    echo '</div>';//col

                    echo '<div class="modal fade" id="srebrna_' . $i . '_opis" role="dialog">';
                    echo '<div class="modal-dialog">';
                    echo '<div class="modal-content">';
                    echo '<div class="modal-header">';
                    echo '<button type="button" class="close" data-dismiss="modal">&times;</button>';
                    echo '<span class="text-medium">' . $value['opis'] . ' - karta srebrna.</span>';
                    echo '</div>';
                    echo '<div class="modal-body">';
                    echo '<div class="row"><div class="col-xs-3">';
                    echo '<img src="' . URL . 'public/img/karty/srebrna' . $i . '.png" class="img-responsive"/></div>';
                    echo '<div class="col-xs-12 col-md-9 text-center">';
                    echo 'Karta brązowa - ' . $value['opis'] . '<br />x ' . $value['srebrna']['ilosc'];
                    echo '<br />Posiadasz ' . $value['srebrna']['ilosc'] . ' sztuk.';
                    echo '</div></div></div>';
                    echo '<div class="modal-body text-center">';
                    echo '<div class="kursor karta walka_button" name="plecak.php?numer_karty=' . $i . '&rodzaj_karty=srebrna"  id="srebrna_' . $i . '">Użyj karty</div>';
                    echo '</div>';

                    echo '<div class="modal-footer">';
                    echo '<button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>';
                    echo '</div>';

                    echo '</div></div></div>';
                }
                if ($value['zlota']['ilosc']) {
                    echo '<div class="col-xs-12 col-md-6 text-center" data-toggle="modal" data-target="#zlota_' . $i . '_opis">';
                    echo '<div class="jeden kursor" title="Karta złota - ' . $value['opis'] . '<br />x ' . $value['zlota']['ilosc'] . '">';
                    echo '<img src="' . URL . 'public/img/karty/zlota' . $i . '.png" class="img-responsive center padding"/>';
                    echo '<div>' . $value['opis'] . ' - karta złota.</div>';
                    echo '</div>';
                    echo '</div>';//col

                    echo '<div class="modal fade" id="zlota_' . $i . '_opis" role="dialog">';
                    echo '<div class="modal-dialog">';
                    echo '<div class="modal-content">';
                    echo '<div class="modal-header">';
                    echo '<button type="button" class="close" data-dismiss="modal">&times;</button>';
                    echo '<span class="text-medium">' . $value['opis'] . ' - karta złota.</span>';
                    echo '</div>';
                    echo '<div class="modal-body">';
                    echo '<div class="row"><div class="col-xs-3">';
                    echo '<img src="' . URL . 'public/img/karty/zlota' . $i . '.png" class="img-responsive"/></div>';
                    echo '<div class="col-xs-12 col-md-9 text-center">';
                    echo 'Karta brązowa - ' . $value['opis'] . '<br />x ' . $value['zlota']['ilosc'];
                    echo '<br />Posiadasz ' . $value['zlota']['ilosc'] . ' sztuk.';
                    echo '</div></div></div>';
                    echo '<div class="modal-body text-center">';
                    echo '<div class="kursor karta walka_button" name="plecak.php?numer_karty=' . $i . '&rodzaj_karty=zlota"  id="zlota_' . $i . '">Użyj karty</div>';
                    echo '</div>';

                    echo '<div class="modal-footer">';
                    echo '<button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>';
                    echo '</div>';

                    echo '</div></div></div>';
                }
                $i++;
            }
            ?>
        </div>
    </div>

<?php if (!isset($_GET['ajax'])) echo '</div></div></div>'; ?>
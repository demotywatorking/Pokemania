<?php if (!isset($_GET['ajax'])) : ?>
    <div class="col-xs-12 col-sm-8 col-md-9" id="prawo">
    <div class="panel panel-success jeden_ttlo">
    <div class="panel-heading text-medium"><span>SKLEP</span></div><div class="panel-body">
    <ul class="nav nav-tabs">
        <li
            <?php if ($this->active == 1) echo 'class="active"'; ?>
        ><a data-toggle="tab" href="#pokeballe">Pokeballe</a></li>
        <li
            <?php if ($this->active == 2) echo 'class="active"'; ?>
        ><a data-toggle="tab" href="#inne">Inne</a></li>
    </ul>

    <div class="tab-content">
<?php endif;
if (isset($this->blad)) echo $this->blad;
if (isset($this->kup)) echo $this->kup;
?>


    <div id="pokeballe" class="tab-pane fade <?php if ($this->active == 1) echo 'in active'; ?>">
        <div class="row noborder nomargin">
            <?php
            for ($i = 1; $i <= count($this->pokeballe); $i++) {
                echo '<div class="col-xs-4 col-sm-3 col-md-2 text-center padding" data-toggle="modal" data-target="#' . $this->pokeballe[$i]['nazwa'] . '_opis">';
                echo '<div class="jeden kursor" title="' . ucfirst($this->pokeballe[$i]['opis']) . '">';
                echo '<img src="' . URL . 'public/img/balle/' . ucfirst($this->pokeballe[$i]['nazwa']) . '.png" />';
                echo '<div>' . ucfirst($this->pokeballe[$i]['nazwa']) . '<br />Posiadasz ' . $this->pokeball[$this->pokeballe[$i]['nazwa'] . 'e'] . '<br />' . $this->pokeballe[$i]['cena'] . ' &yen; za sztukę</div>';
                echo '</div>';


                echo '</div>';//col
                echo '<div class="modal fade" id="' . $this->pokeballe[$i]['nazwa'] . '_opis" role="dialog">';
                echo '<div class="modal-dialog">';

                echo '<div class="modal-content">';
                echo '<div class="modal-header">';
                echo ' <button type="button" class="close" data-dismiss="modal">&times;</button>';
                echo '<span class="text-medium">' . ucfirst($this->pokeballe[$i]['nazwa']) . '</span>';
                echo '</div>';
                echo '<div class="modal-body">';
                echo '<div class="row"><div class="col-xs-3">';
                echo '<img src="' . URL . 'public/img/balle/' . ucfirst($this->pokeballe[$i]['nazwa']) . '.png" class="img-responsive"/></div>';
                echo '<div class="col-xs-12 col-md-9">';
                echo $this->pokeballe[$i]['opis'];
                echo '</div>';
                echo '</div></div>';
                echo '<div class="modal-body">';
                echo '<input type="text" size="15" id="' . $this->pokeballe[$i]['nazwa'] . '_ilosc" class="ilosc_kup" placeholder="Ilość, domyślnie 1"></input> x ' . $this->pokeballe[$i]['cena'] . ' &yen; ';
                echo '<button class="btn btn-info nomargin kursor kup_pokeball" id="' . $this->pokeballe[$i]['nazwa'] . '">Kup</button>';
                echo '</div>';
                echo '<div class="modal-footer">';
                echo '<button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>';
                echo '</div>';

                echo '</div></div></div>';
            }
            ?>
        </div>
    </div>

    <div id="inne" class="tab-pane fade <?php if ($this->active == 2) echo 'in active'; ?>">
        <div class="row noborder nomargin">
            <?php
            if ($this->przedmioty['p_mpa'] < 10) {////przedmiot do +mpa
                $przedmiot = $this->przedmioty['p_mpa'] + 1;
                $s = 1;
                for ($i = 1; $i < $przedmiot; $i++) {
                    $s *= 2;
                }
                $cena = $s * 25000;
                echo '<div class="col-xs-4 col-sm-3 col-md-2 text-center padding" data-toggle="modal" data-target="#mpa_opis">';
                echo '<div class="jeden kursor" title="Przedmiot zwiększa Maksymalne Punkty Akcji o 10.">';
                echo '<img src=""/>';
                echo '<div>Przedmiot do MPA<br />Poziom ' . $przedmiot . '<br />Cena: ' . $cena . ' &yen;</div>';
                echo '</div>';
                echo '</div>';//col
                echo '<div class="modal fade" id="mpa_opis" role="dialog">';
                echo '<div class="modal-dialog">';
                echo '<div class="modal-content">';
                echo '<div class="modal-header">';
                echo ' <button type="button" class="close" data-dismiss="modal">&times;</button>';
                echo '<span class="text-medium">Przedmiot do MPA</span>';
                echo '</div>';
                echo '<div class="modal-body">';
                echo '<div class="row"><div class="col-xs-3">';
                echo '<img src="" class="img-responsive"/></div>';
                echo '<div class="col-xs-12 col-md-9">';
                echo 'Przedmiot zwiększa Maksymalne Punkty Akcji o 10.<br />Cena: ' . $cena . ' &yen;';
                echo '</div>';
                echo '</div></div>';
                echo '<div class="modal-body">';
                echo '<button class="btn btn-info nomargin kursor kup_przedmiot" id="mpa">Kup</button>';
                echo '</div>';
                echo '<div class="modal-footer">';
                echo '<button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>';
                echo '</div>';

                echo '</div></div></div>';
            }
            $przedmiot = $this->przedmioty['kupony'];
            ?>
            <div class="col-xs-4 col-sm-3 col-md-2 text-center padding" data-toggle="modal" data-target="#safari_opis">
                <div class="jeden kursor" title="Na Safari nie możesz wejść bez kuponu.">
                    <img src=""/>
                    <div>Kupon na safari <br/>Posiadasz <?= $przedmiot ?><br/>Cena: 15000 &yen; za sztukę.</div>
                </div>
            </div>

            <div class="modal fade" id="safari_opis" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <span class="text-medium">Kupon na Safari</span>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-xs-3">
                                    <img src="" class="img-responsive"/></div>
                                <div class="col-xs-12 col-md-9">
                                    Na Safari nie możesz wejść bez kuponu.
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <input type="text" size="15" id="safari_ilosc" class="ilosc_kup_przedmiot"
                                   placeholder="Ilość, domyślnie 1"></input> x 15000 &yen;
                            <button class="btn btn-info nomargin kursor kup_przedmiot" id="safari">Kup</button>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                        </div>

                    </div>
                </div>
            </div>
            <?php $przedmiot = $this->przedmioty['karma']; ?>
            <div class="col-xs-4 col-sm-3 col-md-2 text-center padding" data-toggle="modal" data-target="#karma_opis">
                <div class="jeden kursor" title="Pokemony w drużynie muszą jeść karmę, aby miały siłę do walki.">
                    <img src="<?= URL ?>public/img/przedmioty/karma.png"/>
                    <div>Pudełko karmy <br/>Posiadasz <?= $przedmiot ?><br/>Cena: 1500 &yen; za sztukę.</div>
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
                                    <img src="<?= URL ?>public/img/przedmioty/karma.png" class="img-responsive"/></div>
                                <div class="col-xs-12 col-md-9">
                                    Pokemony w drużynie muszą jeść karmę, aby miały siłę do walki.
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <input type="text" size="15" id="karma_ilosc" class="ilosc_kup_przedmiot"
                                   placeholder="Ilość, domyślnie 1"></input> x 1500 &yen;
                            <button class="btn btn-info nomargin kursor kup_przedmiot" id="karma">Kup</button>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                        </div>

                    </div>
                </div>
            </div>
            <?php $przedmiot = $this->przedmioty['loteria']; ?>
            <div class="col-xs-4 col-sm-3 col-md-2 text-center padding" data-toggle="modal" data-target="#loteria_opis">
                <div class="jeden kursor" title="Nie możesz brać udziału w loterii bez kuponu.">
                    <img src=""/>
                    <div>Kupon na loterię <br/>Posiadasz <?= $przedmiot ?><br/>Cena: 60000 &yen; za sztukę.</div>
                </div>
            </div>

            <div class="modal fade" id="loteria_opis" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <span class="text-medium">Kupon na loterię</span>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-xs-3">
                                    <img src="" class="img-responsive"/></div>
                                <div class="col-xs-12 col-md-9">
                                    Nie możesz brać udziału w loterii bez kuponu.
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <input type="text" size="15" id="loteria_ilosc" class="ilosc_kup_przedmiot"
                                   placeholder="Ilość, domyślnie 1"></input> x 60000 &yen;
                            <button class="btn btn-info nomargin kursor kup_przedmiot" id="loteria">Kup</button>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                        </div>

                    </div>
                </div>
            </div>
            <?php $przedmiot = $this->przedmioty['batony']; ?>
            <div class="col-xs-4 col-sm-3 col-md-2 text-center padding" data-toggle="modal" data-target="#batony_opis">
                <div class="jeden kursor" title="Zwiększa nieznacznie przywiązanie pokemona.">
                    <img src="<?= URL ?>public/img/przedmioty/baton.png"/>
                    <div>Baton <br/>Posiadasz <?= $przedmiot ?><br/>Cena: 400 &yen; za sztukę.</div>
                </div>
            </div>

            <div class="modal fade" id="batony_opis" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <span class="text-medium">Baton</span>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-xs-3">
                                    <img src="<?= URL ?>public/img/przedmioty/baton.png" class="img-responsive"/></div>
                                <div class="col-xs-12 col-md-9">
                                    Zwiększa nieznacznie przywiązanie pokemona.
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <input type="text" size="15" id="batony_ilosc" class="ilosc_kup_przedmiot"
                                   placeholder="Ilość, domyślnie 1"></input> x 400 &yen;
                            <button class="btn btn-info nomargin kursor kup_przedmiot" id="batony">Kup</button>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                        </div>

                    </div>
                </div>
            </div>
            <?php $przedmiot = $this->przedmioty['ciastka']; ?>
            <div class="col-xs-4 col-sm-3 col-md-2 text-center padding" data-toggle="modal" data-target="#ciastka_opis">
                <div class="jeden kursor" title="Zwiększa przywiązanie pokemona.">
                    <img src="<?= URL ?>public/img/przedmioty/ciastko.png"/>
                    <div>Ciastko <br/>Posiadasz <?= $przedmiot ?><br/>Cena: 2100 &yen; za sztukę.</div>
                </div>
            </div>

            <div class="modal fade" id="ciastka_opis" role="dialog">
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
                        <div class="modal-body">
                            <input type="text" size="15" id="ciastka_ilosc" class="ilosc_kup_przedmiot"
                                   placeholder="Ilość, domyślnie 1"></input> x 2100 &yen;
                            <button class="btn btn-info nomargin kursor kup_przedmiot" id="ciastka">Kup</button>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                        </div>

                    </div>
                </div>
            </div>
            <?php $przedmiot = $this->przedmioty['box'];
            if ($przedmiot < 5) : ?>
                <div class="col-xs-4 col-sm-3 col-md-2 text-center padding" data-toggle="modal" data-target="#box_opis">
                    <div class="jeden kursor" title="Pozwala przechowywać <?= ($this->magazyn * 2) ?> Pokemonów.">
                        <img src="<?= URL ?>public/img/przedmioty/box.png"/>
                        <div>Magazyn na Pokemony poziom <?= ($przedmiot + 1) ?><br/>Cena: <?= ($przedmiot * 150000) ?>
                            &yen;.
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="box_opis" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <span class="text-medium">Magazyn na pokemony poziom <?= ($przedmiot + 1) ?></span>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <img src="<?= URL ?>public/img/przedmioty/box.png" class="img-responsive"/>
                                    </div>
                                    <div class="col-xs-12 col-md-9">
                                        Pozwala przechowywać <?= ($this->magazyn * 2) ?>
                                        Pokemonów.<br/>Cena: <?= ($przedmiot * 150000) ?> &yen;.
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                <button class="btn btn-info nomargin kursor kup_przedmiot" id="box">Kup</button>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endif;

            $przedmiot = $this->przedmioty['pokedex'];
            if ($przedmiot < 3) : ?>
                <div class="col-xs-4 col-sm-3 col-md-2 text-center padding" data-toggle="modal"
                     data-target="#pokedex_opis">
                    <div class="jeden kursor"
                         title="Zwiększa szansę złapania pokemona o <?= (($przedmiot + 1) * 10) ?> %.">
                        <img src="<?= URL ?>public/img/przedmioty/pokedex<?= ($przedmiot + 1) ?>.png"/>
                        <div>Pokedex <br/>Poziom <?= ($przedmiot + 1) ?>
                            <br/>Cena: <?= ((5 ** ($przedmiot + 1)) * 10000) ?> &yen;.
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="pokedex_opis" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <span class="text-medium">Pokedex <br/>poziom <?= ($przedmiot + 1) ?></span>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <img src="<?= URL ?>public/img/przedmioty/pokedex<?= ($przedmiot + 1) ?>.png"
                                             class="img-responsive"/></div>
                                    <div class="col-xs-12 col-md-9">
                                        Zwiększa szansę złapania pokemona o <?= (($przedmiot + 1) * 10) ?>
                                        %.<br/>Cena: <?= ((5 ** ($przedmiot + 1)) * 10000) ?> &yen;.
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                <button class="btn btn-info nomargin kursor kup_przedmiot" id="pokedex">Kup</button>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endif;
            $przedmiot = $this->przedmioty['apteczka'];
            $p = [1 => 25000, 2 => 180000, 3 => 800000];
            if ($przedmiot < 3) : ?>
                <div class="col-xs-4 col-sm-3 col-md-2 text-center padding" data-toggle="modal"
                     data-target="#apteczka_opis">
                    <div class="jeden kursor"
                         title="Zmniejsza koszt leczenia pokemonów o <?= (($przedmiot + 1) * 10) ?> %.">
                        <img src="<?= URL ?>public/img/przedmioty/apteczka<?= ($przedmiot + 1) ?>.png"/>
                        <div>Apteczka poziom <?= ($przedmiot + 1) ?><br/>Cena: <?= ($p[$przedmiot + 1]) ?> &yen;.</div>
                    </div>
                </div>

                <div class="modal fade" id="apteczka_opis" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <span class="text-medium">Apteczka poziom <?= ($przedmiot + 1) ?></span>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <img src="<?= URL ?>public/img/przedmioty/apteczka<?= ($przedmiot + 1) ?>.png"
                                             class="img-responsive"/></div>
                                    <div class="col-xs-12 col-md-9">
                                        Zmniejsza koszt leczenia pokemonów o <?= (($przedmiot + 1) * 10) ?>
                                        %.<br/>Cena: <?= ($p[$przedmiot + 1]) ?> &yen;.
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                <button class="btn btn-info nomargin kursor kup_przedmiot" id="apteczka">Kup</button>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                            </div>

                        </div>
                    </div>
                </div>

            <?php endif;
            $przedmiot = $this->przedmioty['lopata'];
            if (!$przedmiot) : ?>
                <div class="col-xs-4 col-sm-3 col-md-2 text-center padding" data-toggle="modal"
                     data-target="#lopata_opis">
                    <div class="jeden kursor" title="Bez łopaty nie możesz wykopać cennych przedmiotów na safari.">
                        <img src="<?= URL ?>public/img/przedmioty/lopata.png"/>
                        <div>Złota łopata<br/>Cena: 500000 &yen;</div>
                    </div>
                </div>

                <div class="modal fade" id="lopata_opis" role="dialog">
                    <div class="modal-dialog">

                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <span class="text-medium">Łopata</span>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <img src="<?= URL ?>public/img/przedmioty/lopata.png" class="img-responsive"/>
                                    </div>
                                    <div class="col-xs-12 col-md-9">
                                        Bez łopaty nie możesz wykopać cennych przedmiotów na safari.<br/>Cena: 500000
                                        &yen;
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                <button class="btn btn-info nomargin kursor kup_przedmiot" id="lopata">Kup</button>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                            </div>

                        </div>
                    </div>
                </div>

            <?php endif;
            $przedmiot = $this->przedmioty['runa']; ?>

            <div class="col-xs-4 col-sm-3 col-md-2 text-center padding" data-toggle="modal" data-target="#runa_opis">
                <div class="jeden kursor" title="Niezbędna do ewolucji niektórych Pokemonów.">
                    <img src="<?= URL ?>public/img/przedmioty/runa.png"/>
                    <div>Runa ewolucyjna <br/>Posiadasz <?= $przedmiot ?><br/>100000 &yen; za sztukę.</div>
                </div>
            </div>

            <div class="modal fade" id="runa_opis" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <span class="text-medium">Runa ewolucyjna</span>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-xs-3">
                                    <img src="<?= URL ?>public/img/przedmioty/runa.png" class="img-responsive"/></div>
                                <div class="col-xs-12 col-md-9">
                                    Runa ewolucyjna jest niezbędna do ewolucji niektórych Pokemonów.
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <input type="text" size="15" id="runa_ilosc" class="ilosc_kup_przedmiot"
                                   placeholder="Ilość, domyślnie 1"></input> x 100000 &yen;
                            <button class="btn btn-info nomargin kursor kup_przedmiot" id="runa">Kup</button>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                        </div>

                    </div>
                </div>
            </div>
            <?php $przedmiot = $this->przedmioty['latarka'];
            if (!$przedmiot) : ?>
                <div class="col-xs-4 col-sm-3 col-md-2 text-center padding" data-toggle="modal"
                     data-target="#latarka_opis">
                    <div class="jeden kursor" title="Wyprawy do groty są niemożliwe bez latarki.">
                        <img src="<?= URL ?>public/img/przedmioty/latarka.png"/>
                        <div>Latarka<br/>Cena: 5000 &yen;</div>
                    </div>
                </div>

                <div class="modal fade" id="latarka_opis" role="dialog">
                    <div class="modal-dialog">

                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <span class="text-medium">Latarka</span>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <img src="<?= URL ?>public/img/przedmioty/latarka.png" class="img-responsive"/>
                                    </div>
                                    <div class="col-xs-12 col-md-9">
                                        Wyprawy do groty są niemożliwe bez latarki.<br/>Cena: 5000 &yen;
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                <button class="btn btn-info nomargin kursor kup_przedmiot" id="latarka">Kup</button>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                            </div>

                        </div>
                    </div>
                </div>
            <?php else : $przedmiot = $this->przedmioty['baterie']; ?>
                <div class="col-xs-4 col-sm-3 col-md-2 text-center padding" data-toggle="modal"
                     data-target="#bateria_opis">
                    <div class="jeden kursor" title="Latarka nie będzie działać bez baterii.">
                        <img src="<?= URL ?>public/img/przedmioty/bateria.png"/>
                        <div>Baterie <br/>Posiadasz <?= $przedmiot ?><br/>Cena: 55 &yen; za sztukę.</div>
                    </div>
                </div>

                <div class="modal fade" id="bateria_opis" role="dialog">
                    <div class="modal-dialog">

                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <span class="text-medium">Baterie</span>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <img src="<?= URL ?>public/img/przedmioty/bateria.png" class="img-responsive"/>
                                    </div>
                                    <div class="col-xs-12 col-md-9">
                                        Latarka nie będzie działać bez baterii.
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                <input type="text" size="15" id="bateria_ilosc" class="ilosc_kup_przedmiot"
                                       placeholder="Ilość, domyślnie 1"></input> x 55 &yen;
                                <button class="btn btn-info nomargin kursor kup_przedmiot" id="bateria">Kup</button>
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


<?php if (!isset($_GET['ajax'])) : ?>
    </div></div></div></div>
<?php endif; ?>
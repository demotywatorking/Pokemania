<?php if (!isset($_GET['ajax'])): ?>
<div class="col-xs-12 col-sm-8 col-md-9" id="prawo">
    <div class="panel panel-success jeden_ttlo">
        <div class="panel-heading"><span>WYMIANA</span></div>
        <div class="panel-body">
            <ul class="nav nav-tabs">
                <li <?php if ($this->active == 1) echo 'class="active"'; ?>><a data-toggle="tab" href="#skamielina">Skamieliny</a>
                </li>
                <li <?php if ($this->active == 2) echo 'class="active"'; ?>><a data-toggle="tab"
                                                                               href="#dukaty">Dukaty</a></li>
            </ul>

            <div class="tab-content">
                <?php endif;
                if (isset($this->komunikat)) echo '<div class="alert alert-success text-center"><span>' . $this->komunikat . '</span></div>';
                if (isset($this->blad)) echo '<div class="alert alert-danger text-center"><span>' . $this->blad . '</span></div>';
                ?>

                <div id="skamielina" class="tab-pane fade <?php if ($this->active == 1) echo 'in active'; ?>">
                    <div class="row nomargin">
                        <?php if ($this->wymiana) : ?>
                            <div class="alert alert-info"><span>Pokemony w trakcie ożywiania:</span></div>
                            <?php foreach ($this->pokWymiana as $value) : ?>
                                <div class="well well-primary jeden_ttlo">
                                    <div class="row nomargin">
                                        <div class="col-xs-2"><img
                                                    src="<?= URL ?>public/img/poki/<?= $value['id_poka'] ?>.png"
                                                    class="img-responsive targ_pok"/></div>
                                        <div class="col-xs-2 targ-line"><?= $value['nazwa'] ?></div>
                                        <div class="col-xs-8 targ-line">
                                            <?php if ($value['czas']) {
                                                echo '<button class="btn btn-primary oddaj" name="' . $value['ID'] . '">Odbierz Pokemona</button>';
                                            } else {
                                                echo $value['czasPoka'];
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach;
                        endif; ?>
                        <div class="well well-primary jeden_ttlo">
                            <div class="row nomargin">
                                <div class="col-xs-3"><img src="<?= URL ?>public/img/przedmioty/skamielina.png"
                                                           class="img-responsive"/></div>
                                <div class="col-xs-9 text-center">Posiadasz <?= $this->skamieliny ?> części skamielin.
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info margin-top text-center">
                            <span>Oddaj swoje części, a my ożywimy z nich Pokemona.<br/>Proces ożywiania trwa jeden dzień.</span>
                        </div>

                        <div class="row nomargin row-centered">
                            <div class="col-xs-6 col-centered">
                                <button class="btn btn-primary btn-block <?php if ($this->skamieliny >= 65) echo 'wymien';
                                else echo 'disabled" data-title="Za mało części skamielin" data-toggle="tooltip'; ?>"
                                        id="142">
                                    <img src="<?= URL ?>public/img/poki/142.png" class="img-responsive center"/><br/>Aerodactyl
                                    - 65 części
                                </button>
                            </div>
                            <div class="col-xs-6 col-centered">
                                <button class="btn btn-primary btn-block <?php if ($this->skamieliny >= 40) echo 'wymien';
                                else echo 'disabled" data-title="Za mało części skamielin" data-toggle="tooltip'; ?>"
                                        id="138">
                                    <img src="<?= URL ?>public/img/poki/138.png" class="img-responsive center"/><br/>Omanyte
                                    - 40 części
                                </button>
                            </div>
                            <div class="col-xs-6 col-centered">
                                <button class="btn btn-primary btn-block <?php if ($this->skamieliny >= 40) echo 'wymien';
                                else echo 'disabled" data-title="Za mało części skamielin" data-toggle="tooltip'; ?>"
                                        id="140">
                                    <img src="<?= URL ?>public/img/poki/140.png" class="img-responsive center"/><br/>Kabuto
                                    - 40 części
                                </button>
                            </div>
                            ';
                        </div>
                    </div>
                </div>
                <div id="dukaty" class="tab-pane fade <?php if ($this->active == 2) echo 'in active'; ?>">
                    <div class="row nomargin">
                        <div class="well well-primary jeden_ttlo">
                            <div class="row nomargin">
                                <div class="col-xs-3">
                                    <img src="<?= URL ?>img/przedmioty/dukat.png" class="img-responsive"/>
                                </div>
                                <div class="col-xs-9 text-center">
                                    Posiadasz <?= $this->monety ?> dukatów.
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-info margin-top text-center"><span>W tym miejscu możesz wymienić swoje dukaty na Pokemony lub rzedkie przedmioty.</span>
                        </div>
                        <div class="row nomargin row-centered">
                            <div class="col-xs-6 col-centered">
                                <button class="btn btn-primary btn-block <?php if ($this->monety >= 150) echo 'wymien_d';
                                else echo 'disabled" data-title="Za mało dukatów" data-toggle="tooltip'; ?> " id="133">
                                    <img src="<?= URL ?>public/img/poki/133.png" class="img-responsive center"/><br/>Eevee
                                    - 150 dukatów
                                </button>
                            </div>
                            <div class="col-xs-6 col-centered">
                                <button class="btn btn-primary btn-block <?php if ($this->monety >= 50) echo 'wymien_d';
                                else $show .= 'disabled" data-title="Za mało dukatów" data-toggle="tooltip'; ?>"
                                        id="132">
                                    <img src="<?= URL ?>public/img/poki/132.png" class="img-responsive center"/><br/>Ditto
                                    - 50 dukatów
                                </button>
                            </div>
                            <div class="col-xs-6 col-centered">
                                <button class="btn btn-primary btn-block <?php if ($this->monety >= 120) echo 'wymien_d';
                                else echo 'disabled" data-title="Za mało dukatów" data-toggle="tooltip'; ?>"
                                        id="masterball">
                                    <img src="<?= URL ?>public/img/balle/Masterball.png" class="img-responsive center"/><br/>Masterball
                                    - 120 dukatów
                                </button>
                            </div>
                            <div class="col-xs-6 col-centered">
                                <button class="btn btn-primary btn-block <?php if ($this->monety >= 100) echo 'wymien_d';
                                else echo 'disabled" data-title="Za mało dukatów" data-toggle="tooltip'; ?>" id="candy">
                                    <img src="<?= URL ?>public/img/przedmioty/candy.png" class="img-responsive center"/><br/>Rare
                                    Candy - 100 dukatów
                                </button>
                            </div>
                            <div class="col-xs-6 col-centered">
                                <button class="btn btn-primary btn-block <?php if ($this->monety >= 80) echo 'wymien_d';
                                else echo 'disabled" data-title="Za mało dukatów" data-toggle="tooltip'; ?>" id="czesc">
                                    <img src="<?= URL ?>public/img/przedmioty/skamielina.png"
                                         class="img-responsive center"/><br/>Część skamieliny - 80 dukatów
                                </button>
                            </div>
                        </div>
                    </div>
                </div>


            </div><!--tab-content-->
        </div>
    </div>
</div>
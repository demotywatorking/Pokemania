<?= isset($_GET['ajax']) ? '' : '<div class="col-xs-12 col-sm-8 col-md-9" id="prawo">' ?>
    <div class="panel panel-success jeden_ttlo">
        <div class="panel-heading">
            <span>TARG - KUP POKEMONA</span>
        </div>
        <div class="panel-body">
            <?= isset($this->blad) ? '<div class="alert alert-danger"><span>'.$this->blad.'</span></div>' : '' ?>
            <?= isset($this->komunikat) ? '<div class="alert alert-success"><span>'.$this->komunikat.'</span></div>' : '' ?>
            <div class="row nomargin text-center">
                <div class="col-xs-8 col-xs-offset-2 col-md-6 col-md-offset-3">
                    <input type="text" value="<?= isset($this->ID) ? $this->ID : ''?>" id="id_poka" class="center form-control nomargin" placeholder="ID Pokemona" />
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-8 col-xs-offset-2 col-md-6 col-md-offset-3">
                    <input type="text" id="min_poziom" value="<?= isset($this->minPoziom) ? $this->minPoziom : ''?>" class="form-control nomargin" placeholder="Min. Poziom" />
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-8 col-xs-offset-2 col-md-6 col-md-offset-3">
                    <input type="text" id="max_poziom" value="<?= isset($this->maxPoziom) ? $this->maxPoziom : ''?>" class="form-control nomargin" placeholder="Max. Poziom" />
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-8 col-xs-offset-2 col-md-6 col-md-offset-3">
                    <input type="text" id="min_cena" value="<?= isset($this->minCena) ? $this->minCena : ''?>" class="form-control nomargin" placeholder="Min. Cena" />
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-8 col-xs-offset-2 col-md-6 col-md-offset-3">
                    <input type="text" id="max_cena" value="<?= isset($this->maxCena) ? $this->maxCena : ''?>" class="form-control nomargin" placeholder="Max. Cena" />
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-12 margin-top margin-bottom">
                    <button class="szukaj btn btn-primary center">SZUKAJ</button>
                </div>
            </div>
            <div id="zawartosc">
                <?php if ($this->mode) : ?>
                <div class="panel panel-success jeden_ttlo">
                    <div class="panel-heading">
                        <span>OFERTY POKEMONÓW</span>
                    </div>
                    <div class="panel-body text-center">
                        <div id="pokemon_id" class="d_none"><?= isset($this->ID) ? $this->ID : 0?></div>
                        <?php if(!$this->ilosc) : echo '<div class="alert alert-warning"><span>Brak ofert.</span></div>';
                        else : ?>
                        <div class="row nomargin">
                            <div class="alert alert-success">
                                <span>Znaleziono <?=$this->ilosc?> ofert.</span>
                            </div>
                            <div class="col-xs-2 col-xs-offset-2">Gatunek (lvl)</div>
                            <div class="col-xs-3">Wartość</div>
                            <div class="col-xs-1">Płeć i info</div>
                            <div class="col-xs-3">Cena</div>
                            <div class="col-xs-1">Kup</div>
                        </div>
                        <?php foreach ($this->pokemonOferta as $pokemon) : ?>
                        <div class="row nomargin text-center">
                            <div class="col-xs-12 nopadding">
                                <?php if($pokemon['gracza']) echo '<div class="well targ_oferta_wlasna nopadding">';
                                else echo '<div class="well targ_oferta nopadding">'; ?>
                                    <div class="row nomargin">
                                        <div class="col-xs-2 targ-line">
                                            <img src="<?=URL?>public/img/poki/srednie/<?=$pokemon['shiny'] ? 's' : ''?><?=$pokemon['id_poka']?>.png" class="img-responsive center targ_pok" />
                                        </div>
                                        <div class="col-xs-2 targ-line"><?=$pokemon['gatunek']?> (<?=$pokemon['poziom']?>)</div>
                                        <div class="col-xs-3 targ-line"><?=$pokemon['wartosc']?>&yen;</div>
                                        <div class="col-xs-1 targ-line">
                                            <div class="btn-group">
                                                <?php
                                                if(!$pokemon['plec']) echo '<button class="btn btn-primary btn-sm" data-title="Płeć męska"  
                                                                                data-toggle="tooltip"><i  class="icon-mars"></i></button>';
                                                elseif($pokemon['plec'] == 1) echo '<button class="btn btn-primary btn-sm" data-title="Płeć żeńska"  
                                                                                data-toggle="tooltip"><i  class="icon-venus"></i></button>';
                                                else if($pokemon['plec'] == 2) echo '<button class="btn btn-primary btn-sm" data-title="Pokemon bezpłciowy" 
                                                                                data-toggle="tooltip">BP</button>';
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
                                                 <button class="btn btn-primary btn-sm" data-toggle="tooltip"
                                                         data-title="<?=$pokemon['wiadomosc'] ? $pokemon['wiadomosc'] : 'Brak dodatkowych informacji'?>">D
                                                 </button>
                                            </div>
                                        </div>
                                        <div class="col-xs-3 targ-line"><?=$pokemon['cena']?> &yen;</div>
                                        <div class="col-xs-1 targ-line">
                                            <?php
                                            if($pokemon['gracza']) echo '<button class="btn btn-primary disabled" data-toggle="tooltip" data-title="To Twoja oferta">KUP</button>';
                                            else {
                                                echo '<button class="kup_pokemona btn btn-primary" id="'.$pokemon['ID'].'">KUP</button>';
                                                echo '<a href="profil.php?id='.$pokemon['id_wlasciciela'].'" style="float:left;">?</a>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?= isset($_GET['ajax']) ? '' : '</div>' ?>

<?= isset($_GET['ajax']) ? '' : '<div class="col-xs-12 col-sm-8 col-md-9" id="prawo"><div class="panel panel-success jeden_ttlo">'
    . '<div class="panel-heading"><span>STATYSTYKI</span></div><div class="panel-body">' ?>
<div class="panel-body">
    <div class="row nomargin padding_lr">
        <div class="col-xs-12">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#dzicze">Wyprawy do dziczy</a></li>
                <li><a data-toggle="tab" href="#lapanie">Łapanie Pokemonów</a></li>
                <li><a data-toggle="tab" href="#pojedynki">Pojedynki</a></li>
                <li><a data-toggle="tab" href="#inne">Inne</a>
                <li><a data-toggle="tab" href="#konto">Konto</a>
            </ul>
        </div>
    </div>

    <div class="tab-content">

        <div id="dzicze" class="tab-pane fade in active">
            <div class="panel panel-success jeden_ttlo">
                <div class="panel-heading text-medium"><span>WYPRAWY DO DZICZY</span></div>
                <div class="panel-body">
                    <div class="jeden statystyki">
                        Wypraw na polanę: <?= $this->wyprawyPolana ?>
                    </div>
                    <div class="dwa statystyki">
                        Wypraw na wyspę: <?= $this->wyprawyWyspa ?>
                    </div>
                    <div class="jeden statystyki">
                        Wypraw na grotę: <?= $this->wyprawyGrota ?>
                    </div>
                    <div class="dwa statystyki">
                        Wypraw do domu strachu: <?= $this->wyprawyDomStrachow ?>
                    </div>
                    <div class="jeden statystyki">
                        Wypraw w góry: <?= $this->wyprawyGory ?>
                    </div>
                    <div class="dwa statystyki">
                        Wypraw do wodospadu: <?= $this->wyprawyWodospad ?>
                    </div>
                    <div class="jeden statystyki">
                        Wypraw na safari: <?= $this->wyprawySafari ?>
                    </div>
                </div>
            </div>
        </div>

        <div id="lapanie" class="tab-pane fade">
            <div class="panel panel-success jeden_ttlo">
                <div class="panel-heading text-medium"><span>ŁAPANIE POKEMONÓW</span></div>
                <div class="panel-body">
                    <div class="jeden statystyki">
                        Złapanych shiny Pokemonów: <?= $this->shiny ?>
                    </div>
                    <div class="dwa statystyki">
                        Łącznie złapanych pokemonów: <?= $this->zlapanePokemony ?>
                    </div>
                    <div class="jeden statystyki">
                        Pokemonów złapanych w pokeballe: <?= $this->zlapanePokeball ?>
                    </div>
                    <div class="dwa statystyki">
                        Pokemonów złapanych w nestballe: <?= $this->zlapaneNestball ?>
                    </div>
                    <div class="jeden statystyki">
                        Pokemonów złapanych w greatballe: <?= $this->zlapaneGreatball ?>
                    </div>
                    <div class="dwa statystyki">
                        Pokemonów złapanych w ultraballe: <?= $this->zlapaneUltraball ?>
                    </div>
                    <div class="jeden statystyki">
                        Pokemonów złapanych w duskballe: <?= $this->zlapaneDuskball ?>
                    </div>
                    <div class="dwa statystyki">
                        Pokemonów złapanych w lureballe: <?= $this->zlapaneLureball ?>
                    </div>
                    <div class="jeden statystyki">
                        Pokemonów złapanych w cherishballe: <?= $this->zlapaneCherishball ?>
                    </div>
                    <div class="dwa statystyki">
                        Pokemonów złapanych w repeatballe: <?= $this->zlapaneRepeatball ?>
                    </div>
                </div>
            </div>
        </div>

        <div id="pojedynki" class="tab-pane fade">
            <div class="panel panel-success jeden_ttlo">
                <div class="panel-heading text-medium"><span>POJEDYNKI</span></div>
                <div class="panel-body">
                    <div class="dwa statystyki">
                        Pokonanych trenerów: <?= $this->pokonanychTrenerow ?>
                    </div>
                    <div class="jeden statystyki">
                        Pokonanych pokemonow: <?= $this->pokonanychPokemonow ?>
                    </div>
                </div>
            </div>
        </div>

        <div id="inne" class="tab-pane fade">
            <div class="panel panel-success jeden_ttlo">
                <div class="panel-heading text-medium"><span>INNE</span></div>
                <div class="panel-body">
                    <div class="dwa statystyki">
                        Zebranych jagód: <?= $this->zebranychJagod ?>
                    </div>
                    <div class="jeden statystyki">
                        Zjedzonych przysmaków: <?= $this->zjedzonychPrzysmakow ?>
                    </div>
                    <div class="dwa statystyki">
                        Treningów z pokemonami: <?= $this->treningi ?>
                    </div>
                </div>
            </div>
        </div>
        <div id="konto" class="tab-pane fade">
            <div class="panel panel-success jeden_ttlo">
                <div class="panel-heading text-medium"><span>KONTO</span></div>
                <div class="panel-body">
                    <div class="jeden statystyki">
                        Czas online: <?= $this->online ?>
                    </div>
                    <div class="dwa statystyki">
                        Czas online dzisiaj: <?= $this->onlineDzisiaj ?>
                    </div>
                    <div class="jeden statystyki">
                        <div>Zjedzonych jagód dodających MPA: <?= $this->jagodyMpa ?>
                        </div>
                        <div>Dodano <?= floor($this->jagodyMpa / 15) ?> MPA.</div>
                    </div>
                </div>
            </div>
        </div>
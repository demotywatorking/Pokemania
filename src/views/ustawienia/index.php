<?php if (!isset($_GET['ajax'])) echo '<div class="col-xs-12 col-sm-8 col-md-9" id="prawo">'; ?>

<?php if (!isset($_GET['ajax']) || (isset($_GET['ajax']) && $_GET['ajax'] == 2)) : ?>
    <div class="panel panel-success jeden_ttlo">
    <div class="panel-heading text-medium"><span>USTAWIENIA</span></div>
    <div class="panel-body"><div class="row nomargin padding_lr">
    <ul class="nav nav-tabs">
        <li
            <?= $this->active == 1 ? 'class="active"' : '' ?>
        ><a data-toggle="tab" href="#konto">Profil</a></li>
        <li
            <?= $this->active == 2 ? 'class="active"' : '' ?>
        ><a data-toggle="tab" href="#dane">Konto</a></li>
        <li
            <?= $this->active == 3 ? 'class="active"' : '' ?>
        ><a data-toggle="tab" href="#wyglad">Wygląd</a></li>
    </ul>
    <div class="tab-content">
<?php endif; ?>

    <div id="konto" class="tab-pane fade <?= $this->active == 1 ? 'in active' : '' ?>">

        <div class="alert alert-info margin-top">
            <span>Dodaj avatar </span><span>(Avatar musi mieć wymiary 250x300px)</span></div>
        <input type="text" placeholder="Podaj link do obrazka" name="link_a"
            <?= ($this->avatar != '') ? 'value=' . $this->avatar : '' ?>
        />
        <button class="btn btn-info nomargin kursor" id="dodaj_avatar">Dodaj</button>
        <button class="btn btn-info nomargin kursor" id="usun_avatar">Usuń avatar</button>

    </div>

    <div id="dane" class="tab-pane fade <?= $this->active == 2 ? 'in active' : '' ?>">

        <div class="alert alert-warning margin-top"><span>Zmień hasło</span></div>
        <div class="alert alert-info"><span>Nowe hasło musi posiadać co najmniej 8 znaków.</span></div>
        <input type="password" size="15" placeholder="Podaj stare hasło" name="stare" class="form-control"/>
        <input type="password" size="15" placeholder="Podaj nowe hasło" name="haslo" class="form-control"/>
        <input type="password" size="15" placeholder="Potwierdź nowe hasło" name="haslo2" class="form-control"/>
        <button class="btn btn-info nomargin kursor" id="zmien_haslo">Zmień hasło</button>


        <div class="alert alert-info margin-top"><span>Wyświetlaj podpowiedzi</span></div>
        <div class="btn-group">
            <button type="button" id="zmien/podpowiedz/0/?active=2" class="btn btn-primary 
        <?= $this->podp == 0 ? 'primary-active' : '' ?>
        ">NIE
            </button>
            <button type="button" id="zmien/podpowiedz/1/?active=2" class="btn btn-primary 
        <?= $this->podp == 1 ? 'primary-active' : '' ?>
        ">TAK
            </button>
        </div>

    </div>

    <div id="wyglad" class="tab-pane fade <?= $this->active == 3 ? 'in active' : '' ?>">

        <div class="alert alert-info margin-top"><span>Widok drużyny w profilu</span></div>
        <div class="btn-group">
            <button type="button" id="zmien/druzyna/0/?active=3" class="btn btn-primary 
        <?= $this->blokada == 0 ? 'primary-active' : '' ?>
        ">NIE
            </button>
            <button type="button" id="zmien/druzyna/1/?active=3" class="btn btn-primary 
        <?= $this->blokada == 1 ? 'primary-active' : '' ?>
        ">TAK
            </button>
        </div>

        <div class="alert alert-info margin-top"><span>Pokazuj moje oferty na targu</span></div>
        <div class="btn-group">
            <button type="button" id="zmien/targ/0/?active=3" class="btn btn-primary 
        <?= $this->targ == 0 ? 'primary-active' : '' ?>
        ">NIE
            </button>
            <button type="button" id="zmien/targ/1/?active=3" class="btn btn-primary 
        <?= $this->targ == 1 ? 'primary-active' : '' ?>
        ">TAK
            </button>
        </div>

        <div class="alert alert-info margin-top"><span>Wyświetlaj zegar</span></div>
        <div class="btn-group">
            <button type="button" id="zmien/zegar/0/?active=3" class="btn btn-primary 
        <?= $this->zegar == 0 ? 'primary-active' : '' ?>
        ">NIE
            </button>
            <button type="button" id="zmien/zegar/1/?active=3" class="btn btn-primary 
        <?= $this->zegar == 1 ? 'primary-active' : '' ?>
        ">TAK
            </button>
        </div>

        <div class="alert alert-info margin-top"><span>Wyświetlaj tooltipy</span></div>
        <div class="btn-group">
            <button type="button" id="zmien/tooltip/0/?active=3" class="btn btn-primary 
        <?= $this->tooltip == 0 ? 'primary-active' : '' ?>
        ">NIE
            </button>
            <button type="button" id="zmien/tooltip/1/?active=3" class="btn btn-primary 
        <?= $this->tooltip == 1 ? 'primary-active' : '' ?>
        ">TAK
            </button>
        </div>

        <div class="alert alert-info margin-top"><span>Kolor paneli</span></div>
        <div class="btn-group">
            <button type="button" id="zmien/panel/0/?active=3" class="btn btn-primary 
        <?= $this->css == 0 ? 'primary-active' : '' ?>
        ">ZIELONY(domyślny)
            </button>
            <button type="button" id="zmien/panel/1/?active=3" class="btn btn-primary 
        <?= $this->css == 1 ? 'primary-active' : '' ?>
        ">NIEBIESKI
            </button>
            <button type="button" id="zmien/panel/2/?active=3" class="btn btn-primary 
        <?= $this->css == 2 ? 'primary-active' : '' ?>
        ">POMARAŃCZOWY
            </button>
            <button type="button" id="zmien/panel/3/?active=3" class="btn btn-primary 
        <?= $this->css == 3 ? 'primary-active' : '' ?>
        ">CZERWONY
            </button>
            <button type="button" id="zmien/panel/4/?active=3" class="btn btn-primary 
        <?= $this->css == 4 ? 'primary-active' : '' ?>
        ">BŁĘKITNY
            </button>
            <button type="button" id="zmien/panel/5/?active=3" class="btn btn-primary 
        <?= $this->css == 5 ? 'primary-active' : '' ?>
        ">ŻÓŁTY
            </button>
            <button type="button" id="zmien/panel/6/?active=3" class="btn btn-primary 
        <?= $this->css == 6 ? 'primary-active' : '' ?>
        ">FIOLETOWY
            </button>
        </div>

        <div class="alert alert-info margin-top"><span>Kolor tła</span></div>
        <div class="btn-group">
            <button type="button" id="zmien/tlo/default/?active=3" class="btn btn-primary">Domyślne</button>
            <input id="colorpicker" value="
        <?= ($this->tlo == '') ? '#1c5b4e' : $this->tlo ?>
        " type="color" class="kursor"/>
            <button type="button" id="color" class="btn btn-primary">Ustaw</button>
        </div>

        <div class="alert alert-info margin-top"><span>Tabelka z informacjami</span></div>
        <div class="btn-group">
            <button type="button" id="zmien/tabelka/0/?active=3" class="btn btn-primary 
        <?= $this->tabelka == 0 ? 'primary-active' : '' ?>
        ">Po lewej stronie
            </button>
            <button type="button" id="zmien/tabelka/1/?active=3" class="btn btn-primary 
        <?= $this->tabelka == 1 ? 'primary-active' : '' ?>
        ">Po prawej stronie
            </button>
        </div>

        <div class="alert alert-info margin-top"><span>Motyw</span></div>
        <div class="btn-group">
            <button type="button" id="zmien/motyw/1/?active=3" class="btn btn-primary
        <?= $this->motyw == 1 ? 'primary-active' : '' ?>
        ">Jasny
            </button>
            <button type="button" id="zmien/motyw/2/?active=3" class="btn btn-primary
        <?= $this->motyw == 2 ? 'primary-active' : '' ?>
        ">Ciemny
            </button>
        </div>


        <div class="alert alert-warning margin-top-big text-medium"><span>Ustawienia stopki</span></div>

        <div class="alert alert-info margin-top"><span>Wyświetlaj przycisk leczenia</span></div>
        <div class="btn-group">
            <button type="button" id="zmien/leczenie/0/?active=3" class="btn btn-primary 
        <?= $this->leczenie == 0 ? 'primary-active' : '' ?>
        ">NIE
            </button>
            <button type="button" id="zmien/leczenie/1/?active=3" class="btn btn-primary 
        <?= $this->leczenie == 1 ? 'primary-active' : '' ?>
        ">TAK
            </button>
        </div>

        <div class="alert alert-info margin-top"><span>Wyświetlaj przycisk wypij sodę</span></div>
        <div class="btn-group">
            <button type="button" id="zmien/soda/0/?active=3" class="btn btn-primary 
        <?= $this->soda == 0 ? 'primary-active' : '' ?>
        ">NIE
            </button>
            <button type="button" id="zmien/soda/1/?active=3" class="btn btn-primary ';
        <?= $this->soda == 1 ? 'primary-active' : '' ?>
        ">TAK
            </button>
        </div>

        <div class="alert alert-info margin-top"><span>Wyświetlaj przycisk wypij wodę</span></div>
        <div class="btn-group">
            <button type="button" id="zmien/woda/0/?active=3" class="btn btn-primary 
        <?= $this->woda == 0 ? 'primary-active' : '' ?>
        ">NIE
            </button>
            <button type="button" id="zmien/woda/1/?active=3" class="btn btn-primary 
        <?= $this->woda == 1 ? 'primary-active' : '' ?>
        ">TAK
            </button>
        </div>

        <div class="alert alert-info margin-top"><span>Wyświetlaj przycisk wypij lemoniadę</span></div>
        <div class="btn-group">
            <button type="button" id="zmien/lemoniada/0/?active=3" class="btn btn-primary 
        <?= $this->lemoniada == 0 ? 'primary-active' : '' ?>
        ">NIE
            </button>
            <button type="button" id="zmien/lemoniada/1/?active=3" class="btn btn-primary 
        <?= $this->lemoniada == 1 ? 'primary-active' : '' ?>
        ">TAK
            </button>
        </div>


        <div class="alert alert-info margin-top"><span>Wyświetlaj przycisk ulecz drużynę Cheri Berry</span></div>
        <div class="btn-group">
            <button type="button" id="zmien/cheri/0/?active=3" class="btn btn-primary 
        <?= $this->cheri == 0 ? 'primary-active' : '' ?>
        ">NIE
            </button>
            <button type="button" id="zmien/cheri/1/?active=3" class="btn btn-primary 
        <?= $this->cheri == 1 ? 'primary-active' : '' ?>
        ">TAK
            </button>
        </div>

        <div class="alert alert-info margin-top"><span>Wyświetlaj przycisk ulecz drużynę Wiki Berry</span></div>
        <div class="btn-group">
            <button type="button" id="zmien/wiki/?active=3" class="btn btn-primary 
        <?= $this->wiki == 0 ? 'primary-active' : '' ?>
        ">NIE
            </button>
            <button type="button" id="zmien/wiki/1/?active=3" class="btn btn-primary 
        <?= $this->wiki == 1 ? 'primary-active' : '' ?>
        ">TAK
            </button>
        </div>

        <div class="alert alert-info margin-top"><span>Wyświetlaj przycisk nakarm drużynę</span></div>
        <div class="btn-group">
            <button type="button" id="zmien/nakarm/0/?active=3" class="btn btn-primary 
        <?= $this->nakarm == 0 ? 'primary-active' : '' ?>
        ">NIE
            </button>
            <button type="button" id="zmien/nakarm/1/?active=3" class="btn btn-primary 
        <?= $this->nakarm == 1 ? 'primary-active' : '' ?>
        ">TAK
            </button>
        </div>

    </div>


<?php if (!isset($_GET['ajax']) || (isset($_GET['ajax']) && $_GET['ajax'] == 2)) echo '</div></div></div>';
if (!isset($_GET['ajax'])) echo '</div>' ?>
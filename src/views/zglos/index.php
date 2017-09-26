<?php if(!isset($_GET['ajax']))  : ?>
    <div class="col-xs-12 col-sm-8 col-md-9" id="prawo">
        <div class="panel panel-success jeden_ttlo">
            <div class="panel-heading">
                <span>ZGŁOŚ BŁĄD</span>
            </div>
            <div class="panel-body">
<?php endif; ?>

                <?= isset($this->blad) ? '<div class="alert alert-danger"><span>'.$this->blad.'</span></div>' : '' ?>
                <?= isset($this->komunikat) ? '<div class="alert alert-success"><span>'.$this->komunikat.'</span></div>' : '' ?>
                <div class="row nomargin">
                    <div class="col-xs-12 text-center">
                        <button class="btn btn-primary wyswietl margin-bottom">Zobacz zgłoszone przez siebie błędy.</button>
                    </div>
                </div>

                <div class="row row-centered">
                    <div class="col-xs-12 col-md-8 col-centered">
                        <textarea placeholder="Tytuł" class="form-control" name="tytul"><?=isset($this->tytul) ? $this->tytul : '' ?></textarea>
                    </div>
                </div>

                <div class="row row-centered">
                    <div class="col-xs-12 col-md-8 col-centered">
                        <textarea placeholder="Opis" class="form-control" name="opis"><?=isset($this->opis) ? $this->opis : '' ?></textarea>
                    </div>
                </div>

                <div class="row nomargin">
                    <div class="col-xs-12 margin-bottom text-center">
                        <button class="btn btn-info zglos margin-bottom">ZGŁOŚ</button>
                    </div>
                </div>

                <div class="row nomargin">
                    <div class="well well-primary jeden_ttlo">
                        Witaj na zamkniętych testach beta gry Pokemon.<br />
                        Oto lista rzeczy, które jeszcze nie działają:<br />
                        1.Niektóre ataki, które wymagają osobnego oskrypotowania.<br />
                        2.Punkty do wykorzystania.<br />
                        3.Efekt głodu u Pokemonów.<br />
                        4.Efekt osiągnięć i odznak.<br />
                        5.Niektóre wydarzenia w dziczy.<br />
                        Dodatkowe informacje.<br />
                        Jak zgłosić błąd:<br />
                        W Menu wybierz opcję inne > zgłoś błąd.<br />
                        Ewentualnie napisać PW do gracza o nicku: tester1<br />
                    </div>
                </div>
<?php if(!isset($_GET['ajax']))  : ?>
            </div></div></div>
<?php endif; ?>

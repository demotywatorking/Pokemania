<?= isset($_GET['ajax']) ? '' : '<div class="col-xs-12 col-sm-8 col-md-9" id="prawo">'; ?>
<?= (isset($_GET['ajax']) && isset($_GET['komunikat'])) ? '' : '<div class="panel panel-success jeden_ttlo" id="hodowla_panel">'
    . '<div class="panel-heading"><span>KUPIEC POKEMON</span></div><div class="panel-body">' ?>
    <div class="row nomargin">
    <div class="col-xs-12 text-center">
        <div id="hodowla_info">
            <?= isset($this->error) ? $this->error : '' ?>
            <?= isset($this->komunikat) ? $this->komunikat : '' ?>
        </div>

        <?php if ($this->iloscPokemonow) : ?>
        <div class="row nomargin">
            <div class="col-xs-12">
                <div class="btn-group">
                    <button class="btn btn-primary btn-lg" id="zaznacz_wszystkie">Zaznacz wszystkie</button>
                    <button class="btn btn-primary btn-lg" id="zaznaczone">Sprzedaj zaznaczone</button>
                    <button class="btn btn-primary btn-lg" id="wszystkie">Sprzedaj wszystkie</button>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="well dwa_ttlo margin-top text-medium">
                    <div id="zaznaczonych"></div>
                    <div id="wartosc_zaznaczonych"></div>
                </div>
            </div>
            <div data-toggle="buttons">
                <?php foreach ($this->pokemon as $value) {
                    echo '<label class="btn btn-primary col-xs-3 col-md-2 text-center hodowla-btn" name="' . $value['ID'] . '">';
                    echo '<input autocomplete="off" name="' . $value['ID'] . '" type="checkbox" class="d_none hodowla" />';
                    if ($value['shiny'] == 1) echo '<img class="img-responsive center" src="' . URL . 'public/img/poki/srednie/s' . $value['id_poka'] . '.png" />';
                    else echo '<img class="img-responsive center" src="' . URL . 'public/img/poki/srednie/' . $value['id_poka'] . '.png" />';
                    echo '<span>' . $value['imie'] . ' (' . $value['poziom'] . ')</span><br /><span id="' . $value['ID'] . '_wartosc" class="wartosc">' . number_format($value['wartosc'], 0, '', '.') . '</span> &yen;';
                    echo '</label>';
                }
                echo '</div>';
                else : ?>
                    <div class="alert alert-warning"><span>Brak pokemonów do sprzedania u kupca.</span></div>
                <?php endif; ?>


            </div>
        </div>
    </div>
<?= (isset($_GET['ajax']) && isset($_GET['komunikat'])) ? '' : '</div></div>'; ?>
<?php
if (!isset($_GET['ajax']) && !isset($_GET['komunikat'])) {
    echo '<div class="d_none menu_dr" id="menu_hodowla"><ul class="dropdown-menu dropdown-menu_dr"><li class="info kursor"><a>INFO</a></li><li class="sprzedaj_jeden kursor"><a>SPRZEDAJ</a></li></ul></div>';
    echo '<div class="modal fade in" id="pokemon_modal" role="dialog">';
    echo '<div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button>
              <span name="pokemon_modal" class="modal-title"></span></div><div name="pokemon_modal"  class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-warning" data-dismiss="modal">Zamknij</button></div>';
    echo '</div></div></div>';
}
?>
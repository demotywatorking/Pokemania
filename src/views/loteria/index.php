<?php if (!isset($_GET['ajax'])) echo '<div class="col-xs-12 col-sm-8 col-md-9" id="prawo"><div class="panel panel-success jeden_ttlo">
    <div class="panel-heading text-medium"><span>LOTERIA</span></div><div class="panel-body"><div id="wynik"></div><div id="loteria_body" class="row nomargin">'; ?>

    <div class="col-xs-12">
        <div class="well well-sm well-stan dwa_ttlo noborder">Pozostało Ci <?= $this->losy ?> losów.</div>
        <div class="well well-sm well-stan dwa_ttlo noborder">Codziennie w nocy otrzymujesz 2 losy na loterię. Losy
            możesz również kupić w PokeSklepie
        </div>
    </div>
<?php if ($this->losy) : ?>
    <div class="col-xs-12 text-center  margin-top"><a type="button" class="btn btn-success noborder losuj"
                                                      href="loteria/losuj">LOSUJ</a></div>
<?php else : ?>
    <div class="col-xs-12 text-center margin-top">
        <div class="btn btn-success noborder disabled" data-original-title="Nie masz już losów na loterii"
             data-toggle="tooltip" data-placement="top">LOSUJ
        </div>
    </div>
<?php endif; ?>

<?php if (!isset($_GET['ajax'])) echo '</div></div></div></div>'; ?>
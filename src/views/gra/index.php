<?= isset($_GET['ajax']) ? '' : '<div class="col-xs-12 col-sm-8 col-md-9" id="prawo">' ?>

<div class="panel panel-success jeden_ttlo">
    <div class="panel-heading"><span>STATYSTYKI DZISIEJSZE</span></div>
    <div class="panel-body">
        <div class="row nomargin">
            <?php
            if (isset($this->desktop)) {
                if ($this->desktop) {
                    echo '<div class="row row-centered hidden-md hidden-lg"><button class="btn btn-lg btn-info desktop" name="off">Włącz wersję mobilną</button></div>';
                } else {
                    echo '<div class="row row-centered hidden-md hidden-lg"><button class="btn btn-lg btn-info desktop" name="on">Włącz wersję na komputer</button></div>';
                }
            }
            ?>
            <div class="col-xs-12">
                <div class="well well-primary jeden_ttlo">
                    Złapanych pokemonów dzisiaj:<?= $this->zlapane; ?>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="well well-primary jeden_ttlo">
                    Pozostałe losy z loterii: <?= $this->loteria ?>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="well well-primary jeden_ttlo">
                    Pozostałe kupony na Safari: <?= $this->kupony ?>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="well well-primary jeden_ttlo">
                    Wypraw do dziczy dzisiaj: <?= $this->wyprawy ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
if (0) {
    phpinfo();
}
?>
<?= isset($_GET['ajax']) ? '' : '</div>' ?>


<?= isset($_GET['ajax']) ? '' : '<div class="col-xs-12 col-sm-8 col-md-9" id="prawo">' ?>
    <div class="panel panel-success jeden_ttlo">
        <div class="panel-heading"><span>POLOWANIE</span></div>
        <div class="panel-body" id="panel_polowanie">
            <?php if (isset($this->wydarzenieSp)) echo $this->wydarzenieSp; ?>
            <div id="dzicz_ajax" class="d_none"><?= $this->dzicz ?></div>

            <?= $this->trener ?>
            <?= $this->avatary ?>
            <div class="row nomargin">
                <div class="col-xs-12 text-center margin-bottom">
                    <div id="wyswietl_walke_trener" class="btn btn-info btn-lg">Wyświetl przebieg walki</div>
                </div>
            </div>
            <div id="walka"></div>
            <?= $this->trenerWynik ?>
            <div class="col-xs-12 text-center margin-top">
                <button id="<?= $this->dzicz ?>" class="btn btn-primary btn-lg button_kontynuuj">KONTYNUUJ</button>
            </div>
        </div>
    </div>
<?= isset($_GET['ajax']) ? '' : '</div>';
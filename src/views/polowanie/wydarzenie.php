<?= isset($_GET['ajax']) ? '' : '<div class="col-xs-12 col-sm-8 col-md-9" id="prawo">' ?>
    <div class="panel panel-success jeden_ttlo">
        <div class="panel-heading"><span>POLOWANIE</span></div>
        <div class="panel-body" id="panel_polowanie">
            <div id="dzicz_ajax" class="d_none"><?= $this->dzicz ?></div>
            <?php
            if (isset($this->wydarzenieSp)) {
                echo $this->wydarzenieSp;
            }
            if (isset($this->wydarzenie)) {
                echo $this->wydarzenie;
                echo '<div class="col-xs-12 text-center margin-top"><button id="' . $this->dzicz . '" class="btn btn-primary btn-lg button_kontynuuj">KONTYNUUJ</button></div>';
            }
            ?>
        </div>
    </div>
<?= isset($_GET['ajax']) ? '' : '</div>';
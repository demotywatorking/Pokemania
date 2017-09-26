<?= isset($_GET['ajax']) ? '' : '<div class="col-xs-12 col-sm-8 col-md-9" id="prawo">'?>
    <div class="panel panel-success jeden_ttlo">
        <div class="panel-heading">
            <span>POLOWANIE - BŁĄD</span>
        </div>
    <div class="panel-body" id="panel_polowanie">
    <div id="dzicz_ajax" class="d_none"><?= $this->dzicz ?></div>
    <div class="alert alert-danger"><span>
        <?= $this->error ?>
    </span></div>
<?= isset($_GET['ajax']) ? '' : '</div></div>';
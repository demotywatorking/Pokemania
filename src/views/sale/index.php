<?= isset($_GET['ajax']) ? '' : '<div class="col-xs-12 col-sm-8 col-md-9" id="prawo"><div class="panel panel-success jeden_ttlo">'
    . '<div class="panel-heading"><span>SALE LIDERÓW POKEMON</span></div><div class="panel-body">' ?>
<div class="alert alert-info"><span>Walka z liderem kosztuje 50PA.<br/>W walce mogą brać udział tylko w pełni zdrowe Pokemony.</span>
</div>
<div class="row nomargin">
    <div class="col-xs-12">
        <ul class="nav nav-tabs margin-top">
            <?= $this->ulTrenerzy ?>
        </ul>
    </div>
</div>
<div class="tab-content">
    <?php
    foreach ($this->lider as $value) {
        echo $value;
    } ?>
</div></div></div>
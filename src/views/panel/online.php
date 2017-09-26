<div class="col-xs-12 col-sm-8 col-md-9" id="prawo">
    <div class="panel panel-success jeden_ttlo">
        <div class="panel-heading"><span>PANEL ADMINA</span></div>
        <div class="panel-body">
            <div class="alert alert-success"><span>Graczy online: <?=$this->ile?></span></div>
            <?php
            if($this->ile)   {
                foreach ($this->online as $online) {
                    echo '<pre>'.$online.'</pre>';
                }
            }
            ?>
            <a href="<?=URL?>panel">Powrót</a>
        </div>
    </div>
</div>

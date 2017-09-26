<?php if(!isset($_GET['ajax'])) : ?>
    <div class="col-xs-12 col-sm-8 col-md-9" id="prawo">
        <div class="panel panel-success jeden_ttlo">
            <div class="panel-heading"><span>PODRÓŻ</span></div>
            <div class="panel-body" id="podroz-body">
<?php endif;
if (isset($this->blad))
    echo '<div class="alert alert-warning"><span>'.$this->blad.'</span></div>';
if($this->region == 1) : ?>
    <div class="well well-primary jeden_ttlo text-center">[Tu będzie obrazek statku?] Podróżuj statkiem do Johto<br />
        <div class="row margin-top"><button class="btn btn-primary podroz" data-region="2" >Podróż za 250000 &yen;</button></div>
    </div>
    <?php elseif ($this->region ==2) : ?>
    <div class="well well-primary jeden_ttlo text-center">[Tu będzie obrazek statku?] Podróżuj statkiem do Kanto<br />
        <div class="row margin-top"><button class="btn btn-primary podroz" data-region="1" >Podróż za 250000 &yen;</button></div>
    </div>
<?php endif;?>

<?php if(!isset($_GET['ajax'])) : ?>
    </div></div></div>
<?php endif; ?>
<?php if(!isset($_GET['ajax'])) : ?>
<div class="col-xs-12 col-sm-8 col-md-9" id="prawo">
    <div class="panel panel-success jeden_ttlo">
        <div class="panel-heading"><span>PODRÓŻ</span></div>
        <div class="panel-body" id="podroz-body">
<?php endif; ?>

<div class="alert alert-warning"><span>Kupiono bilet do <?=$this->regionNazwa?></span></div>

<?php if(!isset($_GET['ajax'])) : ?>
    </div></div></div>
<?php endif; ?>
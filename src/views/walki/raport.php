<?php if(!isset($_GET['ajax']))  : ?>
    <div class="col-xs-12 col-sm-8 col-md-9" id="prawo">
        <div class="panel panel-success jeden_ttlo">
            <div class="panel-heading">
                <span>RAPORTY Z WALK</span>
            </div>
            <div class="panel-body">
<?php endif;
    if (isset($this->blad)) echo '<div class="alert alert-danger"><span>'.$this->blad.'</span></div>';
if (isset($this->raport)) echo $this->raport;
?>
    <div class="row row-centered margin-top">
        <a class="btn btn-lg btn-success" href="<?=URL?>walki">POWRÓT DO RAPORTÓW</a>
    </div>
<?php if(!isset($_GET['ajax']))  : ?>
    </div></div></div>
<?php endif; ?>
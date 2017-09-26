<?php if(!isset($_GET['ajax'])) : ?>
<div class="col-xs-12 col-sm-8 col-md-9" id="prawo">
    <div class="panel panel-success jeden_ttlo">
        <div class="panel-heading">
            <span>SAMOUCZEK</span>
        </div>
        <div class="panel-body">
<?php endif; ?>
<?=(isset($this->blad)) ?  '<div class="alert alert-danger"><span>'.$this->blad.'</span></div>' : '' ?>
<?=(isset($this->komunikat)) ? '<div class="alert alert-success"><span>'.$this->komunikat.'</span></div>' : '' ?>
<?=$this->samouczek?>
<?php if(!isset($_GET['ajax']))  : ?>
    </div></div></div>
<?php endif; ?>
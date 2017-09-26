<?php if(!isset($_GET['ajax'])) : ?>
<div class="col-xs-12 col-sm-8 col-md-9" id="prawo">
    <div class="panel panel-success jeden_ttlo">
        <div class="panel-heading"><span>WIADOMOŚCI</span></div>
        <div class="panel-body">
<?php endif;
        if (isset($this->blad)) echo '<div class="alert alert-danger text-center"><span>'.$this->blad.'</span></div>';
?>

        <div class="row nomargin">
            <div class="col-xs-12">
                <input placeholder="Odbiorca" class="form-control" type="text" id="odbiorca" value="<?=$this->odbiorca?>" />
            </div>
            <div class="col-xs-12 margin-top">
                <textarea placeholder="Treść" id="tresc" rows="4" class="form-control"><?=$this->tresc?></textarea>
            </div>
            <div class="col-xs-12 margin-top text-center">
                <button class="btn btn-primary wyslij">Wyślij</button>
            </div>
        </div>
<?php if(!isset($_GET['ajax'])) : ?>
        </div>
    </div>
</div>
<?php endif; ?>
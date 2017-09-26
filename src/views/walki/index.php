<?php if(!isset($_GET['ajax']))  : ?>
<div class="col-xs-12 col-sm-8 col-md-9" id="prawo">
    <div class="panel panel-success jeden_ttlo">
        <div class="panel-heading">
            <span>RAPORTY Z WALK</span>
        </div>
        <div class="panel-body">
<?php endif;
        if (isset($this->blad)) echo '<div class="alert alert-danger"><span>'.$this->blad.'</span></div>';
        if (isset($this->komunikat)) echo '<div class="alert alert-success"><span>'.$this->komunikat.'</span></div>';
        if ($this->ilosc) :
            foreach ($this->raport as $raport) :?>
                <div class="well well-primary jeden_ttlo text-center">
                    <div class="row nomargin">
                        <div class="col-xs-2">
                            <?=$raport['data']?>
                        </div>
                        <div class="col-xs-2">
                            <?=$raport['rodzaj']?>
                        </div>
                        <div class="col-xs-2">
                            <a class="btn btn-primary" href="<?=URL?>walki/zobacz/<?=$raport['ID']?>">ZOBACZ</a>
                        </div>
                        <div class="col-xs-2">
                            <?php if ($raport['odblokowany']) echo 'UDOSTĘPNIONY';
                            else echo '<a class="btn btn-primary" href ="'.URL.'walki/unlock/'.$raport['ID'].'"> UDOSTĘPNIJ RAPORT</a>';?>
                        </div>
                        <div class="col-xs-2">
                            <a class="btn btn-primary" href="<?=URL?>walki/usun/<?=$raport['ID']?>"> USUŃ RAPORT</a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
        <?php endforeach;
            else : ?>
            <div class="alert alert-warning">
                <span>Brak raportów z walk.</span>
            </div>
        <?php endif; ?>
<?php if(!isset($_GET['ajax']))  : ?>
        </div></div></div>
<?php endif; ?>


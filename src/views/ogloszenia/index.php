<?php if(!isset($_GET['ajax'])) : ?>
<div class="col-xs-12 col-sm-8 col-md-9" id="prawo">
    <div class="panel panel-success jeden_ttlo">
        <div class="panel-heading"><span>OGŁOSZENIA</span></div>
        <div class="panel-body">
<?php endif;
        if (isset($this->ogloszenia)) :
           foreach ($this->ogloszenie as $ogloszenie) : ?>
            <div class="panel panel-success jeden_ttlo nopadding">
                <div class="panel-heading text-medium">
                    <span><?=$ogloszenie['tytul']?></span>
                </div>
                <div class="panel-body">
                    <span class="<?=$ogloszenie['nowe'] ? 'pogrubienie text-medium' : ''?>">
                        <?=$ogloszenie['tresc']?>
                    </span>
                </div>
                <div class="panel-footer dwa_ttlo">
                    <span>
                        <?=$ogloszenie['data']?>
                    </span>
                </div>
            </div>
    <?php endforeach;
    else : echo '<div class="alert alert-warning"><span>Brak ogłoszeń.</span></div>';
    endif;

 if(!isset($_GET['ajax'])) : ?>
            </div></div></div>
<?php endif;?>

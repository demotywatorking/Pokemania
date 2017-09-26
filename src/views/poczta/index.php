<?php if(!isset($_GET['ajax'])) : ?>
<div class="col-xs-12 col-sm-8 col-md-9" id="prawo">
<div class="panel panel-success jeden_ttlo">
    <div class="panel-heading text-medium"><span>RAPORTY</span></div>
    <div class="col-xs-12 text-center" id="info"></div>
    <div class="panel-body">
    <?php endif; ?>
        <div class="row nomargin">

            <div class="col-xs-12 text-center"><button class="btn btn-primary usun_w margin-bottom">USUŃ WSZYSTKIE RAPORTY</button></div>
            <?php if ($this->wiadomosci) :
                foreach ($this->wiadomosc as $wiadomosc) : ?>
                   <div class="wiadomosc col-xs-12 kursor raport-line" id="<?=$wiadomosc['ID']?>">
                       <div  class="well well-primary jeden_ttlo padding_small margin_2">
                           <div class="row nomargin text-center">
                               <div class="col-xs-3">
                                   <?=$wiadomosc['odczytana'] ? '' : '<span class="czerwony">NOWA!</span>' ?>
                                   <?=$wiadomosc['data']?>
                               </div>
                               <div class="col-xs-6 pogrubienie" >
                                    <?=$wiadomosc['tytul']?>
                               </div>
                               <div class="col-xs-3" >
                                    <button class="btn btn-danger usun pull-right btn-sm margin-top" id="usun_<?=$wiadomosc['ID']?>">USUŃ</button>
                               </div>
                           </div>
                       </div>
                   </div>
            <?php endforeach;
                endif;?>
        </div>
    </div>
</div>
</div>
<?php
if(!isset($_GET['ajax']))
{
    echo '<div class="modal fade in" id="raport_modal" role="dialog">';
    echo '<div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-title pogrubienie text-center"></div></div><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-warning" data-dismiss="modal">Zamknij</button></div>';
    echo '</div></div></div>';
}
?>
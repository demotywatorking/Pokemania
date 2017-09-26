<?php if (!isset($_GET['ajax'])) : ?>
<div class="col-xs-12 col-sm-8 col-md-9" id="prawo">
    <div class="panel panel-success jeden_ttlo">
        <div class="panel-heading text-medium"><span>WIADOMOŚCI</span></div>
        <div class="col-xs-12 text-center" id="info"></div>
        <div class="panel-body">
<?php endif; ?>
            <div class="row nomargin">
            <?=isset($this->info) ? '<div class="alert alert-success text-center"><span>'.$this->info.'</span></div>' : '' ?>
                <div class="col-xs-12 text-center margin_bottom">
                    <button class="btn btn-primary nowa" href="wiadomosci.php?nowa">NAPISZ NOWĄ WIADOMOŚĆ</button>
                </div>
                <?php if (isset($this->wiadomosc)) :
                        foreach ($this->wiadomosc as $wiadomosc) : ?>
                            <div class="wiadomosc col-xs-12 kursor raport-line" id="<?=$wiadomosc['ID']?>">
                                <div class="well well-primary jeden_ttlo padding_small margin_2">
                                    <div class="row nomargin text-center">
                                        <div class="col-xs-5">
                                            <?=$wiadomosc['odczytana'] ? '' : '<span class="czerwony">NOWA!</span>'?>
                                            <?=$wiadomosc['data_ost']?>
                                        </div>
                                        <div class="col-xs-5 text-left">
                                            <a class="link_adresat" target="_blank" href="<?=URL?>profil/<?=$wiadomosc['id_nadawca']?>"><?=$wiadomosc['login']?></a>
                                        </div>
                                        <!--<div class="col-xs-2">
                                            USUŃ...
                                        </div>-->
                                    </div>
                                </div>
                            </div>
                <?php endforeach;
                    endif; ?>
            </div>
<?php if(!isset($_GET['ajax'])) : ?>
        </div>
    </div>
</div>
<div class="modal fade in" id="wiadomosci_modal" role="dialog">
    <div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-title pogrubienie text-center"></div></div><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-warning" data-dismiss="modal">Zamknij</button></div>
</div></div></div>
<?php endif;?>
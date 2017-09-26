<?php if(!isset($_GET['ajax']))  : ?>
    <div class="col-xs-12 col-sm-8 col-md-9" id="prawo">
        <div class="panel panel-success jeden_ttlo">
            <div class="panel-heading">
                <span>ZGŁOŚ BŁĄD</span>
            </div>
            <div class="panel-body">
<?php endif;
                if ($this->ile) :
                    foreach ($this->blad as $blad) :?>
                        <div class="row row-centered">
                            <div class="col-xs-12 col-md-8 col-centered">
                                <button class="btn 
                                <?php 
                                if(($blad['poprawiony'])) echo 'btn-success';
                                else echo 'btn-danger';
                                ?>
                                btn-block" data-toggle="modal" data-target="#<?=$blad['ID']?>" ><?=$blad['tytul']?></button>
                            </div>
                        </div>
                        <div class="modal fade" id="<?=$blad['ID']?>" role="dialog">
                            <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <span class="text-medium"><?=$blad['tytul']?></span>
                                        </div>
                                        <div class="modal-body text-center">
                                            <?=$blad['opis']?>
                                            <br />Zgłoszony: <?=$blad['data']?>
                                            <?php if($blad['admin']) {
                                                echo '<br />Zgłoszony przez: '.$blad['zgloszony'];
                                            }
                                            if(($blad['poprawiony'])) echo '<br /><span class="zielony">Poprawiony</span>';
                                            else echo '<br /><span class="czerwony">Nie poprawiony</span>';
                                            ?>
                                        </div>

                                        <div class="modal-footer">
                                            <?php if($blad['admin']) {
                                                echo '<div class="btn-group"><button class="btn btn-success usun" button-id="'.$blad['ID'].'">USUŃ</button>';
                                                echo '<button class="btn btn-success popraw" button-id="'.$blad['ID'].'">POPRAW</button></div>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                <?php endforeach;
                else : ?>
                    <div class="alert alert-info text-center"><span>Brak zgłoszonych błędów.</span></div>
                <?php endif; ?>
                <div class="row row-centered">
                    <button class="btn btn-primary powrot">POWRÓT</button>
                </div>
<?php if(!isset($_GET['ajax']))  : ?>
            </div></div></div>
<?php endif; ?>

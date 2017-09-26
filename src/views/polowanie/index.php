<?= isset($_GET['ajax']) ? '' : '<div class="col-xs-12 col-sm-8 col-md-9" id="prawo">' ?>
<div class="panel panel-success jeden_ttlo">
    <div class="panel-heading"><span>POLOWANIE - <?= $this->region ?></span></div>
    <div class="panel-body" id="panel_polowanie">
    <?php
    $i = 1;
    foreach ($this->dzicz as $key => $dzicz) : ?>
        <div class="well well-primary jeden_ttlo">
            <div class="row nomargin">
                <div class="col-xs-6 col-sm-3">
                    <img class="img-responsive dzicz_img kursor" id="<?=$key?>" src="<?=URL?>public/img/dzicze/<?=$i?>d.jpg" />
                </div>
                <div class="col-xs-6 col-sm-9">
                    <p class="text-big">
                        <?=$dzicz['nazwaPl']?>
                    </p>
                    <p><?= $dzicz['opis'] ?></p>
                    <p>Pokemony do złapania:</p>
                    <p>
                        <?php foreach ($dzicz['pokemony'] as $pokemon) {
                            echo '<span class="';
                            if ($pokemon['zlapany'])
                                echo 'zielony" data-title="złapany" data-toggle="tooltip">';
                            else
                                echo 'czerwony" data-title="nie złapany" data-toggle="tooltip">';
                            echo $pokemon['nazwa'].'</span>, ';
                        } ?>
                    </p>
                </div>
            </div>
        </div>
    <?php
    $i++;
    endforeach; ?>
    </div>
</div>
<?= isset($_GET['ajax']) ? '' : '</div>';
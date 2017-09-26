<?= isset($_GET['ajax']) ? '' : '<div class="col-xs-12 col-sm-8 col-md-9" id="prawo"><div class="panel panel-success jeden_ttlo">'
    . '<div class="panel-heading"><span>LECZNICA POKEMON</span></div><div class="panel-body">' ?>
    <div id="wylecz"></div>
    <div class="row nomargin margin-top">
        <div class="col-xs-12 col-sm-4">
            <img class="img-responsive" src="<?= URL ?>public/img/glowne/centrum.png"/>
        </div>
        <div class="col-xs-12 col-md-8">
            <div class="alert alert-success text-center"><span>Witaj w Lecznicy Pokemon! Możesz oddać tutaj swoje pokemony, by wyzdrowiały. 
                Wykwalifikowany personel medyczny  zadba o to by nastąpiło to jak najszybciej.</span>
            </div>
        </div>
        <div class="col-xs-12">
            <?= isset($this->darmoweLeczenia) ? '<div class="alert alert-success margin-top"><span>Możesz wyleczyć za darmo drużynę ' . $this->darmoweLeczenia . ' razy.</span></div>' : '' ?>
        </div>
    </div>
    <div class="row nomargin margin-top" id="lecznica">
        <div class="col-xs-12">
            <?php
            foreach ($this->pok as $value) {
                echo '<div class="col-xs-6 col-sm-4 margin-top"><div class="padding_small jeden">';
                echo '<div class="row nomargin"><div class="col-xs-4 col-sm-3">';
                if ($value['shiny'] == 0) echo '<img src="' . URL . 'public/img/poki/' . $value['id_p'] . '.png" class="lecznica_img img_responsive" />';
                else echo '<img src="' . URL . 'public/img/poki/s' . $value['id_p'] . '.png" class="lecznica_img img_responsive" />';
                echo '</div><div class="col-xs-8 col-sm-9"><div class="text-center">' . $value['imie'] . '</div>';
                echo '<div class="progress progress-gra prog_HP" data-original-title="Życie pokemona" data-toggle="tooltip" data-placement="top">';
                echo '<div class="progress-bar progress-bar-success progBarHP" role="progressbar" aria-valuenow="40" ';
                echo 'aria-valuemin="0" aria-valuemax="100" style="width:' . (floor(($value['akt_zycie'] / $value['zycie']) * 10000) / 100) . '%;">';
                echo '<span>' . $value['akt_zycie'] . ' / ' . $value['zycie'] . ' HP</span></div></div>';
                echo '<div class="text-center"><span>Koszt :' . $value['cena'] . ' &yen;';
                '</span>';
                echo ' <a type="button" class="btn btn-success noborder wylecz btn-sm" href="' . URL . 'lecznica/wylecz/' . $value['i'] . '" >Wylecz</a>';
                echo '</div></div></div></div></div>';
            }
            ?>
            <div class="col-xs-12">
                <div class="text-center margin-top">
                    <a type="button" class="btn btn-success noborder wylecz btn-sm"
                       href="<?= URL ?>lecznica/wylecz/wszystkie">Wylecz wszystkie</a>
                </div>
            </div>
        </div>
    </div>
<?= isset($_GET['ajax']) ? '' : '</div></div>'; ?>
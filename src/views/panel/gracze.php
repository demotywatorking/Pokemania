<div class="col-xs-12 col-sm-8 col-md-9" id="prawo">
    <div class="panel panel-success jeden_ttlo">
        <div class="panel-heading"><span>PANEL ADMINA</span></div>
        <div class="panel-body">
            <div class="alert alert-success"><span>Graczy online: <?=$this->graczy?></span></div>
            <?php foreach ($this->gracz as $gracz) : ?>
                <pre>
                    ID : <?=$gracz['ID']?><br />
                    <?php
                    if($gracz['admin'] > 0) echo '<p style="color:red">ADMIN</p>';
                    if($gracz['id_sesji']) echo '<p style="color:green">ONLINE</p>';
                    ?>
                    login : <?=$gracz['login']?><br />
                    email : <?=$gracz['email']?><br />
                    pieniadze : <?=$gracz['pieniadze']?><br />
                    poziom_trenera : <?=$gracz['poziom_trenera']?><br />
                    doswiadczenie : <?=$gracz['doswiadczenie']?><br />
                    punkty : <?=$gracz['punkty']?><br />
                    mpa : <?=$gracz['mpa']?><br />
                    region : <?=$gracz['region']?><br />
                    <?php
                    if ($gracz['ban'] > 0) {
                        echo 'data wygaśnięcia bana: '.$w['ban_data'].'<br />';
                        echo 'powód bana: '.$w['powod'].'<br />';
                    }
                    ?>
                </pre>
            <?php endforeach; ?>
            <a href="<?=URL?>panel">Powrót</a>
        </div>
    </div>
</div>

<div class="col-xs-12 col-sm-8 col-md-9" id="prawo">
    <div class="panel panel-success jeden_ttlo">
        <div class="panel-heading"><span>PANEL ADMINA</span></div>
        <div class="panel-body">
            <div class="alert alert-success"><span>Wszystkich pokemonów: <?=$this->pokemony?></span></div>
            <?php foreach ($this->pokemon as $pokemon) : ?>
                <pre>
                ID : <?=$pokemon['ID']?><br />
                imie : <?=$pokemon['imie']?><br />
                nazwa : <?=$pokemon['nazwa']?><br />
                poziom : <?=$pokemon['poziom']?><br />
                exp : <?=$pokemon['exp']?><br />
                <?php if ($pokemon['shiny'] > 0) echo '<p style = "color:red">SHINY</p><br />'; ?>
                wlasciciel : <?=$pokemon['login']?><br />
                Atak : <?=$pokemon['Atak']?><br />
                Obrona : <?=$pokemon['Obrona']?><br />
                Sp_Atak : <?=$pokemon['Sp_Atak']?><br />
                Sp_Obrona : <?=$pokemon['Sp_Obrona']?><br />
                Szybkosc : <?=$pokemon['Szybkosc']?><br />
                HP : <?=$pokemon['HP']?><br />
                plec : <?=$pokemon['plec']?><br />
                wartosc : <?=$pokemon['wartosc']?><br />
                data_zlapania : <?=$pokemon['data_zlapania']?><br />
                </pre>
            <?php endforeach; ?>
            <a href="<?=URL?>panel">Powrót</a>
        </div>
    </div>
</div>

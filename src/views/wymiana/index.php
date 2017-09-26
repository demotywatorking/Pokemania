<?php if (!isset($_GET['ajax'])): ?>
<div class="col-xs-12 col-sm-8 col-md-9" id="prawo">
    <div class="panel panel-success jeden_ttlo">
        <div class="panel-heading"><span>WYMIANA</span></div>
        <div class="panel-body">
            <?php endif; ?>

            <?php if (isset($this->pokemonyEwolucja)) : ?>
                <div class="alert alert-info text-center"><span>Pokemony oddane do ewolucji:</span></div>
                <?php foreach ($this->pokemonyEwolucja as $value) : ?>
                    <div class="well well-primary jeden_ttlo">
                        <div class="row nomargin">
                            <div class="col-xs-2"><img src="<?= URL ?>public/img/poki/<?= $value['id_poka'] ?>.png"
                                                       class="img-responsive targ_pok"/></div>
                            <div class="col-xs-2 targ-line"><a class="btn btn-link" target="_blank"
                                                               href="<?= URL ?>pokemon/<?= $value['ID'] ?>"><?= $value['imie'] ?></a>
                            </div>
                            <div class="col-xs-8 targ-line">
                                <?php if ($value['odbierz']) : ?>
                                    <button class="btn btn-primary oddaj" name="<?= $value['ID'] ?>">Odbierz Pokemona
                                    </button>
                                <?php else :
                                    echo $value['czas'];
                                endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach;
                echo '</div></div>';
            endif; ?>


            <?php if (isset($this->pokiWymianaBlad)) : ?>
                <div class="alert alert-warning text-center"><span><?= $this->pokiWymianaBlad ?></span></div>
            <?php else :
            foreach ($this->pokemonWymiana as $value) : ?>
            <div class="panel panel-success jeden_ttlo">
                <div class="panel-heading"><span><?= $value['imie'] ?> (<?= $value['lvl'] ?> poziom)</span></div>
                <div class="panel-body">
                    <div class="alert alert-info"><span>Przedmioty wymagane do ewolucji:
                    <br/> 1x Runa ewolucyjna<img src="<?= URL ?>public/img/przedmioty/runa.png"/>
                    <br/>1x
                            <?php switch ($value['przedmiot']) {
                                case 'kamien' :
                                    echo 'Kamień filozoficzny';
                                    break;
                                case 'pas' :
                                    echo 'Czarny pas';
                                    break;
                                case 'ektoplazma' :
                                    echo 'Ektoplazma';
                                    break;
                                case 'obsydian' :
                                    echo 'Obsydian';
                                    break;
                            } ?>
                            <img src="<?= URL ?>public/img/przedmioty/<?= $value['przedmiot'] ?>.png"/></span>
                    </div>
                    <?php if ($value['ewolucjaLvl'] <= $value['lvl']) {
                        if ($this->przedmiotyEwo['runa'] && $this->przedmiotyEwo[$value['przedmiot']]) {
                            echo '<div class="row row-centered"><button class="btn btn-primary ewoluuj" name="' . $value['id'] . '"><img src="' . URL . 'public/img/poki/' . $value['id_p'] . '.png" /><br /> 
                            Ewoluuj ' . $value['imie'] . '</button></div>';
                        } else {
                            echo '<div class="alert alert-warning"><span>Nie posiadasz przedmiotów do ewolucji Pokemona.</span></div>';
                        }
                    } else {
                        echo '<div class="alert alert-warning"><span>Pokemon ma zbyt niski poziom do ewolucji. Potrzebny ' . $value['ewolucjaLvl'] . ' poziom.</span></div>';
                    }
                    endforeach;
                    endif; ?>


                    <?php if (!isset($_GET['ajax'])): ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

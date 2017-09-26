<div id="menu_div">
    <nav>
        <a id="resp-menu" class="responsive-menu" href="#"><i class="fa fa-reorder"></i>Menu</a>
        <ul class="menu">


            <li>
                <a class="kursor"><i class="fa fa-home"></i>POSTAĆ</a>
                <ul class="sub-menu">
                    <li><a href="<?= URL ?>profil/<?= $this->profil_id ?>">PROFIL</a></li>
                    <li><a href="<?= URL ?>plecak">PLECAK</a></li>
                    <li><a class="kursor">STATYSTYKI</a>
                        <ul>
                            <li><a href="<?= URL ?>gra">DZIEJSZE</a></li>
                            <li><a href="<?= URL ?>statystyki">OGÓLNE</a></li>
                        </ul>
                    </li>
                    <li><a href="<?= URL ?>osiagniecia">OSIĄGNIĘCIA</a></li>
                    <li><a href="<?= URL ?>trening">TRENING</a>
                        <ul>
                            <li><a href="<?= URL ?>trening/trenuj">ROZPOCZNIJ</a></li>
                        </ul>
                    </li>
                    <li><a href="<?= URL ?>wymien">WYMIANA</a></li>
                    <li><a href="<?= URL ?>znajomi">ZNAJOMI</a></li>
                    <!--li><a href="stowarzyszenie.php">STOWARZYSZENIE</a></li-->
                </ul>
            </li>

            <li><a class="kursor"><i class="fa fa-user"></i>POKEMONY</a>
                <ul class="sub-menu">
                    <li><a href="<?= URL ?>pokemony">TWOJE POKEMONY</a>
                    <li>
                    <li><a class="kursor">STAN</a>
                        <ul>
                            <?= $this->stan ?>
                        </ul>
                    </li>
                    <li><a class="kursor">SALA TRENINGOWA</a>
                        <ul>
                            <?= $this->sala ?>
                        </ul>
                    <li><a href="<?= URL ?>kolekcja">KOLEKCJA</a></li>
                    <li><a href="<?= URL ?>wymiana">WYMIANA</a></li>
                </ul>

            </li>

            <li><a class="kursor"><i class="fa fa-camera"></i><?= $this->region ?></a>
                <ul class="sub-menu">
                    <li><a href="<?= URL ?>polowanie">DZICZE</a>
                    <li>
                    <li><a href="<?= URL ?>sklep">POKESKLEP</a></li>
                    <li><a href="<?= URL ?>lecznica">LECZNICA</a></li>
                    <li><a href="<?= URL ?>kupiec">KUPIEC POKEMON</a></li>
                    <li><a href="<?= URL ?>loteria">LOTERIA</a></li>
                    <li><a href="<?= URL ?>sale">SALE POKEMON</a></li>
                    <!--li><a href="stowarzyszenie.php?lista">LISTA STOWARZYSZEŃ</a></li-->
                </ul>

            </li>
            <li><a class="kursor"><i class="fa fa-bullhorn"></i>TARG</a>
                <ul class="sub-menu">
                    <li><a href="<?= URL ?>targ">KUP PRZEDMIOT</a></li>
                    <li><a href="<?= URL ?>targ/pokemon">KUP POKEMONA</a></li>
                    <li><a href="<?= URL ?>targ/wystaw">SPRZEDAJ PRZEDMIOT</a></li>
                    <li><a href="<?= URL ?>targ/wystaw/pokemon">SPRZEDAJ POKEMONA</a></li>
                </ul>
            </li>

            <li>
                <!--
		<?php
                if (isset($_SESSION['ogloszenie']))
                    echo '<a class="kursor" style="color:red" href="ogloszenia.php"><i class="fa fa-tags"></i>OGŁOSZENIA</a>';
                else echo '';
                ?>
                -->
                <a class="kursor"><i class="fa fa-envelope"></i>INNE</a>
                <ul class="sub-menu">
                    <!--
			<?php
                    if (isset($_SESSION['ogloszenie']))
                        echo '<li><a class="kursor" style="color:red" href="ogloszenia.php"><i class="fa fa-tags"></i>OGŁOSZENIA</a></li>';
                    else echo '';
                    ?>
                    -->
                    <li><a href="<?= URL ?>ogloszenia">OGŁOSZENIA</a></li>
                    <li><a href="<?= URL ?>walki">WALKI</a></li>
                    <li><a href="http://forum.pokemania.cf">FORUM</a></li>
                    <li><a class="kursor">POKEDEX</a></li>
                    <li><a href="<?= URL ?>ustawienia">USTAWIENIA</a></li>

                    <?= $this->admin ?>
                    <li><a href="<?= URL ?>zglos">ZGŁOŚ BŁĄD</a></li>
                </ul>
            </li>
            <li><a href="<?= URL ?>wyloguj/1"><i class="fa fa-sitemap"></i>WYLOGUJ</a></li>
        </ul>
    </nav>
</div>
<div class="container-fluid">
    <div class="row">

<?php if (!isset($_GET['ajax'])): ?>
<div class="col-xs-12 col-sm-8 col-md-9" id="prawo">
    <div class="panel panel-success jeden_ttlo">
        <div class="panel-heading"><span>TRENING</span></div>
        <div class="panel-body">
            <?php endif; ?>
            <div class="row nomargin">
                <div class="col-xs-12">
                    <?php if (isset($this->komunikatTrening)) echo $this->komunikatTrening;
                    if (isset($this->przerwij)) {
                        echo '<div class="alert alert-info text-center"><span>Zakończono trening z pokemami.<br />Trenowałeś ' . $this->czas . '.</span></div>';
                        if (!$this->exp) {
                            echo '<div class="alert alert-danger text-medium text-center"><span>Niestety trening był niewystarczająco długi, aby pokemony zdobyły doświadczenie.</span></div>';
                        } else {
                            echo '<div class="alert alert-success text-medium text-center"><span>Trening skutkował zwiększeniem doświadczenia pokemonów: ';
                            echo $this->tekst . '</span></div>';
                        }
                    }
                    ?>
                    <?php if ($this->trening): ?>
                        <div class="well well-stan jeden_ttlo line30 text-center">
                            Trenujesz z pokemonami
                            <span id="godziny"><?= $this->godziny ?></span>
                            :<span id="minuty"><?= $this->minuty ?></span>
                            :<span id="sekundy"><?= $this->sekundy ?></span>
                            <a class="btn btn-primary margin_2" href="<?= URL ?>trening/przerwij"> Zakończ trening</a>
                        </div>
                        <div class="well well-stan jeden_ttlo line30 text-center">
                            <br/>+ 3 pkt. doświadczenia co
                            <?php if ($this->coGodziny) echo $this->coGodziny . ' godzinę '; ?>
                            <?= $this->coMinuty ?> minut
                            <?= $this->coSekundy ?> sekund.
                        </div>

                    <?php else: ?>
                    <div class="well well-stan jeden_ttlo line30 text-center">
                        <a class="btn btn-primary" href="<?= URL ?>trening/trenuj">Trenuj z pokemonami</a>
                    </div>
                    <div class="well well-stan jeden_ttlo line30 text-center">
                        + 3 pkt. doświadczenia co
                        <?php if ($this->godziny) echo $this->godziny . ' godzinę '; ?>
                        <?= $this->minuty ?> minut
                        <?= $this->sekundy ?> sekund.
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!isset($_GET['ajax'])): ?>
    </div>
</div></div>
<?php endif; ?>


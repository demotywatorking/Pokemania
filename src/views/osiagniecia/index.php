<div class="col-xs-12 col-sm-8 col-md-9" id="prawo">
    <div class="panel panel-success jeden_ttlo">
        <div class="panel-heading"><span>OSIĄGNIĘCIA</span></div>
        <div class="panel-body">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#glowne">Główne</a></li>
                <li><a data-toggle="tab" href="#poboczne">Poboczne</a></li>
                <li><a data-toggle="tab" href="#kanto">Kanto</a></li>
            </ul>
            <div class="tab-content">
                <?= $this->poziom ?>
                <div id="glowne" class="tab-pane fade in active">
                    <div class="row nomargin">
                        <?php
                        $i = 1;
                        foreach ($this->osiagniecieGlowne as $value) {
                            echo '<div class="col-xs-12 col-md-6"><div class="panel panel-success text-center ' . $value['tlo'] . '">';
                            echo '<div class="panel-heading"><span class="text-medium">' . $value['nazwa'] . '</span><br />
                              <span>Poziom: <span class="pogrubienie">' . $value['baza'] . '</span></span></div><div class="panel-body">';
                            if (!$value['max']) {
                                echo $value['echo'] . '(' . $value['tabela_1'] . ' / ' . $value['wy'] . ')<br />';
                                echo '<div class="progress progress-gra prog_HP">';
                                echo '<div class="progress-bar progress-bar-success progBarHP" role="progressbar" aria-valuenow="40" ';
                                echo 'aria-valuemin="0" aria-valuemax="100" style="width:' . $value['dl'] . '%;">';
                                echo '<span>' . floor($value['dl']) . '%</span></div></div>';
                            } else {
                                echo $value['echo'] . ' : ' . $value['tabela_1'];
                            }
                            echo '</div></div></div>';
                            if (!($i & 1)) echo '<div class="clearfix"></div>';
                            $i++;
                        }
                        ?>
                    </div>
                </div>
                <div id="poboczne" class="tab-pane fade">
                    <div class="row nomargin">
                        <?php
                        $i = 1;
                        foreach ($this->osiagnieciePoboczne as $value) {
                            echo '<div class="col-xs-12 col-md-6"><div class="panel panel-success text-center ' . $value['tlo'] . '">';
                            echo '<div class="panel-heading"><span class="text-medium">' . $value['nazwa'] . '</span><br />
                              <span>Poziom: <span class="pogrubienie">' . $value['baza'] . '</span></span></div><div class="panel-body">';
                            if (!$value['max']) {
                                echo $value['echo'] . '(' . $value['tabela_1'] . ' / ' . $value['wy'] . ')<br />';
                                echo '<div class="progress progress-gra prog_HP">';
                                echo '<div class="progress-bar progress-bar-success progBarHP" role="progressbar" aria-valuenow="40" ';
                                echo 'aria-valuemin="0" aria-valuemax="100" style="width:' . $value['dl'] . '%;">';
                                echo '<span>' . floor($value['dl']) . '%</span></div></div>';
                            } else {
                                echo $value['echo'] . ' : ' . $value['tabela_1'];
                            }
                            echo '</div></div></div>';
                            if (!($i & 1)) echo '<div class="clearfix"></div>';
                            $i++;
                        }
                        ?>
                    </div>
                </div>
                <div id="kanto" class="tab-pane fade">
                    <div class="row nomargin">
                        <div class="col-xs-12 col-md-6">
                            <div class="panel panel-success text-center <?= $this->kantoZnawca['tlo'] ?>">
                                <div class="panel-heading"><span
                                            class="text-medium"><?= $this->kantoZnawca['nazwa'] ?></span>
                                    <br/><span>Poziom: <span
                                                class="pogrubienie"><?= $this->kantoZnawca['baza'] ?></span></span>
                                </div>
                                <div class="panel-body">
                                    <?= $this->kantoZnawca['echo'] ?>
                                </div>
                            </div>
                        </div>
                        <?php
                        $i = 2;
                        foreach ($this->osiagniecieKanto as $value) {
                            echo '<div class="col-xs-12 col-md-6"><div class="panel panel-success text-center ' . $value['tlo'] . '">';
                            echo '<div class="panel-heading"><span class="text-medium">' . $value['nazwa'] . '</span><br />
                              <span>Poziom: <span class="pogrubienie">' . $value['baza'] . '</span></span></div><div class="panel-body">';
                            if (!$value['max']) {
                                echo $value['echo'] . '(' . $value['tabela_1'] . ' / ' . $value['wy'] . ')<br />';
                                echo '<div class="progress progress-gra prog_HP">';
                                echo '<div class="progress-bar progress-bar-success progBarHP" role="progressbar" aria-valuenow="40" ';
                                echo 'aria-valuemin="0" aria-valuemax="100" style="width:' . $value['dl'] . '%;">';
                                echo '<span>' . floor($value['dl']) . '%</span></div></div>';
                            } else {
                                echo $value['echo'] . ' : ' . $value['tabela_1'];
                            }
                            echo '</div></div></div>';
                            if (!($i & 1)) echo '<div class="clearfix"></div>';
                            $i++;
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
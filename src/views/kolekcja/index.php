<?= isset($_GET['ajax']) ? '' : '<div class="col-xs-12 col-sm-8 col-md-9" id="prawo"><div class="panel panel-success jeden_ttlo">'
    . '<div class="panel-heading"><span>ZNAJOMI</span></div><div class="panel-body">' ?>
<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#kanto">KANTO</a></li>
    <li><a data-toggle="tab" href="#johto">JOHTO</a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane fade in active" id="kanto">
        <div class="row nomargin">

            <div class="alert alert-info text-center text-medium"><span>Spotkane pokemony: <?= $this->spotkaneKanto ?>
                    <br/>Złapane pokemony: <?= $this->zlapaneKanto ?></span></div>
            <?php
            foreach ($this->kanto as $value) {
                echo '<div class="col-xs-4 col-sm-3 col-md-2 col-lg-2 text-center padding_lr margin-top" id="' . $value['id'] . '"><div id="' . $value['id'] . '" class="kolekcja jeden kursor"';
                echo 'data-original-title="Spotkane: ' . $value['s'] . '<br />Złapane: ' . $value['z'] . '" data-toggle="tooltip" data-placement="top auto">';
                if ($value['s'] >= 1) {
                    if ($value['z'] >= 1) {////złapany
                        echo '<img src="img/poki/' . $value['id'] . '.png" class="kolekcja_img" />';
                    } else {/////spotkany, ale nie złapany
                        echo '<img src="img/poki/bw/' . $value['id'] . '.png" class="kolekcja_img" />';
                    }
                    echo '<br />#' . $value['id'] . '<br />' . $value['nazwa'];
                } else {//////nie spotkany
                    echo '<img src="img/poki/b/' . $value['id'] . '.png" class="kolekcja_img" />';
                    echo '<br />#' . $value['id'] . "<br />???";
                }
                echo '</div></div>';
            }
            ?>
        </div>
    </div>
    <div class="tab-pane fade" id="johto">
        <div class="row nomargin">

            <div class="alert alert-info text-center text-medium"><span>Spotkane pokemony: <?= $this->spotkaneJohto ?>
                    <br/>Złapane pokemony: <?= $this->zlapaneJohto ?></span></div>
            <?php
            foreach ($this->johto as $value) {
                echo '<div class="col-xs-4 col-sm-3 col-md-2 col-lg-2 text-center padding_lr margin-top" id="' . $value['id'] . '"><div id="' . $value['id'] . '" class="kolekcja jeden kursor"';
                echo 'data-original-title="Spotkane: ' . $value['s'] . '<br />Złapane: ' . $value['z'] . '" data-toggle="tooltip" data-placement="top auto">';
                if ($value['s'] >= 1) {
                    if ($value['z'] >= 1) {////złapany
                        echo '<img src="img/poki/' . $value['id'] . '.png" class="kolekcja_img" />';
                    } else {/////spotkany, ale nie złapany
                        echo '<img src="img/poki/bw/' . $value['id'] . '.png" class="kolekcja_img" />';
                    }
                    echo '<br />#' . $value['id'] . '<br />' . $value['nazwa'];
                } else {//////nie spotkany
                    echo '<img src="img/poki/b/' . $value['id'] . '.png" class="kolekcja_img" />';
                    echo '<br />#' . $value['id'] . "<br />???";
                }
                echo '</div></div>';
            }
            ?>
        </div>
    </div>
</div></div></div>
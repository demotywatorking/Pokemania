<?= isset($_GET['ajax']) ? '' : '<div class="col-xs-12 col-sm-8 col-md-9" id="prawo"><div class="panel panel-success jeden_ttlo">'
    . '<div class="panel-heading"><span>ZNAJOMI</span></div><div class="panel-body">' ?>

<?= isset($this->dodaj) ? $this->dodaj : '' ?>
<?= isset($this->zaakceptuj) ? $this->zaakceptuj : '' ?>
<?= isset($this->odrzuc) ? $this->odrzuc : '' ?>
<?= isset($this->anuluj) ? $this->anuluj : '' ?>
<?= isset($this->usun) ? $this->usun : '' ?>

<?php if ($this->iloscZnajomych): ?>
    <div class="alert alert-success"><span>Znajomi (<?= $this->iloscZnajomych ?>):</span></div>
    <div class="row nomargin">
        <?php
        echo '<div class="col-xs-12 margin-top">';
        foreach ($this->znajomy as $value) {
            echo '<div class="row nomargin">';
            if ($value['id_sesji']) {
                echo '<img src="img/dost.png" data-toggle="tooltip" data-title="ONLINE" />';
            } else {
                echo '<img src="img/nied.png" data-toggle="tooltip" data-title="OFFLINE" />';
            }
            echo '<a class="col-xs-4 col-lg-3 btn btn-success" href="'.URL.'profil/' . $value['id'] . '">' . $value['login'] . '</a>';
            echo '<a class="btn btn-primary" href="'.URL.'wiadomosci/nowa/' . $value['login'] . '">Wyślij wiadomość</a>'
                . '<button class="btn btn-primary nakarm" id="' . $value['karmienie'] . '">Nakarm Pokemony</button>'
                . '<button class="btn btn-danger usun" id="' . $value['id'] . '">Usuń ze znajomych</button>';
            echo '</div>';
        }
        ?>
        <div id="karmienie"></div>
    </div></div>
<?php else: ?>
    <div class="alert alert-warning"><span>Brak znajomych</span></div>
<?php endif; ?>

<?php if ($this->zaproszenia): ?>
    <div class="alert alert-success margin-top"><span>Otrzymane zaproszenia do znajomych:</span></div>
    <div class="row nomargin">
        <?php
        foreach ($this->zaproszenieDane as $value) {
            echo '<div class="col-xs-12 margin-top"><a class="btn btn-primary" href="'.URL.'profil/' . $value['id'] . '">Zaproszenie od ' . $value['login'] . '</a>'
                . '<button class="btn btn-success zaakceptuj" id="' . $value['id'] . '">Zaakceptuj</button>'
                . '<button class="btn btn-danger odrzuc" id="' . $value['id'] . '">Odrzuć</button></div>';
        }
        ?>
    </div>
<?php endif; ?>

<?php if ($this->wyslane): ?>
    <div class="alert alert-success margin-top"><span>Wysłane zaproszenia do znajomych:</span></div>
    <div class="row nomargin">
        <?php
        foreach ($this->wyslaneDane as $value) {
            echo '<div class="col-xs-12 margin-top"><a class="btn btn-primary" href="'.URL.'profil/' . $value['id'] . '">Zaproszenie dla gracza ' . $value['login'] . '</a>'
                . '<button class="btn btn-danger anuluj" id="' . $value['id'] . '">Anuluj zaproszenie</button></div>';
        }
        ?>
    </div>
<?php endif; ?>


<?= isset($_GET['ajax']) ? '' : '</div></div>'; ?>
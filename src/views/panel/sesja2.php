<div class="col-xs-12 col-sm-8 col-md-9" id="prawo">
    <div class="panel panel-success jeden_ttlo">
        <div class="panel-heading"><span>PANEL ADMINA</span></div>
        <div class="panel-body">
            <?php foreach($this->pokemon as $pokemon) : ?>
            <pre>
                <?php var_export($pokemon); ?>
            </pre>
            <?php endforeach; ?>
            <pre>
                <?php var_export($_SESSION); ?>
            </pre>
            <pre>
            <?php foreach($this->odznaka as $odznaka) : ?>
                <?= $odznaka ?> <br />
            <?php endforeach; ?>
                </pre>
            <a href="<?=URL?>panel">Powrót</a>
        </div>
    </div>
</div>


<div class="col-xs-12 col-sm-8 col-md-9" id="prawo">
    <div class="panel panel-success jeden_ttlo">
        <div class="panel-heading"><span>PANEL ADMINA</span></div>
        <div class="panel-body">
            <?php if(!isset($_POST['tytul'])) : ?>
            <form action="<?=URL?>panel/ogloszenie/" method="post">
            Tytuł:<input type="text" name="tytul"></input><br/ >
            Treść:<input type="text" name="tresc"></input><br/ >
            <input type="submit" value="WYŚLIJ"></input><br/ >
            </form>
            <?php else :
            echo $this->komunikat;
            endif; ?>
            <a href="<?=URL?>panel">Powrót</a>
        </div>
    </div>
</div>

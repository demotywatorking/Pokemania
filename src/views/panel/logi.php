<div class="col-xs-12 col-sm-8 col-md-9" id="prawo">
    <div class="panel panel-success jeden_ttlo">
        <div class="panel-heading"><span>PANEL ADMINA</span></div>
        <div class="panel-body">
            <?php if($this->id) : ?>
                <div class="alert alert-info"><span>Ilość logów: <?=$this->ilosc?></span></div>
                <?=$this->komunikat?>
                <?php foreach ($this->log as $log) : ?>
                    <div class="well well-primary jeden_ttlo"
                        <span>
                            <?=$log['data']?><?=strtoupper($log['co'])?>
                            <span style="margin-left:20px;"> <?=$log['komentarz']?></span>
                        </span>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <form method="post">
                    ID:<input type="text" name="id" /><br/ >
                    mode:<select name="mod" style="color:black"><option value="">ALL</option><option value="logowanie">LOGOWANIE</option><option value="wylogowanie">WYLOGOWANIE</option><option value="kwerenda">KWERENDA</option>'
                        <option value="rejestracja">REJESTRACJA</option><option value="targ">TARG</option><option value="targ_pokemon">TARG POKEMON</option><option value="shiny">SHINY</option>'
                        <option value="stowarzyszenie">STOWARZYSZENIE</option> </select>
                    <input type="submit" value="WYŚLIJ" /><br/ >
                </form>
            <?php endif; ?>
            <a href="<?=URL?>panel">Powrót</a>
        </div>
    </div>
</div>

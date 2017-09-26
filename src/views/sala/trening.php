<?php if (!isset($_GET['ajax'])) {
    echo '<div class="col-xs-12 col-sm-8 col-md-9" id="prawo"><div class="panel panel-success jeden_ttlo"><div class="panel-heading text-medium"><span>SALA TRENINGOWA</span></div><div class="panel-body">';
}
if (isset($this->blad))
    echo $this->blad;
if (isset($this->info))
    echo $this->info;
if (!isset($_GET['ajax'])) {
    echo '</div></div>';
}
?>
<?php
if (isset($this->blad)) {
    echo '<div class="alert alert-warning"><span>' . $this->blad . '</span></div>';
}
if (isset($this->komunikat)) {
    echo '<div class="alert alert-success"><span>' . $this->komunikat . '</span></div>';
}
?>
<?php
if (isset($this->komunikat)) {
    echo '<div class="alert alert-success text-center"><span>'.$this->komunikat.'</span></div>';
}
if (isset($this->error)) {
    echo '<div class="alert alert-danger text-center"><span>'.$this->error.'</span></div>';
}
?>
<?php
if(isset($this->zlyFormat)) {
    echo 'error, zly format';
} else {
    if($this->ilosc) echo 'error';
    else echo 'OK';
}
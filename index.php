<?php
require 'ini.php';
require 'src/libs/config.php';
ob_start();

$router = new src\libs\Router();

ob_end_flush();

if (src\libs\Session::_get('admin')) {
    src\libs\Debug::showInfo();
}
?>
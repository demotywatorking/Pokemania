<?php
// path to libs folder
define('LIBS', './src/libs/');
//base URL
define('URL', 'http://pokemania.ml/');
//name of game
define('NAME', 'pokemania.ml');
//Language on page
define('DEFAULT_LANGUAGE', 'pl');
// path to lang folder
define('__LANG__', 'lang/');
//constants with hours, minutes, seconds
define('GODZIN', date('G'));
define('MINUT', 1 * date('i'));
define('SEKUND', 1 * date('s'));
//samouczek
define('ILOSC_SAMOUCZEK', 1);
require('AutoLoad.php');
require('parameters.php');
spl_autoload_register(['src\libs\AutoLoad', 'load']);

require('./src/includes/functions.php');
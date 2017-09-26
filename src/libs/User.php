<?php

namespace src\libs;

use src\includes\Pokemon;
use src\includes\Przedmioty;
use src\includes\Statystyki;
use src\includes\Umiejetnosci;
use src\includes\Ustawienia;

class User
{

    public static $pok;
    public static $ustawienia;
    public static $odznaki;
    public static $przedmioty;
    public static $umiejetnosci;

    public static function getInstance()
    {
        if (Session::_get('logged')) {
            for ($i = 1; $i < 7; $i++) {
                if (Session::_isset('pok' . $i)) {
                    $u = explode("|", Session::_get('pok' . $i));
                    $pok[$i] = new Pokemon($u);
                }
            }
            self::$pok = $pok;
            self::$ustawienia = new Ustawienia(explode("|", Session::_get('ustawienia')));
            self::$przedmioty = new Przedmioty(explode("|", Session::_get('przedmioty')));
            self::$odznaki = new Statystyki(explode("|", Session::_get('odznaki')));
            self::$umiejetnosci = new Umiejetnosci(explode('|', Session::_get('umiejetnosci')));
            self::setCookie();
        }
    }

    public static function _isset($what, $liczba = 0)
    {
        if ($liczba) {
            if (isset(self::${$what}[$liczba])) {
                return 1;
            } else {
                return 0;
            }
        } else {
            if (isset(self::${$what})) {
                return 1;
            } else {
                return 0;
            }
        }
    }

    public static function _get($what, $liczba = 0)
    {
        if ($liczba) {
            return self::${$what}[$liczba];
        } else {
            return self::${$what};
        }
    }
    
    public static function _set($what, $liczba = 0)
    {
        if ($liczba) {
            self::${$what}[$liczba];
        } else {
            return self::${$what};
        }
    }
    
    public static function _unset($what, $liczba = 0)
    {
        if ($liczba) {
            unset(self::${$what}[$liczba]);
        } else {
            unset(self::${$what});
        }
    }

    public static function setCookie()
    {
        setcookie('sidc', session_id(), NULL, '/', NULL, NULL, 1);
    }

}

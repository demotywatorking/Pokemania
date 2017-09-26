<?php

namespace src\libs;

class Debug
{
    public static $info = [];
    public static $get = [];
    public static $post = [];
    public static $mode;
    public static function addInfo($a, $info)
    {
        self::$info[$a] = $info;
    }

    public static function addGet($a, $info)
    {
        self::$get[$a] = $info;
    }

    public static function addPost($a, $info)
    {
        self::$post[$a] = $info;
    }

    public static function showInfo()
    {
        if (in_array(self::$mode, [ 'lewo', 'raporty', 'wiadomosci', 'lecznica'])) {
            return;
        }
        require ('./src/views/debug/info.php');
    }
}
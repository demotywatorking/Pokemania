<?php

namespace src\libs;

class AutoLoad
{
    /**
     * Load a class
     *
     * @param $class class name
     */
    public static function load($class)
    {
        $classR = $class;
        $class = str_replace('\\', '/', $class);
        if (file_exists('./' . $class . '.php')) {
            require('./' . $class . '.php');
        } else {
            $class = explode('/', $class);
            if (file_exists(LIBS . end($class) . '.php')) {
                require(LIBS . end($class) . '.php');
            } else {
                echo 'Critical error!!';
                \src\libs\Debug::addInfo('Class not found', $classR);
                \src\libs\Debug::showInfo();
                exit;
            }
        }
    }

}
<?php

namespace src\libs;

class Session
{
        public static function getInstance()
        {
            session_start();
        }

        public static function _set( $name , $value )
        {
            $_SESSION[$name] = $value;
        }

        public static function _get( $name )
        {
            if ( self::_isset( $name) )
                return $_SESSION[$name];
            else return false;
        }

        public static function _isset( $name )
        {
            return isset($_SESSION[$name]);
        }

        public static function _unset( $name )
        {
            unset( $_SESSION[$name] );
        }

        public static function _destroy()
        {
            session_destroy();
        }

        public static function regenerate()
        {
            self::_destroy();
            self::getInstance();
        }
}
?>
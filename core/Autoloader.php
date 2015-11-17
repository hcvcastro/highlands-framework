<?php

class Autoloader {
    public static $corePath;

    public static function register(){
        self::$corePath = realpath(dirname(__FILE__))  . '/';

        spl_autoload_extensions('.php');
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    public static function autoload($class){
        $file = self::$corePath . $class . '.php';
        if(file_exists($file))
        {
            require_once $file;
        }

    }
}
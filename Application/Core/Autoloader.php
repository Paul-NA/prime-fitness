<?php
/**
 * Autoloader
 */
class Autoloader
{

    public static function register()
    {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    public static function autoload($class) : bool
    {
        $file = str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
        if (file_exists($file)) {
            require $file;
            return true;
        }
        return false;
    }
}
Autoloader::register();
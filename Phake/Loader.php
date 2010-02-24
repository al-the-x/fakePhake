<?php
/**
 * @author David Rogers <david@ethos-development.com>
 * @package Phake_Loader
 * @category Autoloaders
 */

/**
 * @author David Rogers <david@ethos-development.com>
 * @package Phake_Loader
 * @category Autoloaders
 */
class Phake_Loader
{
    /**
     * The static register() method registers the provided $callback
     * as an __autoloader() function, using Phake_Loader::autoload()
     * if none is specified.
     *
     * @param callback $callback to register()
     */
    public static function register ( $callback = null )
    {
        $callback = ( is_null($callback) ?
            array('Phake_Loader', 'autoload') : $callback
        );

        spl_autoload_register($callback, true, true);
    } // END register


    /**
     * The autoload() method is a _very simple_ implementation for
     * PHP5's __autoload() functionality. There's lots of improvement
     * that can be made here.
     *
     * @param string $classname to attempt to autoload()
     */
    public function autoload ( $classname )
    {
        return $this->load($classname);
    } // END autoload


    public static function load ( $classname, $env_vars = array() )
    {
        $filename = strtr($classname, '_', DIRECTORY_SEPARATOR) . '.php';

        extract($env_vars);

        return require $filename;
    } // END load
        
} // END Phake_Loader


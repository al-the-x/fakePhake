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
     * @throws Phake_Exception if $callback cannot be registered
     */
    public static function register ( $callback = null )
    {
        $callback = ( is_null($callback) ?
            array('Phake_Loader', 'load') : $callback
        );

        if ( spl_autoload_register($callback) === false )
        {
            throw new Phake_Exception(
                'Registering the supplied $callback failed: ' . print_r($callback, true)
            );
        }
    } // END register
    

    /**
     * The static load() method does all most of the heavy lifting by
     * require()ing or include()ing the supplied $classname, as appropriate,
     * and setting up the $local_vars just prior.
     *
     * @param string $classname to load()
     * @param array $local_vars to extract() into the local scope before load()ing
     * @param boolean $require $classname or just include() it?
     * @param boolean $once if we should use require_once() or include_once()
     * @throws Phake_Exception if the expected $filename does not exist for $classname
     */
    public static function load ( $classname, $local_vars = array(), $require = true, $once = true )
    {
        /**
         * @todo Use a configurable Inflector instead of hard-coded instructions to determine the $filename
         */
        $filename = strtr($classname, '_', DIRECTORY_SEPARATOR) . '.php';

        /**
         * Phake_Loader checks for the existence of the $filename before
         * attempting any require()s or include()s and throws an appropriate
         * Exception if it doesn't exist.
         */
        if ( !file_exists($filename) )
        {
            /**
             * The only time we should ever need to explicitly require()
             * a file in the whole library, thanks to autoloading.
             */
            require_once 'Phake/Exception.php';

            throw new Phake_Exception(
                'The specified filename does not exist: ' . $filename
            );
        } 

        /**
         * The $local_vars are extract()ed into the local scope prior to
         * the inclusion of the $filename, so that explicit calls to load()
         * can be made for "resource" files that return a value.
         */
        extract($local_vars);

        /**
         * The $require and $once flags indicate whether the $filename should
         * be require()d or include()d and whether to use the "_once()" variations.
         */
        if ( $require and $once ) return require_once $filename;

        if ( $require ) return require $filename;

        if ( $once ) return include_once $filename;

        return include $filename;
    } // END load
        
} // END Phake_Loader


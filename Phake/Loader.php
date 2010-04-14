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
     * @throws Phake_Exception if the expected $class_file does not exist for $classname
     */
    public static function load ( $classname, $local_vars = array(), $require = true, $once = true )
    {
        /**
         * @todo Use a configurable Inflector instead of hard-coded instructions to determine the $class_file
         */
        $class_file = strtr($classname, '_', DIRECTORY_SEPARATOR) . '.php';

        /**
         * Phake_Loader checks for the existence of the $class_file before
         * attempting any require()s or include()s and throws an appropriate
         * Exception if it doesn't exist.
         */
        if ( !@fopen($class_file, 'r', true) )
        {
            /**
             * The only time we should ever need to explicitly require()
             * a file in the whole library, thanks to autoloading.
             */
            require_once 'Phake/Exception.php';

            throw new Phake_Exception(
                'The specified filename does not exist: ' . $class_file
            );
        } 

        /**
         * The $local_vars are extract()ed into the local scope prior to
         * the inclusion of the $class_file, so that explicit calls to load()
         * can be made for "resource" files that return a value.
         */
        extract($local_vars);

        /**
         * The $require and $once flags indicate whether the $class_file should
         * be require()d or include()d and whether to use the "_once()" variations.
         */
        if ( $require and $once ) return require_once $class_file;

        if ( $require ) return require $class_file;

        if ( $once ) return include_once $class_file;

        return include $class_file;
    } // END load
        

    /**
     * The loadScript() method only loads "Scripts": procedural code that returns some
     * interesting values. For the most part, these files expect certain variables to
     * exist in the local scope, which load() extract()s from the $local_vars parameter.
     * If the $scriptname isn't "namespaced", loadScript() will add the "Phake_Scripts"
     * prefix before load()ing.
     *
     * @param string $scriptname to load()
     * @param array $local_vars to extract() into the local scope before load()ing
     * @return mixed values provided by $scriptname
     */
    public static function loadScript ( $scriptname, $local_vars = array() )
    {
        /**
         * If the "namespace" prefix of a $scriptname is omitted, loadScript() prepends
         * "Phake_Scripts" by default.
         */
        if ( strpos('_', $scriptname) === false )
        {
            $scriptname = 'Phake_Scripts_' . $scriptname;
        }

        return self::load($scriptname, $local_vars, false, false);

    } // END loadScript

} // END Phake_Loader

